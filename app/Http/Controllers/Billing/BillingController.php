<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillPayment;
use App\Models\BillServiceCharge;
use App\Models\BloodRequest;
use App\Models\DeathCertificate;
use App\Models\Dispensing;
use App\Models\LabOrder;
use App\Models\MortuaryRecord;
use App\Models\Patient;
use App\Models\RadiologyOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Bill::with(['patient', 'createdBy'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('bill_number', 'like', "%$s%")
                    ->orWhereHas('patient', fn ($p) => $p->where('name', 'like', "%$s%")
                        ->orWhere('mrn', 'like', "%$s%")
                        ->orWhere('phone', 'like', "%$s%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('bill_type')) {
            $query->where('bill_type', $request->bill_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }

        $bills = $query->paginate(20)->withQueryString();

        $stats = [
            'today_bills' => Bill::whereDate('bill_date', today())->count(),
            'today_collected' => BillPayment::whereDate('payment_date', today())->sum('amount'),
            'total_due' => Bill::where('payment_status', '!=', 'Paid')
                ->where('status', 'Finalized')
                ->sum('due_amount'),
            'draft_count' => Bill::where('status', 'Draft')->count(),
        ];

        return view('billing.billing_index', compact('bills', 'stats'));
    }

    // ─── CREATE ───────────────────────────────────────────────────────
    public function create(Request $request)
    {
        $patients = Patient::orderBy('name')->get(['id', 'mrn', 'name', 'phone', 'patient_type']);
        $serviceCharges = BillServiceCharge::active()->orderBy('category')->orderBy('name')->get();

        $selectedPatient = null;
        $pendingServices = [];

        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
            if ($selectedPatient) {
                $pendingServices = $this->getPendingServices($selectedPatient->id);
            }
        }

        return view('billing.billing_create', compact('patients', 'serviceCharges', 'selectedPatient', 'pendingServices'));
    }

    // ─── STORE ────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bill_date' => 'required|date',
            'bill_type' => 'required|in:OPD,IPD,Emergency',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string|max:255',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_type' => 'required|string',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.reference_type' => 'nullable|string',
            'items.*.reference_id' => 'nullable|integer',
        ]);

        // FIX: $createdBill reference se closure ke bahar pass karo
        $createdBill = null;

        DB::transaction(function () use ($request, &$createdBill) {
            $bill = Bill::create([
                'bill_number' => Bill::generateBillNumber(),
                'patient_id' => $request->patient_id,
                'created_by' => Auth::id(),
                'bill_date' => $request->bill_date,
                'bill_type' => $request->bill_type,
                'status' => 'Draft',
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_reason' => $request->discount_reason,
                'tax_amount' => $request->tax_amount ?? 0,
                'notes' => $request->notes,
                'subtotal' => 0,
                'net_amount' => 0,
                'paid_amount' => 0,
                'due_amount' => 0,
                'payment_status' => 'Unpaid',
            ]);

            foreach ($request->items as $item) {
                BillItem::create([
                    'bill_id' => $bill->id,
                    'service_type' => $item['service_type'],
                    'description' => $item['description'],
                    'reference_type' => $item['reference_type'] ?? null,
                    'reference_id' => $item['reference_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                ]);
            }

            if ($request->discount_amount > 0) {
                $bill->update(['discount_by' => Auth::id()]);
            }

            $bill->recalculateTotals();

            $createdBill = $bill;
        });

        return redirect()->route('billing.show', $createdBill)
            ->with('success', 'Bill created successfully. Review and finalize when ready.');
    }

    // ─── SHOW ─────────────────────────────────────────────────────────
    public function show(Bill $bill)
    {
        $bill->load(['patient', 'items', 'payments.receivedBy', 'createdBy', 'discountBy']);

        return view('billing.billing_show', compact('bill'));
    }

    // ─── EDIT ─────────────────────────────────────────────────────────
    public function edit(Bill $bill)
    {
        if (! $bill->isDraft()) {
            return redirect()->route('billing.show', $bill)
                ->with('error', 'Only Draft bills can be edited.');
        }

        $patients = Patient::orderBy('name')->get(['id', 'mrn', 'name', 'phone', 'patient_type']);
        $serviceCharges = BillServiceCharge::active()->orderBy('category')->orderBy('name')->get();
        $bill->load(['patient', 'items']);

        return view('billing.billing_edit', compact('bill', 'patients', 'serviceCharges'));
    }

    // ─── UPDATE ───────────────────────────────────────────────────────
    public function update(Request $request, Bill $bill)
    {
        if (! $bill->isDraft()) {
            return redirect()->route('billing.show', $bill)
                ->with('error', 'Only Draft bills can be edited.');
        }

        // FIX: store() jaisi full items validation
        $request->validate([
            'bill_date' => 'required|date',
            'bill_type' => 'required|in:OPD,IPD,Emergency',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_reason' => 'nullable|string|max:255',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_type' => 'required|string',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.reference_type' => 'nullable|string',
            'items.*.reference_id' => 'nullable|integer',
        ]);

        DB::transaction(function () use ($request, $bill) {
            $bill->update([
                'bill_date' => $request->bill_date,
                'bill_type' => $request->bill_type,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_reason' => $request->discount_reason,
                'tax_amount' => $request->tax_amount ?? 0,
                'notes' => $request->notes,
                'discount_by' => ($request->discount_amount > 0) ? Auth::id() : null,
            ]);

            $bill->items()->delete();

            foreach ($request->items as $item) {
                BillItem::create([
                    'bill_id' => $bill->id,
                    'service_type' => $item['service_type'],
                    'description' => $item['description'],
                    'reference_type' => $item['reference_type'] ?? null,
                    'reference_id' => $item['reference_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                ]);
            }

            $bill->recalculateTotals();
        });

        return redirect()->route('billing.show', $bill)
            ->with('success', 'Bill updated successfully.');
    }

    // ─── FINALIZE ─────────────────────────────────────────────────────
    public function finalize(Bill $bill)
    {
        if (! $bill->isDraft()) {
            return redirect()->route('billing.show', $bill)
                ->with('error', 'Only Draft bills can be finalized.');
        }

        if ($bill->items()->count() === 0) {
            return redirect()->route('billing.show', $bill)
                ->with('error', 'Cannot finalize a bill with no items.');
        }

        $bill->update([
            'status' => 'Finalized',
            'finalized_at' => now(),
        ]);

        return redirect()->route('billing.show', $bill)
            ->with('success', 'Bill has been finalized. You can now record payments.');
    }

    // ─── ADD PAYMENT ──────────────────────────────────────────────────
    public function addPayment(Request $request, Bill $bill)
    {
        if ($bill->isCancelled()) {
            return back()->with('error', 'Cannot record payment on a cancelled bill.');
        }

        if ($bill->isDraft()) {
            return back()->with('error', 'Please finalize the bill before recording payment.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.($bill->due_amount + 0.01),
            'payment_method' => 'required|in:Cash,Card,Bank Transfer,Cheque,Insurance,Online',
            'payment_date' => 'required|date|before_or_equal:today',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $bill) {

            BillPayment::create([
                'payment_number' => BillPayment::generatePaymentNumber(),
                'bill_id' => $bill->id,
                'received_by' => Auth::id(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
            ]);

            $totalPaid = $bill->fresh()->payments()->sum('amount');
            $dueAmount = max(0, $bill->net_amount - $totalPaid);

            $paymentStatus = 'Partial';
            if ($dueAmount <= 0) {
                $paymentStatus = 'Paid';
            } elseif ($totalPaid <= 0) {
                $paymentStatus = 'Unpaid';
            }

            $bill->update([
                'paid_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
            ]);

            // Sync payment status to all linked source modules
            // (lab_orders, radiology_orders, dispensings, death_certificates,
            //  mortuary_records, blood_requests, appointments, beds)
            $bill->refresh()->syncSourcePayments();
        });

        return redirect()->route('billing.show', $bill)
            ->with('success', 'Payment of Rs. '.number_format($request->amount, 2).' recorded successfully.');
    }

    // ─── PRINT / INVOICE ──────────────────────────────────────────────
    public function printInvoice(Bill $bill)
    {
        $bill->load(['patient', 'items', 'payments.receivedBy', 'createdBy']);

        return view('billing.print_invoice', compact('bill'));
    }

    // ─── CANCEL ───────────────────────────────────────────────────────
    public function cancel(Request $request, Bill $bill)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);

        if ($bill->isPaid()) {
            return back()->with('error', 'Cannot cancel a fully paid bill.');
        }

        $bill->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->route('billing.index')
            ->with('success', 'Bill #'.$bill->bill_number.' has been cancelled.');
    }

    // ─── AJAX: PATIENT SEARCH ─────────────────────────────────────────
    public function patientSearch(Request $request)
    {
        $q = $request->get('q', '');

        $query = Patient::where(function ($query) use ($q) {
            $query->where('name', 'like', "%$q%")
                ->orWhere('mrn', 'like', "%$q%")
                ->orWhere('phone', 'like', "%$q%");
        });

        // Optional status filter — OT: "Active,Admitted", default: sab
        if ($request->filled('status')) {
            $statuses = explode(',', $request->status);
            $query->whereIn('status', $statuses);
        }

        $patients = $query->limit(10)
            ->get(['id', 'mrn', 'name', 'phone', 'patient_type', 'status']);

        return response()->json($patients);
    }

    // ─── AJAX: PENDING SERVICES FOR PATIENT ───────────────────────────
    public function pendingServices(Request $request, $patientId)
    {
        $services = $this->getPendingServices($patientId);

        return response()->json($services);
    }

    // ─── PRIVATE: GET PENDING SERVICES ────────────────────────────────
    private function getPendingServices(int $patientId): array
    {
        $services = [];

        // 1. Appointments — completed, not yet billed
        $appointments = Appointment::where('patient_id', $patientId)
            ->where('status', 'Completed')
            ->whereDoesntHave('billItems', function ($query) {
                $query->where('reference_type', 'appointments');
            })
            ->with('doctor.employee')
            ->get();

        foreach ($appointments as $app) {
            $services[] = [
                'service_type' => 'Consultation',
                'description' => 'Consultation Fee: Dr. '.($app->doctor->employee->first_name ?? 'Doctor'),
                'reference_type' => 'appointments',
                'reference_id' => $app->id,
                'quantity' => 1,
                'unit_price' => $app->doctor->consultation_fee ?? 0,
                'discount' => 0,
            ];
        }

        // 2. Bed Charges — currently occupied
        $bed = Bed::where('patient_id', $patientId)
            ->where('status', 'Occupied')
            ->with('ward')
            ->first();

        if ($bed && $bed->admitted_at) {
            $days = max(1, Carbon::parse($bed->admitted_at)->diffInDays(now()));

            $services[] = [
                'service_type' => 'Bed Charges',
                'description' => "Bed Charges: {$bed->ward->name} (Bed: {$bed->bed_number}) - {$days} Days",
                'reference_type' => 'beds',
                'reference_id' => $bed->id,
                'quantity' => $days,
                'unit_price' => $bed->ward->bed_charges ?? 0,
                'discount' => 0,
            ];
        }

        // 3. Lab Orders — unpaid, not cancelled
        $labOrders = LabOrder::where('patient_id', $patientId)
            ->where('payment_status', '!=', 'Paid')
            ->whereNotIn('status', ['Cancelled'])
            ->get();

        foreach ($labOrders as $order) {
            $services[] = [
                'service_type' => 'Lab',
                'description' => 'Lab Order #'.$order->order_number,
                'reference_type' => 'lab_orders',
                'reference_id' => $order->id,
                'quantity' => 1,
                'unit_price' => max(0, ($order->total_amount ?? 0) - ($order->discount ?? 0)),
                'discount' => 0,
            ];
        }

        // 4. Radiology Orders — unpaid, not cancelled
        $radOrders = RadiologyOrder::where('patient_id', $patientId)
            ->where('payment_status', '!=', 'Paid')
            ->whereNotIn('status', ['Cancelled'])
            ->get();

        foreach ($radOrders as $order) {
            $services[] = [
                'service_type' => 'Radiology',
                'description' => 'Radiology Order #'.$order->order_number,
                'reference_type' => 'radiology_orders',
                'reference_id' => $order->id,
                'quantity' => 1,
                'unit_price' => max(0, ($order->net_amount ?? 0) - ($order->paid_amount ?? 0)),
                'discount' => 0,
            ];
        }

        // 5. Pharmacy Dispensings — unpaid
        $dispensings = Dispensing::where('patient_id', $patientId)
            ->where('payment_status', '!=', 'Paid')
            ->get();

        foreach ($dispensings as $disp) {
            $services[] = [
                'service_type' => 'Pharmacy',
                'description' => 'Pharmacy Dispensing #'.$disp->dispense_number,
                'reference_type' => 'dispensings',
                'reference_id' => $disp->id,
                'quantity' => 1,
                'unit_price' => $disp->total_amount ?? 0,
                'discount' => 0,
            ];
        }

        // 6. Blood Bank — fulfilled, not yet billed
        $bloodRequests = BloodRequest::where('patient_id', $patientId)
            ->where('status', 'Fulfilled')
            ->whereDoesntHave('billItems', function ($query) {
                $query->where('reference_type', 'blood_requests');
            })
            ->get();

        foreach ($bloodRequests as $req) {
            $charge = BillServiceCharge::where('blood_component', $req->component)
                ->where('is_active', 1)
                ->first();

            $services[] = [
                'service_type' => 'Blood Bank',
                'description' => 'Blood Issue: '.$req->blood_group.' ('.$req->component.')',
                'reference_type' => 'blood_requests',
                'reference_id' => $req->id,
                'quantity' => $req->units_approved,
                'unit_price' => $charge?->default_price ?? 0,
                'discount' => 0,
            ];
        }

        // 7. Death Certificate Fee — unpaid, not already billed
        $deathCertificates = DeathCertificate::whereHas('mortuaryRecord', function ($q) use ($patientId) {
            $q->where('patient_id', $patientId);
        })
            ->where('fee_charged', '>', 0)
            ->where('fee_paid', false)
            ->whereNull('bill_id')
            ->whereDoesntHave('billItems', function ($query) {
                $query->where('reference_type', 'death_certificates');
            })
            ->get();

        foreach ($deathCertificates as $cert) {
            $services[] = [
                'service_type' => 'Service',
                'description' => 'Death Certificate Fee — '.$cert->certificate_number.' ('.$cert->purpose.')',
                'reference_type' => 'death_certificates',
                'reference_id' => $cert->id,
                'quantity' => $cert->total_copies,
                'unit_price' => $cert->fee_charged,
                'discount' => 0,
            ];
        }

        // 8. Mortuary Body Storage Charges — not yet billed
        $mortuaryRecord = MortuaryRecord::where('patient_id', $patientId)
            ->whereNotNull('locker_number')
            ->whereDoesntHave('billItems', function ($query) {
                $query->where('reference_type', 'mortuary_records');
            })
            ->first();

        if ($mortuaryRecord) {
            $charge = BillServiceCharge::where('name', 'like', '%Mortuary%')
                ->where('is_active', 1)
                ->first();

            if ($charge) {
                $days = max(1, $mortuaryRecord->days_in_mortuary);

                $services[] = [
                    'service_type' => 'Service',
                    'description' => 'Mortuary Storage — '.$mortuaryRecord->mortuary_id.' ('.$days.' day(s))',
                    'reference_type' => 'mortuary_records',
                    'reference_id' => $mortuaryRecord->id,
                    'quantity' => $days,
                    'unit_price' => $charge->default_price,
                    'discount' => 0,
                ];
            }
        }

        return $services;
    }
}

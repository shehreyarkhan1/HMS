<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabOrder;
use App\Models\LabOrderItem;
use App\Models\LabResult;
use App\Models\LabSample;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;


class LabOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = LabOrder::with(['patient', 'doctor', 'items'])
            ->latest('order_date');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => LabOrder::whereDate('order_date', today())->count(),
            'pending' => LabOrder::whereDate('order_date', today())->where('status', 'Pending')->count(),
            'processing' => LabOrder::whereDate('order_date', today())->whereIn('status', ['Sample Collected', 'Processing'])->count(),
            'completed' => LabOrder::whereDate('order_date', today())->where('status', 'Completed')->count(),
            'stat' => LabOrder::whereDate('order_date', today())->where('priority', 'STAT')->whereNotIn('status', ['Completed', 'Cancelled'])->count(),
        ];

        return view('laboratory.lab_index', compact('orders', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create(Request $request)
    {
        $patients = Patient::orderBy('name')->get(['id', 'name', 'mrn', 'gender', 'date_of_birth']);
        $doctors = Doctor::with('employee')
            ->where('is_active', true)
            ->join('employees', 'doctors.employee_id', '=', 'employees.id')
            ->orderBy('employees.first_name')
            ->select('doctors.*')
            ->get();
        $tests = LabTest::with('category', 'sampleType')
            ->active()
            ->orderBy('name')
            ->get();

        // Group tests by category for the UI
        $testsByCategory = $tests->groupBy('category.name');

        // Pre-fill from appointment if given
        $selectedPatient = null;
        $selectedDoctor = null;
        if ($request->filled('appointment_id')) {
            $appt = Appointment::with('patient', 'doctor')->find($request->appointment_id);
            if ($appt) {
                $selectedPatient = $appt->patient;
                $selectedDoctor = $appt->doctor;
            }
        }

        return view('laboratory.lab_create', compact(
            'patients',
            'doctors',
            'testsByCategory',
            'selectedPatient',
            'selectedDoctor'
        ));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'order_date' => 'required|date',
            'priority' => 'required|in:Routine,Urgent,STAT',
            'tests' => 'required|array|min:1',
            'tests.*.id' => 'required|exists:lab_tests,id',
            'tests.*.price' => 'required|numeric|min:0',
            'tests.*.discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        DB::transaction(function () use ($request) {
            $order = LabOrder::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'order_date' => $request->order_date,
                'priority' => $request->priority,
                'notes' => $request->notes,
                'discount' => $request->discount ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
            ]);

            foreach ($request->tests as $testData) {
                LabOrderItem::create([
                    'lab_order_id' => $order->id,
                    'lab_test_id' => $testData['id'],
                    'price' => $testData['price'],
                    'discount' => $testData['discount'] ?? 0,
                ]);
            }

            // Sync payment status
            $order->syncPaymentStatus();
        });

        return redirect()->route('lab.orders.index')
            ->with('success', 'Lab order created successfully.');
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(LabOrder $labOrder)
    {
        $labOrder->load([
            'patient',
            'doctor',
            'appointment',
            'items.labTest.category',
            'items.sample.sampleType',
            'items.result.verifiedBy',
            'samples.sampleType',
        ]);

        return view('laboratory.lab_show', compact('labOrder'));
    }

    // ─────────────────────────────────────────────
    //  COLLECT SAMPLE
    // ─────────────────────────────────────────────
    public function collectSample(Request $request, LabOrder $labOrder)
    {
        $request->validate([
            'sample_type_id' => 'required|exists:lab_sample_types,id',
            'collected_by' => 'required|string|max:100',
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:lab_order_items,id',
        ]);

        DB::transaction(function () use ($request, $labOrder) {
            $sample = LabSample::create([
                'lab_order_id' => $labOrder->id,
                'sample_type_id' => $request->sample_type_id,
                'collected_by' => $request->collected_by,
                'collected_at' => now(),
                'status' => 'Collected',
                'notes' => $request->notes,
            ]);

            // Link items to this sample
            LabOrderItem::whereIn('id', $request->item_ids)
                ->update(['lab_sample_id' => $sample->id]);

            // Update order status
            $labOrder->update(['status' => 'Sample Collected']);
        });

        return back()->with('success', 'Sample collected successfully.');
    }

    // ─────────────────────────────────────────────
    //  ENTER RESULTS
    // ─────────────────────────────────────────────
    public function storeResults(Request $request, LabOrder $labOrder)
    {
        $request->validate([
            'results' => 'required|array',
            'results.*.item_id' => 'required|exists:lab_order_items,id',
            'results.*.result_value' => 'nullable|string|max:255',
            'results.*.unit' => 'nullable|string|max:50',
            'results.*.normal_range' => 'nullable|string|max:100',
            'results.*.remarks' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $labOrder) {
            foreach ($request->results as $data) {
                if (empty($data['result_value']))
                    continue;

                $item = LabOrderItem::find($data['item_id']);

                $result = LabResult::updateOrCreate(
                    ['lab_order_item_id' => $item->id],
                    [
                        'result_value' => $data['result_value'],
                        'unit' => $data['unit'] ?? $item->labTest->unit,
                        'normal_range' => $data['normal_range'] ?? $item->labTest->normal_range,
                        'remarks' => $data['remarks'] ?? null,
                        'reported_at' => now(),
                    ]
                );

                // Auto flag
                $result->autoSetFlag();

                // Mark item completed
                $item->update([
                    'status' => 'Completed',
                    'completed_at' => now(),
                    'technician_name' => auth()->user()->name ?? 'Lab Staff',
                ]);
            }

            // Check if all items are completed → update order status
            $allDone = $labOrder->items()->where('status', '!=', 'Completed')
                ->where('status', '!=', 'Cancelled')
                ->doesntExist();

            if ($allDone) {
                $labOrder->update(['status' => 'Completed']);
            } else {
                $labOrder->update(['status' => 'Processing']);
            }
        });

        return back()->with('success', 'Results saved successfully.');
    }

    // ─────────────────────────────────────────────
    //  VERIFY RESULT
    // ─────────────────────────────────────────────
    public function verifyResult(LabResult $labResult)
    {
        $labResult->update([
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Result verified.');
    }

    // ─────────────────────────────────────────────
    //  RECORD PAYMENT
    // ─────────────────────────────────────────────
    public function recordPayment(Request $request, LabOrder $labOrder)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $labOrder->update([
            'paid_amount' => $labOrder->paid_amount + $request->paid_amount,
        ]);
        $labOrder->syncPaymentStatus();

        return back()->with('success', 'Payment recorded.');
    }

    // ─────────────────────────────────────────────
    //  CANCEL
    // ─────────────────────────────────────────────
    public function cancel(LabOrder $labOrder)
    {
        abort_if($labOrder->status === 'Completed', 403, 'Completed orders cannot be cancelled.');

        $labOrder->update(['status' => 'Cancelled']);
        $labOrder->items()->where('status', 'Pending')->update(['status' => 'Cancelled']);

        return back()->with('success', 'Order cancelled.');
    }

    // ─────────────────────────────────────────────
    //  DELIVER REPORT
    // ─────────────────────────────────────────────
    public function deliverReport(LabOrder $labOrder)
    {
        abort_if($labOrder->status !== 'Completed', 403, 'Order must be completed before delivery.');

        $labOrder->update([
            'report_delivered' => true,
            'report_delivered_at' => now(),
        ]);

        return back()->with('success', 'Report marked as delivered.');
    }
}

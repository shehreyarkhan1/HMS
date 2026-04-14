<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RadiologyOrder;
use App\Models\RadiologyOrderItem;
use App\Models\RadiologyReport;
use App\Models\RadiologyConsent;
use App\Models\RadiologyImage;
use App\Models\RadiologyModality;
use App\Models\RadiologyExam;
use App\Models\RadiologyBodyPart;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class RadiologyController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = RadiologyOrder::with(['patient', 'doctor', 'items.exam.modality'])
            ->latest('order_date');

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('modality_id')) {
            $query->whereHas('items.exam', fn($q) => $q->where('modality_id', $request->modality_id));
        }
        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => RadiologyOrder::today()->count(),
            'pending' => RadiologyOrder::today()->whereIn('status', ['Pending', 'Scheduled'])->count(),
            'inprogress' => RadiologyOrder::today()->whereIn('status', ['In Progress', 'Scan Completed', 'Reporting'])->count(),
            'reported' => RadiologyOrder::today()->whereIn('status', ['Reported', 'Verified'])->count(),
            'stat' => RadiologyOrder::today()->where('priority', 'STAT')
                ->whereNotIn('status', ['Verified', 'Delivered', 'Cancelled'])->count(),
            'critical' => RadiologyOrder::today()->withCritical()->count(),
        ];

        $modalities = RadiologyModality::active()->orderBy('name')->get();

        return view('radiology.radiology_index', compact('orders', 'stats', 'modalities'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create(Request $request)
    {
        $patients = Patient::orderBy('name')->get(['id', 'name', 'mrn', 'gender', 'date_of_birth']);
        $doctors = Doctor::orderBy('name')->get(['id', 'name']);
        $modalities = RadiologyModality::with('exams.bodyPart')->active()->orderBy('name')->get();
        $examsByModality = $modalities->mapWithKeys(fn($m) => [
            $m->id => [
                'modality' => $m,
                'exams' => $m->exams->where('is_active', true)->values(),
            ]
        ]);

        $selectedPatient = null;
        $selectedDoctor = null;

        if ($request->filled('appointment_id')) {
            $appt = Appointment::with('patient', 'doctor')->find($request->appointment_id);
            if ($appt) {
                $selectedPatient = $appt->patient;
                $selectedDoctor = $appt->doctor;
            }
        }

        return view('radiology.radiology_create', compact(
            'patients',
            'doctors',
            'examsByModality',
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
            'clinical_history' => 'nullable|string|max:2000',
            'clinical_indication' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'appointment_id' => 'nullable|exists:appointments,id',
            'scheduled_at' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'exams' => 'required|array|min:1',
            'exams.*.id' => 'required|exists:radiology_exams,id',
            'exams.*.price' => 'required|numeric|min:0',
            'exams.*.discount' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $order = RadiologyOrder::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'order_date' => $request->order_date,
                'scheduled_at' => $request->scheduled_at,
                'priority' => $request->priority,
                'clinical_history' => $request->clinical_history,
                'clinical_indication' => $request->clinical_indication,
                'notes' => $request->notes,
                'discount' => $request->discount ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
                'status' => $request->scheduled_at ? 'Scheduled' : 'Pending',
            ]);

            foreach ($request->exams as $examData) {
                RadiologyOrderItem::create([
                    'radiology_order_id' => $order->id,
                    'radiology_exam_id' => $examData['id'],
                    'price' => $examData['price'],
                    'discount' => $examData['discount'] ?? 0,
                ]);
            }

            // Auto-create consent if any exam requires it
            $needsConsent = RadiologyExam::whereIn('id', collect($request->exams)->pluck('id'))
                ->where('requires_consent', true)->exists();

            if ($needsConsent) {
                $order->consents()->create([
                    'consent_type' => 'Contrast/Procedure Consent',
                ]);
            }

            $order->syncPaymentStatus();
        });

        return redirect()->route('radiology.orders.index')
            ->with('success', 'Radiology order created successfully.');
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(RadiologyOrder $radiologyOrder)
    {
        $radiologyOrder->load([
            'patient',
            'doctor',
            'appointment',
            'items.exam.modality',
            'items.exam.bodyPart',
            'items.report.reportedBy',
            'items.report.verifiedBy',
            'items.images',
            'consents',
        ]);

        return view('radiology.radiology_show', compact('radiologyOrder'));
    }

    // ─────────────────────────────────────────────
    //  START SCAN  (In Progress)
    // ─────────────────────────────────────────────
    public function startScan(Request $request, RadiologyOrder $radiologyOrder)
    {
        $request->validate([
            'technician_name' => 'required|string|max:100',
            'equipment_used' => 'nullable|string|max:100',
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:radiology_order_items,id',
        ]);

        DB::transaction(function () use ($request, $radiologyOrder) {
            RadiologyOrderItem::whereIn('id', $request->item_ids)
                ->where('radiology_order_id', $radiologyOrder->id)
                ->update([
                    'status' => 'In Progress',
                    'technician_name' => $request->technician_name,
                    'equipment_used' => $request->equipment_used,
                ]);

            $radiologyOrder->update(['status' => 'In Progress']);
        });

        return back()->with('success', 'Scan started.');
    }

    // ─────────────────────────────────────────────
    //  COMPLETE SCAN  (mark scanned, upload images)
    // ─────────────────────────────────────────────
    public function completeScan(Request $request, RadiologyOrder $radiologyOrder)
    {
        $request->validate([
            'item_id' => 'required|exists:radiology_order_items,id',
            'contrast_used' => 'boolean',
            'contrast_agent' => 'nullable|string|max:100',
            'contrast_dose_ml' => 'nullable|numeric|min:0',
            'contrast_reaction' => 'boolean',
            'contrast_reaction_notes' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,pdf,dcm|max:20480',
        ]);

        DB::transaction(function () use ($request, $radiologyOrder) {
            $item = RadiologyOrderItem::findOrFail($request->item_id);

            $item->update([
                'status' => 'Scan Completed',
                'scanned_at' => now(),
                'contrast_used' => $request->boolean('contrast_used'),
                'contrast_agent' => $request->contrast_agent,
                'contrast_dose_ml' => $request->contrast_dose_ml,
                'contrast_reaction' => $request->boolean('contrast_reaction'),
                'contrast_reaction_notes' => $request->contrast_reaction_notes,
            ]);

            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $idx => $file) {
                    $path = $file->store("radiology/images/{$radiologyOrder->order_number}", 'public');
                    RadiologyImage::create([
                        'radiology_order_item_id' => $item->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => in_array($file->getExtension(), ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf',
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'is_primary' => $idx === 0,
                    ]);
                }
            }

            // Update order status
            $allScanned = $radiologyOrder->items()
                ->whereNotIn('status', ['Scan Completed', 'Cancelled'])
                ->doesntExist();

            $radiologyOrder->update([
                'status' => $allScanned ? 'Scan Completed' : 'In Progress'
            ]);
        });

        return back()->with('success', 'Scan completed. Images uploaded.');
    }

    // ─────────────────────────────────────────────
    //  STORE REPORT
    // ─────────────────────────────────────────────
    public function storeReport(Request $request, RadiologyOrder $radiologyOrder)
    {
        $request->validate([
            'item_id' => 'required|exists:radiology_order_items,id',
            'findings' => 'required|string',
            'impression' => 'required|string',
            'recommendations' => 'nullable|string',
            'comparison' => 'nullable|string|max:255',
            'is_critical' => 'boolean',
            'critical_notes' => 'required_if:is_critical,true|nullable|string',
            'critical_notified_to' => 'required_if:is_critical,true|nullable|string',
        ]);

        DB::transaction(function () use ($request, $radiologyOrder) {
            $item = RadiologyOrderItem::findOrFail($request->item_id);

            $report = RadiologyReport::updateOrCreate(
                ['radiology_order_item_id' => $item->id],
                [
                    'findings' => $request->findings,
                    'impression' => $request->impression,
                    'recommendations' => $request->recommendations,
                    'comparison' => $request->comparison,
                    'is_critical' => $request->boolean('is_critical'),
                    'critical_notes' => $request->critical_notes,
                    'critical_notified_to' => $request->critical_notified_to,
                    'critical_notified_at' => $request->boolean('is_critical') ? now() : null,
                    'status' => 'Pending Verification',
                    'reported_by' => auth()->id(),
                    'reported_at' => now(),
                ]
            );

            $item->update(['status' => 'Reported']);

            // Check if all items reported
            $allReported = $radiologyOrder->items()
                ->whereNotIn('status', ['Reported', 'Cancelled'])
                ->doesntExist();

            $radiologyOrder->update([
                'status' => $allReported ? 'Reported' : 'Reporting'
            ]);
        });

        return back()->with('success', 'Report saved successfully.');
    }

    // ─────────────────────────────────────────────
    //  VERIFY REPORT
    // ─────────────────────────────────────────────
    public function verifyReport(RadiologyReport $radiologyReport)
    {
        DB::transaction(function () use ($radiologyReport) {
            $radiologyReport->update([
                'is_verified' => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'status' => 'Verified',
            ]);

            $order = $radiologyReport->orderItem->order;

            $allVerified = $order->items()
                ->whereHas('report', fn($q) => $q->where('is_verified', false))
                ->doesntExist();

            if ($allVerified) {
                $order->update(['status' => 'Verified']);
            }
        });

        return back()->with('success', 'Report verified.');
    }

    // ─────────────────────────────────────────────
    //  AMEND REPORT
    // ─────────────────────────────────────────────
    public function amendReport(Request $request, RadiologyReport $radiologyReport)
    {
        $request->validate([
            'amendment_reason' => 'required|string|max:500',
            'findings' => 'required|string',
            'impression' => 'required|string',
        ]);

        $radiologyReport->update([
            'findings' => $request->findings,
            'impression' => $request->impression,
            'recommendations' => $request->recommendations,
            'amendment_reason' => $request->amendment_reason,
            'amended_by' => auth()->id(),
            'amended_at' => now(),
            'status' => 'Amended',
            'is_verified' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);

        return back()->with('success', 'Report amended. Pending re-verification.');
    }

    // ─────────────────────────────────────────────
    //  RECORD PAYMENT
    // ─────────────────────────────────────────────
    public function recordPayment(Request $request, RadiologyOrder $radiologyOrder)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
        ]);

        $radiologyOrder->increment('paid_amount', $request->paid_amount);
        $radiologyOrder->syncPaymentStatus();

        return back()->with('success', 'Payment recorded.');
    }

    // ─────────────────────────────────────────────
    //  DELIVER REPORT
    // ─────────────────────────────────────────────
    public function deliverReport(Request $request, RadiologyOrder $radiologyOrder)
    {
        abort_if(
            !in_array($radiologyOrder->status, ['Verified', 'Reported']),
            403,
            'Report must be completed before delivery.'
        );

        $radiologyOrder->update([
            'status' => 'Delivered',
            'report_delivered' => true,
            'report_delivered_at' => now(),
            'delivered_to' => $request->delivered_to ?? $radiologyOrder->patient->name,
        ]);

        return back()->with('success', 'Report marked as delivered.');
    }

    // ─────────────────────────────────────────────
    //  SCHEDULE
    // ─────────────────────────────────────────────
    public function schedule(Request $request, RadiologyOrder $radiologyOrder)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $radiologyOrder->update([
            'scheduled_at' => $request->scheduled_at,
            'status' => 'Scheduled',
        ]);

        return back()->with('success', 'Order scheduled for ' . \Carbon\Carbon::parse($request->scheduled_at)->format('d M Y H:i'));
    }

    // ─────────────────────────────────────────────
    //  CANCEL
    // ─────────────────────────────────────────────
    public function cancel(Request $request, RadiologyOrder $radiologyOrder)
    {
        abort_unless($radiologyOrder->isCancellable(), 403, 'This order cannot be cancelled.');

        DB::transaction(function () use ($radiologyOrder) {
            $radiologyOrder->update(['status' => 'Cancelled']);
            $radiologyOrder->items()
                ->whereIn('status', ['Pending', 'Scheduled'])
                ->update(['status' => 'Cancelled']);
        });

        return back()->with('success', 'Order cancelled.');
    }
}

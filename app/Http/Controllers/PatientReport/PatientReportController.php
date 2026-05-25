<?php

namespace App\Http\Controllers\PatientReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\LabOrder;
use App\Models\RadiologyOrder;
use App\Models\Prescription;
use App\Models\OtSchedule;
use App\Models\BloodRequest;
use App\Models\Bill;
use App\Models\Bed;
use App\Models\MortuaryRecord;
use App\Models\Doctor;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class PatientReportController extends Controller
{
 /**
     * Search / index — patient select karne ka page
     */
    public function index(Request $request)
    {
        $patients = collect();

        if ($request->filled('search')) {
            $q = $request->search;
            $patients = Patient::where('mrn', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhere('cnic', 'like', "%{$q}%")
                ->withCount([
                    'appointments',
                    'labOrders',
                    'radiologyOrders',
                    'prescriptions',
                    'otSchedules',
                    'bloodRequests',
                    'bills',
                ])
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        return view('reports.patient_report_index', compact('patients'));
    }

    /**
     * Complete patient report — ek patient ki poori history
     */
    public function show(Patient $patient)
    {
        // ─── 1. Core patient info + assigned doctor ───────────────────────────
        $patient->load([
            'doctor.employee',  // assigned doctor
        ]);

        // ─── 2. Appointments ──────────────────────────────────────────────────
        $appointments = Appointment::with('doctor.employee')
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        // ─── 3. Lab Orders + items + results ─────────────────────────────────
        $labOrders = LabOrder::with([
            'doctor.employee',
            'items.labTest.category',
            'items.Sample.sampleType',
            'items.result',
        ])
            ->where('patient_id', $patient->id)
            ->orderBy('order_date', 'desc')
            ->get();

        $abnormalResults = $labOrders->flatMap(fn($o) => $o->items)
            ->filter(fn($i) => $i->result && $i->result->is_abnormal);

        // ─── 4. Radiology Orders + items + reports + images ──────────────────
        $radiologyOrders = RadiologyOrder::with([
            'doctor.employee',
            'items.exam.modality',
            'items.exam.bodyPart',
            'items.report',
            'items.images',
        ])
            ->where('patient_id', $patient->id)
            ->orderBy('order_date', 'desc')
            ->get();

        $criticalRadiology = $radiologyOrders->flatMap(fn($o) => $o->items)
            ->filter(fn($i) => $i->report && $i->report->is_critical);

        // ─── 5. Prescriptions + items + dispensing ───────────────────────────
        $prescriptions = Prescription::with([
            'doctor.employee',
            'items.medicine',
            'dispensings.items',
        ])
            ->where('patient_id', $patient->id)
            ->orderBy('prescribed_date', 'desc')
            ->get();

        // ─── 6. OT Schedules ─────────────────────────────────────────────────
        $otSchedules = OtSchedule::with([
            'surgeon.employee',
            'anesthesiologist.employee',
            'otRoom',
            'teamMembers.doctor.employee',
            'teamMembers.employee',
        ])
            ->where('patient_id', $patient->id)
            ->orderBy('scheduled_date', 'desc')
            ->get();

        // ─── 7. Blood Bank Requests + issues + crossmatches ──────────────────
        $bloodRequests = BloodRequest::with([
            'doctor.employee',
            'issues',
            'crossmatches',
        ])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // ─── 8. Bills + items + payments ─────────────────────────────────────
        $bills = Bill::with([
            'items',
            'payments',
            'createdBy',
        ])
            ->where('patient_id', $patient->id)
            ->orderBy('bill_date', 'desc')
            ->get();

        $billingTotals = [
            'subtotal'       => $bills->sum('subtotal'),
            'discount'       => $bills->sum('discount_amount'),
            'tax'            => $bills->sum('tax_amount'),
            'net'            => $bills->sum('net_amount'),
            'paid'           => $bills->sum('paid_amount'),
            'due'            => $bills->sum('due_amount'),
        ];

        // ─── 9. Bed / Ward Admissions ─────────────────────────────────────────
        $bedHistory = Bed::with('ward')
            ->where('patient_id', $patient->id)
            ->orderBy('admitted_at', 'desc')
            ->get();

        // current active bed
        $activeBed = $bedHistory->first(fn($b) => $b->status === 'Occupied');

        // ─── 10. Mortuary (if deceased) ───────────────────────────────────────
        $mortuaryRecord = null;
        if ($patient->status === 'Deceased') {
            $mortuaryRecord = MortuaryRecord::with([
                'declaredBy.employee',
                'bodyRelease',
                'deathCertificates',
            ])->where('patient_id', $patient->id)->latest()->first();
        }

        // ─── 11. Summary Stats ────────────────────────────────────────────────
        $stats = [
            'appointments'      => $appointments->count(),
            'lab_orders'        => $labOrders->count(),
            'abnormal_results'  => $abnormalResults->count(),
            'radiology_orders'  => $radiologyOrders->count(),
            'critical_reports'  => $criticalRadiology->count(),
            'prescriptions'     => $prescriptions->count(),
            'ot_surgeries'      => $otSchedules->count(),
            'blood_requests'    => $bloodRequests->count(),
            'bills'             => $bills->count(),
            'total_billed'      => $billingTotals['net'],
            'total_due'         => $billingTotals['due'],
        ];

        // ─── 12. Unified Timeline ─────────────────────────────────────────────
        $timeline = $this->buildTimeline(
            $patient,
            $appointments,
            $labOrders,
            $radiologyOrders,
            $prescriptions,
            $otSchedules,
            $bloodRequests,
            $bills,
            $bedHistory,
            $mortuaryRecord
        );

        return view('reports.patient_report_show', compact(
            'patient',
            'appointments',
            'labOrders',
            'abnormalResults',
            'radiologyOrders',
            'criticalRadiology',
            'prescriptions',
            'otSchedules',
            'bloodRequests',
            'bills',
            'billingTotals',
            'bedHistory',
            'activeBed',
            'mortuaryRecord',
            'stats',
            'timeline'
        ));
    }

    /**
     * Build a sorted chronological timeline from all modules.
     * Each item: [ datetime, type, icon, color, title, subtitle, badges[], reference ]
     */
    private function buildTimeline(
        Patient        $patient,
        $appointments,
        $labOrders,
        $radiologyOrders,
        $prescriptions,
        $otSchedules,
        $bloodRequests,
        $bills,
        $bedHistory,
        $mortuaryRecord
    ): \Illuminate\Support\Collection {
        $events = collect();

        // Registration
        $events->push([
            'datetime' => $patient->created_at,
            'type'     => 'registration',
            'icon'     => 'bi-person-plus-fill',
            'color'    => '#1d4ed8',
            'title'    => 'Patient Registered',
            'subtitle' => "MRN {$patient->mrn} — {$patient->patient_type}",
            'badges'   => [$patient->mrn, $patient->patient_type],
            'ref'      => null,
        ]);

        // Appointments
        foreach ($appointments as $a) {
            $events->push([
                'datetime' => $a->created_at,
                'type'     => 'appointment',
                'icon'     => 'bi-calendar-check-fill',
                'color'    => '#0891b2',
                'title'    => "Appointment — {$a->type}",
                'subtitle' => ($a->doctor ? $a->doctor->employee->first_name . ' ' . $a->doctor->employee->last_name : '—')
                    . ($a->reason ? ' · ' . \Str::limit($a->reason, 60) : ''),
                'badges'   => [$a->status, $a->type],
                'ref'      => $a->id,
            ]);
        }

        // Lab Orders
        foreach ($labOrders as $lo) {
            $abnCount = $lo->items->filter(fn($i) => $i->result && $i->result->is_abnormal)->count();
            $events->push([
                'datetime' => $lo->created_at,
                'type'     => 'lab',
                'icon'     => 'bi-flask-fill',
                'color'    => '#d97706',
                'title'    => "Lab Order — {$lo->order_number}",
                'subtitle' => $lo->items->count() . ' test(s) · '
                    . ($lo->doctor ? $lo->doctor->employee->first_name : '—'),
                'badges'   => array_filter([
                    $lo->status,
                    $lo->priority !== 'Routine' ? $lo->priority : null,
                    $abnCount > 0 ? "{$abnCount} Abnormal" : null,
                ]),
                'ref'      => $lo->id,
            ]);
        }

        // Radiology Orders
        foreach ($radiologyOrders as $ro) {
            $hasCritical = $ro->items->contains(fn($i) => $i->report && $i->report->is_critical);
            $events->push([
                'datetime' => $ro->created_at,
                'type'     => 'radiology',
                'icon'     => 'bi-radioactive',
                'color'    => '#7c3aed',
                'title'    => "Radiology — {$ro->order_number}",
                'subtitle' => $ro->items->map(fn($i) => $i->exam->modality->name ?? '')->implode(', ')
                    . ' · ' . ($ro->doctor ? $ro->doctor->employee->first_name : '—'),
                'badges'   => array_filter([
                    $ro->status,
                    $ro->priority !== 'Routine' ? $ro->priority : null,
                    $hasCritical ? 'Critical Finding' : null,
                ]),
                'ref'      => $ro->id,
            ]);
        }

        // Prescriptions
        foreach ($prescriptions as $rx) {
            $events->push([
                'datetime' => $rx->created_at,
                'type'     => 'pharmacy',
                'icon'     => 'bi-capsule-pill',
                'color'    => '#059669',
                'title'    => "Prescription — {$rx->prescription_number}",
                'subtitle' => $rx->items->count() . ' medicine(s)'
                    . ($rx->diagnosis ? ' · ' . \Str::limit($rx->diagnosis, 50) : ''),
                'badges'   => [$rx->status],
                'ref'      => $rx->id,
            ]);
        }

        // OT Schedules
        foreach ($otSchedules as $ot) {
            $events->push([
                'datetime' => $ot->created_at,
                'type'     => 'ot',
                'icon'     => 'bi-activity',
                'color'    => '#dc2626',
                'title'    => "Surgery — {$ot->surgery_id}",
                'subtitle' => \Str::limit($ot->procedure_name, 60)
                    . ' · ' . ($ot->surgeon ? $ot->surgeon->employee->first_name : '—'),
                'badges'   => [$ot->status, $ot->surgery_type],
                'ref'      => $ot->id,
            ]);
        }

        // Blood Requests
        foreach ($bloodRequests as $br) {
            $events->push([
                'datetime' => $br->created_at,
                'type'     => 'blood',
                'icon'     => 'bi-droplet-fill',
                'color'    => '#be123c',
                'title'    => "Blood Request — {$br->request_id}",
                'subtitle' => "{$br->units_required} unit(s) {$br->component} ({$br->blood_group})",
                'badges'   => array_filter([$br->status, $br->urgency !== 'Routine' ? $br->urgency : null]),
                'ref'      => $br->id,
            ]);
        }

        // Bills
        foreach ($bills as $b) {
            $events->push([
                'datetime' => $b->created_at,
                'type'     => 'billing',
                'icon'     => 'bi-receipt',
                'color'    => '#475569',
                'title'    => "Bill — {$b->bill_number}",
                'subtitle' => "Rs " . number_format($b->net_amount, 0) . ' · ' . $b->bill_type,
                'badges'   => [$b->payment_status, $b->status],
                'ref'      => $b->id,
            ]);
        }

        // Bed admissions
        foreach ($bedHistory as $bed) {
            if ($bed->admitted_at) {
                $events->push([
                    'datetime' => \Carbon\Carbon::parse($bed->admitted_at),
                    'type'     => 'admission',
                    'icon'     => 'bi-hospital-fill',
                    'color'    => '#0369a1',
                    'title'    => "Admitted — {$bed->ward->name}",
                    'subtitle' => "Bed {$bed->bed_number} · {$bed->type}",
                    'badges'   => [$bed->status],
                    'ref'      => $bed->id,
                ]);
            }
        }

        // Mortuary
        if ($mortuaryRecord) {
            $events->push([
                'datetime' => $mortuaryRecord->death_datetime,
                'type'     => 'mortuary',
                'icon'     => 'bi-file-earmark-medical-fill',
                'color'    => '#374151',
                'title'    => "Death Record — {$mortuaryRecord->mortuary_id}",
                'subtitle' => \Str::limit($mortuaryRecord->immediate_cause, 60),
                'badges'   => [$mortuaryRecord->manner_of_death],
                'ref'      => $mortuaryRecord->id,
            ]);
        }

        return $events->sortByDesc('datetime')->values();
    }
}

<?php

namespace App\Http\Controllers\Death;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeathCertificate;
use App\Models\BodyReleaseRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\MortuaryRecord;  // ← YEH ADD KAREIN

class MortuaryController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = MortuaryRecord::with(['patient', 'declaringDoctor.employee']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('mortuary_id', 'like', "%$s%")
                    ->orWhereHas('patient', fn ($q) => $q->where('name', 'like', "%$s%")
                        ->orWhere('mrn', 'like', "%$s%")
                    );
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('manner')) {
            $query->where('manner_of_death', $request->manner);
        }

        if ($request->filled('mlc')) {
            $query->where('is_medico_legal', $request->mlc === 'yes');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('death_datetime', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('death_datetime', '<=', $request->date_to);
        }

        $records = $query->orderBy('death_datetime', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => MortuaryRecord::count(),
            'admitted' => MortuaryRecord::where('status', 'Admitted')->count(),
            'pending_postmortem' => MortuaryRecord::where('postmortem_status', 'Pending')->count(),
            'unclaimed' => MortuaryRecord::where('status', 'Unclaimed')->count(),
            'mlc' => MortuaryRecord::where('is_medico_legal', true)
                ->whereNotIn('status', ['Released'])->count(),
            'today' => MortuaryRecord::whereDate('death_datetime', today())->count(),
        ];

        return view('mortuarydeath.mortuary_index', compact('records', 'stats'));
    }

    // ── CREATE ───────────────────────────────────────────────────────
    public function create(Request $request)
    {
        // Direct patient se aana (patient show page se)
        $patient = $request->filled('patient_id')
            ? Patient::findOrFail($request->patient_id)
            : null;

        $doctors = Doctor::with('employee')->where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();
        $patients = Patient::whereIn('status', ['Active', 'Admitted'])
            ->orderBy('name')->get();

        return view('mortuarydeath.mortuary_create', compact(
            'patient', 'doctors', 'employees', 'patients'
        ));
    }

    // ── STORE ────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'death_datetime' => 'required|date|before_or_equal:now',
            'death_location' => 'required|in:Ward,ICU,CCU,Emergency,OT,DOA,Outside Hospital',
            'ward' => 'nullable|string|max:100',
            'bed_number' => 'nullable|string|max:20',
            'immediate_cause' => 'required|string|max:255',
            'intermediate_cause' => 'nullable|string|max:255',
            'underlying_cause' => 'nullable|string|max:255',
            'contributing_cause' => 'nullable|string|max:255',
            'manner_of_death' => 'required|in:Natural,Accidental,Homicidal,Suicidal,Undetermined',
            'declared_by' => 'nullable|exists:doctors,id',
            'declared_at' => 'nullable|date',
            'locker_number' => 'nullable|string|max:20',
            'body_condition' => 'nullable|in:Normal,Decomposed,Burned,Traumatic,Other',
            'body_weight_kg' => 'nullable|numeric|min:0|max:300',
            'identification_marks' => 'nullable|string',
            'postmortem_required' => 'boolean',
            'postmortem_ordered_by' => 'nullable|in:Doctor,Police,Court,Hospital',
            'is_medico_legal' => 'boolean',
            'mlc_number' => 'nullable|string|max:50',
            'police_station' => 'nullable|string|max:100',
            'investigating_officer' => 'nullable|string|max:100',
            'fir_number' => 'nullable|string|max:50',
            'police_informed_at' => 'nullable|date',
            'nok_name' => 'nullable|string|max:100',
            'nok_relation' => 'nullable|string|max:50',
            'nok_cnic' => 'nullable|string|max:15',
            'nok_phone' => 'nullable|string|max:15',
            'nok_informed' => 'boolean',
            'nok_informed_at' => 'nullable|date',
            'admitted_by' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        // Postmortem status set karo
        $validated['postmortem_status'] = $validated['postmortem_required']
            ? 'Pending'
            : 'Not Required';

        // MLC case mein postmortem required hota hai
        if (! empty($validated['is_medico_legal'])) {
            $validated['postmortem_required'] = true;
            $validated['postmortem_status'] = 'Pending';
        }

        try {
            $record = MortuaryRecord::create($validated);

            return redirect()
                ->route('mortuary.show', $record->id)
                ->with('success', "Mortuary record {$record->mortuary_id} created successfully.");

        } catch (\Exception $e) {
            Log::error('Mortuary record creation failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to create record. '.$e->getMessage());
        }
    }

    // ── SHOW ─────────────────────────────────────────────────────────
    public function show(MortuaryRecord $mortuary)
    {
        $mortuary->load([
            'patient',
            'declaringDoctor.employee',
            'postmortemDoctor.employee',
            'admittedBy',
            'deathCertificates.signingDoctor.employee',
            'deathCertificates.issuedBy',
            'bodyRelease.releasedBy',
        ]);

        $doctors = Doctor::with('employee')->where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();

        return view('mortuarydeath.mortuary_show', compact('mortuary', 'doctors', 'employees'));
    }

    // ── EDIT ─────────────────────────────────────────────────────────
    public function edit(MortuaryRecord $mortuary)
    {
        if ($mortuary->status === 'Released') {
            return back()->with('error', 'Released records cannot be edited.');
        }

        $doctors = Doctor::with('employee')->where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();

        return view('mortuarydeath.mortuary_edit', compact('mortuary', 'doctors', 'employees'));
    }

    // ── UPDATE ───────────────────────────────────────────────────────
    public function update(Request $request, MortuaryRecord $mortuary)
    {
        if ($mortuary->status === 'Released') {
            return back()->with('error', 'Released records cannot be edited.');
        }

        $validated = $request->validate([
            'death_datetime' => 'required|date|before_or_equal:now',
            'death_location' => 'required|in:Ward,ICU,CCU,Emergency,OT,DOA,Outside Hospital',
            'ward' => 'nullable|string|max:100',
            'bed_number' => 'nullable|string|max:20',
            'immediate_cause' => 'required|string|max:255',
            'intermediate_cause' => 'nullable|string|max:255',
            'underlying_cause' => 'nullable|string|max:255',
            'contributing_cause' => 'nullable|string|max:255',
            'manner_of_death' => 'required|in:Natural,Accidental,Homicidal,Suicidal,Undetermined',
            'declared_by' => 'nullable|exists:doctors,id',
            'declared_at' => 'nullable|date',
            'locker_number' => 'nullable|string|max:20',
            'body_condition' => 'nullable|in:Normal,Decomposed,Burned,Traumatic,Other',
            'body_weight_kg' => 'nullable|numeric|min:0|max:300',
            'identification_marks' => 'nullable|string',
            'postmortem_required' => 'boolean',
            'postmortem_ordered_by' => 'nullable|in:Doctor,Police,Court,Hospital',
            'postmortem_status' => 'nullable|in:Not Required,Pending,In Progress,Completed',
            'postmortem_started_at' => 'nullable|date',
            'postmortem_completed_at' => 'nullable|date',
            'postmortem_by' => 'nullable|exists:doctors,id',
            'postmortem_findings' => 'nullable|string',
            'postmortem_report_number' => 'nullable|string|max:50',
            'is_medico_legal' => 'boolean',
            'mlc_number' => 'nullable|string|max:50',
            'police_station' => 'nullable|string|max:100',
            'investigating_officer' => 'nullable|string|max:100',
            'fir_number' => 'nullable|string|max:50',
            'police_informed_at' => 'nullable|date',
            'nok_name' => 'nullable|string|max:100',
            'nok_relation' => 'nullable|string|max:50',
            'nok_cnic' => 'nullable|string|max:15',
            'nok_phone' => 'nullable|string|max:15',
            'nok_informed' => 'boolean',
            'nok_informed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Status auto update based on postmortem
        if (
            isset($validated['postmortem_status']) &&
            $validated['postmortem_status'] === 'Completed' &&
            $mortuary->status === 'Postmortem Pending'
        ) {
            $validated['status'] = 'Postmortem Done';
        }

        $mortuary->update($validated);

        return redirect()
            ->route('mortuary.show', $mortuary->id)
            ->with('success', 'Mortuary record updated successfully.');
    }

    // ── UPDATE STATUS (AJAX/Quick) ────────────────────────────────────
    public function updateStatus(Request $request, MortuaryRecord $mortuary)
    {
        $request->validate([
            'status' => 'required|in:Admitted,Postmortem Pending,Postmortem Done,Certificate Issued,Released,Transferred,Unclaimed',
        ]);

        $mortuary->update(['status' => $request->status]);

        return back()->with('success', 'Status updated to '.$request->status);
    }

    // ── DESTROY ──────────────────────────────────────────────────────
    public function destroy(MortuaryRecord $mortuary)
    {
        if (in_array($mortuary->status, ['Released', 'Certificate Issued'])) {
            return back()->with('error', 'Cannot delete a released or certified record.');
        }

        try {
            $mortuary->delete();

            return redirect()
                ->route('mortuary.index')
                ->with('success', 'Mortuary record removed.');
        } catch (\Exception $e) {
            Log::error('Mortuary delete failed: '.$e->getMessage());

            return back()->with('error', 'Delete failed: '.$e->getMessage());
        }
    }
}

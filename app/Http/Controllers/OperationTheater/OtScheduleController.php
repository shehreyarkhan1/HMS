<?php

namespace App\Http\Controllers\OperationTheater;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtSchedule;
use App\Models\OtRoom;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\OtTeam;
use Illuminate\Support\Facades\DB;

class OtScheduleController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = OtSchedule::with([
            'patient',
            'surgeon.employee',
            'anesthesiologist.employee',
            'otRoom',
        ]);

        // ── FILTERS ──────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('surgery_id', 'like', "%$search%")
                    ->orWhere('procedure_name', 'like', "%$search%")
                    ->orWhere('diagnosis', 'like', "%$search%")
                    ->orWhereHas(
                        'patient',
                        fn($q) =>
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('mrn', 'like', "%$search%")
                    );
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('surgery_type')) {
            $query->where('surgery_type', $request->surgery_type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->date);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('scheduled_date', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('ot_room_id')) {
            $query->where('ot_room_id', $request->ot_room_id);
        }

        $schedules = $query->orderBy('scheduled_date')->orderBy('scheduled_time')->paginate(12)->withQueryString();

        // ── STATS ─────────────────────────────────────────────────────────
        $stats = [
            'today_total' => OtSchedule::whereDate('scheduled_date', today())->count(),
            'today_pending' => OtSchedule::whereDate('scheduled_date', today())
                ->whereIn('status', ['Scheduled', 'Confirmed', 'Preparing'])->count(),
            'in_progress' => OtSchedule::where('status', 'In-Progress')->count(),
            'this_month' => OtSchedule::whereMonth('scheduled_date', now()->month)
                ->whereYear('scheduled_date', now()->year)->count(),
            'completed_today' => OtSchedule::whereDate('scheduled_date', today())
                ->where('status', 'Completed')->count(),
            'emergency' => OtSchedule::whereDate('scheduled_date', today())
                ->where('surgery_type', 'Emergency')->count(),
        ];

        $rooms = OtRoom::active()->orderBy('room_code')->get();

        return view('operationtheater.operation_index', compact('schedules', 'stats', 'rooms'));
    }

    // ── CREATE ───────────────────────────────────────────────────────────

    public function create()
    {
        $patients = Patient::whereIn('status', ['Active', 'Admitted'])->orderBy('name')->get();

        // 1. Surgeons: Inhein bhi filter karein taake sirf 'Surgery' department wale aayen
        $surgeons = Doctor::with('employee')
            ->where('is_active', true)
            ->whereHas('employee', function ($q) {
                $q->where('department', 'LIKE', '%Surgery%');
            })->get();

        // 2. Anesthesiologists: Ismein LIKE use karein dash ke masle se bachne ke liye
        $anesthesiologists = Doctor::with('employee')
            ->where('is_active', true)
            ->whereHas('employee', function ($q) {
                // Hum 'Anesthesia' lafz ko search kar rahe hain
                $q->where('department', 'LIKE', '%Anesthesia%');
            })->get();

        $rooms = OtRoom::where('status', 'Available')->orderBy('room_code')->get();

        // Nursing staff
        $employees = Employee::where(function ($query) {
            $query->where('department', 'LIKE', '%Anesthesia%')
                ->orWhere('department', 'LIKE', '%Nursing%');
        })
            ->orderBy('first_name')
            ->get();

        return view('operationtheater.operation_create', compact(
            'patients',
            'surgeons',
            'anesthesiologists',
            'rooms',
            'employees'
        ));
    }

    // ── STORE ────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ot_room_id' => 'nullable|exists:ot_rooms,id',
            'surgeon_id' => 'required|exists:doctors,id',
            'anesthesiologist_id' => 'nullable|exists:doctors,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'estimated_duration_mins' => 'required|integer|min:10|max:600',
            'surgery_type' => 'required|in:Elective,Urgent,Emergency,Diagnostic',
            'priority' => 'required|in:Routine,Priority,Urgent,Emergency',
            'anesthesia_type' => 'nullable|in:General,Local,Regional,Spinal,Epidural,Sedation,None',
            'diagnosis' => 'required|string|max:500',
            'procedure_name' => 'required|string|max:500',
            'procedure_details' => 'nullable|string',
            'pre_op_instructions' => 'nullable|string',
            'consent_obtained' => 'boolean',
            'consent_by' => 'nullable|string|max:200',
            'pre_op_assessment_done' => 'boolean',
            'pre_op_assessment_notes' => 'nullable|string',
            'notes' => 'nullable|string',

            // Team members (array)
            'team' => 'nullable|array',
            'team.*.role' => 'required|string',
            'team.*.doctor_id' => 'nullable|exists:doctors,id',
            'team.*.employee_id' => 'nullable|exists:employees,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $schedule = OtSchedule::create(array_merge(
                $validated,
                [
                    'booked_by' => auth()->id(),
                    'consent_obtained' => $request->boolean('consent_obtained'),
                    'pre_op_assessment_done' => $request->boolean('pre_op_assessment_done'),
                ]
            ));

            // Save team members
            if (!empty($validated['team'])) {
                foreach ($validated['team'] as $member) {
                    if (!empty($member['doctor_id']) || !empty($member['employee_id'])) {
                        $schedule->teamMembers()->create($member);
                    }
                }
            }

            // Update OT room status if assigned
            if ($schedule->ot_room_id) {
                OtRoom::find($schedule->ot_room_id)->update(['status' => 'Occupied']);
            }
        });

        return redirect()->route('ot.index')
            ->with('success', 'Surgery scheduled successfully.');
    }

    // ── SHOW ─────────────────────────────────────────────────────────────

    public function show(OtSchedule $ot)
    {
        $ot->load([
            'patient',
            'otRoom',
            'surgeon.employee',
            'anesthesiologist.employee',
            'teamMembers.doctor.employee',
            'teamMembers.employee',
            'bookedBy',
        ]);

        return view('operationtheater.operation_show', compact('ot'));
    }

    // ── EDIT ─────────────────────────────────────────────────────────────

    public function edit(OtSchedule $ot)
    {
        abort_if(!$ot->isEditable(), 403, 'This surgery record cannot be edited.');

        $ot->load('teamMembers');

        $patients = Patient::whereIn('status', ['Active', 'Admitted'])->orderBy('name')->get();
        $surgeons = Doctor::with('employee')
            ->where('is_active', true)
            ->whereHas('employee', function ($q) {
                $q->where('department', 'LIKE', '%Surgery%');
            })->get();
        $anesthesiologists = Doctor::with('employee')
            ->where('is_active', true)
            ->whereHas('employee', function ($q) {
                // Hum 'Anesthesia' lafz ko search kar rahe hain
                $q->where('department', 'LIKE', '%Anesthesia%');
            })->get();
        $rooms = OtRoom::active()->orderBy('room_code')->get();
        $employees = Employee::where(function ($query) {
            $query->where('department', 'LIKE', '%Anesthesia%')
                ->orWhere('department', 'LIKE', '%Nursing%');
        })
            ->orderBy('first_name')
            ->get();

        return view('operationtheater.operation_edit', compact('ot', 'patients', 'surgeons', 'anesthesiologists', 'rooms', 'employees'));
    }

    // ── UPDATE ───────────────────────────────────────────────────────────

    public function update(Request $request, OtSchedule $ot)
    {
        abort_if(!$ot->isEditable(), 403, 'This surgery record cannot be edited.');

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ot_room_id' => 'nullable|exists:ot_rooms,id',
            'surgeon_id' => 'required|exists:doctors,id',
            'anesthesiologist_id' => 'nullable|exists:doctors,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'estimated_duration_mins' => 'required|integer|min:10|max:600',
            'surgery_type' => 'required|in:Elective,Urgent,Emergency,Diagnostic',
            'priority' => 'required|in:Routine,Priority,Urgent,Emergency',
            'anesthesia_type' => 'nullable|in:General,Local,Regional,Spinal,Epidural,Sedation,None',
            'status' => 'required|in:Scheduled,Confirmed,Preparing,In-Progress,Completed,Postponed,Cancelled',
            'diagnosis' => 'required|string|max:500',
            'procedure_name' => 'required|string|max:500',
            'procedure_details' => 'nullable|string',
            'pre_op_instructions' => 'nullable|string',
            'post_op_notes' => 'nullable|string',
            'complications' => 'nullable|string',
            'post_op_destination' => 'nullable|string|max:200',
            'consent_obtained' => 'boolean',
            'consent_by' => 'nullable|string|max:200',
            'pre_op_assessment_done' => 'boolean',
            'pre_op_assessment_notes' => 'nullable|string',
            'postpone_reason' => 'nullable|string|max:500',
            'cancellation_reason' => 'nullable|string|max:500',
            'rescheduled_date' => 'nullable|date',
            'notes' => 'nullable|string',

            'team' => 'nullable|array',
            'team.*.role' => 'required|string',
            'team.*.doctor_id' => 'nullable|exists:doctors,id',
            'team.*.employee_id' => 'nullable|exists:employees,id',
        ]);

        DB::transaction(function () use ($validated, $request, $ot) {
            // Free old room if changed
            if ($ot->ot_room_id && $ot->ot_room_id !== ($validated['ot_room_id'] ?? null)) {
                $this->freeRoom($ot->ot_room_id);
            }

            $ot->update(array_merge($validated, [
                'consent_obtained' => $request->boolean('consent_obtained'),
                'pre_op_assessment_done' => $request->boolean('pre_op_assessment_done'),
            ]));

            // Sync team
            $ot->teamMembers()->delete();
            if (!empty($validated['team'])) {
                foreach ($validated['team'] as $member) {
                    if (!empty($member['doctor_id']) || !empty($member['employee_id'])) {
                        $ot->teamMembers()->create($member);
                    }
                }
            }

            // Update new room status
            if ($ot->ot_room_id && in_array($ot->status, ['Scheduled', 'Confirmed', 'Preparing', 'In-Progress'])) {
                OtRoom::find($ot->ot_room_id)->update(['status' => 'Occupied']);
            }

            // Free room when done
            if ($ot->ot_room_id && in_array($ot->status, ['Completed', 'Cancelled', 'Postponed'])) {
                $this->freeRoom($ot->ot_room_id);
            }
        });

        return redirect()->route('ot.schedules.show', $ot)
            ->with('success', 'Surgery record updated successfully.');
    }

    // ── DESTROY ──────────────────────────────────────────────────────────

    public function destroy(OtSchedule $ot)
    {
        if ($ot->ot_room_id) {
            $this->freeRoom($ot->ot_room_id);
        }
        $ot->delete();

        return redirect()->route('ot.index')
            ->with('success', 'Surgery schedule removed.');
    }

    // ── QUICK STATUS UPDATE (AJAX) ────────────────────────────────────────

    public function updateStatus(Request $request, OtSchedule $ot)
    {
        $request->validate([
            'status' => 'required|in:Scheduled,Confirmed,Preparing,In-Progress,Completed,Postponed,Cancelled',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'In-Progress' && !$ot->actual_start_time) {
            $data['actual_start_time'] = now();
        }
        if ($request->status === 'Completed' && !$ot->actual_end_time) {
            $data['actual_end_time'] = now();
        }

        $ot->update($data);

        if (in_array($request->status, ['Completed', 'Cancelled', 'Postponed']) && $ot->ot_room_id) {
            $this->freeRoom($ot->ot_room_id);
        }

        return response()->json(['success' => true, 'status' => $ot->status]);
    }

    // ── PRIVATE HELPERS ──────────────────────────────────────────────────

    private function freeRoom(int $roomId): void
    {
        // Only free if no other active schedule using this room
        $activeCount = OtSchedule::where('ot_room_id', $roomId)
            ->whereIn('status', ['Scheduled', 'Confirmed', 'Preparing', 'In-Progress'])
            ->count();

        if ($activeCount === 0) {
            OtRoom::find($roomId)?->update(['status' => 'Available']);
        }
    }
}

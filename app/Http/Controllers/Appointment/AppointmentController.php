<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // ── Helper: doctors with employee loaded, sorted by employee first_name ──
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.employee'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'asc');

        // Search by patient name or MRN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('mrn', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        } else {
            // Default: show today + upcoming
            $query->whereDate('appointment_date', '>=', today()->subDays(7));
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'today' => Appointment::today()->count(),
            'scheduled' => Appointment::today()->byStatus('Scheduled')->count(),
            'completed' => Appointment::today()->byStatus('Completed')->count(),
            'cancelled' => Appointment::today()->byStatus('Cancelled')->count(),
        ];

        $doctors = Doctor::with('employee')
            ->active()
            ->orderBy('doctor_id')
            ->get();

        return view('appointments.appointments_index', compact('appointments', 'stats', 'doctors'));
    }

    // =============================================
    // CREATE — Show form
    // =============================================
    public function create(Request $request)
    {
        $doctors = Doctor::with('employee')
            ->active()
            ->available()
            ->get();

        $patients = Patient::orderBy('name')->get();

        // Pre-fill patient if coming from patient profile
        $selectedPatient = $request->filled('patient_id')
            ? Patient::find($request->patient_id)
            : null;

        return view('appointments.appointments_create', compact('doctors', 'patients', 'selectedPatient'));
    }

    // =============================================
    // STORE — Save new appointment
    // =============================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'nullable|date_format:H:i,H:i:s',
            'duration_minutes' => 'nullable|integer|min:5|max:120',
            'type' => 'required|in:OPD,IPD,Follow-up,Emergency',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after:appointment_date',
        ]);

        // Doctor availability conflict check
        if (!empty($validated['doctor_id']) && !empty($validated['appointment_time'])) {
            $conflict = Appointment::hasConflict(
                $validated['doctor_id'],
                $validated['appointment_date'],
                $validated['appointment_time']
            );

            if ($conflict) {
                return back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Doctor already has an appointment at this time.']);
            }
        }

        // Auto-generate token for OPD
        if ($validated['type'] === 'OPD' && !empty($validated['doctor_id'])) {
            $validated['token_number'] = Appointment::nextToken(
                $validated['doctor_id'],
                $validated['appointment_date']
            );
        }

        $appointment = Appointment::create($validated);

        return redirect()
            ->route('appointments.show', $appointment->id)
            ->with('success', "Appointment booked! Token: #{$appointment->token_number}");
    }

    // =============================================
    // SHOW — Single appointment detail
    // =============================================
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.employee']);
        return view('appointments.appointments_show', compact('appointment'));
    }

    // =============================================
    // EDIT — Show edit form
    // =============================================
    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::with('employee')
            ->active()
            ->orderBy('doctor_id')
            ->get();

        $patients = Patient::orderBy('name')->get();

        return view('appointments.appointments_edit', compact('appointment', 'doctors', 'patients'));
    }

    // =============================================
    // UPDATE — Save changes
    // =============================================
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i,H:i:s',
            'duration_minutes' => 'nullable|integer|min:5|max:120',
            'type' => 'required|in:OPD,IPD,Follow-up,Emergency',
            'status' => 'required|in:Scheduled,Confirmed,In-Progress,Completed,Cancelled,No-show',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'cancelled_by' => 'nullable|in:Patient,Doctor,Admin',
            'cancellation_reason' => 'nullable|string|max:255',
        ]);

        // Conflict check (exclude current appointment)
        if (!empty($validated['doctor_id']) && !empty($validated['appointment_time'])) {
            $conflict = Appointment::hasConflict(
                $validated['doctor_id'],
                $validated['appointment_date'],
                $validated['appointment_time'],
                $appointment->id
            );

            if ($conflict) {
                return back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Doctor already has an appointment at this time.']);
            }
        }

        // Auto-set consulted_at when status → In-Progress
        if ($validated['status'] === 'In-Progress' && !$appointment->consulted_at) {
            $validated['consulted_at'] = now();
        }

        $appointment->update($validated);

        return redirect()
            ->route('appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully!');
    }

    // =============================================
    // DESTROY — Soft delete
    // =============================================
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment removed.');
    }

    // =============================================
    // QUICK STATUS UPDATE (AJAX-friendly)
    // =============================================
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Scheduled,Confirmed,In-Progress,Completed,Cancelled,No-show',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'In-Progress' && !$appointment->consulted_at) {
            $data['consulted_at'] = now();
        }

        $appointment->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $request->status]);
        }

        return back()->with('success', "Status updated to {$request->status}.");
    }

    // =============================================
    // CALENDAR VIEW
    // =============================================
    public function calendar(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);
        $doctorId = $request->integer('doctor_id');

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query = Appointment::with(['patient', 'doctor.employee'])
            ->whereBetween('appointment_date', [$startDate, $endDate]);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        // Group by date string for easy calendar rendering
        $appointments = $query->get()->groupBy(fn($a) => $a->appointment_date->format('Y-m-d'));

        $doctors = Doctor::with('employee')
            ->active()
            ->orderBy('doctor_id')
            ->get();

        return view('appointments.appointments_calendar', compact(
            'appointments',
            'doctors',
            'month',
            'year',
            'startDate',
            'endDate',
            'doctorId'
        ));
    }

    // =============================================
    // DOCTOR AVAILABILITY (AJAX)
    // =============================================
    public function availability(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        $booked = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->date)
            ->whereNotIn('status', ['Cancelled', 'No-show'])
            ->pluck('appointment_time')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        return response()->json([
            'date' => $request->date,
            'booked_slots' => $booked,
            'total_booked' => count($booked),
        ]);
    }
}
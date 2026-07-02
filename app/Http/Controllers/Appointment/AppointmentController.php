<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Facades\AuditLog;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.employee'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('mrn', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        } else {
            $query->whereDate('appointment_date', '>=', today()->subDays(7));
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20)->withQueryString();

        $stats = [
            'today' => Appointment::today()->count(),
            'scheduled' => Appointment::today()->byStatus('Scheduled')->count(),
            'completed' => Appointment::today()->byStatus('Completed')->count(),
            'cancelled' => Appointment::today()->byStatus('Cancelled')->count(),
        ];

        $doctors = Doctor::with('employee')->active()->orderBy('doctor_id')->get();

        return view('appointments.appointments_index', compact('appointments', 'stats', 'doctors'));
    }

    public function create(Request $request)
    {
        $doctors = Doctor::with('employee')
            ->where('is_active', true)
            ->get()
            ->sortBy('employee.first_name');

        $selectedDoctor = null;
        $selectedPatient = null;

        if (auth()->user()->hasRole('doctor')) {
            $employeeId = auth()->user()->employee_id;
            if ($employeeId) {
                $selectedDoctor = Doctor::where('employee_id', $employeeId)->first();
            }
        }

        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('appointments.appointments_create', compact('doctors', 'selectedPatient', 'selectedDoctor'));
    }

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

        // ── Business rule checks (try-catch se bahar — yeh intentional redirects hain) ──
        $patient = Patient::findOrFail($validated['patient_id']);

        if ($patient->status === 'Deceased') {
            return back()->withInput()
                ->withErrors(['patient_id' => 'Cannot book appointment for a deceased patient.']);
        }

        if ($patient->status === 'Discharged') {
            return back()->withInput()
                ->withErrors(['patient_id' => 'Cannot book appointment for a discharged patient.']);
        }

        if (! empty($validated['doctor_id']) && ! empty($validated['appointment_time'])) {
            $conflict = Appointment::hasConflict(
                $validated['doctor_id'],
                $validated['appointment_date'],
                $validated['appointment_time']
            );

            if ($conflict) {
                return back()->withInput()
                    ->withErrors(['appointment_time' => 'Doctor already has an appointment at this time.']);
            }
        }

        // ── DB write — try-catch ──
        try {
            if ($validated['type'] === 'OPD' && ! empty($validated['doctor_id'])) {
                $validated['token_number'] = Appointment::nextToken(
                    $validated['doctor_id'],
                    $validated['appointment_date']
                );
            }

            $appointment = Appointment::create($validated);

            return redirect()
                ->route('appointments.show', $appointment->id)
                ->with('success', "Appointment booked! Token: #{$appointment->token_number}");

        } catch (\Exception $e) {
            Log::error('Appointment store failed', [
                'user_id' => auth()->id(),
                'data' => $validated,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Appointment booking is not Completed. Please Try again.');
        }
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.employee']);

        return view('appointments.appointments_show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::with('employee')->active()->orderBy('doctor_id')->get();
        $selectedPatient = $appointment->patient;

        return view('appointments.appointments_edit', compact('appointment', 'doctors', 'selectedPatient'));
    }

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

        // ── Conflict check (business rule — try-catch se bahar) ──
        if (! empty($validated['doctor_id']) && ! empty($validated['appointment_time'])) {
            $conflict = Appointment::hasConflict(
                $validated['doctor_id'],
                $validated['appointment_date'],
                $validated['appointment_time'],
                $appointment->id
            );

            if ($conflict) {
                return back()->withInput()
                    ->withErrors(['appointment_time' => 'Doctor already has an appointment at this time.']);
            }
        }

        // ── DB write — try-catch ──
        try {
            if ($validated['status'] === 'In-Progress' && ! $appointment->consulted_at) {
                $validated['consulted_at'] = now();
            }

            $appointment->update($validated);

            return redirect()
                ->route('appointments.show', $appointment->id)
                ->with('success', 'Appointment updated successfully!');

        } catch (\Exception $e) {
            Log::error('Appointment update failed', [
                'user_id' => auth()->id(),
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Appointment update has not done. Please Try again.');
        }
    }

    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();

            return redirect()
                ->route('appointments.index')
                ->with('success', 'Appointment removed.');

        } catch (\Exception $e) {
            Log::error('Appointment delete failed', [
                'user_id' => auth()->id(),
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Appointment delete nahi ho saka.');
        }
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Scheduled,Confirmed,In-Progress,Completed,Cancelled,No-show',
        ]);

        try {
            $data = ['status' => $request->status];

            if ($request->status === 'In-Progress' && ! $appointment->consulted_at) {
                $data['consulted_at'] = now();
            }

            $appointment->update($data);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'status' => $request->status]);
            }

            return back()->with('success', "Status updated to {$request->status}.");

        } catch (\Exception $e) {
            Log::error('Appointment status update failed', [
                'user_id' => auth()->id(),
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Status update failed.'], 500);
            }

            return back()->with('error', 'Status update nahi ho saka.');
        }
    }

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

        $appointments = $query->get()->groupBy(fn ($a) => $a->appointment_date->format('Y-m-d'));

        $doctors = Doctor::with('employee')->active()->orderBy('doctor_id')->get();

        return view('appointments.appointments_calendar', compact(
            'appointments', 'doctors', 'month', 'year',
            'startDate', 'endDate', 'doctorId'
        ));
    }

    public function availability(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        try {
            $booked = Appointment::where('doctor_id', $request->doctor_id)
                ->whereDate('appointment_date', $request->date)
                ->whereNotIn('status', ['Cancelled', 'No-show'])
                ->pluck('appointment_time')
                ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
                ->toArray();

            return response()->json([
                'date' => $request->date,
                'booked_slots' => $booked,
                'total_booked' => count($booked),
            ]);

        } catch (\Exception $e) {
            Log::error('Availability check failed', [
                'doctor_id' => $request->doctor_id,
                'date' => $request->date,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Availability check failed.'], 500);
        }
    }
}

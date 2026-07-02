<?php

namespace App\Http\Controllers\Patient;

use App\Facades\AuditLog;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('doctor')->latest();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('patient_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $patients = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Patient::count(),
            'opd' => Patient::where('patient_type', 'OPD')->count(),
            'ipd' => Patient::where('patient_type', 'IPD')->count(),
            'emergency' => Patient::where('patient_type', 'Emergency')->count(),
        ];

        return view('patients.patients_index', compact('patients', 'stats'));
    }

    // ===== CREATE - Show form =====
    public function create()
    {
        $doctors = Doctor::all();

        return view('patients.patients_create', compact('doctors'));
    }

    // ===== STORE - Save new patient =====
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'required|string|max:15',
            'emergency_contact' => 'nullable|string|max:15',
            'emergency_relation' => 'nullable|string|max:50',
            'cnic' => 'nullable|digits:13|unique:patients,cnic',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'patient_type' => 'required|in:OPD,IPD,Emergency',
            'doctor_id' => 'nullable|exists:doctors,id',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::create($request->all());

        return redirect()
            ->route('appointments.create', ['patient_id' => $patient->id])
            ->with('success', "Patient {$patient->name} registered! MRN: {$patient->mrn} — Now book appointment.");
    }

    // ===== SHOW - View single patient =====
    public function show(Patient $patient)
    {
        AuditLog::viewed($patient, 'patient');
        $patient->load([
            'doctor',
            'appointments.doctor',
            'labOrders',
            'radiologyOrders',
            'prescriptions.doctor',
        ]);

        return view('patients.patients_show', compact('patient'));
    }

    // ===== EDIT - Show edit form =====
    public function edit(Patient $patient)
    {
        if (in_array(auth()->user()->role, ['doctor', 'nurse'])) {
            return redirect()->back()->with('error', 'You are not authorized to perform this operation.');
        }
        $doctors = Doctor::all();

        return view('patients.patients_edit', compact('patient', 'doctors'));
    }

    // ===== UPDATE - Save changes =====
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'required|string|max:15',
            'emergency_contact' => 'nullable|string|max:15',
            'emergency_relation' => 'nullable|string|max:50',
            'cnic' => 'nullable|string|size:13|unique:patients,cnic,'.$patient->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'patient_type' => 'required|in:OPD,IPD,Emergency',
            'status' => 'required|in:Active,Admitted,Discharged,Deceased',
            'doctor_id' => 'nullable|exists:doctors,id',
            'notes' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()
            ->route('patients.show', $patient->id)
            ->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        // 1. Authorization Check
        if (in_array(auth()->user()->role, ['doctor', 'nurse'])) {
            return redirect()->back()->with('error', 'You are not authorized to perform this operation.');
        }

        // 2. Dependency Checks — jo bhi "pending/active" ho, delete block karo
        $blockers = $this->getDeletionBlockers($patient);

        if (! empty($blockers)) {
            return redirect()->back()
                ->with('error', 'Cannot remove patient. Pending items: '.implode(', ', $blockers));
        }

        // 3. Perform soft delete
        try {
            $patientName = $patient->name;
            $patient->delete();

            return redirect()
                ->route('patients.index')
                ->with('success', "Patient {$patientName} removed successfully.");

        } catch (\Throwable $e) {
            Log::error('Patient delete failed', [
                'patient_id' => $patient->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Patient can not be removed. Please try again.');
        }
    }

    /**
     * Check karo patient ke pas koi pending/active records hain ya nahi
     */
    private function getDeletionBlockers(Patient $patient): array
    {
        $blockers = [];

        // Unpaid/Partial bills
        if ($patient->bills()->whereIn('payment_status', ['Unpaid', 'Partial'])->exists()) {
            $blockers[] = 'unpaid bills';
        }

        // Active/scheduled appointments
        if ($patient->appointments()->whereIn('status', ['Scheduled', 'Confirmed', 'In-Progress'])->exists()) {
            $blockers[] = 'active appointments';
        }

        // Pending lab orders
        if ($patient->labOrders()->whereNotIn('status', ['Completed', 'Cancelled'])->exists()) {
            $blockers[] = 'pending lab tests';
        }

        // Pending radiology orders
        if ($patient->radiologyOrders()->whereNotIn('status', ['Completed', 'Cancelled'])->exists()) {
            $blockers[] = 'pending radiology tests';
        }

        // Currently admitted (bed occupied)
        if ($patient->beds()->where('status', 'Occupied')->exists()) {
            $blockers[] = 'active bed admission';
        }

        // Scheduled OT / Surgery
        if ($patient->otSchedules()->whereIn('status', ['Scheduled', 'In-Progress'])->exists()) {
            $blockers[] = 'scheduled surgery';
        }

        return $blockers;
    }
}

<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;

use Illuminate\Http\Request;

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
            'cnic' => 'nullable|string|size:13|unique:patients,cnic',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'patient_type' => 'required|in:OPD,IPD,Emergency',
            'doctor_id' => 'nullable|exists:doctors,id',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::create($request->all());

        return redirect()
            ->route('patients.show', $patient->id)
            ->with('success', "Patient {$patient->name} registered successfully! MRN: {$patient->mrn}");
    }

    // ===== SHOW - View single patient =====
    public function show(Patient $patient)
    {
        $patient->load('doctor', 'appointments');
        return view('patients.patients_show', compact('patient'));
    }

    // ===== EDIT - Show edit form =====
    public function edit(Patient $patient)
    {
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
            'cnic' => 'nullable|string|size:13|unique:patients,cnic,' . $patient->id,
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

    // ===== DESTROY - Soft delete =====
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', "Patient {$patient->name} removed.");
    }
}

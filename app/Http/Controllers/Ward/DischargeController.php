<?php

namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bed;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientDischarge;
use App\Models\PatientDoctorOrder;
use App\Models\PatientNursingNote;
use App\Models\PatientVisitNote;
use Illuminate\Support\Facades\Auth;

class DischargeController extends Controller
{
     public function create(Patient $patient)
    {
        $bed    = $patient->bed;
        $doctor = Auth::user()->employee?->doctor;
        $doctors = Doctor::with('employee')->get();

        return view('wards.discharge_create', compact('patient', 'bed', 'doctor', 'doctors'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'doctor_id'                   => 'required|exists:doctors,id',
            'discharge_date'              => 'required|date',
            'discharge_type'              => 'required|string',
            'condition_at_discharge'      => 'required|string',
            'admission_diagnosis'         => 'required|string',
            'final_diagnosis'             => 'required|string',
            'treatment_summary'           => 'required|string',
            'procedures_done'             => 'nullable|string',
            'discharge_instructions'      => 'required|string',
            'medications_on_discharge'    => 'nullable|string',
            'diet_instructions'           => 'nullable|string',
            'activity_instructions'       => 'nullable|string',
            'follow_up_date'              => 'nullable|date',
            'follow_up_with'              => 'nullable|string',
            'notes'                       => 'nullable|string',
        ]);

        $bed = $patient->bed;
        if (!$bed) {
            return back()->with('error', 'Patient is not assigned to any bed.');
        }

        $data['patient_id']    = $patient->id;
        $data['bed_id']        = $bed->id;
        $data['processed_by']  = Auth::id();
        $data['admitted_date'] = $bed->admitted_at ?? $patient->created_at->toDateString();
        $data['status']        = 'Draft';

        $discharge = PatientDischarge::create($data);

        // Free up the bed
        $bed->update([
            'status'        => 'Available',
            'patient_id'    => null,
            'discharged_at' => now(),
        ]);

        return redirect()
            ->route('ward.discharge.show', $discharge->id)
            ->with('success', 'Patient discharged! Discharge summary created.');
    }

    public function show(PatientDischarge $discharge)
    {
        $discharge->load(['patient', 'doctor.employee', 'bed.ward', 'processedBy']);
        return view('wards.discharge_show', compact('discharge'));
    }

    public function finalize(PatientDischarge $discharge)
    {
        $discharge->update([
            'status'       => 'Final',
            'finalized_at' => now(),
        ]);

        return back()->with('success', 'Discharge summary finalized!');
    }

    public function print(PatientDischarge $discharge)
    {
        $discharge->load(['patient', 'doctor.employee', 'bed.ward']);
        return view('wards.discharge_print', compact('discharge'));
    }
}

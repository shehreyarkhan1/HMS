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

class VisitNoteController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'visit_type' => 'required|string',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
            'examination_findings' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'is_discharge_ready' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $doctor = $user->employee?->doctor;

        $data['patient_id'] = $patient->id;
        $data['doctor_id'] = $doctor?->id ?? Doctor::first()->id;
        $data['is_discharge_ready'] = $request->boolean('is_discharge_ready');

        $bed = $patient->bed ?? null;
        if ($bed) {
            $data['bed_id'] = $bed->id;
        }

        PatientVisitNote::create($data);

        return back()->with('success', 'Visit note saved!');
    }

    public function show(PatientVisitNote $visitNote)
    {
        return view('wards.tabs.visit_note_show', compact('visitNote'));
    }
}

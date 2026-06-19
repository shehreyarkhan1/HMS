<?php

namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\PatientVital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VitalController extends Controller
{
    // ── Store new vitals ──────────────────────────────────────
    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'temperature' => 'nullable|numeric|between:90,110',
            'temperature_route' => 'nullable|string',
            'pulse_rate' => 'nullable|integer|between:20,300',
            'pulse_rhythm' => 'nullable|string',
            'respiratory_rate' => 'nullable|integer|between:4,60',
            'systolic_bp' => 'nullable|integer|between:50,300',
            'diastolic_bp' => 'nullable|integer|between:20,200',
            'bp_position' => 'nullable|string',
            'oxygen_saturation' => 'nullable|numeric|between:50,100',
            'oxygen_delivery' => 'nullable|string',
            'blood_glucose' => 'nullable|numeric|between:20,800',
            'blood_glucose_timing' => 'nullable|string',
            'weight' => 'nullable|numeric|between:1,300',
            'height' => 'nullable|numeric|between:30,250',
            'pain_score' => 'nullable|integer|between:0,10',
            'pain_location' => 'nullable|string',
            'gcs_eye' => 'nullable|integer|between:1,4',
            'gcs_verbal' => 'nullable|integer|between:1,5',
            'gcs_motor' => 'nullable|integer|between:1,6',
            'urine_output' => 'nullable|numeric',
            'fluid_intake' => 'nullable|numeric',
            'fluid_output' => 'nullable|numeric',
            'shift' => 'required|in:Morning,Afternoon,Evening,Night',
            'notes' => 'nullable|string',
        ]);

        // GCS total
        if (isset($data['gcs_eye'], $data['gcs_verbal'], $data['gcs_motor'])) {
            $data['gcs_score'] = $data['gcs_eye'] + $data['gcs_verbal'] + $data['gcs_motor'];
        }

        $data['patient_id'] = $patient->id;
        $data['recorded_by'] = Auth::id();
        $data['recorded_at'] = now();

        // Active bed
        $bed = $patient->bed ?? null;
        if ($bed) {
            $data['bed_id'] = $bed->id;
        }

        PatientVital::create($data);

        return back()->with('success', 'Vitals recorded successfully!');
    }

    // ── Vitals history (AJAX/partial) ────────────────────────
    public function history(Patient $patient)
    {
        $vitals = PatientVital::where('patient_id', $patient->id)
            ->with('recordedBy')
            ->latest('recorded_at')
            ->take(20)
            ->get();

        return view('wards.vital_history', compact('vitals', 'patient'));
    }
}

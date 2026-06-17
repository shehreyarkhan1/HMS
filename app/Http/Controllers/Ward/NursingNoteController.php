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

class NursingNoteController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'shift' => 'required|in:Morning,Afternoon,Evening,Night',
            'note_type' => 'required|string',
            'note' => 'required|string|max:2000',
            'interventions' => 'nullable|string',
            'patient_response' => 'nullable|string',
            'requires_doctor_attention' => 'nullable|boolean',
            'is_urgent' => 'nullable|boolean',
        ]);

        $data['patient_id'] = $patient->id;
        $data['nurse_id'] = Auth::id();
        $data['noted_at'] = now();
        $data['requires_doctor_attention'] = $request->boolean('requires_doctor_attention');
        $data['is_urgent'] = $request->boolean('is_urgent');

        $bed = $patient->bed ?? null;
        if ($bed) {
            $data['bed_id'] = $bed->id;
        }

        PatientNursingNote::create($data);

        return back()->with('success', 'Nursing note added!');
    }

    public function destroy(PatientNursingNote $note)
    {
        // Sirf same nurse delete kar sakti hai — 1 ghante ke andar
        if ($note->nurse_id !== Auth::id() || $note->created_at->diffInHours(now()) > 1) {
            return back()->with('error', 'Cannot delete this note.');
        }
        $note->delete();

        return back()->with('success', 'Note deleted.');
    }
}

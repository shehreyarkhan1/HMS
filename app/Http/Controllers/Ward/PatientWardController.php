<?php

namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDoctorOrder;
use App\Models\PatientNursingNote;
use App\Models\PatientVisitNote;
use App\Models\PatientVital;

class PatientWardController extends Controller
{
    // show() method mein $order use ho raha hai blade mein lekin pass nahi kiya
    // Patient header mein: Dr. {{ $order->doctor->employee->full_name ?? '—' }}
    // Ya toh latest order pass karo:

    public function show(Patient $patient)
    {
        $bed = $patient->bed;

        $vitals = PatientVital::where('patient_id', $patient->id)
            ->with('recordedBy')
            ->latest('recorded_at')
            ->get();

        $nursingNotes = PatientNursingNote::where('patient_id', $patient->id)
            ->with('nurse')
            ->latest('noted_at')
            ->get();

        $doctorOrders = PatientDoctorOrder::where('patient_id', $patient->id)
            ->with(['doctor.employee', 'acknowledgedBy'])
            ->latest('ordered_at')
            ->get();

        $visitNotes = PatientVisitNote::where('patient_id', $patient->id)
            ->with('doctor.employee')
            ->latest('visited_at')
            ->get();

        $pendingOrders = $doctorOrders->whereIn('status', ['Pending', 'Acknowledged'])->count();

        // ── Yeh add karo ──
        $order = $doctorOrders->first();

        return view('wards.patient_show', compact(
            'patient', 'bed',
            'vitals', 'nursingNotes',
            'doctorOrders', 'visitNotes',
            'pendingOrders', 'order',   // <-- yeh add karo
        ));
    }
}

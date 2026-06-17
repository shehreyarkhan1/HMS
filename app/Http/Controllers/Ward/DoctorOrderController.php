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

class DoctorOrderController extends Controller
{
      public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'order_type'           => 'required|string',
            'title'                => 'required|string|max:200',
            'details'              => 'required|string',
            'special_instructions' => 'nullable|string',
            'priority'             => 'required|in:Routine,Urgent,STAT',
        ]);

        $user   = Auth::user();
        $doctor = $user->employee?->doctor;

        if (!$doctor && !$user->isSuperAdmin()) {
            return back()->with('error', 'Only doctors can write orders.');
        }

        $data['patient_id'] = $patient->id;
        $data['doctor_id']  = $doctor?->id ?? Doctor::first()->id;
        $data['status']     = 'Pending';

        $bed = $patient->bed ?? null;
        if ($bed) $data['bed_id'] = $bed->id;

        PatientDoctorOrder::create($data);

        return back()->with('success', 'Order written successfully!');
    }

    // Nurse acknowledges the order
    public function acknowledge(PatientDoctorOrder $order)
    {
        if ($order->status !== 'Pending') {
            return back()->with('error', 'Order already acknowledged.');
        }

        $order->update([
            'status'          => 'Acknowledged',
            'acknowledged_by' => Auth::id(),
            'acknowledged_at' => now(),
        ]);

        return back()->with('success', 'Order acknowledged!');
    }

    // Mark order complete
    public function complete(PatientDoctorOrder $order)
    {
        $order->update([
            'status'       => 'Completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Order marked as completed!');
    }

    // Cancel order
    public function cancel(Request $request, PatientDoctorOrder $order)
    {
        $request->validate(['cancellation_reason' => 'required|string']);

        $order->update([
            'status'               => 'Cancelled',
            'cancellation_reason'  => $request->cancellation_reason,
        ]);

        return back()->with('success', 'Order cancelled.');
    }
}

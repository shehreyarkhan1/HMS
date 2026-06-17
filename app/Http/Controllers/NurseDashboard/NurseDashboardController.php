<?php

namespace App\Http\Controllers\NurseDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bed;
use App\Models\PatientDoctorOrder;
use App\Models\PatientVital;
use App\Models\PatientNursingNote;
use Illuminate\Support\Facades\Auth;
class NurseDashboardController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        $nurse = $user;
        $nurseId = $user->id;

        // ── Occupied beds (all — nurse sees all patients in her ward) ──
        $occupiedBeds = Bed::where('status', 'Occupied')
            ->with(['patient', 'ward'])
            ->get();

        $patientIds = $occupiedBeds->pluck('patient_id')->filter()->values();

        // ── Pending doctor orders (unacknowledged) ──
        $pendingOrders = PatientDoctorOrder::whereIn('patient_id', $patientIds)
            ->whereIn('status', ['Pending', 'Acknowledged'])
            ->with(['patient', 'doctor.employee'])
            ->orderByRaw("FIELD(priority, 'STAT', 'Urgent', 'Routine')")
            ->orderBy('ordered_at')
            ->get();

        // ── STAT / Urgent orders ──
        $urgentOrders = $pendingOrders->whereIn('priority', ['STAT', 'Urgent']);

        // ── Vitals due (patients who have no vitals in last 6 hours) ──
        $vitalsDue = $occupiedBeds->filter(function ($bed) {
            if (! $bed->patient) {
                return false;
            }
            $lastVital = PatientVital::where('patient_id', $bed->patient_id)
                ->latest('recorded_at')
                ->first();

            // No vitals at all, or last vital > 6 hours ago
            return ! $lastVital || $lastVital->recorded_at->diffInHours(now()) >= 6;
        });

        // ── Urgent nursing notes (requires_doctor_attention) ──
        $urgentNotes = PatientNursingNote::whereIn('patient_id', $patientIds)
            ->where('requires_doctor_attention', true)
            ->whereDate('created_at', today())
            ->with('patient')
            ->latest()
            ->get();

        // ── My activity today (notes I wrote) ──
        $myTodayNotes = PatientNursingNote::where('nurse_id', $nurseId)
            ->whereDate('created_at', today())
            ->with('patient')
            ->latest()
            ->get();

        // ── My vitals recorded today ──
        $myTodayVitals = PatientVital::where('recorded_by', $nurseId)
            ->whereDate('recorded_at', today())
            ->with('patient')
            ->latest('recorded_at')
            ->get();

        // ── Stats ──
        $stats = [
            'total_patients' => $occupiedBeds->count(),
            'pending_orders' => $pendingOrders->count(),
            'urgent_orders' => $urgentOrders->count(),
            'vitals_due' => $vitalsDue->count(),
            'my_notes_today' => $myTodayNotes->count(),
            'my_vitals_today' => $myTodayVitals->count(),
            'urgent_notes' => $urgentNotes->count(),
        ];

        // ── Current shift ──
        $hour = now()->hour;
        $shift = match (true) {
            $hour >= 6 && $hour < 14 => 'Morning',
            $hour >= 14 && $hour < 22 => 'Evening',
            default => 'Night',
        };

        return view('nursedashboard.nurse_dashboard', compact(
            'nurse',
            'stats',
            'occupiedBeds',
            'pendingOrders',
            'urgentOrders',
            'vitalsDue',
            'urgentNotes',
            'myTodayNotes',
            'myTodayVitals',
            'shift',
        ));
    }
}

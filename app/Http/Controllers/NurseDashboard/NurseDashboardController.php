<?php

namespace App\Http\Controllers\NurseDashboard;

use App\Http\Controllers\Controller;
use App\Models\WardNurseAssignment;
use App\Models\Bed;
use App\Models\PatientDoctorOrder;
use App\Models\PatientVital;
use App\Models\PatientNursingNote;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;

class NurseDashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $nurse = $user;

        // ── Super Admin / Admin: sab wards ka data dikhaو ──
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return $this->adminViewOfNurseDashboard($nurse);
        }

        // ── Nurse: apna assignment check karo ──
        $myAssignment = WardNurseAssignment::where('user_id', $user->id)
            ->activeToday()
            ->with('ward')
            ->first();

        $emptyData = [
            'nurse'         => $nurse,
            'myAssignment'  => null,
            'shift'         => 'Morning',
            'occupiedBeds'  => collect(),
            'pendingOrders' => collect(),
            'urgentOrders'  => collect(),
            'vitalsDue'     => collect(),
            'urgentNotes'   => collect(),
            'myTodayNotes'  => collect(),
            'myTodayVitals' => collect(),
            'isAdminView'   => false,
            'stats' => [
                'total_patients'  => 0,
                'pending_orders'  => 0,
                'urgent_orders'   => 0,
                'vitals_due'      => 0,
                'my_notes_today'  => 0,
                'my_vitals_today' => 0,
                'urgent_notes'    => 0,
            ],
        ];

        if (! $myAssignment) {
            return view('nursedashboard.nurse_dashboard', $emptyData);
        }

        // ── Normal nurse flow ──
        $occupiedBeds = Bed::where('status', 'Occupied')
            ->where('ward_id', $myAssignment->ward_id)
            ->with(['patient', 'ward'])
            ->get();

        $patientIds = $occupiedBeds->pluck('patient_id')->filter()->values();

        $pendingOrders = PatientDoctorOrder::whereIn('patient_id', $patientIds)
            ->whereIn('status', ['Pending', 'Acknowledged'])
            ->with(['patient', 'doctor.employee'])
            ->orderByRaw("FIELD(priority, 'STAT', 'Urgent', 'Routine')")
            ->orderBy('ordered_at')
            ->get();

        $urgentOrders = $pendingOrders->whereIn('priority', ['STAT', 'Urgent']);

        $vitalsDue = $occupiedBeds->filter(function ($bed) {
            if (! $bed->patient) return false;
            $last = PatientVital::where('patient_id', $bed->patient_id)
                ->latest('recorded_at')->first();
            return ! $last || $last->recorded_at->diffInHours(now()) >= 6;
        });

        $urgentNotes = PatientNursingNote::whereIn('patient_id', $patientIds)
            ->where('requires_doctor_attention', true)
            ->whereDate('created_at', today())
            ->with(['patient', 'nurse'])
            ->latest()
            ->get();

        $myTodayNotes = PatientNursingNote::where('nurse_id', $user->id)
            ->whereDate('created_at', today())
            ->with('patient')
            ->latest()
            ->get();

        $myTodayVitals = PatientVital::where('recorded_by', $user->id)
            ->whereDate('recorded_at', today())
            ->with('patient')
            ->latest('recorded_at')
            ->get();

        $stats = [
            'total_patients'  => $occupiedBeds->count(),
            'pending_orders'  => $pendingOrders->count(),
            'urgent_orders'   => $urgentOrders->count(),
            'vitals_due'      => $vitalsDue->count(),
            'my_notes_today'  => $myTodayNotes->count(),
            'my_vitals_today' => $myTodayVitals->count(),
            'urgent_notes'    => $urgentNotes->count(),
        ];

        return view('nursedashboard.nurse_dashboard', compact(
            'nurse', 'myAssignment', 'stats',
            'occupiedBeds', 'pendingOrders', 'urgentOrders',
            'vitalsDue', 'urgentNotes', 'myTodayNotes',
            'myTodayVitals',
        ))->with([
            'shift'       => $myAssignment->shift,
            'isAdminView' => false,
        ]);
    }

    // ── Admin ke liye: sab wards ka combined data ──
    private function adminViewOfNurseDashboard($nurse)
    {
        // Fake assignment object banao taake blade crash na kare
        $fakeWard = Ward::first(); // ya koi bhi default

        $myAssignment = (object) [
            'ward'    => $fakeWard,
            'ward_id' => $fakeWard?->id,
            'shift'   => $this->getCurrentShift(),
        ];

        // Sab occupied beds — kisi bhi ward ki
        $occupiedBeds = Bed::where('status', 'Occupied')
            ->with(['patient', 'ward'])
            ->get();

        $patientIds = $occupiedBeds->pluck('patient_id')->filter()->values();

        $pendingOrders = PatientDoctorOrder::whereIn('patient_id', $patientIds)
            ->whereIn('status', ['Pending', 'Acknowledged'])
            ->with(['patient', 'doctor.employee'])
            ->orderByRaw("FIELD(priority, 'STAT', 'Urgent', 'Routine')")
            ->orderBy('ordered_at')
            ->get();

        $urgentOrders = $pendingOrders->whereIn('priority', ['STAT', 'Urgent']);

        $vitalsDue = $occupiedBeds->filter(function ($bed) {
            if (! $bed->patient) return false;
            $last = PatientVital::where('patient_id', $bed->patient_id)
                ->latest('recorded_at')->first();
            return ! $last || $last->recorded_at->diffInHours(now()) >= 6;
        });

        $urgentNotes = PatientNursingNote::whereIn('patient_id', $patientIds)
            ->where('requires_doctor_attention', true)
            ->whereDate('created_at', today())
            ->with(['patient', 'nurse'])
            ->latest()
            ->get();

        // Admin ke liye "my" notes/vitals = aaj ke sab notes/vitals
        $myTodayNotes = PatientNursingNote::whereDate('created_at', today())
            ->with('patient')
            ->latest()
            ->get();

        $myTodayVitals = PatientVital::whereDate('recorded_at', today())
            ->with('patient')
            ->latest('recorded_at')
            ->get();

        $stats = [
            'total_patients'  => $occupiedBeds->count(),
            'pending_orders'  => $pendingOrders->count(),
            'urgent_orders'   => $urgentOrders->count(),
            'vitals_due'      => $vitalsDue->count(),
            'my_notes_today'  => $myTodayNotes->count(),
            'my_vitals_today' => $myTodayVitals->count(),
            'urgent_notes'    => $urgentNotes->count(),
        ];

        return view('nursedashboard.nurse_dashboard', compact(
            'nurse', 'myAssignment', 'stats',
            'occupiedBeds', 'pendingOrders', 'urgentOrders',
            'vitalsDue', 'urgentNotes', 'myTodayNotes',
            'myTodayVitals',
        ))->with([
            'shift'       => $myAssignment->shift,
            'isAdminView' => true,
        ]);
    }

    private function getCurrentShift(): string
    {
        $hour = now()->hour;
        if ($hour >= 7 && $hour < 15)  return 'Morning';
        if ($hour >= 15 && $hour < 23) return 'Evening';
        return 'Night';
    }
}

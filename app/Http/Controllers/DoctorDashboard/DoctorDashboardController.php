<?php

namespace App\Http\Controllers\DoctorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\LabOrder;
use App\Models\Prescription;
use App\Models\RadiologyOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $employee = $user->employee;
    $doctor   = $employee ? $employee->doctor : null;
    $doctorId = $doctor ? $doctor->id : null;

    $isSuperAdmin = $user->isSuperAdmin();

    if (! $doctorId && ! $isSuperAdmin) {
        return redirect()->route('dashboard')
            ->with('error', 'Your account is not linked to a doctor profile.');
    }

    // ── Base Queries ──────────────────────────────────────────────────
    $apptQuery         = Appointment::query();
    $labQuery          = LabOrder::query();
    $radiologyQuery    = RadiologyOrder::query();
    $prescriptionQuery = Prescription::query();

    if ($doctorId) {
        $apptQuery->where('doctor_id', $doctorId);
        $labQuery->where('doctor_id', $doctorId);
        $radiologyQuery->where('doctor_id', $doctorId);
        $prescriptionQuery->where('doctor_id', $doctorId);
    }

    // ── Stats ─────────────────────────────────────────────────────────
    $stats = [
        'today_appointments' => (clone $apptQuery)
            ->whereDate('appointment_date', Carbon::today())
            ->count(),

        'month_patients' => (clone $apptQuery)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->whereYear('appointment_date', Carbon::now()->year)
            ->distinct('patient_id')
            ->count('patient_id'),

        'pending_lab' => (clone $labQuery)
            ->whereIn('status', ['Pending', 'Sample Collected', 'Processing'])
            ->count(),

        'ready_lab' => (clone $labQuery)
            ->where('status', 'Completed')
            ->count(),

        'pending_radiology' => (clone $radiologyQuery)
            ->whereIn('status', ['Pending', 'Scheduled', 'In Progress'])
            ->count(),

        'ready_radiology' => (clone $radiologyQuery)
            ->whereIn('status', ['Reported', 'Verified'])
            ->count(),
    ];

    // ── Growth ────────────────────────────────────────────────────────
    $stats['last_month_patients'] = (clone $apptQuery)
        ->whereMonth('appointment_date', Carbon::now()->subMonth()->month)
        ->whereYear('appointment_date', Carbon::now()->subMonth()->year)
        ->distinct('patient_id')
        ->count('patient_id');

    $stats['patient_growth'] = $stats['last_month_patients'] > 0
        ? round((($stats['month_patients'] - $stats['last_month_patients']) / $stats['last_month_patients']) * 100, 1)
        : ($stats['month_patients'] > 0 ? 100 : 0);

    // ── Lists ─────────────────────────────────────────────────────────
    $todayAppointments = (clone $apptQuery)
        ->with('patient')
        ->whereDate('appointment_date', Carbon::today())
        ->orderBy('appointment_time')
        ->get();

    $recentLabResults = (clone $labQuery)
        ->with('patient')
        ->latest()
        ->take(5)
        ->get();

    $recentRadiologyReports = (clone $radiologyQuery)
        ->with('patient')
        ->latest()
        ->take(5)
        ->get();

    $recentPrescriptions = (clone $prescriptionQuery)
        ->with('patient')
        ->withCount('items')
        ->latest()
        ->take(5)
        ->get();

    // ── Monthly Trend ─────────────────────────────────────────────────
    $monthlyTrend = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $monthlyTrend[] = [
            'month' => $month->format('M'),
            'count' => (clone $apptQuery)
                ->whereMonth('appointment_date', $month->month)
                ->whereYear('appointment_date', $month->year)
                ->distinct('patient_id')
                ->count('patient_id'),
        ];
    }

    return view('doctors.doctor_dashboard', compact(
        'doctor',
        'stats',
        'todayAppointments',
        'recentLabResults',
        'recentRadiologyReports',
        'recentPrescriptions',
        'monthlyTrend'
    ));
}
}

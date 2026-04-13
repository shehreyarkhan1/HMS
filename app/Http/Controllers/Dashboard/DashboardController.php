<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Core counts ──
        $patient = Patient::count();
        $appointment = Appointment::whereDate('appointment_date', today())->count();

        // ── Patient growth: this month vs last month ──
        $thisMonthPatients = Patient::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthPatients = Patient::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $patientGrowth = $lastMonthPatients > 0
            ? round((($thisMonthPatients - $lastMonthPatients) / $lastMonthPatients) * 100, 1)
            : ($thisMonthPatients > 0 ? 100 : 0);

        // ── Appointment change: today vs yesterday ──
        $yesterdayCount = Appointment::whereDate('appointment_date', today()->subDay())->count();
        $appointmentChange = $appointment - $yesterdayCount;

        // ── Department Occupancy ──
        // Har department mein aaj kitne active appointments hain
        // Doctor table se departments nikalo, phir appointments count karo
        $allDepartments = Doctor::whereNotNull('department')
            ->where('is_active', true)
            ->pluck('department')
            ->unique()
            ->values();

        // Har department ka total doctor count (capacity)
        // Aur aaj ki appointments count
        $departmentColors = [
            'General' => '#1d4ed8',
            'Cardiology' => '#ef4444',
            'Pediatrics' => '#16a34a',
            'Surgery' => '#d97706',
            'Gynecology' => '#0f766e',
            'Gynae' => '#0f766e',
            'ICU' => '#dc2626',
            'Orthopedics' => '#7c3aed',
            'Neurology' => '#0891b2',
            'Dermatology' => '#db2777',
            'ENT' => '#65a30d',
            'Urology' => '#ea580c',
        ];

        $departmentOccupancy = [];

        foreach ($allDepartments as $dept) {
            // Is department ke doctors
            $deptDoctorIds = Doctor::where('department', $dept)
                ->where('is_active', true)
                ->pluck('id');

            // Aaj ki appointments in this department
            $todayAppts = Appointment::whereIn('doctor_id', $deptDoctorIds)
                ->whereDate('appointment_date', today())
                ->whereNotIn('status', ['Cancelled', 'No-show'])
                ->count();

            // Total capacity = doctors * 20 slots per day (adjustable)
            $capacity = max($deptDoctorIds->count() * 20, 1);

            $percent = min(round(($todayAppts / $capacity) * 100), 100);

            // Agar aaj koi appointment nahi to is month ka data use karo
            if ($todayAppts === 0) {
                $monthAppts = Appointment::whereIn('doctor_id', $deptDoctorIds)
                    ->whereMonth('appointment_date', now()->month)
                    ->whereYear('appointment_date', now()->year)
                    ->whereNotIn('status', ['Cancelled', 'No-show'])
                    ->count();

                $monthCapacity = max($deptDoctorIds->count() * 20 * now()->daysInMonth, 1);
                $percent = min(round(($monthAppts / $monthCapacity) * 100), 100);
            }

            $departmentOccupancy[] = [
                'name' => $dept,
                'percent' => $percent,
                'count' => $todayAppts,
                'color' => $departmentColors[$dept] ?? '#6366f1',
            ];
        }

        // Sort by percent descending
        usort($departmentOccupancy, fn($a, $b) => $b['percent'] <=> $a['percent']);

        // Max 6 departments dikhao
        // $departmentOccupancy = array_slice($departmentOccupancy, 0, 6);

        // ── Recent data ──
        $recentPatients = Patient::with('doctor')->latest()->take(5)->get();

        $recentAppointments = Appointment::with('patient', 'doctor')
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'patient',
            'appointment',
            'patientGrowth',
            'appointmentChange',
            'departmentOccupancy',
            'recentPatients',
            'recentAppointments'
        ));
    }
}
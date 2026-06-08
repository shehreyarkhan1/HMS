<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\BillPayment;
use App\Models\BloodDonor;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\DeathCertificate;
use App\Models\Dispensing;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\LabOrder;
use App\Models\Medicine;
use App\Models\MortuaryRecord;
use App\Models\OtRoom;
use App\Models\OtSchedule;
use App\Models\Patient;
use App\Models\RadiologyOrder;
use App\Models\Ward;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Doctors ka apna dashboard hai — unhe yahan nahi aana chahiye
        if (auth()->check() && auth()->user()->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        }

        // Pure data ko 10 minutes ke liye cache mein save kar rahe hain
        $data = Cache::remember('hospital_dashboard_stats_v2', 600, function () {

            // --- Core counts ---
            $stats['patient'] = Patient::count();
            $stats['appointment'] = Appointment::whereDate('appointment_date', today())->count();

            // --- Beds & Wards ---
            $stats['availableBeds'] = Bed::where('status', 'Available')->count();
            $stats['occupiedBeds'] = Bed::where('status', 'Occupied')->count();
            $stats['reservedBeds'] = Bed::whereIn('status', ['Reserved', 'Maintenance'])->count();
            $stats['totalBeds'] = Bed::count();
            $stats['totalWards'] = Ward::where('is_active', true)->count();
            $stats['occupancyRate'] = $stats['totalBeds'] > 0 ? round(($stats['occupiedBeds'] / $stats['totalBeds']) * 100) : 0;

            // --- Patient growth ---
            $thisMonthPatients = Patient::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $lastMonthPatients = Patient::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
            $stats['patientGrowth'] = $lastMonthPatients > 0 ? round((($thisMonthPatients - $lastMonthPatients) / $lastMonthPatients) * 100, 1) : ($thisMonthPatients > 0 ? 100 : 0);

            // --- Appointment change ---
            $yesterdayCount = Appointment::whereDate('appointment_date', today()->subDay())->count();
            $stats['appointmentChange'] = $stats['appointment'] - $yesterdayCount;

            // --- Revenue ---
            $stats['todayRevenue'] = BillPayment::whereDate('created_at', today())->where('amount', '>', 0)->sum('amount');
            $stats['monthRevenue'] = BillPayment::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
            $stats['totalRevenue'] = BillPayment::where('amount', '>', 0)->sum('amount');

            // --- Lab & Radiology ---
            $stats['labTotalOrders'] = LabOrder::count();
            $stats['labTodayOrders'] = LabOrder::whereDate('created_at', today())->count();
            $stats['labPendingOrders'] = LabOrder::whereIn('status', ['Pending', 'Sample Collected', 'Processing'])->count();
            $stats['labCompletedOrders'] = LabOrder::where('status', 'Completed')->count();
            $stats['labCompletionRate'] = $stats['labTotalOrders'] > 0 ? round(($stats['labCompletedOrders'] / $stats['labTotalOrders']) * 100) : 0;

            $stats['radTotalOrders'] = RadiologyOrder::count();
            $stats['radTodayOrders'] = RadiologyOrder::whereDate('created_at', today())->count();
            $stats['radReportedOrders'] = RadiologyOrder::whereIn('status', ['Reported', 'Verified', 'Delivered'])->count();
            $stats['radPendingOrders'] = RadiologyOrder::whereIn('status', ['Pending', 'Scheduled', 'In Progress', 'Scan Completed', 'Reporting'])->count();
            $stats['radRevenue'] = BillPayment::whereHas('bill.items', fn ($q) => $q->where('service_type', 'Radiology'))->sum('amount');
            $stats['radReportRate'] = $stats['radTotalOrders'] > 0 ? round(($stats['radReportedOrders'] / $stats['radTotalOrders']) * 100) : 0;

            // --- OT / Surgeries ---
            $stats['otTotalScheduled'] = OtSchedule::count();
            $stats['otTodayScheduled'] = OtSchedule::whereDate('scheduled_date', today())->count();
            $stats['otCompleted'] = OtSchedule::where('status', 'Completed')->count();
            $stats['otUpcoming'] = OtSchedule::whereDate('scheduled_date', '>=', today())->whereNotIn('status', ['Completed', 'Cancelled'])->count();
            $stats['otCompletionRate'] = $stats['otTotalScheduled'] > 0 ? round(($stats['otCompleted'] / $stats['otTotalScheduled']) * 100) : 0;
            $stats['otRooms'] = OtRoom::where('is_active', true)->count();

            // --- Pharmacy ---
            $stats['totalMedicines'] = Medicine::where('is_active', true)->count();
            $stats['lowStockMeds'] = Medicine::where('is_active', true)->whereColumn('total_stock', '<=', 'reorder_level')->count();
            $stats['totalDispensings'] = Dispensing::count();
            $stats['todayDispensings'] = Dispensing::whereDate('created_at', today())->count();
            $stats['pharmacyRevenue'] = Dispensing::sum('total_amount');
            $stats['stockHealthRate'] = $stats['totalMedicines'] > 0 ? round((($stats['totalMedicines'] - $stats['lowStockMeds']) / $stats['totalMedicines']) * 100) : 0;

            // --- Blood Bank & Mortuary ---
            $stats['totalDonors'] = BloodDonor::count();
            $stats['totalBloodRequests'] = BloodRequest::count();
            $stats['pendingBloodReqs'] = BloodRequest::whereIn('status', ['Pending', 'Under Review', 'Crossmatch'])->count();
            $stats['fulfilledBloodReqs'] = BloodRequest::where('status', 'Fulfilled')->count();
            $stats['bloodUnitsAvail'] = BloodInventory::sum('units_available');
            $stats['bloodFulfillRate'] = $stats['totalBloodRequests'] > 0 ? round(($stats['fulfilledBloodReqs'] / $stats['totalBloodRequests']) * 100) : 0;

            $stats['totalMortuary'] = MortuaryRecord::count();
            $stats['totalCertificates'] = DeathCertificate::count();
            $stats['releasedBodies'] = MortuaryRecord::where('status', 'Released')->count();
            $stats['medicoLegal'] = MortuaryRecord::where('is_medico_legal', true)->count();
            $stats['unclaimedBodies'] = MortuaryRecord::where('status', 'Unclaimed')->count();
            $stats['mortuaryReleaseRate'] = $stats['totalMortuary'] > 0 ? round(($stats['releasedBodies'] / $stats['totalMortuary']) * 100) : 0;

            // --- HR ---
            $stats['totalStaff'] = Employee::where('employment_status', 'Active')->count();
            $stats['totalDoctors'] = Doctor::where('is_active', true)->count();
            $stats['totalNurses'] = Employee::where('employment_status', 'Active')->where('department', 'Nursing')->count();
            $stats['clinicalStaff'] = Employee::where('employment_status', 'Active')->where('department', 'like', 'Clinical%')->count();
            $stats['clinicalRatio'] = $stats['totalStaff'] > 0 ? round(($stats['clinicalStaff'] / $stats['totalStaff']) * 100) : 0;

            // --- Department Occupancy Logic ---
            $stats['departmentOccupancy'] = $this->calculateDeptStats();

            // --- Recent Lists ---
            $stats['recentPatients'] = Patient::with('doctor.employee')->latest()->take(5)->get();
            $stats['recentAppointments'] = Appointment::with(['patient', 'doctor.employee'])
                ->whereDate('appointment_date', today())
                ->orderBy('appointment_time', 'asc')->take(5)->get();

            return $stats;
        });

        return view('dashboard.index', $data);
    }

    private function calculateDeptStats()
    {
        $allDepartments = Doctor::with('employee')
            ->whereHas('employee', function ($q) {
                $q->where('department', 'like', 'Clinical — %')->where('employment_status', 'Active');
            })->where('is_active', true)->get()->pluck('employee.department')->unique()->filter()->values();

        $departmentColors = [
            'Clinical — General Medicine' => '#1d4ed8', 'Clinical — Cardiology' => '#ef4444',
            'Clinical — Pediatrics' => '#16a34a', 'Clinical — Surgery' => '#d97706',
            'Clinical — Gynecology' => '#0f766e', 'Clinical — ICU' => '#dc2626',
        ];

        $occ = [];
        foreach ($allDepartments as $dept) {
            $deptDoctorIds = Doctor::whereHas('employee', fn ($q) => $q->where('department', $dept))->pluck('id');
            $todayAppts = Appointment::whereIn('doctor_id', $deptDoctorIds)->whereDate('appointment_date', today())->count();
            $capacity = max($deptDoctorIds->count() * 20, 1);

            $occ[] = [
                'name' => str_replace('Clinical — ', '', $dept),
                'percent' => min(round(($todayAppts / $capacity) * 100), 100),
                'count' => $todayAppts,
                'color' => $departmentColors[$dept] ?? '#6366f1',
            ];
        }
        usort($occ, fn ($a, $b) => $b['percent'] <=> $a['percent']);

        return $occ;
    }
}

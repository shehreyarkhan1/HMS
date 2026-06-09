<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
     // ── INDEX ─────────────────────────────────────────────────────────
    public function index()
    {
        $runs = PayrollRun::withCount('payslips')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(12);

        $stats = [
            'total_paid'    => PayrollRun::where('status', 'Paid')->sum('total_net'),
            'pending_runs'  => PayrollRun::whereIn('status', ['Draft', 'Processed'])->count(),
            'this_month'    => PayrollRun::where('year', now()->year)
                ->where('month', now()->month)->first(),
        ];

        return view('hr.payroll_index', compact('runs', 'stats'));
    }

    // ── CREATE — Start new payroll run ────────────────────────────────
    public function create(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        // Check if already exists
        $existing = PayrollRun::where('year', $year)->where('month', $month)->first();
        if ($existing) {
            return redirect()->route('hr.payroll.show', $existing)
                ->with('info', 'Payroll for this month already exists.');
        }

        $employees = Employee::with('salaryStructure')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get();

        return view('hr.payroll_create', compact('employees', 'month', 'year'));
    }

    // ── STORE — Generate payroll ──────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer|min:2020|max:2099',
            'payment_date' => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $month = $request->integer('month');
        $year  = $request->integer('year');

        // Duplicate check
        if (PayrollRun::where('year', $year)->where('month', $month)->exists()) {
            return back()->withErrors(['month' => 'Payroll for this month already processed.']);
        }

        $run = DB::transaction(function () use ($request, $month, $year) {

            $run = PayrollRun::create([
                'year'         => $year,
                'month'        => $month,
                'month_name'   => Carbon::createFromDate($year, $month, 1)->format('F Y'),
                'status'       => 'Processing',
                'payment_date' => $request->payment_date,
                'notes'        => $request->notes,
                'created_by'   => Auth::id(),
            ]);

            $employees = Employee::with(['salaryStructure', 'attendances' => fn ($q) =>
                $q->forMonth($month, $year)
            ])->where('employment_status', 'Active')->get();

            foreach ($employees as $employee) {
                $this->generatePayslip($run, $employee, $month, $year);
            }

            $run->update(['status' => 'Processed', 'processed_at' => now()]);
            $run->recalculateSummary();

            return $run;
        });

        return redirect()->route('hr.payroll.show', $run)
            ->with('success', "Payroll generated for {$run->month_name}. {$run->total_employees} employees processed.");
    }

    // ── SHOW ──────────────────────────────────────────────────────────
    public function show(PayrollRun $payroll)
    {
        $payroll->load('createdBy', 'approvedBy');

        $payslips = Payslip::with('employee')
            ->where('payroll_run_id', $payroll->id)
            ->orderBy('payslip_number')
            ->paginate(25);

        return view('hr.payroll_show', compact('payroll', 'payslips'));
    }

    // ── APPROVE ───────────────────────────────────────────────────────
    public function approve(PayrollRun $payroll)
    {
        if (! $payroll->isProcessed()) {
            return back()->with('error', 'Only processed payroll can be approved.');
        }

        $payroll->update([
            'status'      => 'Approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Payroll approved. Ready for payment.');
    }

    // ── MARK PAID ─────────────────────────────────────────────────────
    public function markPaid(Request $request, PayrollRun $payroll)
    {
        if (! $payroll->isApproved()) {
            return back()->with('error', 'Only approved payroll can be marked as paid.');
        }

        $request->validate([
            'payment_date' => 'required|date|before_or_equal:today',
        ]);

        DB::transaction(function () use ($payroll, $request) {
            $payroll->payslips()->update([
                'is_paid'  => true,
                'paid_on'  => $request->payment_date,
                'status'   => 'Paid',
            ]);

            $payroll->update([
                'status'       => 'Paid',
                'payment_date' => $request->payment_date,
            ]);
        });

        return back()->with('success', 'Payroll marked as paid.');
    }

    // ── PAYSLIP — Individual ──────────────────────────────────────────
    public function payslip(Payslip $payslip)
    {
        $payslip->load(['employee.salaryStructure', 'payrollRun']);

        return view('hr.payslip_view', compact('payslip'));
    }

    // ── PRINT PAYSLIP ─────────────────────────────────────────────────
    public function printPayslip(Payslip $payslip)
    {
        $payslip->load(['employee', 'payrollRun']);

        return view('hr.payslip_print', compact('payslip'));
    }

    // ── PRIVATE: Generate payslip for one employee ────────────────────
    private function generatePayslip(PayrollRun $run, Employee $employee, int $month, int $year): void
    {
        $structure = $employee->salaryStructure;

        if (! $structure) return; // No salary structure — skip

        // ── Attendance data ────────────────────────────────────────
        $attendances = $employee->attendances;

        $startDate     = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate       = $startDate->copy()->endOfMonth();
        $totalWorkDays = $this->countWorkingDays($startDate, $endDate);

        $presentDays  = $attendances->whereIn('status', ['Present', 'Work From Home'])->count();
        $lateDays     = $attendances->where('status', 'Late')->count();
        $absentDays   = $attendances->where('status', 'Absent')->count();
        $halfDays     = $attendances->where('status', 'Half Day')->count();
        $leaveDays    = $attendances->where('status', 'On Leave')->count();
        $holidayDays  = $attendances->where('status', 'Holiday')->count();
        $overtimeHours = round($attendances->sum('overtime_minutes') / 60, 2);

        // ── Per day salary ─────────────────────────────────────────
        $perDaySalary = $totalWorkDays > 0
            ? round($structure->gross_salary / $totalWorkDays, 2)
            : 0;

        // ── Absent deduction ───────────────────────────────────────
        $absentDeduction = $perDaySalary * $absentDays;
        $halfDayDeduction = ($perDaySalary / 2) * $halfDays;
        $lateDeduction = 0; // Can add fine logic here

        // ── Overtime pay ───────────────────────────────────────────
        $hourlyRate    = $totalWorkDays > 0 ? $perDaySalary / 8 : 0;
        $overtimeAmount = round($hourlyRate * $overtimeHours * 1.5, 2); // 1.5x rate

        $grossSalary = $structure->gross_salary + $overtimeAmount;

        // ── Total deductions ───────────────────────────────────────
        $totalDeductions = $structure->total_deductions
            + $absentDeduction
            + $halfDayDeduction
            + $lateDeduction;

        $netSalary = max(0, $grossSalary - $totalDeductions);

        Payslip::create([
            'payroll_run_id'       => $run->id,
            'employee_id'          => $employee->id,
            'salary_structure_id'  => $structure->id,

            // Attendance
            'total_working_days'   => $totalWorkDays,
            'present_days'         => $presentDays,
            'absent_days'          => $absentDays,
            'late_days'            => $lateDays,
            'half_days'            => $halfDays,
            'leave_days'           => $leaveDays,
            'holiday_days'         => $holidayDays,
            'overtime_hours'       => $overtimeHours,

            // Earnings — snapshot from structure
            'basic_salary'         => $structure->basic_salary,
            'house_rent_allowance' => $structure->house_rent_allowance,
            'medical_allowance'    => $structure->medical_allowance,
            'transport_allowance'  => $structure->transport_allowance,
            'meal_allowance'       => $structure->meal_allowance,
            'special_allowance'    => $structure->special_allowance,
            'other_allowance'      => $structure->other_allowance,
            'overtime_amount'      => $overtimeAmount,
            'bonus'                => 0,
            'arrears'              => 0,
            'gross_salary'         => $grossSalary,

            // Deductions — snapshot
            'income_tax_monthly'   => $structure->income_tax_monthly,
            'tax_slab'             => $structure->tax_slab,
            'eobi_employee_share'  => $structure->eobi_employee_share,
            'provident_fund'       => $structure->provident_fund,
            'loan_deduction'       => $structure->loan_deduction,
            'absent_deduction'     => $absentDeduction + $halfDayDeduction,
            'late_deduction'       => $lateDeduction,
            'other_deduction'      => $structure->other_deduction,
            'total_deductions'     => $totalDeductions,

            // Net
            'net_salary'           => $netSalary,
            'per_day_salary'       => $perDaySalary,

            // Payment info snapshot
            'bank_account_number'  => $employee->bank_account_number,
            'bank_name'            => $employee->bank_name,
            'payment_method'       => 'Bank Transfer',
            'status'               => 'Generated',
        ]);
    }

    // ── PRIVATE: Count working days in a month ────────────────────────
    private function countWorkingDays(Carbon $start, Carbon $end): int
    {
        $days = 0;
        $current = $start->copy();

        $holidays = Holiday::active()
            ->whereBetween('date', [$start, $end])
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            if (! $current->isFriday() && ! $current->isSaturday()
                && ! in_array($dateStr, $holidays)) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }
}

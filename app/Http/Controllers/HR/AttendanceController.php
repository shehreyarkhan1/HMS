<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $query = Attendance::with('employee')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(25)->withQueryString();

        $employees = Employee::where('employment_status', 'Active')
            ->orderBy('first_name')->get();

        // Monthly stats
        $stats = [
            'present' => Attendance::whereMonth('date', $month)->whereYear('date', $year)
                ->whereIn('status', ['Present', 'Late', 'Work From Home'])->count(),
            'absent' => Attendance::whereMonth('date', $month)->whereYear('date', $year)
                ->where('status', 'Absent')->count(),
            'on_leave' => Attendance::whereMonth('date', $month)->whereYear('date', $year)
                ->where('status', 'On Leave')->count(),
            'late' => Attendance::whereMonth('date', $month)->whereYear('date', $year)
                ->where('status', 'Late')->count(),
        ];

        return view('hr.attendance_index', compact(
            'attendances', 'employees', 'stats', 'month', 'year'
        ));
    }

    // ── MONTHLY VIEW — Grid per employee ──────────────────────────────
    public function monthly(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employees = Employee::where('employment_status', 'Active')
            ->orderBy('first_name')->get();

        // All attendance for this month
        $attendances = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy('employee_id')
            ->map(fn ($records) => $records->keyBy(
                fn ($r) => Carbon::parse($r->date)->format('Y-m-d')
            ));

        // Holidays this month
        $holidays = Holiday::active()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        // Generate dates array
        $dates = [];
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dates[] = $current->copy();
            $current->addDay();
        }

        return view('hr.attendance_monthly', compact(
            'employees', 'attendances', 'dates', 'holidays', 'month', 'year'
        ));
    }

    // ── MARK ATTENDANCE (Single) ──────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:Present,Absent,Late,Half Day,On Leave,Holiday,Weekend,Work From Home',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'notes' => 'nullable|string|max:255',
            'source' => 'in:Manual,Biometric,System',
        ]);

        // Calculate working minutes
        if (! empty($validated['check_in']) && ! empty($validated['check_out'])) {
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $validated['working_minutes'] = $checkOut->diffInMinutes($checkIn);

            // Calculate overtime (after 8 hours = 480 mins)
            $validated['overtime_minutes'] = max(0, $validated['working_minutes'] - 480);

            // Calculate late (if after 9:00 AM)
            $shiftStart = Carbon::parse('09:00');
            if ($checkIn->gt($shiftStart)) {
                $validated['late_minutes'] = $shiftStart->diffInMinutes($checkIn);
                if ($validated['status'] === 'Present' && $validated['late_minutes'] > 15) {
                    $validated['status'] = 'Late';
                }
            }
        }

        $validated['source'] = $request->input('source', 'Manual');

        Attendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            $validated
        );

        return back()->with('success', 'Attendance marked successfully.');
    }

    // ── BULK MARK (For all employees for a date) ──────────────────────
    public function bulkStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.status' => 'required|in:Present,Absent,Late,Half Day,On Leave,Holiday,Weekend,Work From Home',
            'attendances.*.check_in' => 'nullable|date_format:H:i',
            'attendances.*.check_out' => 'nullable|date_format:H:i',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->attendances as $data) {
                $workingMinutes = 0;
                $overtimeMinutes = 0;
                $lateMinutes = 0;

                if (! empty($data['check_in']) && ! empty($data['check_out'])) {
                    $checkIn = Carbon::parse($data['check_in']);
                    $checkOut = Carbon::parse($data['check_out']);
                    $workingMinutes = $checkOut->diffInMinutes($checkIn);
                    $overtimeMinutes = max(0, $workingMinutes - 480);

                    $shiftStart = Carbon::parse('09:00');
                    if ($checkIn->gt($shiftStart)) {
                        $lateMinutes = $shiftStart->diffInMinutes($checkIn);
                    }
                }

                Attendance::updateOrCreate(
                    ['employee_id' => $data['employee_id'], 'date' => $request->date],
                    [
                        'status' => $data['status'],
                        'check_in' => $data['check_in'] ?? null,
                        'check_out' => $data['check_out'] ?? null,
                        'working_minutes' => $workingMinutes,
                        'overtime_minutes' => $overtimeMinutes,
                        'late_minutes' => $lateMinutes,
                        'source' => 'Manual',
                    ]
                );
            }
        });

        return back()->with('success', 'Attendance saved for '.count($request->attendances).' employees.');
    }

    // ── REGULARIZE (Correct a past attendance) ────────────────────────
    public function regularize(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:Present,Absent,Late,Half Day,On Leave,Holiday,Weekend,Work From Home',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'regularization_reason' => 'required|string|max:500',
        ]);

        $attendance->update([
            'status' => $request->status,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'is_regularized' => true,
            'regularized_by' => Auth::id(),
            'regularization_reason' => $request->regularization_reason,
        ]);

        return back()->with('success', 'Attendance regularized successfully.');
    }

    // ── EMPLOYEE ATTENDANCE SUMMARY ───────────────────────────────────
    public function summary(Request $request, Employee $employee)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $attendances = Attendance::forEmployee($employee->id)
            ->forMonth($month, $year)
            ->orderBy('date')
            ->get();

        $summary = [
            'total_days' => $attendances->count(),
            'present' => $attendances->whereIn('status', ['Present', 'Work From Home'])->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'half_day' => $attendances->where('status', 'Half Day')->count(),
            'on_leave' => $attendances->where('status', 'On Leave')->count(),
            'total_working_minutes' => $attendances->sum('working_minutes'),
            'total_overtime_minutes' => $attendances->sum('overtime_minutes'),
            'total_late_minutes' => $attendances->sum('late_minutes'),
        ];

        return view('hr.attendance_summary', compact(
            'employee', 'attendances', 'summary', 'month', 'year'
        ));
    }
}

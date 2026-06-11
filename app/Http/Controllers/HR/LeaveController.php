<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday;

class LeaveController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'leaveType', 'reviewedBy'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%")
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('month')) {
            $query->whereMonth('from_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('from_date', $request->year);
        }

        $requests = $query->paginate(20)->withQueryString();
        $leaveTypes = LeaveType::active()->orderBy('name')->get();

        $stats = [
            'pending' => LeaveRequest::where('status', 'Pending')->count(),
            'approved' => LeaveRequest::where('status', 'Approved')
                ->whereYear('from_date', now()->year)->count(),
            'rejected' => LeaveRequest::where('status', 'Rejected')
                ->whereYear('from_date', now()->year)->count(),
            'today_on_leave' => LeaveRequest::where('status', 'Approved')
                ->whereDate('from_date', '<=', today())
                ->whereDate('to_date', '>=', today())
                ->count(),
        ];

        // ✅ Fix: dot notation — hr/leaves/index.blade.php
        return view('hr.leave_request_index', compact('requests', 'leaveTypes', 'stats'));
    }

    // ── CREATE ────────────────────────────────────────────────────────
    public function create(Request $request)
    {
        $employees = Employee::where('employment_status', 'Active')
            ->orderBy('first_name')->get();
        $leaveTypes = LeaveType::active()->orderBy('name')->get();

        $selectedEmployee = $request->filled('employee_id')
            ? Employee::with('leaveBalances.leaveType')->find($request->employee_id)
            : null;

        return view('hr.leave_create', compact('employees', 'leaveTypes', 'selectedEmployee'));
    }

    // ── STORE ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'half_day' => 'boolean',
            'half_day_type' => 'nullable|in:Morning,Afternoon',
            'reason' => 'required|string|max:500',
            'document_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);

        // ── Calculate working days ─────────────────────────────────
        $totalDays = $this->calculateWorkingDays(
            Carbon::parse($validated['from_date']),
            Carbon::parse($validated['to_date'])
        );

        if ($request->boolean('half_day')) {
            $totalDays = 0.5;
        }

        // ── Check balance ──────────────────────────────────────────
        $balance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveType->id)
            ->where('year', now()->year)
            ->first();

        if ($balance && ! $balance->hasBalance($totalDays)) {
            return back()->withInput()->withErrors([
                'leave_type_id' => "Insufficient balance. Available: {$balance->remaining_days} days.",
            ]);
        }

        // ── Overlap check ──────────────────────────────────────────
        $overlap = LeaveRequest::where('employee_id', $employee->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('from_date', [$validated['from_date'], $validated['to_date']])
                    ->orWhereBetween('to_date', [$validated['from_date'], $validated['to_date']])
                    ->orWhere(function ($q2) use ($validated) {
                        $q2->where('from_date', '<=', $validated['from_date'])
                            ->where('to_date', '>=', $validated['to_date']);
                    });
            })->exists();

        if ($overlap) {
            return back()->withInput()->withErrors([
                'from_date' => 'Employee already has an overlapping leave request.',
            ]);
        }

        DB::transaction(function () use ($validated, $request, $totalDays, $balance) {
            if ($request->hasFile('document_path')) {
                $validated['document_path'] = $request->file('document_path')
                    ->store('leave-documents', 'public');
            }

            $validated['total_days'] = $totalDays;
            $validated['half_day'] = $request->boolean('half_day');

            LeaveRequest::create($validated);

            if ($balance) {
                $balance->pending_days += $totalDays;
                $balance->recalculate();
            }
        });

        return redirect()->route('hr.leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    // ── SHOW ──────────────────────────────────────────────────────────
    public function show(LeaveRequest $leave)
    {
        $leave->load(['employee', 'leaveType', 'reviewedBy', 'cancelledBy']);

        return view('hr.leave_show', compact('leave'));
    }

    // ── APPROVE ───────────────────────────────────────────────────────
    public function approve(Request $request, LeaveRequest $leave)
    {
        if (! $leave->isPending()) {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $request->validate([
            'review_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($leave, $request) {
            $leave->update([
                'status' => 'Approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
            ]);

            $balance = LeaveBalance::where('employee_id', $leave->employee_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', Carbon::parse($leave->from_date)->year)
                ->first();

            if ($balance) {
                $balance->pending_days -= $leave->total_days;
                $balance->used_days += $leave->total_days;
                $balance->recalculate();
            }
        });

        return back()->with('success', 'Leave request approved.');
    }

    // ── REJECT ────────────────────────────────────────────────────────
    public function reject(Request $request, LeaveRequest $leave)
    {
        if (! $leave->isPending()) {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $request->validate([
            'review_notes' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($leave, $request) {
            $leave->update([
                'status' => 'Rejected',
                'reviewed_by' => Auth::id(), // ✅ Fix: users.id
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
            ]);

            $balance = LeaveBalance::where('employee_id', $leave->employee_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', Carbon::parse($leave->from_date)->year)
                ->first();

            if ($balance) {
                $balance->pending_days = max(0, $balance->pending_days - $leave->total_days);
                $balance->recalculate();
            }
        });

        return back()->with('success', 'Leave request rejected.');
    }

    // ── CANCEL ────────────────────────────────────────────────────────
    public function cancel(Request $request, LeaveRequest $leave)
    {
        if (! in_array($leave->status, ['Pending', 'Approved'])) {
            return back()->with('error', 'Cannot cancel this request.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($leave, $request) {
            $wasApproved = $leave->isApproved();
            $wasPending = $leave->isPending();

            $leave->update([
                'status' => 'Cancelled',
                'cancelled_by' => Auth::id(), // ✅ Fix: users.id
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            $balance = LeaveBalance::where('employee_id', $leave->employee_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', Carbon::parse($leave->from_date)->year)
                ->first();

            if ($balance) {
                if ($wasApproved) {
                    $balance->used_days = max(0, $balance->used_days - $leave->total_days);
                } elseif ($wasPending) {
                    $balance->pending_days = max(0, $balance->pending_days - $leave->total_days);
                }
                $balance->recalculate();
            }
        });

        return back()->with('success', 'Leave request cancelled.');
    }

    // ── BALANCES ──────────────────────────────────────────────────────
    public function balances(Request $request)
    {
        $year = $request->integer('year', now()->year);
        $employees = Employee::with(['leaveBalances' => fn ($q) => $q->where('year', $year)
            ->with('leaveType')])
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->paginate(20);

        $leaveTypes = LeaveType::active()->get();

        return view('hr.leave_balances', compact('employees', 'leaveTypes', 'year'));
    }

    // ── PRIVATE: Calculate working days ───────────────────────────────
    private function calculateWorkingDays(Carbon $from, Carbon $to): float
    {
        $days = 0;
        $current = $from->copy();

        // 1. Database se wo saari holidays nikalen jo is leave period ke darmiyan hain
        $holidayRecords = Holiday::where('is_active', true)
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                    ->orWhereBetween('date_to', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                    ->orWhere(function ($q) use ($from, $to) {
                        $q->where('date', '<=', $from->format('Y-m-d'))
                            ->where('date_to', '>=', $to->format('Y-m-d'));
                    });
            })->get();

        // 2. In holidays ki saari dates ko ek array mein jama karein (taake multi-day handle ho sake)
        $holidayDates = [];
        foreach ($holidayRecords as $h) {
            $hStart = Carbon::parse($h->date);
            $hEnd = $h->date_to ? Carbon::parse($h->date_to) : $hStart;

            while ($hStart->lte($hEnd)) {
                $holidayDates[] = $hStart->format('Y-m-d');
                $hStart->addDay();
            }
        }
        // Duplicate dates khatam karein (agar koi overlap ho)
        $holidayDates = array_unique($holidayDates);

        // 3. Main Loop: Har din ko check karein
        while ($current->lte($to)) {
            $dateStr = $current->format('Y-m-d');

            $isWeekend = $current->isFriday() || $current->isSaturday();
            $isHoliday = in_array($dateStr, $holidayDates);

            // Sirf tab count karein agar na weekend ho aur na hi holiday
            if (! $isWeekend && ! $isHoliday) {
                $days++;
            }
            $current->addDay();
        }

        return (float) $days;
    }

    // ── ALLOCATE BALANCES (Yearly) ────────────────────────────
    public function allocateBalances(Request $request)
    {
        $year = $request->integer('year', now()->year);

        $employees = Employee::where('employment_status', 'Active')->get();
        $leaveTypes = LeaveType::active()->get();

        $count = 0;

        DB::transaction(function () use ($employees, $leaveTypes, $year, &$count) {
            foreach ($employees as $emp) {
                foreach ($leaveTypes as $lt) {
                    // Already exists toh skip karo
                    $exists = LeaveBalance::where('employee_id', $emp->id)
                        ->where('leave_type_id', $lt->id)
                        ->where('year', $year)
                        ->exists();

                    if (! $exists) {
                        LeaveBalance::create([
                            'employee_id' => $emp->id,
                            'leave_type_id' => $lt->id,
                            'year' => $year,
                            'allocated_days' => $lt->days_per_year,
                            'used_days' => 0,
                            'pending_days' => 0,
                            'remaining_days' => $lt->days_per_year,
                        ]);
                        $count++;
                    }
                }
            }
        });

        return back()->with('success', "{$count} leave balances allocated for {$year}.");
    }
}

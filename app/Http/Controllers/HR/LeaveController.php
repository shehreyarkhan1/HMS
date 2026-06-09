<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Employee;

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

        return view('hr.leave_type_index', compact('requests', 'leaveTypes', 'stats'));
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

            // Document upload
            if ($request->hasFile('document_path')) {
                $validated['document_path'] = $request->file('document_path')
                    ->store('leave-documents', 'public');
            }

            $validated['total_days'] = $totalDays;
            $validated['half_day'] = $request->boolean('half_day');

            $leaveRequest = LeaveRequest::create($validated);

            // Update pending days in balance
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
                'reviewed_by' => Auth::user()->employee?->id,
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
            ]);

            // Move from pending to used in balance
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
                'reviewed_by' => Auth::user()->employee?->id,
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
            ]);

            // Remove from pending in balance
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
                'cancelled_by' => Auth::user()->employee?->id,
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Restore balance
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

        while ($current->lte($to)) {
            // Skip weekends (Friday=5, Saturday=6 for Pakistan)
            if (! $current->isFriday() && ! $current->isSaturday()) {
                $days++;
            }
            $current->addDay();
        }

        return (float) $days;
    }
}

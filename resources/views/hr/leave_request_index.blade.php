@extends('layouts.master')
@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')
@section('breadcrumb', 'HR / Leaves')

@push('styles')
<style>
    .stat-card{background:#fff;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;height:100%;transition:box-shadow .15s}
    .stat-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.07)}
    .stat-label{font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem}
    .stat-value{font-size:32px;font-weight:700;color:#1e293b;line-height:1;margin-bottom:.4rem}
    .stat-sub{font-size:12px;color:#94a3b8}
    .stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
    .section-header{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .section-title{font-size:14px;font-weight:600;color:#1e293b}
    .lv-row{display:flex;align-items:center;padding:.8rem 1.25rem;border-bottom:1px solid #f8fafc;gap:12px;transition:background .1s}
    .lv-row:last-child{border-bottom:none}
    .lv-row:hover{background:#f8fafc}
    .emp-avatar{width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .lv-info{flex:1;min-width:0}
    .lv-name{font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .lv-meta{font-size:11px;color:#94a3b8;margin-top:1px}
    .status-badge{font-size:10px;font-weight:600;padding:3px 10px;border-radius:20px;white-space:nowrap}
    .badge-pending{background:#fef3c7;color:#b45309}
    .badge-approved{background:#dcfce7;color:#16a34a}
    .badge-rejected{background:#fee2e2;color:#dc2626}
    .badge-cancelled{background:#f1f5f9;color:#64748b}
    .filter-bar{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:1rem 1.25rem;margin-bottom:1rem}
    .empty-state{padding:3rem;text-align:center;color:#94a3b8;font-size:13px}
    .empty-state i{font-size:32px;display:block;margin-bottom:.75rem;color:#cbd5e1}
    .today-badge{font-size:10px;font-weight:700;background:#dcfce7;color:#16a34a;padding:2px 8px;border-radius:20px}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Leave Requests</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">Review and manage employee leave applications</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.leaves.balances') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-bar-chart me-1"></i> Balances
        </a>
        <a href="{{ route('hr.leaves.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Request
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Pending</div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-sub">awaiting review</div></div>
                <div class="stat-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Approved</div><div class="stat-value">{{ $stats['approved'] }}</div><div class="stat-sub">this year</div></div>
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">On Leave Today</div><div class="stat-value">{{ $stats['today_on_leave'] }}</div><div class="stat-sub">employees</div></div>
                <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-person-dash"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Rejected</div><div class="stat-value">{{ $stats['rejected'] }}</div><div class="stat-sub">this year</div></div>
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-x-circle"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Filter bar --}}
<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-3">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">SEARCH</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / ID…" value="{{ request('search') }}">
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">STATUS</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach(['Pending','Approved','Rejected','Cancelled'] as $s)
                    <option {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">LEAVE TYPE</label>
            <select name="leave_type_id" class="form-select form-select-sm">
                <option value="">All Types</option>
                @foreach($leaveTypes as $lt)
                    <option value="{{ $lt->id }}" {{ request('leave_type_id') == $lt->id ? 'selected' : '' }}>{{ $lt->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4 col-md-2">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">MONTH</label>
            <select name="month" class="form-select form-select-sm">
                <option value="">All</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('M') }}</option>
                @endfor
            </select>
        </div>
        <div class="col-4 col-md-1">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">YEAR</label>
            <input type="number" name="year" class="form-control form-control-sm" value="{{ request('year', now()->year) }}" min="2020">
        </div>
        <div class="col-4 col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
    </form>
</div>

<div class="section-card">
    <div class="section-header">
        <span class="section-title"><i class="bi bi-calendar2-week me-2" style="color:#1d4ed8"></i>Requests</span>
        <span style="font-size:12px;color:#94a3b8">{{ $requests->total() }} total</span>
    </div>

    @forelse($requests as $leave)
    @php
        $statusClass = match($leave->status) {
            'Approved'  => 'badge-approved',
            'Rejected'  => 'badge-rejected',
            'Cancelled' => 'badge-cancelled',
            default     => 'badge-pending',
        };
        $emp = $leave->employee;
        $initials = strtoupper(substr($emp->first_name ?? 'E', 0, 1) . substr($emp->last_name ?? '', 0, 1));
    @endphp
    <div class="lv-row">
        <div class="emp-avatar">{{ $initials }}</div>
        <div class="lv-info">
            <div class="lv-name">
                {{ $emp->first_name ?? '' }} {{ $emp->last_name ?? '' }}
                @if($leave->status === 'Approved' && $leave->from_date <= today() && $leave->to_date >= today())
                    <span class="today-badge ms-1">On Leave Now</span>
                @endif
            </div>
            <div class="lv-meta">
                {{ $leave->leaveType->name ?? '—' }} &middot;
                {{ \Carbon\Carbon::parse($leave->from_date)->format('d M') }} – {{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}
                &middot; {{ $leave->total_days }} day(s)
                @if($leave->half_day) &middot; <span style="color:#7c3aed">Half Day ({{ $leave->half_day_type }})</span> @endif
            </div>
        </div>
        <span class="status-badge {{ $statusClass }}">{{ $leave->status }}</span>
        <a href="{{ route('hr.leaves.show', $leave) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
            <i class="bi bi-eye"></i>
        </a>
    </div>
    @empty
        <div class="empty-state"><i class="bi bi-calendar-x"></i>No leave requests found.</div>
    @endforelse
</div>

<div class="mt-3 d-flex justify-content-center">
    {{ $requests->links() }}
</div>
@endsection

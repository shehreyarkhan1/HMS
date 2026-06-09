@extends('layouts.master')
@section('title', 'Leave Balances')
@section('page-title', 'Leave Balances')
@section('breadcrumb', 'HR / Leaves / Balances')

@push('styles')
<style>
    .section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
    .section-header{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .section-title{font-size:14px;font-weight:600;color:#1e293b}
    .bal-badge{font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;white-space:nowrap}
    .bal-green{background:#dcfce7;color:#16a34a}
    .bal-yellow{background:#fef3c7;color:#b45309}
    .bal-red{background:#fee2e2;color:#dc2626}
    .bal-grey{background:#f1f5f9;color:#64748b}
    table th{font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;background:#f8fafc;border-bottom:1px solid #e2e8f0!important;padding:.75rem 1rem!important}
    table td{font-size:13px;color:#1e293b;padding:.75rem 1rem!important;vertical-align:middle!important;border-bottom:1px solid #f8fafc!important}
    table tbody tr:hover{background:#f8fafc}
    .emp-cell{display:flex;align-items:center;gap:10px}
    .emp-avatar{width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .progress-thin{height:5px;border-radius:3px;background:#f1f5f9;margin-top:4px}
    .progress-fill{height:100%;border-radius:3px;background:#1d4ed8}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Leave Balances</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">Annual leave entitlements and usage</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" class="d-flex gap-2">
            <input type="number" name="year" class="form-control form-control-sm" style="width:90px" value="{{ $year }}" min="2020">
            <button type="submit" class="btn btn-primary btn-sm">Go</button>
        </form>
        <a href="{{ route('hr.leaves.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <span class="section-title"><i class="bi bi-bar-chart me-2" style="color:#1d4ed8"></i>{{ $year }} Leave Balances</span>
        <span style="font-size:12px;color:#94a3b8">{{ $employees->total() }} employees</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    @foreach($leaveTypes as $lt)<th class="text-center">{{ $lt->code }}</th>@endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php
                    $balMap = $emp->leaveBalances->keyBy('leave_type_id');
                @endphp
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar">{{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $emp->employee_id }} &middot; {{ $emp->department }}</div>
                            </div>
                        </div>
                    </td>
                    @foreach($leaveTypes as $lt)
                    @php
                        $bal = $balMap[$lt->id] ?? null;
                        $used = $bal?->used_days ?? 0;
                        $alloc = $bal?->allocated_days ?? $lt->days_per_year;
                        $rem = $bal?->remaining_days ?? $alloc;
                        $pct = $alloc > 0 ? round(($used / $alloc) * 100) : 0;
                        $cls = $rem <= 0 ? 'bal-red' : ($pct >= 70 ? 'bal-yellow' : 'bal-green');
                    @endphp
                    <td class="text-center">
                        @if($bal)
                            <span class="bal-badge {{ $cls }}">{{ $rem }}/{{ $alloc }}</span>
                            <div class="progress-thin"><div class="progress-fill" style="width:{{ $pct }}%;background:{{ $rem <= 0 ? '#dc2626' : ($pct >= 70 ? '#b45309' : '#1d4ed8') }}"></div></div>
                        @else
                            <span class="bal-badge bal-grey">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr><td colspan="{{ $leaveTypes->count() + 1 }}" class="text-center py-4" style="color:#94a3b8">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center">
    <div style="font-size:12px;color:#94a3b8">
        <span class="bal-badge bal-green">Remaining/Allocated</span>
        &nbsp; Green = OK &nbsp;
        <span class="bal-badge bal-yellow">Yellow</span> = 70%+ used &nbsp;
        <span class="bal-badge bal-red">Red</span> = Exhausted
    </div>
    {{ $employees->links() }}
</div>
@endsection

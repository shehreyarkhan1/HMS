@extends('layouts.master')
@section('title', 'Attendance')
@section('page-title', 'Attendance')
@section('breadcrumb', 'HR / Attendance')

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
    .filter-bar{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:1rem 1.25rem;margin-bottom:1rem}
    .att-row{display:flex;align-items:center;padding:.75rem 1.25rem;border-bottom:1px solid #f8fafc;gap:12px;transition:background .1s}
    .att-row:last-child{border-bottom:none}
    .att-row:hover{background:#f8fafc}
    .emp-avatar{width:34px;height:34px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .att-info{flex:1;min-width:0}
    .att-name{font-size:13px;font-weight:600;color:#1e293b}
    .att-meta{font-size:11px;color:#94a3b8;margin-top:1px}
    .status-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
    .status-badge{font-size:10px;font-weight:600;padding:3px 9px;border-radius:20px;white-space:nowrap}
    .s-present{background:#dcfce7;color:#16a34a}
    .s-absent{background:#fee2e2;color:#dc2626}
    .s-late{background:#fef3c7;color:#b45309}
    .s-leave{background:#ede9fe;color:#7c3aed}
    .s-half{background:#dbeafe;color:#1d4ed8}
    .s-holiday{background:#f0fdf4;color:#16a34a}
    .s-wfh{background:#e0f2fe;color:#0284c7}
    .s-weekend{background:#f1f5f9;color:#94a3b8}
    .time-pill{font-size:11px;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:20px;white-space:nowrap}
    .empty-state{padding:3rem;text-align:center;color:#94a3b8;font-size:13px}
    .empty-state i{font-size:32px;display:block;margin-bottom:.75rem;color:#cbd5e1}
    .source-icon{font-size:10px;color:#94a3b8}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Attendance</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }} attendance records</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.attendance.monthly', ['month' => $month, 'year' => $year]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-grid-3x3-gap me-1"></i> Monthly Grid
        </a>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#markModal">
            <i class="bi bi-plus-lg me-1"></i> Mark Attendance
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Present</div><div class="stat-value">{{ $stats['present'] }}</div><div class="stat-sub">incl. late & WFH</div></div>
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-person-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Absent</div><div class="stat-value">{{ $stats['absent'] }}</div><div class="stat-sub">this month</div></div>
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-person-x"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">On Leave</div><div class="stat-value">{{ $stats['on_leave'] }}</div><div class="stat-sub">this month</div></div>
                <div class="stat-icon" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-person-dash"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Late</div><div class="stat-value">{{ $stats['late'] }}</div><div class="stat-sub">this month</div></div>
                <div class="stat-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-6 col-md-2">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">MONTH</label>
            <select name="month" class="form-select form-select-sm">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">YEAR</label>
            <input type="number" name="year" class="form-control form-control-sm" value="{{ $year }}" min="2020">
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">EMPLOYEE</label>
            <select name="employee_id" class="form-select form-select-sm">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->first_name }} {{ $emp->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">STATUS</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach(['Present','Absent','Late','Half Day','On Leave','Work From Home','Holiday','Weekend'] as $s)
                    <option {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
    </form>
</div>

<div class="section-card">
    <div class="section-header">
        <span class="section-title"><i class="bi bi-clock me-2" style="color:#1d4ed8"></i>Records</span>
        <span style="font-size:12px;color:#94a3b8">{{ $attendances->total() }} records</span>
    </div>

    @forelse($attendances as $att)
    @php
        $statusClass = match($att->status) {
            'Present'       => 's-present',
            'Absent'        => 's-absent',
            'Late'          => 's-late',
            'On Leave'      => 's-leave',
            'Half Day'      => 's-half',
            'Holiday'       => 's-holiday',
            'Work From Home'=> 's-wfh',
            default         => 's-weekend',
        };
        $emp = $att->employee;
        $initials = strtoupper(substr($emp->first_name ?? 'E', 0, 1) . substr($emp->last_name ?? '', 0, 1));
    @endphp
    <div class="att-row">
        <div class="emp-avatar">{{ $initials }}</div>
        <div class="att-info">
            <div class="att-name">{{ $emp->first_name ?? '' }} {{ $emp->last_name ?? '' }}</div>
            <div class="att-meta">
                {{ \Carbon\Carbon::parse($att->date)->format('D, d M Y') }}
                @if($att->check_in) &middot; In: <strong>{{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }}</strong> @endif
                @if($att->check_out) &middot; Out: <strong>{{ \Carbon\Carbon::parse($att->check_out)->format('H:i') }}</strong> @endif
                @if($att->working_minutes) &middot; {{ floor($att->working_minutes/60) }}h {{ $att->working_minutes%60 }}m @endif
            </div>
        </div>
        @if($att->overtime_minutes > 0)
            <span class="time-pill"><i class="bi bi-plus-circle me-1"></i>OT {{ floor($att->overtime_minutes/60) }}h {{ $att->overtime_minutes%60 }}m</span>
        @endif
        @if($att->late_minutes > 0)
            <span class="time-pill" style="background:#fef3c7;color:#b45309"><i class="bi bi-clock me-1"></i>Late {{ $att->late_minutes }}m</span>
        @endif
        <span class="status-badge {{ $statusClass }}">{{ $att->status }}</span>
        <span class="source-icon" title="{{ $att->source }}">
            <i class="bi bi-{{ $att->source === 'Biometric' ? 'fingerprint' : ($att->source === 'System' ? 'cpu' : 'pencil') }}"></i>
        </span>
        @if($att->is_regularized)
            <span style="font-size:10px;color:#7c3aed;font-weight:600" title="{{ $att->regularization_reason }}"><i class="bi bi-pencil-square"></i> R</span>
        @endif
        <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px"
                data-bs-toggle="modal" data-bs-target="#regularizeModal"
                data-id="{{ $att->id }}"
                data-status="{{ $att->status }}"
                data-check_in="{{ $att->check_in }}"
                data-check_out="{{ $att->check_out }}">
            <i class="bi bi-pencil"></i>
        </button>
    </div>
    @empty
        <div class="empty-state"><i class="bi bi-clock-history"></i>No attendance records found.</div>
    @endforelse
</div>

<div class="mt-3 d-flex justify-content-center">{{ $attendances->links() }}</div>


{{-- ══ MARK ATTENDANCE MODAL ══ --}}
<div class="modal fade" id="markModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e2e8f0">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.5rem">
                <h6 class="modal-title fw-semibold" style="color:#1e293b"><i class="bi bi-plus-circle me-2" style="color:#1d4ed8"></i>Mark Attendance</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('hr.attendance.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:1.5rem">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select employee…</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ today()->toDateString() }}" max="{{ today()->toDateString() }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                @foreach(['Present','Absent','Late','Half Day','On Leave','Holiday','Weekend','Work From Home'] as $s)
                                    <option>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Check In</label>
                            <input type="time" name="check_in" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Check Out</label>
                            <input type="time" name="check_out" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Notes</label>
                            <input type="text" name="notes" class="form-control" placeholder="Optional note…">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ REGULARIZE MODAL ══ --}}
<div class="modal fade" id="regularizeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e2e8f0">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.5rem">
                <h6 class="modal-title fw-semibold" style="color:#1e293b"><i class="bi bi-pencil-square me-2" style="color:#7c3aed"></i>Regularize Attendance</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="regularizeForm" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body" style="padding:1.5rem">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Status <span class="text-danger">*</span></label>
                            <select id="reg_status" name="status" class="form-select" required>
                                @foreach(['Present','Absent','Late','Half Day','On Leave','Holiday','Weekend','Work From Home'] as $s)
                                    <option>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Check In</label>
                            <input type="time" id="reg_check_in" name="check_in" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Check Out</label>
                            <input type="time" id="reg_check_out" name="check_out" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Reason <span class="text-danger">*</span></label>
                            <textarea name="regularization_reason" class="form-control" rows="2" placeholder="Reason for regularization…" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm text-white"><i class="bi bi-check-lg me-1"></i>Regularize</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('regularizeModal').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    const f = document.getElementById('regularizeForm');
    f.action = `{{ url('hr/attendance') }}/${b.dataset.id}/regularize`;
    document.getElementById('reg_status').value     = b.dataset.status    || '';
    document.getElementById('reg_check_in').value   = b.dataset.check_in  || '';
    document.getElementById('reg_check_out').value  = b.dataset.check_out || '';
});
</script>
@endpush

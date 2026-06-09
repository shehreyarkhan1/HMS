@extends('layouts.master')
@section('title', 'Salary Management')
@section('page-title', 'Salary Management')
@section('breadcrumb', 'HR / Salary')

@push('styles')
<style>
    .stat-card{background:#fff;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;height:100%;transition:box-shadow .15s}
    .stat-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.07)}
    .stat-label{font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem}
    .stat-value{font-size:30px;font-weight:700;color:#1e293b;line-height:1;margin-bottom:.3rem}
    .stat-sub{font-size:12px;color:#94a3b8}
    .stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
    .section-header{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .section-title{font-size:14px;font-weight:600;color:#1e293b}
    .filter-bar{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:1rem 1.25rem;margin-bottom:1rem}
    table th{font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;background:#f8fafc;border-bottom:1px solid #e2e8f0!important;padding:.75rem 1rem!important}
    table td{font-size:13px;color:#1e293b;padding:.75rem 1rem!important;vertical-align:middle!important;border-bottom:1px solid #f8fafc!important}
    table tbody tr:hover{background:#f8fafc}
    .emp-cell{display:flex;align-items:center;gap:10px}
    .emp-av{width:34px;height:34px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .no-struct{font-size:11px;color:#ef4444;font-weight:600}
    .sal-pill{font-size:12px;font-weight:700;color:#1e293b}
    .empty-state{padding:3rem;text-align:center;color:#94a3b8;font-size:13px}
    .empty-state i{font-size:32px;display:block;margin-bottom:.75rem;color:#cbd5e1}
    .btn-icon{width:28px;height:28px;border-radius:7px;border:1px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;font-size:12px;color:#64748b;text-decoration:none;transition:all .15s;background:#fff}
    .btn-icon:hover{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Salary Management</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">Employee salary structures and compensation overview</p>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Total Payroll</div><div class="stat-value">{{ number_format($stats['total_payroll']/1000, 0) }}K</div><div class="stat-sub">PKR per month</div></div>
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Avg Salary</div><div class="stat-value">{{ number_format($stats['avg_salary']/1000, 0) }}K</div><div class="stat-sub">PKR gross</div></div>
                <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-graph-up"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Configured</div><div class="stat-value">{{ $stats['with_structure'] }}</div><div class="stat-sub">have salary structure</div></div>
                <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">No Structure</div><div class="stat-value">{{ $stats['without_structure'] }}</div><div class="stat-sub">need configuration</div></div>
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-exclamation-circle"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">SEARCH</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / Employee ID…" value="{{ request('search') }}">
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">DEPARTMENT</label>
            <select name="department" class="form-select form-select-sm">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
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
        <span class="section-title"><i class="bi bi-people me-2" style="color:#1d4ed8"></i>Employees</span>
        <span style="font-size:12px;color:#94a3b8">{{ $employees->total() }} employees</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th class="text-end">Basic</th>
                    <th class="text-end">Gross</th>
                    <th class="text-end">Deductions</th>
                    <th class="text-end">Net</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php $s = $emp->salaryStructure; @endphp
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-av">{{ strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $emp->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b">{{ $emp->department }}</td>
                    <td class="text-end">
                        @if($s) <span class="sal-pill">{{ number_format($s->basic_salary) }}</span>
                        @else <span class="no-struct">—</span> @endif
                    </td>
                    <td class="text-end">
                        @if($s) <span class="sal-pill">{{ number_format($s->gross_salary) }}</span>
                        @else <span class="no-struct">—</span> @endif
                    </td>
                    <td class="text-end" style="color:#dc2626">
                        @if($s) {{ number_format($s->total_deductions) }} @else — @endif
                    </td>
                    <td class="text-end" style="color:#16a34a;font-weight:700">
                        @if($s) {{ number_format($s->net_salary) }} @else — @endif
                    </td>
                    <td class="text-center">
                        @if($s)
                            <span style="font-size:10px;font-weight:600;padding:3px 10px;border-radius:20px;background:#dcfce7;color:#16a34a">Configured</span>
                        @else
                            <span style="font-size:10px;font-weight:600;padding:3px 10px;border-radius:20px;background:#fee2e2;color:#dc2626">Not Set</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('hr.salary.show', $emp) }}" class="btn-icon" title="History"><i class="bi bi-clock-history"></i></a>
                            @if($s)
                                <a href="{{ route('hr.salary.edit', [$emp, $s]) }}" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            @else
                                <a href="{{ route('hr.salary.create', $emp) }}" class="btn-icon" style="color:#16a34a;border-color:#bbf7d0" title="Create"><i class="bi bi-plus-lg"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="bi bi-people"></i>No employees found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">{{ $employees->links() }}</div>
@endsection

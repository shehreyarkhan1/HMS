@extends('layouts.master')

@section('title', 'Employees')
@section('page-title', 'Employees')
@section('breadcrumb', 'Home / Employees')

@push('styles')
<style>
    .stat-pill{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px}
    .stat-pill-label{font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em}
    .stat-pill-value{font-size:22px;font-weight:700;color:#1e293b}

    .filter-bar input,.filter-bar select{
        height:36px;border:1px solid #e2e8f0;border-radius:8px;
        font-size:13px;padding:0 12px;color:#374151;background:#f8fafc
    }
    .filter-bar input:focus,.filter-bar select:focus{outline:none;border-color:#93c5fd;background:#fff}

    .card-section{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden}
    .card-section-header{padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}

    .table-clean th{font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0 !important;padding:10px 16px !important;background:#f8fafc}
    .table-clean td{font-size:13px;color:#374151;padding:12px 16px !important;vertical-align:middle;border-bottom:1px solid #f1f5f9 !important}
    .table-clean tr:last-child td{border-bottom:none !important}
    .table-clean tr:hover td{background:#fafafa}

    .emp-avatar{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;background:#dbeafe;color:#1d4ed8}
    .emp-avatar img{width:100%;height:100%;border-radius:50%;object-fit:cover}

    /* Status badges */
    .badge-Active     {background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .badge-On-Leave   {background:#fef9c3;color:#854d0e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .badge-Suspended  {background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .badge-Terminated {background:#f3f4f6;color:#374151;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .badge-Resigned   {background:#f3f4f6;color:#374151;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
    .badge-Retired    {background:#f3f4f6;color:#374151;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}

    /* Type badges */
    .type-Permanent    {background:#eff6ff;color:#1e40af;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500}
    .type-Contractual  {background:#fff7ed;color:#c2410c;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500}
    .type-Probationary {background:#fdf4ff;color:#7e22ce;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500}
    .type-Part-Time    {background:#f0fdf4;color:#15803d;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500}
    .type-Intern       {background:#ecfeff;color:#0e7490;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500}
    .type-Daily-Wage   {background:#fafaf9;color:#44403c;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500}
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Total employees</div>
            <div class="stat-pill-value">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Active</div>
            <div class="stat-pill-value" style="color:#16a34a">{{ number_format($stats['active']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">On leave</div>
            <div class="stat-pill-value" style="color:#ca8a04">{{ number_format($stats['on_leave']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Inactive</div>
            <div class="stat-pill-value" style="color:#94a3b8">{{ number_format($stats['inactive']) }}</div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card-section">
    <div class="card-section-header">
        <span style="font-size:14px;font-weight:600;color:#1e293b">
            <i class="bi bi-people me-2 text-primary"></i>All employees
        </span>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" action="{{ route('employees.index') }}" class="d-flex gap-2 flex-wrap filter-bar">

                <input type="text" name="search"
                       placeholder="Name, ID, CNIC, designation..."
                       value="{{ request('search') }}" style="width:220px">

                <select name="department">
                    <option value="">All departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected':'' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>

                <select name="status">
                    <option value="">All status</option>
                    @foreach(['Active','On Leave','Suspended','Terminated','Resigned','Retired'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>

                <select name="type">
                    <option value="">All types</option>
                    @foreach(['Permanent','Contractual','Probationary','Part-Time','Intern','Daily-Wage'] as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                    <i class="bi bi-search me-1"></i>Filter
                </button>

                @if(request()->hasAny(['search','department','status','type','shift']))
                    <a href="{{ route('employees.index') }}"
                       class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">
                        Clear
                    </a>
                @endif
            </form>

            <a href="{{ route('employees.create') }}"
               class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>Add employee
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Type</th>
                    <th>Shift</th>
                    <th>Joining date</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td>
                        <code style="font-size:12px;color:#6366f1">{{ $emp->employee_id }}</code>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="emp-avatar">
                                @if($emp->photo_url)
                                    <img src="{{ $emp->photo_url }}" alt="{{ $emp->full_name }}">
                                @else
                                    {{ $emp->initials }}
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:500;color:#1e293b">{{ $emp->full_name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $emp->personal_phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b">{{ $emp->department }}</td>
                    <td style="color:#374151">{{ $emp->designation }}</td>
                    <td>
                        @php $typeKey = str_replace(' ', '-', $emp->employment_type); @endphp
                        <span class="type-{{ $typeKey }}">{{ $emp->employment_type }}</span>
                    </td>
                    <td>
                        <span style="font-size:11px;padding:2px 8px;border-radius:6px;background:#f1f5f9;color:#475569;font-weight:500">
                            {{ $emp->shift }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:#64748b">
                        {{ $emp->joining_date->format('d M Y') }}
                    </td>
                    <td style="font-weight:500;color:#1e293b">
                        Rs {{ number_format($emp->basic_salary, 0) }}
                        <span style="font-size:10px;color:#94a3b8">/{{ strtolower($emp->salary_type) }}</span>
                    </td>
                    <td>
                        @php $statusKey = str_replace(' ', '-', $emp->employment_status); @endphp
                        <span class="badge-{{ $statusKey }}">{{ $emp->employment_status }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('employees.show', $emp->id) }}"
                               class="btn btn-sm btn-outline-primary py-0 px-2"
                               style="font-size:12px" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $emp->id) }}"
                               class="btn btn-sm btn-outline-warning py-0 px-2"
                               style="font-size:12px" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('employees.destroy', $emp->id) }}"
                                  onsubmit="return confirm('Remove {{ $emp->full_name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"
                                        style="font-size:12px" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5" style="color:#94a3b8">
                        <i class="bi bi-people" style="font-size:40px;display:block;margin-bottom:8px"></i>
                        No employees found.
                        <a href="{{ route('employees.create') }}" class="text-primary ms-1">Add first employee</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->hasPages())
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="border-top:1px solid #e2e8f0">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }}
            of {{ $employees->total() }} employees
        </span>
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
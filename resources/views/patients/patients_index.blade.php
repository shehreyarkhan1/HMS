@extends('layouts.master')

@section('title', 'Patients')
@section('page-title', 'Patients')
@section('breadcrumb', 'Home / Patients')

@push('styles')
<style>
    .stat-pill {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 20px;
    }
    .stat-pill-label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; }
    .stat-pill-value { font-size: 22px; font-weight: 700; color: #1e293b; }

    .card-section { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .card-section-header { padding: 14px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }

    .filter-bar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .filter-bar input, .filter-bar select {
        height: 36px; border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 13px; padding: 0 12px; color: #374151; background: #f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus {
        outline: none; border-color: #93c5fd; background: #fff;
    }

    .table-clean th {
        font-size: 11px; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .05em;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 10px 16px !important; background: #f8fafc;
    }
    .table-clean td {
        font-size: 13px; color: #374151;
        padding: 12px 16px !important; vertical-align: middle;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .table-clean tr:last-child td { border-bottom: none !important; }
    .table-clean tr:hover td { background: #fafafa; }

    .badge-status { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; white-space: nowrap; }
    .badge-Active     { background: #dcfce7; color: #166534; }
    .badge-Admitted   { background: #dbeafe; color: #1e40af; }
    .badge-Discharged { background: #f3f4f6; color: #374151; }
    .badge-Deceased   { background: #fee2e2; color: #991b1b; }

    .type-pill { font-size: 11px; padding: 2px 8px; border-radius: 6px; font-weight: 500; }
    .type-OPD       { background: #eff6ff; color: #1d4ed8; }
    .type-IPD       { background: #f0fdf4; color: #15803d; }
    .type-Emergency { background: #fff7ed; color: #c2410c; }

    .patient-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 600; flex-shrink: 0;
        background: #dbeafe; color: #1d4ed8;
    }
</style>
@endpush

@section('content')

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Total patients</div>
                <div class="stat-pill-value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">OPD</div>
                <div class="stat-pill-value" style="color:#1d4ed8">{{ number_format($stats['opd']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">IPD</div>
                <div class="stat-pill-value" style="color:#15803d">{{ number_format($stats['ipd']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Emergency</div>
                <div class="stat-pill-value" style="color:#c2410c">{{ number_format($stats['emergency']) }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-people me-2 text-primary"></i>All patients
            </span>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Search & Filters --}}
                <form method="GET" action="{{ route('patients.index') }}" class="filter-bar">
                    <input type="text" name="search" placeholder="Search name, MRN, CNIC..."
                           value="{{ request('search') }}" style="width:220px">

                    <select name="type">
                        <option value="">All types</option>
                        <option value="OPD"       {{ request('type') == 'OPD' ? 'selected' : '' }}>OPD</option>
                        <option value="IPD"       {{ request('type') == 'IPD' ? 'selected' : '' }}>IPD</option>
                        <option value="Emergency" {{ request('type') == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>

                    <select name="status">
                        <option value="">All status</option>
                        <option value="Active"     {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Admitted"   {{ request('status') == 'Admitted' ? 'selected' : '' }}>Admitted</option>
                        <option value="Discharged" {{ request('status') == 'Discharged' ? 'selected' : '' }}>Discharged</option>
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>

                    @if(request()->hasAny(['search','type','status']))
                        <a href="{{ route('patients.index') }}"
                           class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">
                            Clear
                        </a>
                    @endif
                </form>

                {{-- Add New --}}
                <a href="{{ route('patients.create') }}"
                   class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New patient
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>MRN</th>
                        <th>Patient</th>
                        <th>Age / Gender</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>
                            <code style="font-size:12px;color:#6366f1">{{ $patient->mrn }}</code>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar">{{ $patient->initials }}</div>
                                <div>
                                    <div style="font-weight:500;color:#1e293b">{{ $patient->name }}</div>
                                    <div style="font-size:11px;color:#94a3b8">{{ $patient->city }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $patient->age }} yrs / {{ $patient->gender }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>
                            <span class="type-pill type-{{ $patient->patient_type }}">
                                {{ $patient->patient_type }}
                            </span>
                        </td>
                        <td>{{ $patient->doctor->name ?? '—' }}</td>
                        <td>
                            <span class="badge-status badge-{{ $patient->status }}">
                                {{ $patient->status }}
                            </span>
                        </td>
                        <td style="color:#94a3b8;font-size:12px">
                            {{ $patient->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('patients.show', $patient->id) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient->id) }}"
                                   class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('patients.destroy', $patient->id) }}"
                                      onsubmit="return confirm('Delete this patient?')">
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
                        <td colspan="9" class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-people" style="font-size:40px;display:block;margin-bottom:8px"></i>
                            No patients found.
                            <a href="{{ route('patients.create') }}" class="text-primary ms-1">Add first patient</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($patients->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top:1px solid #e2e8f0">
            <span style="font-size:12px;color:#94a3b8">
                Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }}
                of {{ $patients->total() }} patients
            </span>
            {{ $patients->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

@endsection
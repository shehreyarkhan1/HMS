@extends('layouts.master')

@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('breadcrumb', 'Home / Appointments')

@push('styles')
    <style>
        .stat-pill {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 20px;
        }

        .stat-pill-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .stat-pill-value {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }

        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-bar input,
        .filter-bar select {
            height: 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            padding: 0 12px;
            color: #374151;
            background: #f8fafc;
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff;
        }

        .table-clean th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 16px !important;
            background: #f8fafc;
        }

        .table-clean td {
            font-size: 13px;
            color: #374151;
            padding: 12px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .table-clean tr:last-child td {
            border-bottom: none !important;
        }

        .table-clean tr:hover td {
            background: #fafafa;
        }

        /* Status badges */
        .badge-status {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-Scheduled {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-Confirmed {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-In-Progress {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-Completed {
            background: #dcfce7;
            color: #166534;
        }

        .badge-Cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-No-show {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Type pills */
        .type-pill {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        .type-OPD {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .type-IPD {
            background: #f0fdf4;
            color: #15803d;
        }

        .type-Emergency {
            background: #fff7ed;
            color: #c2410c;
        }

        .type-Follow-up {
            background: #faf5ff;
            color: #7e22ce;
        }

        /* Token */
        .token-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f0fdf4;
            color: #15803d;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #bbf7d0;
        }

        /* Quick status dropdown */
        .status-select {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
        }

        /* Today highlight */
        .row-today td {
            background: #fffbeb !important;
        }
    </style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Today's Total</div>
                <div class="stat-pill-value">{{ number_format($stats['today']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Scheduled</div>
                <div class="stat-pill-value" style="color:#1d4ed8">{{ number_format($stats['scheduled']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Completed</div>
                <div class="stat-pill-value" style="color:#15803d">{{ number_format($stats['completed']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Cancelled</div>
                <div class="stat-pill-value" style="color:#991b1b">{{ number_format($stats['cancelled']) }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-calendar2-week me-2 text-primary"></i>Appointments
            </span>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('appointments.index') }}" class="filter-bar">
                    <input type="text" name="search" placeholder="Patient name / MRN..." value="{{ request('search') }}"
                        style="width:200px">

                    <input type="date" name="date" value="{{ request('date') }}" style="width:145px">

                    <select name="doctor_id" style="width:200px">
                        <option value="">All doctors</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="type">
                        <option value="">All types</option>
                        @foreach(['OPD', 'IPD', 'Follow-up', 'Emergency'] as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>

                    <select name="status">
                        <option value="">All status</option>
                        @foreach(['Scheduled', 'Confirmed', 'In-Progress', 'Completed', 'Cancelled', 'No-show'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'date', 'doctor_id', 'type', 'status']))
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>

                <a href="{{ route('appointments.calendar') }}" class="btn btn-sm btn-outline-primary px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-calendar3 me-1"></i>Calendar
                </a>
                <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-success px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New appointment
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Token</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appt)
                        <tr class="{{ $appt->is_today ? 'row-today' : '' }}">
                            <td>
                                @if($appt->token_number)
                                    <div class="token-badge">#{{ $appt->token_number }}</div>
                                @else
                                    <span style="color:#cbd5e1">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('patients.show', $appt->patient_id) }}"
                                    style="font-weight:500;color:#1e293b;text-decoration:none">
                                    {{ $appt->patient->name ?? '—' }}
                                </a>
                                <div style="font-size:11px;color:#94a3b8">
                                    {{ $appt->patient->mrn ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:500">{{ $appt->doctor?->name ?? '—' }}</div>
                                @if($appt->doctor?->specialization)
                                    <div style="font-size:11px;color:#94a3b8">
                                        {{ $appt->doctor->specialization }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">
                                    {{ $appt->appointment_date->format('d M Y') }}
                                </div>
                                <div style="font-size:11px;color:#94a3b8">
                                    {{ $appt->formatted_time }}
                                    @if($appt->duration_minutes)
                                        · {{ $appt->duration_minutes }}min
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="type-pill type-{{ $appt->type }}">{{ $appt->type }}</span>
                            </td>
                            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $appt->reason ?? '—' }}
                            </td>
                            <td>
                                {{-- Quick status update --}}
                                <form method="POST" action="{{ route('appointments.status', $appt->id) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" class="status-select badge-status badge-{{ $appt->status }}"
                                        onchange="this.form.submit()">
                                        @foreach(['Scheduled', 'Confirmed', 'In-Progress', 'Completed', 'Cancelled', 'No-show'] as $s)
                                            <option value="{{ $s }}" {{ $appt->status === $s ? 'selected' : '' }}>
                                                {{ $s }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('appointments.show', $appt->id) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appt->id) }}"
                                        class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('appointments.destroy', $appt->id) }}"
                                        onsubmit="return confirm('Delete this appointment?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px"
                                            title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:8px"></i>
                                No appointments found.
                                <a href="{{ route('appointments.create') }}" class="text-primary ms-1">Book first
                                    appointment</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($appointments->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }}
                    of {{ $appointments->total() }} appointments
                </span>
                {{ $appointments->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
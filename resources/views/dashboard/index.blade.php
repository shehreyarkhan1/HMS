@extends('layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')

@push('styles')
    <style>
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: box-shadow 0.2s;
            height: 100%;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .stat-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .stat-card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-card-title-group {
            display: flex;
            flex-direction: column;
        }

        .stat-card-sub {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.3;
        }

        .stat-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.3;
        }

        .stat-live-badge {
            font-size: 10px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .stat-numbers-box {
            display: flex;
            align-items: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 0;
            margin-bottom: 14px;
        }

        .stat-num-col {
            flex: 1;
            text-align: center;
            padding: 0 8px;
        }

        .stat-num-col+.stat-num-col {
            border-left: 1px solid #e5e7eb;
        }

        .stat-num-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .stat-num-value {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.1;
        }

        .stat-num-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .stat-divider-v {
            width: 1px;
            background: #e5e7eb;
            align-self: stretch;
            margin: 0 4px;
            flex-shrink: 0;
        }

        .stat-bar-row {
            margin-top: auto;
        }

        .stat-bar-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .stat-bar-track {
            height: 5px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .stat-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.8s ease;
        }

        .stat-bar-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* ── Bottom sections ── */
        .card-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-section-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .table-clean th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 16px !important;
            background: #f8fafc;
        }

        .table-clean td {
            font-size: 13px;
            color: #374151;
            padding: 11px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .table-clean tr:last-child td {
            border-bottom: none !important;
        }

        .table-clean tr:hover td {
            background: #f8fafc;
        }

        .badge-status {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-Active {
            background: #dcfce7;
            color: #166534;
        }

        .badge-Admitted {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-Discharged {
            background: #f3f4f6;
            color: #374151;
        }

        .badge-Deceased {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-Scheduled {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-Confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .badge-In-Progress {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-Completed {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-Cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-No-show {
            background: #f3f4f6;
            color: #6b7280;
        }

        .bar-track {
            height: 6px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 1s ease;
        }

        .bar-low {
            background: #16a34a !important;
        }

        .bar-medium {
            background: #d97706 !important;
        }

        .bar-high {
            background: #dc2626 !important;
        }

        .appt-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')

    {{-- ===== STATS ROW ===== --}}
    <div class="row g-3 mb-4">

        {{-- ── 1. Total Patients ── --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#eff6ff">
                            <i class="bi bi-people" style="color:#1d4ed8"></i>
                        </div>
                        <div class="stat-card-title-group">
                            <span class="stat-card-sub">Hospital</span>
                            <span class="stat-card-title">Patients</span>
                        </div>
                    </div>
                    @php $pg = $patientGrowth; @endphp
                    <span class="stat-live-badge"
                        style="background:{{ $pg >= 0 ? '#dcfce7' : '#fee2e2' }};
                           color:{{ $pg >= 0 ? '#166534' : '#991b1b' }};
                           border:1px solid {{ $pg >= 0 ? '#bbf7d0' : '#fecaca' }}">
                        {{ $pg >= 0 ? '+' : '' }}{{ $pg }}%
                    </span>
                </div>

                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total</div>
                        <div class="stat-num-value" style="color:#1d4ed8">
                            {{ number_format($patient) }}
                        </div>
                        <div class="stat-num-hint">all time</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">This month</div>
                        <div class="stat-num-value" style="color:#1e293b">
                            {{ \App\Models\Patient::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}
                        </div>
                        <div class="stat-num-hint">registered</div>
                    </div>
                </div>

                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Monthly growth</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ abs($pg) }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill"
                            style="width:{{ min(abs($pg), 100) }}%;
                                background:{{ $pg >= 0 ? '#1d4ed8' : '#dc2626' }}">
                        </div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#6b7280">
                            <span style="color:{{ $pg >= 0 ? '#16a34a' : '#dc2626' }};font-weight:500">
                                <i class="bi bi-arrow-{{ $pg >= 0 ? 'up' : 'down' }}-short"></i>
                                {{ $pg >= 0 ? 'Growing' : 'Declining' }}
                            </span> vs last month
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2. Today's Appointments ── --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#f0fdf4">
                            <i class="bi bi-calendar-check" style="color:#16a34a"></i>
                        </div>
                        <div class="stat-card-title-group">
                            <span class="stat-card-sub">Schedule</span>
                            <span class="stat-card-title">Appointments</span>
                        </div>
                    </div>
                    @php $ac = $appointmentChange; @endphp
                    <span class="stat-live-badge" style="background:#f0fdf4;color:#0f766e;border:1px solid #99f6e4">
                        Live
                    </span>
                </div>

                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Today</div>
                        <div class="stat-num-value" style="color:#16a34a">
                            {{ number_format($appointment) }}
                        </div>
                        <div class="stat-num-hint">{{ now()->format('d M') }}</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Yesterday</div>
                        <div class="stat-num-value" style="color:#1e293b">
                            {{ $appointment - $ac }}
                        </div>
                        <div class="stat-num-hint">{{ now()->subDay()->format('d M') }}</div>
                    </div>
                </div>

                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">vs yesterday</span>
                        <span
                            style="font-size:11px;font-weight:600;
                                 color:{{ $ac >= 0 ? '#16a34a' : '#dc2626' }}">
                            {{ $ac >= 0 ? '+' : '' }}{{ $ac }}
                        </span>
                    </div>
                    <div class="stat-bar-track">
                        @php
                            $apptMax = max($appointment, $appointment - $ac, 1);
                            $apptPct = round(($appointment / $apptMax) * 100);
                        @endphp
                        <div class="stat-bar-fill" style="width:{{ $apptPct }}%;background:#16a34a">
                        </div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#6b7280">
                            @if ($ac == 0)
                                Same as yesterday
                            @elseif($ac > 0)
                                <span style="color:#16a34a;font-weight:500">
                                    <i class="bi bi-arrow-up-short"></i>{{ $ac }} more
                                </span> than yesterday
                            @else
                                <span style="color:#dc2626;font-weight:500">
                                    <i class="bi bi-arrow-down-short"></i>{{ abs($ac) }} fewer
                                </span> than yesterday
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 3. Beds & Wards ── --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#f0fdfa">
                            <i class="bi bi-hospital" style="color:#0f766e"></i>
                        </div>
                        <div class="stat-card-title-group">
                            <span class="stat-card-sub">Bed management</span>
                            <span class="stat-card-title">Wards & beds</span>
                        </div>
                    </div>
                    <span class="stat-live-badge" style="background:#f0fdfa;color:#0f766e;border:1px solid #99f6e4">
                        Live
                    </span>
                </div>

                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Available beds</div>
                        <div class="stat-num-value" style="color:#0f766e">{{ $availableBeds }}</div>
                        <div class="stat-num-hint">of {{ $totalBeds }} total</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Active wards</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $totalWards }}</div>
                        <div class="stat-num-hint">all operational</div>
                    </div>
                </div>

                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Occupancy rate</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $occupancyRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill"
                            style="width:{{ $occupancyRate }}%;
                                background:{{ $occupancyRate >= 80 ? '#dc2626' : ($occupancyRate >= 50 ? '#d97706' : '#0f766e') }}">
                        </div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#6b7280">
                            <span style="color:#e24b4a;font-weight:500">{{ $occupiedBeds }}</span> occupied
                        </span>
                        <span style="font-size:11px;color:#6b7280">
                            {{ $totalBeds - $availableBeds - $occupiedBeds }} reserved/maint.
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 4. Revenue ── --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#fffbeb">
                            <i class="bi bi-cash-stack" style="color:#d97706"></i>
                        </div>
                        <div class="stat-card-title-group">
                            <span class="stat-card-sub">Billing</span>
                            <span class="stat-card-title">Revenue</span>
                        </div>
                    </div>
                    <span class="stat-live-badge" style="background:#fffbeb;color:#92400e;border:1px solid #fde68a">
                        PKR
                    </span>
                </div>

                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Today</div>
                        <div class="stat-num-value" style="color:#d97706;font-size:18px">
                            {{ number_format($todayRevenue) }}
                        </div>
                        <div class="stat-num-hint">{{ now()->format('d M') }}</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">{{ now()->format('M') }}</div>
                        <div class="stat-num-value" style="color:#1e293b;font-size:18px">
                            {{ number_format($monthRevenue) }}
                        </div>
                        <div class="stat-num-hint">this month</div>
                    </div>
                </div>

                <div class="stat-bar-row">
                    @php
                        $revPct = $totalRevenue > 0 ? round(($monthRevenue / $totalRevenue) * 100) : 0;
                    @endphp
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">This month / total</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $revPct }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $revPct }}%;background:#d97706"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#6b7280">
                            Total&nbsp;<span style="font-weight:600;color:#1e293b">
                                Rs {{ number_format($totalRevenue) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- END STATS ROW --}}


    {{-- ===== MIDDLE ROW ===== --}}
    <div class="row g-3 mb-4">

        {{-- Today's Appointments List --}}
        <div class="col-12 col-lg-6">
            <div class="card-section h-100">
                <div class="card-section-header">
                    <span class="card-section-title">
                        <i class="bi bi-calendar2-week me-2 text-primary"></i>Today's appointments
                    </span>
                    @if (Route::has('appointments.index'))
                        <a href="{{ route('appointments.index') }}" class="text-primary text-decoration-none"
                            style="font-size:12px">
                            View all
                        </a>
                    @endif
                </div>

                <div class="p-0">
                    @forelse($recentAppointments as $appt)
                        @php
                            $avatarColors = [
                                'OPD' => ['bg' => '#dbeafe', 'color' => '#1d4ed8'],
                                'IPD' => ['bg' => '#dcfce7', 'color' => '#166534'],
                                'Emergency' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                'Follow-up' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                            ];
                            $c = $avatarColors[$appt->type] ?? ['bg' => '#f1f5f9', 'color' => '#475569'];
                            $badgeClass = 'badge-' . str_replace(' ', '-', $appt->status);
                        @endphp
                        <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #f1f5f9">
                            <span style="font-size:12px;color:#94a3b8;min-width:52px;flex-shrink:0">
                                {{ $appt->appointment_time ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') : '—' }}
                            </span>
                            <div class="appt-avatar" style="background:{{ $c['bg'] }};color:{{ $c['color'] }}">
                                {{ $appt->patient->initials ?? '??' }}
                            </div>
                            <div class="flex-grow-1" style="min-width:0">
                                <div
                                    style="font-size:13px;font-weight:500;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $appt->patient->name ?? '—' }}
                                </div>
                                <div
                                    style="font-size:12px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $appt->type }}
                                    @if ($appt->doctor)
                                        &nbsp;·&nbsp;{{ $appt->doctor->name }}
                                    @endif
                                </div>
                            </div>
                            <span class="badge-status {{ $badgeClass }}" style="flex-shrink:0">
                                {{ $appt->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-5" style="color:#94a3b8;font-size:13px">
                            <i class="bi bi-calendar-x"
                                style="font-size:32px;display:block;margin-bottom:8px;opacity:.5"></i>
                            No appointments today
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Department Occupancy --}}
        <div class="col-12 col-lg-6">
            <div class="card-section h-100">
                <div class="card-section-header">
                    <span class="card-section-title">
                        <i class="bi bi-building me-2" style="color:#0f766e"></i>Department occupancy
                    </span>
                    {{-- <div class="d-flex gap-3 mt-4 pt-3" style="border-top:1px solid #f1f5f9"> --}}
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px">
                                <span
                                    style="width:10px;height:10px;border-radius:50%;background:#16a34a;display:inline-block"></span>Low
                                (&lt;50%)
                            </span>
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px">
                                <span
                                    style="width:10px;height:10px;border-radius:50%;background:#d97706;display:inline-block"></span>Medium
                                (50–79%)
                            </span>
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px">
                                <span
                                    style="width:10px;height:10px;border-radius:50%;background:#dc2626;display:inline-block"></span>High
                                (80%+)
                            </span>
                        {{-- </div> --}}
                    <span style="font-size:11px;color:#94a3b8">Today's load</span>
                </div>
                <div class="p-4">
                    @if (count($departmentOccupancy) > 0)
                        @foreach ($departmentOccupancy as $dept)
                            @php
                                $barClass =
                                    $dept['percent'] >= 80
                                        ? 'bar-high'
                                        : ($dept['percent'] >= 50
                                            ? 'bar-medium'
                                            : 'bar-low');
                            @endphp
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="min-width:90px;font-size:13px;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                    title="{{ $dept['name'] }}">
                                    {{ $dept['name'] }}
                                </div>
                                <div class="bar-track flex-grow-1">
                                    <div class="bar-fill {{ $barClass }}" style="width:{{ $dept['percent'] }}%">
                                    </div>
                                </div>
                                <div style="min-width:60px;text-align:right;font-size:12px;color:#64748b">
                                    <span style="font-weight:600;color:#1e293b">{{ $dept['percent'] }}%</span>
                                    <span style="font-size:11px;color:#94a3b8">({{ $dept['count'] }})</span>
                                </div>
                            </div>
                        @endforeach

                    @else
                        <div class="text-center py-4" style="color:#94a3b8;font-size:13px">
                            <i class="bi bi-building"
                                style="font-size:28px;display:block;margin-bottom:6px;opacity:.5"></i>
                            No department data available
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    {{-- END MIDDLE ROW --}}


    {{-- ===== RECENT PATIENTS TABLE ===== --}}
    <div class="card-section">
        <div class="card-section-header">
            <span class="card-section-title">
                <i class="bi bi-people me-2 text-primary"></i>Recent patients
            </span>
            @if (Route::has('patients.index'))
                <a href="{{ route('patients.index') }}" class="text-primary text-decoration-none"
                    style="font-size:12px">
                    View all
                </a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>MRN</th>
                        <th>Patient name</th>
                        <th>Age / Gender</th>
                        <th>Type</th>
                        <th>Doctor</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPatients as $pat)
                        <tr>
                            <td><code style="font-size:12px;color:#6366f1">{{ $pat->mrn }}</code></td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $pat->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $pat->city }}</div>
                            </td>
                            <td>{{ $pat->age }} / {{ $pat->gender }}</td>
                            <td>{{ $pat->patient_type }}</td>
                            <td>{{ $pat->doctor->name ?? '—' }}</td>
                            <td style="color:#94a3b8;font-size:12px">{{ $pat->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge-status badge-{{ $pat->status }}">{{ $pat->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('patients.show', $pat->id) }}"
                                    class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4" style="color:#94a3b8;font-size:13px">
                                <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:6px"></i>
                                No patients found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- END RECENT PATIENTS --}}

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.bar-fill, .stat-bar-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = w;
                }, 200);
            });
        });
    </script>
@endpush

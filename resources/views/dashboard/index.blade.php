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
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .stat-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .stat-label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .stat-change {
            font-size: 12px;
            margin-top: 6px;
        }

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

        /* Patient status */
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

        /* Appointment status */
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

        /* Occupancy bar color hints */
        .bar-low {
            background: #16a34a !important;
        }

        .bar-medium {
            background: #d97706 !important;
        }

        .bar-high {
            background: #dc2626 !important;
        }
    </style>
@endpush

@section('content')

    {{-- ===== STATS ROW ===== --}}
    <div class="row g-3 mb-4">

        {{-- Total Patients --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box" style="background:#eff6ff">
                    <i class="bi bi-people" style="color:#1d4ed8"></i>
                </div>
                <div class="stat-label">Total patients</div>
                <div class="stat-value">{{ number_format($patient) }}</div>
                @php $pg = $patientGrowth; @endphp
                <div class="stat-change {{ $pg >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="bi bi-arrow-{{ $pg >= 0 ? 'up' : 'down' }}-short"></i>
                    @if($pg == 0)
                        No change this month
                    @else
                        {{ $pg > 0 ? '+' : '' }}{{ $pg }}% this month
                    @endif
                </div>
            </div>
        </div>

        {{-- Today's Appointments --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box" style="background:#f0fdf4">
                    <i class="bi bi-calendar-check" style="color:#16a34a"></i>
                </div>
                <div class="stat-label">Today's appointments</div>
                <div class="stat-value">{{ number_format($appointment) }}</div>
                @php $ac = $appointmentChange; @endphp
                <div class="stat-change {{ $ac >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="bi bi-arrow-{{ $ac >= 0 ? 'up' : 'down' }}-short"></i>
                    @if($ac == 0)
                        Same as yesterday
                    @else
                        {{ $ac > 0 ? '+' : '' }}{{ $ac }} from yesterday
                    @endif
                </div>
            </div>
        </div>

        {{-- Available Beds --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box" style="background:#f0fdfa">
                    <i class="bi bi-hospital" style="color:#0f766e"></i>
                </div>
                <div class="stat-label">Available beds</div>
                <div class="stat-value">{{ $availableBeds ?? '34' }}</div>
                <div class="stat-change text-danger">
                    <i class="bi bi-arrow-down-short"></i> -6 from last week
                </div>
            </div>
        </div>

        {{-- Today's Revenue --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon-box" style="background:#fffbeb">
                    <i class="bi bi-cash-stack" style="color:#d97706"></i>
                </div>
                <div class="stat-label">Today's revenue</div>
                <div class="stat-value">{{ $todayRevenue ?? 'Rs 84k' }}</div>
                <div class="stat-change text-success">
                    <i class="bi bi-arrow-up-short"></i> +12% vs avg
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
                    @if(Route::has('appointments.index'))
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

                                <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom: 1px solid #f1f5f9">

                                    <span style="font-size:12px;color:#94a3b8;min-width:52px;flex-shrink:0">
                                        {{ $appt->appointment_time
                        ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A')
                        : '—' }}
                                    </span>

                                    <div class="appt-avatar" style="background:{{ $c['bg'] }};color:{{ $c['color'] }}">
                                        {{ $appt->patient->initials ?? '??' }}
                                    </div>

                                    <div class="flex-grow-1" style="min-width:0">
                                        <div style="font-size:13px;font-weight:500;color:#1e293b;
                                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ $appt->patient->name ?? '—' }}
                                        </div>
                                        <div style="font-size:12px;color:#94a3b8;
                                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ $appt->type }}
                                            @if($appt->doctor)
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
                            <i class="bi bi-calendar-x" style="font-size:32px;display:block;margin-bottom:8px;opacity:.5"></i>
                            No appointments today
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Department Occupancy — Real Data --}}
        <div class="col-12 col-lg-6">
            <div class="card-section h-100">
                <div class="card-section-header">
                    <span class="card-section-title">
                        <i class="bi bi-building me-2" style="color:#0f766e"></i>Department occupancy
                    </span>
                    <span style="font-size:11px;color:#94a3b8">Today's load</span>
                </div>
                <div class="p-4">

                    @if(count($departmentOccupancy) > 0)
                        @foreach($departmentOccupancy as $dept)
                            @php
                                $barClass = $dept['percent'] >= 80
                                    ? 'bar-high'
                                    : ($dept['percent'] >= 50 ? 'bar-medium' : 'bar-low');
                            @endphp
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="min-width:90px;font-size:13px;color:#374151;
                                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                    title="{{ $dept['name'] }}">
                                    {{ $dept['name'] }}
                                </div>
                                <div class="bar-track flex-grow-1">
                                    <div class="bar-fill {{ $barClass }}" style="width:{{ $dept['percent'] }}%">
                                    </div>
                                </div>
                                <div style="min-width:60px;text-align:right;font-size:12px;color:#64748b">
                                    <span style="font-weight:600;color:#1e293b">{{ $dept['percent'] }}%</span>
                                    <span style="font-size:11px;color:#94a3b8">
                                        ({{ $dept['count'] }})
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        {{-- Legend --}}
                        <div class="d-flex gap-3 mt-4 pt-3" style="border-top:1px solid #f1f5f9">
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px">
                                <span
                                    style="width:10px;height:10px;border-radius:50%;background:#16a34a;display:inline-block"></span>
                                Low (&lt;50%)
                            </span>
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px">
                                <span
                                    style="width:10px;height:10px;border-radius:50%;background:#d97706;display:inline-block"></span>
                                Medium (50–79%)
                            </span>
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px">
                                <span
                                    style="width:10px;height:10px;border-radius:50%;background:#dc2626;display:inline-block"></span>
                                High (80%+)
                            </span>
                        </div>

                    @else
                        {{-- Koi doctor/department nahi yet --}}
                        <div class="text-center py-4" style="color:#94a3b8;font-size:13px">
                            <i class="bi bi-building" style="font-size:28px;display:block;margin-bottom:6px;opacity:.5"></i>
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
            @if(Route::has('patients.index'))
                <a href="{{ route('patients.index') }}" class="text-primary text-decoration-none" style="font-size:12px">
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
                            <td>
                                <code style="font-size:12px;color:#6366f1">{{ $pat->mrn }}</code>
                            </td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $pat->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $pat->city }}</div>
                            </td>
                            <td>{{ $pat->age }} / {{ $pat->gender }}</td>
                            <td>{{ $pat->patient_type }}</td>
                            <td>{{ $pat->doctor->name ?? '—' }}</td>
                            <td style="color:#94a3b8;font-size:12px">
                                {{ $pat->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $pat->status }}">
                                    {{ $pat->status }}
                                </span>
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
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.bar-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = w; }, 200);
            });
        });
    </script>
@endpush
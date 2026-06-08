@extends('layouts.master')

@section('title', 'My Dashboard')
@section('page-title', 'Doctor Dashboard')
@section('breadcrumb', 'Home / My Dashboard')

@push('styles')
    <style>
        /* Styling bilkul wahi hai jo aapne di thi */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            height: 100%;
            transition: box-shadow 0.15s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 0.4rem;
        }

        .stat-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .growth-up {
            color: #16a34a;
            font-size: 12px;
            font-weight: 600;
        }

        .growth-down {
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
        }

        .section-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .section-link {
            font-size: 12px;
            color: #1d4ed8;
            text-decoration: none;
        }

        .section-link:hover {
            text-decoration: underline;
        }

        .result-row {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
            transition: background 0.1s;
        }

        .result-row:last-child {
            border-bottom: none;
        }

        .result-row:hover {
            background: #f8fafc;
        }

        .patient-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .result-info {
            flex: 1;
            min-width: 0;
        }

        .result-patient {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .result-detail {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px;
        }

        .appt-time {
            font-size: 11px;
            font-weight: 600;
            color: #1d4ed8;
            background: #eff6ff;
            padding: 2px 8px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* Chart bars */
        .chart-wrap {
            padding: 1.25rem;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 130px;
        }

        .bar-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            height: 100%;
            justify-content: flex-end;
        }

        .bar {
            width: 100%;
            background: #1d4ed8;
            border-radius: 4px 4px 0 0;
            min-height: 4px;
            transition: background 0.15s;
        }

        .bar:hover {
            background: #1e40af;
        }

        .bar-label {
            font-size: 10px;
            color: #94a3b8;
        }

        .status-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .badge-ready {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-progress {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-done {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-confirmed {
            background: #ede9fe;
            color: #7c3aed;
        }

        .empty-state {
            padding: 2.5rem;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        .empty-state i {
            font-size: 28px;
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $maxBar = collect($monthlyTrend)->max('count') ?: 1;
    @endphp

    {{-- ── WELCOME ROW ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                Dr. {{ $doctor->first_name ?? $user->name }} 👋
            </h5>
            <p class="mb-0" style="font-size:13px; color:#94a3b8">
                {{ now()->format('l, d F Y') }} &mdash; Here's your overview
            </p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Appointment
        </a>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="row g-3 mb-4">
        {{-- Today's Appointments --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Today's Appointments</div>
                        <div class="stat-value">{{ $stats['today_appointments'] }}</div>
                        <div class="stat-sub">scheduled for today</div>
                    </div>
                    <div class="stat-icon" style="background:#eff6ff; color:#1d4ed8">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- This Month Patients --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">This Month</div>
                        <div class="stat-value">{{ $stats['month_patients'] }}</div>
                        <div class="stat-sub">
                            @if ($stats['patient_growth'] > 0)
                                <span class="growth-up"><i
                                        class="bi bi-arrow-up-short"></i>{{ $stats['patient_growth'] }}%</span>
                            @elseif($stats['patient_growth'] < 0)
                                <span class="growth-down"><i
                                        class="bi bi-arrow-down-short"></i>{{ abs($stats['patient_growth']) }}%</span>
                            @endif
                            vs last month
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#f0fdf4; color:#16a34a">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lab Results --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Lab Results</div>
                        <div class="stat-value">{{ $stats['ready_lab'] }}</div>
                        <div class="stat-sub">ready to review</div>
                    </div>
                    <div class="stat-icon" style="background:#fefce8; color:#ca8a04">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Radiology --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Radiology Reports</div>
                        <div class="stat-value">{{ $stats['ready_radiology'] }}</div>
                        <div class="stat-sub">ready to review</div>
                    </div>
                    <div class="stat-icon" style="background:#fdf4ff; color:#9333ea">
                        <i class="bi bi-radioactive"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="row g-3">
        {{-- LEFT COL --}}
        <div class="col-12 col-lg-5">
            {{-- Trend --}}
            <div class="section-card mb-3">
                <div class="section-header">
                    <span class="section-title"><i class="bi bi-bar-chart me-2" style="color:#1d4ed8"></i>Patient
                        Trend</span>
                </div>
                <div class="chart-wrap">
                    @foreach ($monthlyTrend as $item)
                        @php $height = $maxBar > 0 ? round(($item['count'] / $maxBar) * 85) : 4; @endphp
                        <div class="bar-group">
                            <div class="bar" style="height: {{ max($height, 4) }}px"></div>
                            <div class="bar-label">{{ $item['month'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Today's Schedule --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title"><i class="bi bi-calendar2-day me-2" style="color:#1d4ed8"></i>Today's
                        Schedule</span>
                </div>
                @forelse($todayAppointments as $appt)
                    <div class="result-row">
                        <div class="patient-avatar">
                            {{ strtoupper(substr($appt->patient->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="result-info">
                            <div class="result-patient">{{ $appt->patient->name ?? 'Unknown' }}</div>
                            <div class="result-detail">{{ $appt->type }} •
                                {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</div>
                        </div>
                        <a href="{{ route('appointments.show', $appt->id) }}"
                            class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                @empty
                    <div class="empty-state"><i class="bi bi-calendar-x"></i>No appointments today</div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COL --}}
        <div class="col-12 col-lg-7">
            {{-- Lab Results --}}
            <div class="section-card mb-3">
                <div class="section-header">
                    <span class="section-title"><i class="bi bi-clipboard2-pulse me-2" style="color:#ca8a04"></i>Recent Lab
                        Results</span>
                </div>
                @forelse($recentLabResults as $order)
                    <div class="result-row">
                        <div class="patient-avatar" style="background:#fefce8; color:#ca8a04">
                            {{ strtoupper(substr($order->patient->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="result-info">
                            <div class="result-patient">{{ $order->patient->name ?? 'Patient' }}</div>
                            <div class="result-detail">Order #{{ $order->order_number }} •
                                {{ $order->created_at->diffForHumans() }}</div>
                        </div>
                        <a href="{{ route('lab.orders.show', $order) }}"
                            style="font-size:12px; color:#1d4ed8; text-decoration:none;">
                            View <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">No recent lab results</div>
                @endforelse
            </div>

            {{-- Radiology --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title"><i class="bi bi-radioactive me-2" style="color:#9333ea"></i>Recent Radiology
                        Reports</span>
                </div>
                @forelse($recentRadiologyReports as $order)
                    <div class="result-row">
                        <div class="patient-avatar" style="background:#fdf4ff; color:#9333ea">
                            {{ strtoupper(substr($order->patient->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="result-info">
                            <div class="result-patient">{{ $order->patient->name ?? 'Patient' }}</div>
                            <div class="result-detail">Order #{{ $order->order_number }} • {{ $order->status }}</div>
                        </div>
                        <a href="{{ route('radiology.orders.show', $order) }}"
                            style="font-size:12px; color:#1d4ed8; text-decoration:none;">
                            View <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">No recent radiology reports</div>
                @endforelse
            </div>
            {{-- Prescriptions --}}
            <div class="section-card mt-3">
                <div class="section-header">
                    <span class="section-title">
                        <i class="bi bi-capsule me-2" style="color:#16a34a"></i>Recent Prescriptions
                    </span>
                    <a href="{{ route('pharmacy.prescriptions.create') }}" class="section-link">
                        <i class="bi bi-plus-lg me-1"></i>New
                    </a>
                </div>
                @forelse($recentPrescriptions as $rx)
                    <div class="result-row">
                        <div class="patient-avatar" style="background:#f0fdf4; color:#16a34a">
                            {{ strtoupper(substr($rx->patient->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="result-info">
                            <div class="result-patient">{{ $rx->patient->name ?? 'Patient' }}</div>
                            <div class="result-detail">
                                {{ $rx->prescription_number }} •
                                {{ $rx->items_count ?? $rx->items->count() }} medicine(s) •
                                {{ $rx->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span
                            class="status-badge
                {{ $rx->status === 'Dispensed' ? 'badge-ready' : ($rx->status === 'Partial' ? 'badge-progress' : 'badge-pending') }}">
                            {{ $rx->status }}
                        </span>
                        <a href="{{ route('pharmacy.prescriptions.show', $rx->id) }}"
                            style="font-size:12px; color:#1d4ed8; text-decoration:none;">
                            View <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-capsule"></i>No recent prescriptions
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

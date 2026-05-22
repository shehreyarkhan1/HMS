@extends('layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')

@push('styles')
    <style>
        /* ── Base card ── */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .stat-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-card-header-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .stat-card-sub {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.3;
        }

        .stat-card-title {
            font-size: 13px;
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
            padding: 9px 0;
            margin-bottom: 12px;
        }

        .stat-num-col {
            flex: 1;
            text-align: center;
            padding: 0 6px;
        }

        .stat-num-col+.stat-num-col {
            border-left: 1px solid #e5e7eb;
        }

        .stat-num-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .stat-num-value {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.1;
        }

        .stat-num-hint {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .stat-bar-row {
            margin-top: auto;
        }

        .stat-bar-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .stat-bar-track {
            height: 5px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 4px;
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

        /* ── Section cards (bottom panels) ── */
        .section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .sec-head {
            padding: 14px 18px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sec-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sec-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sec-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .sec-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px;
        }

        .sec-link {
            font-size: 12px;
            color: #1d4ed8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .sec-link:hover {
            color: #1e40af;
        }

        /* ── Appointments ── */
        .appt-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .appt-row:last-child {
            border-bottom: none;
        }

        .appt-time {
            font-size: 12px;
            color: #94a3b8;
            min-width: 56px;
            flex-shrink: 0;
            font-variant-numeric: tabular-nums;
        }

        .appt-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .appt-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .appt-meta {
            font-size: 11px;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Badges ── */
        .badge-pill {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .bp-Scheduled {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .bp-Confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .bp-In-Progress {
            background: #fef9c3;
            color: #854d0e;
        }

        .bp-Completed {
            background: #f0fdf4;
            color: #15803d;
        }

        .bp-Cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .bp-No-show {
            background: #f3f4f6;
            color: #6b7280;
        }

        .bp-Active {
            background: #dcfce7;
            color: #166534;
        }

        .bp-Admitted {
            background: #dbeafe;
            color: #1e40af;
        }

        .bp-Discharged {
            background: #f3f4f6;
            color: #374151;
        }

        .bp-Deceased {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ── Dept occupancy bars ── */
        .dept-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dept-row:last-child {
            border-bottom: none;
        }

        .dept-rank {
            font-size: 11px;
            color: #cbd5e1;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .dept-name {
            font-size: 13px;
            color: #374151;
            min-width: 110px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dept-bar-bg {
            flex: 1;
            height: 6px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
        }

        .dept-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 1s ease;
        }

        .dept-pct {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            min-width: 36px;
            text-align: right;
        }

        .dept-count {
            font-size: 11px;
            color: #94a3b8;
            min-width: 28px;
            text-align: right;
        }

        .bar-low {
            background: #16a34a;
        }

        .bar-medium {
            background: #d97706;
        }

        .bar-high {
            background: #dc2626;
        }

        /* ── Patient table ── */
        .pt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pt-table th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 10px 14px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .pt-table td {
            font-size: 13px;
            color: #374151;
            padding: 11px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .pt-table tr:last-child td {
            border-bottom: none;
        }

        .pt-table tbody tr:hover td {
            background: #f8fafc;
        }

        .mrn-code {
            font-size: 11px;
            color: #6366f1;
            background: #eef2ff;
            padding: 2px 7px;
            border-radius: 5px;
            font-family: monospace;
            white-space: nowrap;
        }

        .pt-initials {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .view-btn {
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            color: #374151;
            background: transparent;
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
        }

        .view-btn:hover {
            background: #f8fafc;
        }

        /* ── Row section label ── */
        .row-section-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 8px;
            margin-top: 4px;
        }
    </style>
@endpush

@section('content')

    {{-- ===== ROW 1: Core Stats ===== --}}
    <div class="row-section-label">Overview</div>
    <div class="row g-3 mb-4">

        {{-- Patients --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#eff6ff"><i class="bi bi-people" style="color:#1d4ed8"></i>
                        </div>
                        <div><span class="stat-card-sub">Hospital</span><br><span class="stat-card-title">Patients</span>
                        </div>
                    </div>
                    @php $pg = $patientGrowth; @endphp
                    <span class="stat-live-badge"
                        style="background:{{ $pg >= 0 ? '#dcfce7' : '#fee2e2' }};color:{{ $pg >= 0 ? '#166534' : '#991b1b' }};border:1px solid {{ $pg >= 0 ? '#bbf7d0' : '#fecaca' }}">
                        {{ $pg >= 0 ? '+' : '' }}{{ $pg }}%
                    </span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total</div>
                        <div class="stat-num-value" style="color:#1d4ed8">{{ number_format($patient) }}</div>
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
                            style="width:{{ min(abs($pg), 100) }}%;background:{{ $pg >= 0 ? '#1d4ed8' : '#dc2626' }}"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:{{ $pg >= 0 ? '#16a34a' : '#dc2626' }};font-weight:500">
                            <i
                                class="bi bi-arrow-{{ $pg >= 0 ? 'up' : 'down' }}-short"></i>{{ $pg >= 0 ? 'Growing' : 'Declining' }}
                        </span>
                        <span style="font-size:11px;color:#94a3b8">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointments --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#f0fdf4"><i class="bi bi-calendar-check"
                                style="color:#16a34a"></i></div>
                        <div><span class="stat-card-sub">Schedule</span><br><span
                                class="stat-card-title">Appointments</span></div>
                    </div>
                    <span class="stat-live-badge"
                        style="background:#f0fdfa;color:#0f766e;border:1px solid #99f6e4">Live</span>
                </div>
                @php $ac = $appointmentChange; @endphp
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Today</div>
                        <div class="stat-num-value" style="color:#16a34a">{{ number_format($appointment) }}</div>
                        <div class="stat-num-hint">{{ now()->format('d M') }}</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Yesterday</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $appointment - $ac }}</div>
                        <div class="stat-num-hint">{{ now()->subDay()->format('d M') }}</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">vs yesterday</span>
                        <span
                            style="font-size:11px;font-weight:600;color:{{ $ac >= 0 ? '#16a34a' : '#dc2626' }}">{{ $ac >= 0 ? '+' : '' }}{{ $ac }}</span>
                    </div>
                    <div class="stat-bar-track">
                        @php
                            $apptMax = max($appointment, $appointment - $ac, 1);
                            $apptPct = round(($appointment / $apptMax) * 100);
                        @endphp
                        <div class="stat-bar-fill" style="width:{{ $apptPct }}%;background:#16a34a"></div>
                    </div>
                    <div class="stat-bar-footer">
                        @if ($ac == 0)
                            <span style="font-size:11px;color:#94a3b8">Same as yesterday</span>
                        @elseif($ac > 0)
                            <span style="font-size:11px;color:#16a34a;font-weight:500"><i
                                    class="bi bi-arrow-up-short"></i>{{ $ac }} more</span>
                        @else
                            <span style="font-size:11px;color:#dc2626;font-weight:500"><i
                                    class="bi bi-arrow-down-short"></i>{{ abs($ac) }} fewer</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Beds & Wards --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#f0fdfa"><i class="bi bi-hospital"
                                style="color:#0f766e"></i></div>
                        <div><span class="stat-card-sub">Bed management</span><br><span class="stat-card-title">Wards &
                                beds</span></div>
                    </div>
                    <span class="stat-live-badge"
                        style="background:#f0fdfa;color:#0f766e;border:1px solid #99f6e4">Live</span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Available</div>
                        <div class="stat-num-value" style="color:#0f766e">{{ $availableBeds }}</div>
                        <div class="stat-num-hint">of {{ $totalBeds }} total</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Wards</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $totalWards }}</div>
                        <div class="stat-num-hint">active</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Occupancy</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $occupancyRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill"
                            style="width:{{ $occupancyRate }}%;background:{{ $occupancyRate >= 80 ? '#dc2626' : ($occupancyRate >= 50 ? '#d97706' : '#0f766e') }}">
                        </div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#6b7280"><span
                                style="color:#e24b4a;font-weight:500">{{ $occupiedBeds }}</span> occupied</span>
                        <span style="font-size:11px;color:#94a3b8">{{ $reservedBeds }} reserved/maint.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#fffbeb"><i class="bi bi-cash-stack"
                                style="color:#d97706"></i></div>
                        <div><span class="stat-card-sub">Billing</span><br><span class="stat-card-title">Revenue</span>
                        </div>
                    </div>
                    <span class="stat-live-badge"
                        style="background:#fffbeb;color:#92400e;border:1px solid #fde68a">PKR</span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Today</div>
                        <div class="stat-num-value" style="color:#d97706;font-size:17px">
                            {{ number_format($todayRevenue) }}</div>
                        <div class="stat-num-hint">{{ now()->format('d M') }}</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">{{ now()->format('M') }}</div>
                        <div class="stat-num-value" style="color:#1e293b;font-size:17px">
                            {{ number_format($monthRevenue) }}</div>
                        <div class="stat-num-hint">this month</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    @php $revPct = $totalRevenue > 0 ? round(($monthRevenue / $totalRevenue) * 100) : 0; @endphp
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Month / total</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $revPct }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $revPct }}%;background:#d97706"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#6b7280">Total <span style="font-weight:600;color:#1e293b">Rs
                                {{ number_format($totalRevenue) }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ROW 2: Clinical Modules ===== --}}
    <div class="row-section-label">Clinical modules</div>
    <div class="row g-3 mb-4">

        {{-- Lab Orders --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#eef2ff"><i class="bi bi-eyedropper"
                                style="color:#6366f1"></i></div>
                        <div><span class="stat-card-sub">Laboratory</span><br><span class="stat-card-title">Lab
                                orders</span></div>
                    </div>
                    <span class="stat-live-badge" style="background:#eef2ff;color:#3730a3;border:1px solid #c7d2fe">
                        {{ $labTodayOrders > 0 ? $labTodayOrders . ' today' : 'Orders' }}
                    </span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total</div>
                        <div class="stat-num-value" style="color:#6366f1">{{ $labTotalOrders }}</div>
                        <div class="stat-num-hint">all time</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Pending</div>
                        <div class="stat-num-value" style="color:{{ $labPendingOrders > 0 ? '#dc2626' : '#16a34a' }}">
                            {{ $labPendingOrders }}</div>
                        <div class="stat-num-hint">awaiting</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Completion rate</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $labCompletionRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $labCompletionRate }}%;background:#6366f1"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#16a34a;font-weight:500">{{ $labCompletedOrders }}
                            completed</span>
                        <span style="font-size:11px;color:#94a3b8">{{ $labPendingOrders }} processing</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Radiology --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#eff6ff"><i class="bi bi-layers"
                                style="color:#1d4ed8"></i></div>
                        <div><span class="stat-card-sub">Radiology</span><br><span class="stat-card-title">Imaging
                                orders</span></div>
                    </div>
                    <span class="stat-live-badge" style="background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe">
                        {{ $radTodayOrders > 0 ? $radTodayOrders . ' today' : 'Orders' }}
                    </span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total</div>
                        <div class="stat-num-value" style="color:#1d4ed8">{{ $radTotalOrders }}</div>
                        <div class="stat-num-hint">all time</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Reported</div>
                        <div class="stat-num-value" style="color:#16a34a">{{ $radReportedOrders }}</div>
                        <div class="stat-num-hint">delivered</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Report rate</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $radReportRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $radReportRate }}%;background:#1d4ed8"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#94a3b8">Rs {{ number_format($radRevenue) }} rev.</span>
                        <span
                            style="font-size:11px;color:{{ $radPendingOrders > 0 ? '#d97706' : '#94a3b8' }};font-weight:{{ $radPendingOrders > 0 ? '500' : '400' }}">{{ $radPendingOrders }}
                            pending</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- OT / Surgeries --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#fff1f2"><i class="bi bi-scissors"
                                style="color:#be123c"></i></div>
                        <div><span class="stat-card-sub">Operation theater</span><br><span
                                class="stat-card-title">Surgeries</span></div>
                    </div>
                    <span class="stat-live-badge" style="background:#fff1f2;color:#9f1239;border:1px solid #fecdd3">
                        {{ $otRooms }} OT rooms
                    </span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total</div>
                        <div class="stat-num-value" style="color:#be123c">{{ $otTotalScheduled }}</div>
                        <div class="stat-num-hint">scheduled</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Today</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $otTodayScheduled }}</div>
                        <div class="stat-num-hint">{{ now()->format('d M') }}</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Completion rate</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $otCompletionRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $otCompletionRate }}%;background:#be123c"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#16a34a;font-weight:500">{{ $otCompleted }} completed</span>
                        <span style="font-size:11px;color:#94a3b8">{{ $otUpcoming }} upcoming</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pharmacy --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#f0fdf4"><i class="bi bi-capsule"
                                style="color:#15803d"></i></div>
                        <div><span class="stat-card-sub">Pharmacy</span><br><span
                                class="stat-card-title">Dispensing</span></div>
                    </div>
                    @if ($lowStockMeds > 0)
                        <span class="stat-live-badge" style="background:#fef9c3;color:#854d0e;border:1px solid #fde68a">
                            {{ $lowStockMeds }} low stock
                        </span>
                    @else
                        <span class="stat-live-badge"
                            style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0">Stock OK</span>
                    @endif
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Dispensings</div>
                        <div class="stat-num-value" style="color:#15803d">{{ $totalDispensings }}</div>
                        <div class="stat-num-hint">all time</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Low stock</div>
                        <div class="stat-num-value" style="color:{{ $lowStockMeds > 0 ? '#dc2626' : '#16a34a' }}">
                            {{ $lowStockMeds }}</div>
                        <div class="stat-num-hint">medicines</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Stock health</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $stockHealthRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill"
                            style="width:{{ $stockHealthRate }}%;background:{{ $stockHealthRate < 50 ? '#dc2626' : ($stockHealthRate < 75 ? '#d97706' : '#15803d') }}">
                        </div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#94a3b8">{{ $totalMedicines }} medicines</span>
                        <span style="font-size:11px;color:#94a3b8">Rs {{ number_format($pharmacyRevenue) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ROW 3: Support Modules ===== --}}
    <div class="row-section-label">Support modules</div>
    <div class="row g-3 mb-4">

        {{-- Blood Bank --}}
        <div class="col-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#fee2e2"><i class="bi bi-droplet-half"
                                style="color:#dc2626"></i></div>
                        <div><span class="stat-card-sub">Blood bank</span><br><span class="stat-card-title">Blood
                                inventory</span></div>
                    </div>
                    <span class="stat-live-badge"
                        style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5">Live</span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Donors</div>
                        <div class="stat-num-value" style="color:#dc2626">{{ $totalDonors }}</div>
                        <div class="stat-num-hint">registered</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Requests</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $totalBloodRequests }}</div>
                        <div class="stat-num-hint">total</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Units</div>
                        <div class="stat-num-value" style="color:#16a34a">{{ $bloodUnitsAvail }}</div>
                        <div class="stat-num-hint">available</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Fulfillment rate</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $bloodFulfillRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $bloodFulfillRate }}%;background:#dc2626"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#16a34a;font-weight:500">{{ $fulfilledBloodReqs }}
                            fulfilled</span>
                        <span
                            style="font-size:11px;color:{{ $pendingBloodReqs > 0 ? '#d97706' : '#94a3b8' }};font-weight:{{ $pendingBloodReqs > 0 ? '500' : '400' }}">{{ $pendingBloodReqs }}
                            pending</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mortuary --}}
        <div class="col-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#f3f4f6"><i class="bi bi-file-earmark-text"
                                style="color:#4b5563"></i></div>
                        <div><span class="stat-card-sub">Mortuary</span><br><span class="stat-card-title">Mortuary
                                records</span></div>
                    </div>
                    @if ($unclaimedBodies > 0)
                        <span class="stat-live-badge"
                            style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5">{{ $unclaimedBodies }}
                            unclaimed</span>
                    @else
                        <span class="stat-live-badge"
                            style="background:#f3f4f6;color:#4b5563;border:1px solid #d1d5db">Records</span>
                    @endif
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total</div>
                        <div class="stat-num-value" style="color:#4b5563">{{ $totalMortuary }}</div>
                        <div class="stat-num-hint">all time</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Certificates</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $totalCertificates }}</div>
                        <div class="stat-num-hint">issued</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Released</div>
                        <div class="stat-num-value" style="color:#16a34a">{{ $releasedBodies }}</div>
                        <div class="stat-num-hint">bodies</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Release rate</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $mortuaryReleaseRate }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $mortuaryReleaseRate }}%;background:#4b5563"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#94a3b8">{{ $medicoLegal }} medico-legal</span>
                        <span
                            style="font-size:11px;color:{{ $unclaimedBodies > 0 ? '#dc2626' : '#94a3b8' }};font-weight:{{ $unclaimedBodies > 0 ? '500' : '400' }}">{{ $unclaimedBodies }}
                            unclaimed</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- HR / Staff --}}
        <div class="col-12 col-xl-4">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-header-left">
                        <div class="stat-icon-box" style="background:#fdf4ff"><i class="bi bi-person-badge"
                                style="color:#a21caf"></i></div>
                        <div><span class="stat-card-sub">Human resources</span><br><span class="stat-card-title">Staff
                                overview</span></div>
                    </div>
                    <span class="stat-live-badge"
                        style="background:#fdf4ff;color:#86198f;border:1px solid #f0abfc">Active</span>
                </div>
                <div class="stat-numbers-box">
                    <div class="stat-num-col">
                        <div class="stat-num-label">Total staff</div>
                        <div class="stat-num-value" style="color:#a21caf">{{ $totalStaff }}</div>
                        <div class="stat-num-hint">active</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Doctors</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $totalDoctors }}</div>
                        <div class="stat-num-hint">active</div>
                    </div>
                    <div class="stat-num-col">
                        <div class="stat-num-label">Nurses</div>
                        <div class="stat-num-value" style="color:#1e293b">{{ $totalNurses }}</div>
                        <div class="stat-num-hint">active</div>
                    </div>
                </div>
                <div class="stat-bar-row">
                    <div class="stat-bar-meta">
                        <span style="font-size:11px;color:#6b7280">Clinical ratio</span>
                        <span style="font-size:11px;font-weight:600;color:#1e293b">{{ $clinicalRatio }}%</span>
                    </div>
                    <div class="stat-bar-track">
                        <div class="stat-bar-fill" style="width:{{ $clinicalRatio }}%;background:#a21caf"></div>
                    </div>
                    <div class="stat-bar-footer">
                        <span style="font-size:11px;color:#94a3b8">{{ $clinicalStaff }} clinical</span>
                        <span style="font-size:11px;color:#94a3b8">{{ $totalStaff - $clinicalStaff }} support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MIDDLE ROW: Appointments + Dept Occupancy ===== --}}
    <div class="row g-3 mb-4">

        {{-- Today's Appointments --}}
        <div class="col-12 col-lg-6">
            <div class="section-card h-100">
                <div class="sec-head">
                    <div class="sec-head-left">
                        <div class="sec-icon" style="background:#eff6ff"><i class="bi bi-calendar2-week"
                                style="color:#1d4ed8;font-size:16px"></i></div>
                        <div>
                            <div class="sec-title">Today's appointments</div>
                            <div class="sec-sub">{{ now()->format('D, d M') }} &middot;
                                {{ $recentAppointments->count() }} scheduled</div>
                        </div>
                    </div>
                    @if (Route::has('appointments.index'))
                        <a href="{{ route('appointments.index') }}" class="sec-link">View all <i
                                class="bi bi-arrow-right" style="font-size:11px"></i></a>
                    @endif
                </div>

                @forelse($recentAppointments as $appt)
                    @php
                        $avatarColors = [
                            'OPD' => ['bg' => '#dbeafe', 'color' => '#1d4ed8'],
                            'IPD' => ['bg' => '#dcfce7', 'color' => '#166534'],
                            'Emergency' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                            'Follow-up' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                        ];
                        $c = $avatarColors[$appt->type] ?? ['bg' => '#f1f5f9', 'color' => '#475569'];
                        $bpClass = 'bp-' . str_replace(' ', '-', $appt->status);
                    @endphp
                    <div class="appt-row">
                        <span
                            class="appt-time">{{ $appt->appointment_time ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') : '—' }}</span>
                        <div class="appt-avatar" style="background:{{ $c['bg'] }};color:{{ $c['color'] }}">
                            {{ $appt->patient->initials ?? '??' }}</div>
                        <div style="flex:1;min-width:0">
                            <div class="appt-name">{{ $appt->patient->name ?? '—' }}</div>
                            <div class="appt-meta">{{ $appt->type }}@if ($appt->doctor)
                                    &middot; {{ $appt->doctor->name }}
                                @endif
                            </div>
                        </div>
                        <span class="badge-pill {{ $bpClass }}">{{ $appt->status }}</span>
                    </div>
                    @empty
                        <div class="text-center py-5" style="color:#94a3b8;font-size:13px">
                            <i class="bi bi-calendar-x" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4"></i>
                            No appointments today
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Department Occupancy --}}
            <div class="col-12 col-lg-6">
                <div class="section-card h-100">
                    <div class="sec-head">
                        <div class="sec-head-left">
                            <div class="sec-icon" style="background:#f0fdfa"><i class="bi bi-building"
                                    style="color:#0f766e;font-size:16px"></i></div>
                            <div>
                                <div class="sec-title">Department occupancy</div>
                                <div class="sec-sub">Today's patient load</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:4px"><span
                                    style="width:8px;height:8px;border-radius:50%;background:#16a34a;display:inline-block"></span>&lt;50%</span>
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:4px"><span
                                    style="width:8px;height:8px;border-radius:50%;background:#d97706;display:inline-block"></span>50–79%</span>
                            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:4px"><span
                                    style="width:8px;height:8px;border-radius:50%;background:#dc2626;display:inline-block"></span>80%+</span>
                        </div>
                    </div>

                    @if (count($departmentOccupancy) > 0)
                        @foreach ($departmentOccupancy as $i => $dept)
                            @php
                                $barClass =
                                    $dept['percent'] >= 80
                                        ? 'bar-high'
                                        : ($dept['percent'] >= 50
                                            ? 'bar-medium'
                                            : 'bar-low');
                                $countColor =
                                    $dept['percent'] >= 80
                                        ? '#dc2626'
                                        : ($dept['percent'] >= 50
                                            ? '#d97706'
                                            : '#94a3b8');
                            @endphp
                            <div class="dept-row">
                                <span class="dept-rank">{{ $i + 1 }}</span>
                                <span class="dept-name" title="{{ $dept['name'] }}">{{ $dept['name'] }}</span>
                                <div class="dept-bar-bg">
                                    <div class="dept-bar-fill {{ $barClass }}" style="width:{{ $dept['percent'] }}%">
                                    </div>
                                </div>
                                <span class="dept-pct">{{ $dept['percent'] }}%</span>
                                <span class="dept-count"
                                    style="color:{{ $countColor }};font-weight:500">({{ $dept['count'] }})</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5" style="color:#94a3b8;font-size:13px">
                            <i class="bi bi-building" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4"></i>
                            No department data available
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== RECENT PATIENTS ===== --}}
        <div class="section-card">
            <div class="sec-head">
                <div class="sec-head-left">
                    <div class="sec-icon" style="background:#eef2ff"><i class="bi bi-people"
                            style="color:#6366f1;font-size:16px"></i></div>
                    <div>
                        <div class="sec-title">Recent patients</div>
                        <div class="sec-sub">Last {{ $recentPatients->count() }} registered</div>
                    </div>
                </div>
                @if (Route::has('patients.index'))
                    <a href="{{ route('patients.index') }}" class="sec-link">View all <i class="bi bi-arrow-right"
                            style="font-size:11px"></i></a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="pt-table">
                    <thead>
                        <tr>
                            <th>MRN</th>
                            <th>Patient</th>
                            <th>Age / Gender</th>
                            <th>Type</th>
                            <th>Doctor</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPatients as $pat)
                            @php
                                $initials = collect(explode(' ', $pat->name))
                                    ->map(fn($w) => strtoupper($w[0] ?? ''))
                                    ->take(2)
                                    ->join('');
                                $avatarPalette = [
                                    ['bg' => '#E6F1FB', 'color' => '#185FA5'],
                                    ['bg' => '#EAF3DE', 'color' => '#3B6D11'],
                                    ['bg' => '#FAEEDA', 'color' => '#633806'],
                                    ['bg' => '#EEEDFE', 'color' => '#3C3489'],
                                    ['bg' => '#E1F5EE', 'color' => '#085041'],
                                ];
                                $av = $avatarPalette[$loop->index % count($avatarPalette)];
                            @endphp
                            <tr>
                                <td><span class="mrn-code">{{ $pat->mrn }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="pt-initials"
                                            style="background:{{ $av['bg'] }};color:{{ $av['color'] }}">
                                            {{ $initials }}</div>
                                        <div>
                                            <div style="font-weight:500;color:#1e293b;font-size:13px">{{ $pat->name }}
                                            </div>
                                            <div style="font-size:11px;color:#94a3b8">{{ $pat->city }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:#64748b">{{ $pat->age }} / {{ $pat->gender }}</td>
                                <td style="color:#64748b">{{ $pat->patient_type }}</td>
                                <td style="color:#64748b">{{ $pat->doctor->name ?? '—' }}</td>
                                <td style="color:#94a3b8;font-size:12px">{{ $pat->created_at->format('d M Y') }}</td>
                                <td><span class="badge-pill bp-{{ $pat->status }}">{{ $pat->status }}</span></td>
                                <td>
                                    <a href="{{ route('patients.show', $pat->id) }}">
                                        <button class="view-btn">View</button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4" style="color:#94a3b8;font-size:13px">
                                    <i class="bi bi-people"
                                        style="font-size:28px;display:block;margin-bottom:6px;opacity:.4"></i>
                                    No patients found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.stat-bar-fill, .dept-bar-fill').forEach(bar => {
                    const w = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = w;
                    }, 200);
                });
            });
        </script>
    @endpush

{{-- resources/views/wards/patient_show.blade.php --}}
@extends('layouts.master')

@section('title', $patient->name . ' — Ward')
@section('page-title', 'IPD Patient')
@section('breadcrumb', 'Home / Wards / ' . ($bed->ward->name ?? '—') . ' / ' . $patient->name)

@push('styles')
    <style>
        .patient-header {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 20px
        }

        .tab-nav {
            display: flex;
            gap: 4px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 20px;
            flex-wrap: wrap
        }

        .tab-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            border: none;
            background: transparent;
            transition: all .15s;
            white-space: nowrap
        }

        .tab-btn.active {
            background: #fff;
            color: #1d4ed8;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08)
        }

        .tab-btn:hover:not(.active) {
            color: #1e293b;
            background: #fff
        }

        .tab-pane {
            display: none
        }

        .tab-pane.active {
            display: block
        }

        .section-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px
        }

        .section-header {
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .section-header h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #374151
        }

        .section-body {
            padding: 18px
        }

        /* Vital cards */
        .vital-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
            margin-bottom: 16px
        }

        .vital-card {
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc
        }

        .vital-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 4px
        }

        .vital-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b
        }

        .vital-unit {
            font-size: 10px;
            color: #94a3b8;
            margin-left: 2px
        }

        .vital-normal {
            border-color: #bbf7d0;
            background: #f0fdf4
        }

        .vital-normal .vital-value {
            color: #15803d
        }

        .vital-high {
            border-color: #fca5a5;
            background: #fef2f2
        }

        .vital-high .vital-value {
            color: #dc2626
        }

        .vital-low {
            border-color: #93c5fd;
            background: #eff6ff
        }

        .vital-low .vital-value {
            color: #1d4ed8
        }

        .vital-critical {
            border-color: #f87171;
            background: #fef2f2;
            animation: pulse 1.5s infinite
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .7
            }
        }

        /* Order rows */
        .order-row {
            padding: 12px 18px;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: flex-start;
            gap: 12px
        }

        .order-row:last-child {
            border-bottom: none
        }

        .order-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0
        }

        .priority-STAT {
            background: #fef2f2;
            color: #dc2626
        }

        .priority-Urgent {
            background: #fffbeb;
            color: #d97706
        }

        .priority-Routine {
            background: #f0fdf4;
            color: #16a34a
        }

        /* Note rows */
        .note-row {
            padding: 14px 18px;
            border-bottom: 1px solid #f8fafc
        }

        .note-row:last-child {
            border-bottom: none
        }

        /* Form labels */
        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px
        }

        .form-control,
        .form-select {
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 11px
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1)
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309
        }

        .badge-ack {
            background: #dbeafe;
            color: #1d4ed8
        }

        .badge-done {
            background: #dcfce7;
            color: #15803d
        }

        .badge-cancel {
            background: #f1f5f9;
            color: #64748b
        }

        .badge-stat {
            background: #fee2e2;
            color: #991b1b
        }
    </style>
@endpush

@section('content')

    @php
        $user = auth()->user();
        $isDoctor = $user->hasAnyRole(['doctor', 'super_admin']);
        $isNurse = $user->hasAnyRole(['nurse', 'super_admin']);
        $canDischarge = $user->hasAnyRole(['doctor', 'super_admin']);
        $latestVital = $vitals->first();
    @endphp

    {{-- ── Patient Header ── --}}
    <div class="patient-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div
                    style="width:50px;height:50px;border-radius:12px;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:16px;font-weight:600;color:#1e293b">{{ $patient->name }}</div>
                    <div style="font-size:12px;color:#94a3b8">
                        MRN: {{ $patient->mrn }}
                        &bull; {{ $patient->gender ?? '—' }}
                        &bull; {{ $patient->age ?? '—' }} yrs
                        Dr. {{ $order->doctor->employee->full_name ?? '—' }}
                        @if ($bed)
                            &bull; Bed: <strong>{{ $bed->bed_number }}</strong> &bull; {{ $bed->ward->name }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if ($bed)
                    <span
                        style="font-size:12px;background:#f0fdf4;color:#15803d;padding:4px 12px;border-radius:8px;font-weight:500">
                        <i class="bi bi-calendar3 me-1"></i>
                        Admitted: {{ \Carbon\Carbon::parse($bed->admitted_at)->format('d M Y') }}
                        ({{ \Carbon\Carbon::parse($bed->admitted_at)->diffInDays(now()) }} days)
                    </span>
                @endif
                @if ($canDischarge && $bed)
                    <a href="{{ route('ward.discharge.create', $patient->id) }}" class="btn btn-sm btn-danger"
                        style="font-size:13px">
                        <i class="bi bi-box-arrow-right me-1"></i>Discharge
                    </a>
                @endif
                <a href="{{ route('wards.show', $bed->ward_id ?? 0) }}" class="btn btn-sm btn-outline-secondary"
                    style="font-size:13px">Back</a>
            </div>
        </div>
    </div>

    {{-- ── Tabs ── --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('vitals', this)">
            <i class="bi bi-heart-pulse me-1"></i>Vitals
        </button>
        <button class="tab-btn" onclick="switchTab('orders', this)">
            <i class="bi bi-clipboard2-check me-1"></i>Doctor Orders
            @if ($pendingOrders > 0)
                <span class="badge bg-danger ms-1" style="font-size:10px">{{ $pendingOrders }}</span>
            @endif
        </button>
        <button class="tab-btn" onclick="switchTab('notes', this)">
            <i class="bi bi-journal-text me-1"></i>Nursing Notes
        </button>
        <button class="tab-btn" onclick="switchTab('visits', this)">
            <i class="bi bi-person-badge me-1"></i>Doctor Visits
        </button>
        <button class="tab-btn" onclick="switchTab('summary', this)">
            <i class="bi bi-file-medical me-1"></i>Summary
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 1 — VITALS                                        --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="tab-vitals" class="tab-pane active">
        <div class="row g-3">
            <div class="col-12 col-lg-7">

                {{-- Latest Vitals Display --}}
                @if ($latestVital)
                    <div class="section-card">
                        <div class="section-header">
                            <h6><i class="bi bi-activity me-2 text-danger"></i>Latest Vitals</h6>
                            <small class="text-muted">{{ $latestVital->recorded_at->format('d M Y, h:i A') }} —
                                {{ $latestVital->shift }} shift — by {{ $latestVital->recordedBy->name ?? '—' }}</small>
                        </div>
                        <div class="section-body">
                            <div class="vital-grid">
                                <div class="vital-card vital-{{ $latestVital->bp_status }}">
                                    <div class="vital-label">Blood Pressure</div>
                                    <div class="vital-value">
                                        {{ $latestVital->systolic_bp ?? '—' }}/{{ $latestVital->diastolic_bp ?? '—' }}<span
                                            class="vital-unit">mmHg</span></div>
                                </div>
                                <div class="vital-card vital-{{ $latestVital->spo2_status }}">
                                    <div class="vital-label">SpO2</div>
                                    <div class="vital-value">{{ $latestVital->oxygen_saturation ?? '—' }}<span
                                            class="vital-unit">%</span></div>
                                </div>
                                <div class="vital-card vital-{{ $latestVital->temp_status }}">
                                    <div class="vital-label">Temperature</div>
                                    <div class="vital-value">{{ $latestVital->temperature ?? '—' }}<span
                                            class="vital-unit">°F</span></div>
                                </div>
                                <div class="vital-card">
                                    <div class="vital-label">Pulse Rate</div>
                                    <div class="vital-value">{{ $latestVital->pulse_rate ?? '—' }}<span
                                            class="vital-unit">bpm</span></div>
                                </div>
                                <div class="vital-card">
                                    <div class="vital-label">Respiratory</div>
                                    <div class="vital-value">{{ $latestVital->respiratory_rate ?? '—' }}<span
                                            class="vital-unit">/min</span></div>
                                </div>
                                <div class="vital-card">
                                    <div class="vital-label">Pain Score</div>
                                    <div
                                        class="vital-value {{ ($latestVital->pain_score ?? 0) >= 7 ? 'text-danger' : '' }}">
                                        {{ $latestVital->pain_score ?? '—' }}<span class="vital-unit">/10</span>
                                    </div>
                                </div>
                                @if ($latestVital->blood_glucose)
                                    <div class="vital-card">
                                        <div class="vital-label">Blood Glucose</div>
                                        <div class="vital-value">{{ $latestVital->blood_glucose }}<span
                                                class="vital-unit">mg/dL</span></div>
                                    </div>
                                @endif
                                @if ($latestVital->gcs_score)
                                    <div
                                        class="vital-card vital-{{ $latestVital->gcs_score < 9 ? 'critical' : ($latestVital->gcs_score < 13 ? 'high' : 'normal') }}">
                                        <div class="vital-label">GCS Score</div>
                                        <div class="vital-value">{{ $latestVital->gcs_score }}<span
                                                class="vital-unit">/15</span></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Vitals History --}}
                <div class="section-card">
                    <div class="section-header">
                        <h6><i class="bi bi-clock-history me-2 text-secondary"></i>Vitals History</h6>
                    </div>
                    <div style="overflow-x:auto">
                        <table class="table table-sm table-hover mb-0" style="font-size:12px">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Shift</th>
                                    <th>BP</th>
                                    <th>SpO2</th>
                                    <th>Temp</th>
                                    <th>Pulse</th>
                                    <th>RR</th>
                                    <th>Pain</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vitals as $v)
                                    <tr>
                                        <td class="text-muted">{{ $v->recorded_at->format('d M, h:i A') }}</td>
                                        <td><span class="badge bg-light text-secondary border"
                                                style="font-size:10px">{{ $v->shift }}</span></td>
                                        <td class="{{ $v->bp_status === 'high' ? 'text-danger fw-semibold' : '' }}">
                                            {{ $v->systolic_bp ? "{$v->systolic_bp}/{$v->diastolic_bp}" : '—' }}
                                        </td>
                                        <td class="{{ $v->spo2_status === 'critical' ? 'text-danger fw-semibold' : '' }}">
                                            {{ $v->oxygen_saturation ? $v->oxygen_saturation . '%' : '—' }}
                                        </td>
                                        <td class="{{ $v->temp_status === 'high' ? 'text-danger' : '' }}">
                                            {{ $v->temperature ? $v->temperature . '°F' : '—' }}
                                        </td>
                                        <td>{{ $v->pulse_rate ? $v->pulse_rate . ' bpm' : '—' }}</td>
                                        <td>{{ $v->respiratory_rate ? $v->respiratory_rate . '/m' : '—' }}</td>
                                        <td>{{ $v->pain_score !== null ? $v->pain_score . '/10' : '—' }}</td>
                                        <td class="text-muted">{{ $v->recordedBy->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">No vitals recorded yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Record Vitals Form (Nurse only) --}}
            @if ($isNurse)
                <div class="col-12 col-lg-5">
                    <div class="section-card">
                        <div class="section-header">
                            <h6><i class="bi bi-plus-circle me-2 text-success"></i>Record Vitals</h6>
                        </div>
                        <div class="section-body">
                            <form method="POST" action="{{ route('ward.vitals.store', $patient->id) }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label">Shift *</label>
                                        <select name="shift" class="form-select form-select-sm" required>
                                            <option value="Morning">🌅 Morning</option>
                                            <option value="Afternoon">☀️ Afternoon</option>
                                            <option value="Evening">🌆 Evening</option>
                                            <option value="Night">🌙 Night</option>
                                        </select>
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Systolic BP</label>
                                        <input type="number" name="systolic_bp" class="form-control form-control-sm"
                                            placeholder="120" min="50" max="300">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Diastolic BP</label>
                                        <input type="number" name="diastolic_bp" class="form-control form-control-sm"
                                            placeholder="80" min="20" max="200">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">SpO2 (%)</label>
                                        <input type="number" name="oxygen_saturation"
                                            class="form-control form-control-sm" placeholder="98" step="0.1"
                                            min="50" max="100">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Temperature (°F)</label>
                                        <input type="number" name="temperature" class="form-control form-control-sm"
                                            placeholder="98.6" step="0.1">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Pulse Rate (bpm)</label>
                                        <input type="number" name="pulse_rate" class="form-control form-control-sm"
                                            placeholder="72">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Respiratory Rate</label>
                                        <input type="number" name="respiratory_rate"
                                            class="form-control form-control-sm" placeholder="16">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Pain Score (0–10)</label>
                                        <input type="number" name="pain_score" class="form-control form-control-sm"
                                            placeholder="0" min="0" max="10">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Blood Glucose (mg/dL)</label>
                                        <input type="number" name="blood_glucose" class="form-control form-control-sm"
                                            placeholder="110" step="0.1">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Weight (kg)</label>
                                        <input type="number" name="weight" class="form-control form-control-sm"
                                            placeholder="70" step="0.1">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Height (cm)</label>
                                        <input type="number" name="height" class="form-control form-control-sm"
                                            placeholder="170" step="0.1">
                                    </div>

                                    {{-- GCS --}}
                                    <div class="col-12 mt-1">
                                        <div
                                            style="font-size:11px;font-weight:600;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">
                                            GCS Score</div>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <label class="form-label">Eye (1–4)</label>
                                                <input type="number" name="gcs_eye" class="form-control form-control-sm"
                                                    min="1" max="4">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Verbal (1–5)</label>
                                                <input type="number" name="gcs_verbal"
                                                    class="form-control form-control-sm" min="1" max="5">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Motor (1–6)</label>
                                                <input type="number" name="gcs_motor"
                                                    class="form-control form-control-sm" min="1" max="6">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" rows="2" class="form-control form-control-sm"
                                            placeholder="Additional observations..."></textarea>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success w-100 btn-sm"
                                            style="height:38px;font-size:13px;font-weight:500">
                                            <i class="bi bi-check-lg me-1"></i>Save Vitals
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 2 — DOCTOR ORDERS                                 --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="tab-orders" class="tab-pane">
        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="section-card">
                    <div class="section-header">
                        <h6><i class="bi bi-clipboard2-check me-2 text-primary"></i>Active Orders</h6>
                    </div>
                    @forelse($doctorOrders as $order)
                        <div class="order-row">
                            <div class="order-icon priority-{{ $order->priority }}">
                                <i
                                    class="bi {{ match ($order->order_type) {
                                        'Medication' => 'bi-capsule',
                                        'Investigation' => 'bi-clipboard2-pulse',
                                        'Diet' => 'bi-egg-fried',
                                        'Procedure' => 'bi-bandaid',
                                        'Monitoring' => 'bi-activity',
                                        'Discharge' => 'bi-box-arrow-right',
                                        default => 'bi-file-text',
                                    } }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $order->title }}</span>
                                    @if ($order->priority === 'STAT')
                                        <span class="badge bg-danger" style="font-size:10px">STAT</span>
                                    @elseif($order->priority === 'Urgent')
                                        <span class="badge bg-warning text-dark" style="font-size:10px">Urgent</span>
                                    @endif
                                    <span
                                        class="badge badge-{{ $order->status === 'Pending' ? 'pending' : ($order->status === 'Acknowledged' ? 'ack' : ($order->status === 'Completed' ? 'done' : 'cancel')) }}"
                                        style="font-size:10px">{{ $order->status }}</span>
                                </div>
                                <div style="font-size:12px;color:#64748b">{{ $order->details }}</div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                                    {{ $order->order_type }} &bull; Dr. {{ $order->doctor->employee->full_name ?? '—' }}
                                    &bull; {{ $order->ordered_at->format('d M, h:i A') }}
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                @if ($isNurse && $order->status === 'Pending')
                                    <form method="POST" action="{{ route('ward.orders.acknowledge', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-xs btn-outline-primary py-0 px-2"
                                            style="font-size:11px">Acknowledge</button>
                                    </form>
                                @endif
                                @if ($isNurse && $order->status === 'Acknowledged')
                                    <form method="POST" action="{{ route('ward.orders.complete', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-xs btn-outline-success py-0 px-2"
                                            style="font-size:11px">Complete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4" style="font-size:13px">
                            <i class="bi bi-clipboard2 d-block fs-3 mb-2"></i>No active orders
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Write Order (Doctor only) --}}
            @if ($isDoctor)
                <div class="col-12 col-lg-4">
                    <div class="section-card">
                        <div class="section-header">
                            <h6><i class="bi bi-pencil-square me-2 text-primary"></i>Write Order</h6>
                        </div>
                        <div class="section-body">
                            <form method="POST" action="{{ route('ward.orders.store', $patient->id) }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label">Order Type *</label>
                                        <select name="order_type" class="form-select form-select-sm" required>
                                            <option value="Medication">💊 Medication</option>
                                            <option value="Investigation">🔬 Investigation</option>
                                            <option value="Diet">🍽️ Diet</option>
                                            <option value="Activity">🏃 Activity</option>
                                            <option value="Procedure">⚕️ Procedure</option>
                                            <option value="Monitoring">📊 Monitoring</option>
                                            <option value="Consult">👨‍⚕️ Consult</option>
                                            <option value="Other">📝 Other</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Priority *</label>
                                        <select name="priority" class="form-select form-select-sm" required>
                                            <option value="Routine">Routine</option>
                                            <option value="Urgent">Urgent</option>
                                            <option value="STAT">STAT (Immediate)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Order Title *</label>
                                        <input type="text" name="title" class="form-control form-control-sm"
                                            placeholder="e.g. Inj. Paracetamol 1g IV" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Details *</label>
                                        <textarea name="details" rows="3" class="form-control form-control-sm"
                                            placeholder="Full order details, dose, frequency..." required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Special Instructions</label>
                                        <input type="text" name="special_instructions"
                                            class="form-control form-control-sm" placeholder="Optional...">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100 btn-sm"
                                            style="height:38px;font-size:13px">
                                            <i class="bi bi-send me-1"></i>Write Order
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 3 — NURSING NOTES                                 --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="tab-notes" class="tab-pane">
        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="section-card">
                    <div class="section-header">
                        <h6><i class="bi bi-journal-text me-2 text-success"></i>Nursing Notes</h6>
                    </div>
                    @forelse($nursingNotes as $note)
                        <div class="note-row">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="d-flex gap-2 align-items-center">
                                    <span
                                        style="font-size:12px;font-weight:600;color:#1e293b">{{ $note->nurse->name ?? '—' }}</span>
                                    <span class="badge bg-light text-secondary border"
                                        style="font-size:10px">{{ $note->shift }}</span>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"
                                        style="font-size:10px">{{ $note->note_type }}</span>
                                    @if ($note->is_urgent)
                                        <span class="badge bg-danger" style="font-size:10px">Urgent</span>
                                    @endif
                                    @if ($note->requires_doctor_attention)
                                        <span class="badge bg-warning text-dark" style="font-size:10px">Doctor
                                            Attention</span>
                                    @endif
                                </div>
                                <span
                                    style="font-size:11px;color:#94a3b8">{{ $note->noted_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <p style="font-size:13px;color:#374151;margin-bottom:4px">{{ $note->note }}</p>
                            @if ($note->interventions)
                                <div style="font-size:12px;color:#64748b"><strong>Interventions:</strong>
                                    {{ $note->interventions }}</div>
                            @endif
                            @if ($note->patient_response)
                                <div style="font-size:12px;color:#64748b"><strong>Patient response:</strong>
                                    {{ $note->patient_response }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4" style="font-size:13px">
                            <i class="bi bi-journal d-block fs-3 mb-2"></i>No nursing notes yet
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($isNurse)
                <div class="col-12 col-lg-4">
                    <div class="section-card">
                        <div class="section-header">
                            <h6><i class="bi bi-plus me-2 text-success"></i>Add Note</h6>
                        </div>
                        <div class="section-body">
                            <form method="POST" action="{{ route('ward.nursing-notes.store', $patient->id) }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">Shift *</label>
                                        <select name="shift" class="form-select form-select-sm" required>
                                            <option>Morning</option>
                                            <option>Afternoon</option>
                                            <option>Evening</option>
                                            <option>Night</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Type *</label>
                                        <select name="note_type" class="form-select form-select-sm" required>
                                            <option>General</option>
                                            <option>Medication Given</option>
                                            <option>Procedure Done</option>
                                            <option>Patient Complaint</option>
                                            <option>Family Communication</option>
                                            <option>Incident Report</option>
                                            <option>Handover Note</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Note *</label>
                                        <textarea name="note" rows="4" class="form-control form-control-sm"
                                            placeholder="Document your observation..." required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Interventions done</label>
                                        <input type="text" name="interventions" class="form-control form-control-sm"
                                            placeholder="What was done?">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Patient response</label>
                                        <input type="text" name="patient_response"
                                            class="form-control form-control-sm" placeholder="How did patient respond?">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="requires_doctor_attention"
                                                class="form-check-input" id="rda">
                                            <label class="form-check-label" for="rda" style="font-size:12px">Needs
                                                Doctor Attention</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="is_urgent" class="form-check-input"
                                                id="urg">
                                            <label class="form-check-label" for="urg"
                                                style="font-size:12px">Urgent</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success w-100 btn-sm"
                                            style="height:38px;font-size:13px">
                                            <i class="bi bi-journal-plus me-1"></i>Save Note
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 4 — DOCTOR VISITS                                 --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="tab-visits" class="tab-pane">
        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="section-card">
                    <div class="section-header">
                        <h6><i class="bi bi-person-badge me-2 text-purple"></i>Doctor Visit Notes</h6>
                    </div>
                    @forelse($visitNotes as $visit)
                        <div class="note-row">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span style="font-size:13px;font-weight:600">Dr.
                                        {{ $visit->doctor->employee->full_name ?? '—' }}</span>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-2"
                                        style="font-size:10px">{{ $visit->visit_type }}</span>
                                    @if ($visit->is_discharge_ready)
                                        <span class="badge bg-success ms-1" style="font-size:10px">Ready for
                                            Discharge</span>
                                    @endif
                                </div>
                                <span
                                    style="font-size:11px;color:#94a3b8">{{ $visit->visited_at->format('d M Y, h:i A') }}</span>
                            </div>
                            {{-- SOAP --}}
                            <div class="row g-2" style="font-size:12px">
                                @if ($visit->subjective)
                                    <div class="col-6"><strong style="color:#374151">S —</strong> <span
                                            class="text-muted">{{ $visit->subjective }}</span></div>
                                @endif
                                @if ($visit->objective)
                                    <div class="col-6"><strong style="color:#374151">O —</strong> <span
                                            class="text-muted">{{ $visit->objective }}</span></div>
                                @endif
                                @if ($visit->assessment)
                                    <div class="col-6"><strong style="color:#374151">A —</strong> <span
                                            class="text-muted">{{ $visit->assessment }}</span></div>
                                @endif
                                @if ($visit->plan)
                                    <div class="col-6"><strong style="color:#374151">P —</strong> <span
                                            class="text-muted">{{ $visit->plan }}</span></div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4" style="font-size:13px">
                            <i class="bi bi-person-badge d-block fs-3 mb-2"></i>No visit notes yet
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($isDoctor)
                <div class="col-12 col-lg-4">
                    <div class="section-card">
                        <div class="section-header">
                            <h6><i class="bi bi-pencil me-2 text-primary"></i>Add Visit Note</h6>
                        </div>
                        <div class="section-body">
                            <form method="POST" action="{{ route('ward.visits.store', $patient->id) }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label">Visit Type *</label>
                                        <select name="visit_type" class="form-select form-select-sm" required>
                                            <option>Morning Round</option>
                                            <option>Evening Round</option>
                                            <option>Emergency Visit</option>
                                            <option>Consultation</option>
                                            <option>Post-Op Review</option>
                                            <option>Follow-up</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">S — Subjective (Patient complaints)</label>
                                        <textarea name="subjective" rows="2" class="form-control form-control-sm" placeholder="What patient says..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">O — Objective (Exam findings)</label>
                                        <textarea name="objective" rows="2" class="form-control form-control-sm"
                                            placeholder="Your examination findings..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">A — Assessment (Diagnosis) *</label>
                                        <textarea name="assessment" rows="2" class="form-control form-control-sm" placeholder="Clinical impression..."
                                            required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">P — Plan *</label>
                                        <textarea name="plan" rows="2" class="form-control form-control-sm" placeholder="Treatment plan..."
                                            required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_discharge_ready" class="form-check-input"
                                                id="dready">
                                            <label class="form-check-label" for="dready" style="font-size:12px">Patient
                                                ready for discharge</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100 btn-sm"
                                            style="height:38px;font-size:13px">
                                            <i class="bi bi-save me-1"></i>Save Visit Note
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 5 — SUMMARY                                       --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="tab-summary" class="tab-pane">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="section-card">
                    <div class="section-header">
                        <h6><i class="bi bi-person me-2"></i>Patient Info</h6>
                    </div>
                    <div class="section-body">
                        <table class="table table-sm table-borderless mb-0" style="font-size:13px">
                            <tr>
                                <td class="text-muted">Name</td>
                                <td class="fw-medium">{{ $patient->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">MRN</td>
                                <td>{{ $patient->mrn }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Gender</td>
                                <td>{{ $patient->gender ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Age</td>
                                <td>{{ $patient->age ?? '—' }} yrs</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Blood Group</td>
                                <td>{{ $patient->blood_group ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Contact</td>
                                <td>{{ $patient->phone ?? '—' }}</td>
                            </tr>
                            @if ($bed)
                                <tr>
                                    <td class="text-muted">Bed</td>
                                    <td>{{ $bed->bed_number }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Ward</td>
                                    <td>{{ $bed->ward->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Admitted</td>
                                    <td>{{ \Carbon\Carbon::parse($bed->admitted_at)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Days</td>
                                    <td>{{ \Carbon\Carbon::parse($bed->admitted_at)->diffInDays(now()) }} days</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="section-card">
                    <div class="section-header">
                        <h6><i class="bi bi-bar-chart me-2"></i>Activity Summary</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div style="font-size:28px;font-weight:700;color:#1d4ed8">{{ $vitals->count() }}</div>
                                <div style="font-size:12px;color:#94a3b8">Vitals Recorded</div>
                            </div>
                            <div class="col-4">
                                <div style="font-size:28px;font-weight:700;color:#16a34a">{{ $nursingNotes->count() }}
                                </div>
                                <div style="font-size:12px;color:#94a3b8">Nursing Notes</div>
                            </div>
                            <div class="col-4">
                                <div style="font-size:28px;font-weight:700;color:#9333ea">{{ $visitNotes->count() }}</div>
                                <div style="font-size:12px;color:#94a3b8">Doctor Visits</div>
                            </div>
                            <div class="col-4">
                                <div style="font-size:28px;font-weight:700;color:#d97706">{{ $doctorOrders->count() }}
                                </div>
                                <div style="font-size:12px;color:#94a3b8">Doctor Orders</div>
                            </div>
                            <div class="col-4">
                                <div style="font-size:28px;font-weight:700;color:#dc2626">{{ $pendingOrders }}</div>
                                <div style="font-size:12px;color:#94a3b8">Pending Orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function switchTab(name, btn) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            btn.classList.add('active');
        }
    </script>
@endpush

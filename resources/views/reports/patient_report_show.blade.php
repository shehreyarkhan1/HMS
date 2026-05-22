@extends('layouts.master')

@section('title', 'Patient Report — ' . $patient->name)
@section('page-title', 'Reports — Patient Report')
@section('breadcrumb', 'Home / Reports / Patients / ' . $patient->mrn)

@push('styles')
<style>
/* ── Base (matches your existing system) ───────────────────────────── */
.stat-pill {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 20px;
    cursor: pointer;
    transition: border-color .15s, box-shadow .15s
}
.stat-pill:hover  { border-color: #93c5fd }
.stat-pill.active { border-color: #1d4ed8; box-shadow: 0 0 0 3px #dbeafe }
.stat-pill-label  { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em }
.stat-pill-value  { font-size: 22px; font-weight: 700; color: #1e293b }

.card-section         { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden }
.card-section-header  {
    padding: 14px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px
}

.table-clean th {
    font-size: 11px; font-weight: 600; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .05em;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 10px 16px !important; background: #f8fafc
}
.table-clean td {
    font-size: 13px; color: #374151;
    padding: 12px 16px !important; vertical-align: middle;
    border-bottom: 1px solid #f1f5f9 !important
}
.table-clean tr:last-child td { border-bottom: none !important }
.table-clean tr:hover td { background: #fafafa }

/* ── Patient header card ──────────────────────────────────────────── */
.patient-hero {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 20px
}
.patient-avatar {
    width: 54px; height: 54px; border-radius: 50%;
    background: #dbeafe; color: #1d4ed8;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; flex-shrink: 0
}
.patient-hero h4 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 2px }
.patient-meta-row { display: flex; flex-wrap: wrap; gap: 6px 20px; margin-top: 8px }
.meta-chip { font-size: 12px; color: #64748b }
.meta-chip strong { color: #1e293b; font-weight: 600 }

/* ── Status / priority badges (reused from your existing files) ───── */
.badge-Active    { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Admitted  { background:#dbeafe;color:#1e40af;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Discharged{ background:#f1f5f9;color:#475569;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Deceased  { background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Completed { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Pending   { background:#fef9c3;color:#854d0e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Cancelled { background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Dispensed { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Verified  { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Reported  { background:#d1fae5;color:#065f46;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Fulfilled { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Paid      { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Unpaid    { background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Partial   { background:#dbeafe;color:#1e40af;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-IPD       { background:#fef3c7;color:#92400e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-OPD       { background:#e0f2fe;color:#0369a1;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Emergency { background:#fee2e2;color:#991b1b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
.badge-Scheduled { background:#dbeafe;color:#1e40af;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }

.priority-STAT    { background:#fee2e2;color:#dc2626;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:700;letter-spacing:.04em }
.priority-Urgent  { background:#fef3c7;color:#d97706;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:600 }
.priority-Routine { background:#f1f5f9;color:#64748b;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500 }

.flag-Normal       { color: #16a34a; font-weight: 600 }
.flag-High         { color: #dc2626; font-weight: 600 }
.flag-Low          { color: #d97706; font-weight: 600 }
.flag-Critical-High{ color: #dc2626; font-weight: 700 }
.flag-Critical-Low { color: #d97706; font-weight: 700 }

/* ── Tab navigation ───────────────────────────────────────────────── */
.report-tabs {
    display: flex; gap: 2px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 20px;
    overflow-x: auto; padding-bottom: 1px
}
.report-tab {
    padding: 9px 16px; font-size: 13px; font-weight: 500;
    color: #64748b; cursor: pointer; white-space: nowrap;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
    transition: color .15s, border-color .15s;
    background: none; border-left: none; border-right: none; border-top: none;
    display: flex; align-items: center; gap: 5px
}
.report-tab:hover { color: #1e293b }
.report-tab.active { color: #1d4ed8; border-bottom-color: #1d4ed8 }
.report-tab .tab-count {
    background: #f1f5f9; color: #64748b;
    font-size: 10px; padding: 1px 6px; border-radius: 10px; font-weight: 600
}
.report-tab.active .tab-count { background: #dbeafe; color: #1d4ed8 }

/* ── Tab sections ─────────────────────────────────────────────────── */
.tab-section { display: none }
.tab-section.active { display: block }

/* ── Timeline ─────────────────────────────────────────────────────── */
.tl-wrap { position: relative; padding-left: 32px }
.tl-wrap::before {
    content: ''; position: absolute; left: 12px; top: 4px; bottom: 0;
    width: 2px; background: #e2e8f0
}
.tl-item { position: relative; margin-bottom: 16px }
.tl-dot {
    position: absolute; left: -24px; top: 8px;
    width: 14px; height: 14px; border-radius: 50%;
    border: 2.5px solid #fff; box-shadow: 0 0 0 2px currentColor
}
.tl-card {
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 10px; padding: 12px 16px
}
.tl-card-title { font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 2px }
.tl-card-date  { font-size: 11px; color: #94a3b8; margin-bottom: 6px }
.tl-card-sub   { font-size: 12px; color: #64748b; line-height: 1.5 }
.tl-badges     { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px }
.tl-badge {
    font-size: 10px; padding: 2px 8px; border-radius: 10px;
    background: #f1f5f9; color: #475569; font-weight: 500
}

/* ── Alert boxes ──────────────────────────────────────────────────── */
.rpt-alert {
    border-radius: 10px; padding: 10px 16px;
    font-size: 13px; display: flex; align-items: center; gap: 8px;
    margin-bottom: 14px
}
.rpt-alert-warning { background: #fef9c3; border: 1px solid #fde047; color: #854d0e }
.rpt-alert-danger  { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b }

/* ── Billing summary ──────────────────────────────────────────────── */
.bill-line {
    display: flex; justify-content: space-between;
    padding: 8px 0; border-bottom: 1px solid #f1f5f9;
    font-size: 13px; color: #374151
}
.bill-line:last-child { border-bottom: none }
.bill-total {
    display: flex; justify-content: space-between;
    padding: 10px 0 2px; font-size: 16px; font-weight: 700; color: #1e293b
}

/* ── OT detail card ───────────────────────────────────────────────── */
.ot-detail-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px 28px;
    font-size: 13px
}
.ot-detail-grid .ot-label { color: #94a3b8; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em; margin-bottom: 1px }
.ot-detail-grid .ot-value { color: #1e293b; font-weight: 500 }
.ot-full { grid-column: 1 / -1 }

/* ── Print styles ─────────────────────────────────────────────────── */
@media print {
    .no-print { display: none !important }
    .tab-section { display: block !important }
    .report-tabs { display: none }
    .card-section { box-shadow: none }
    .patient-hero { border: 1px solid #ccc }
    body { font-size: 12px }
}
</style>
@endpush

@section('content')

{{-- ── Back + Print bar ──────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-3 no-print">
    <a href="{{ route('reports.patients.index') }}"
       class="btn btn-sm btn-outline-secondary"
       style="font-size:13px;display:inline-flex;align-items:center;gap:5px">
        <i class="bi bi-arrow-left"></i> Back to Search
    </a>
    <div class="d-flex gap-2">
        <button onclick="window.print()"
                class="btn btn-sm btn-outline-primary"
                style="font-size:13px;display:inline-flex;align-items:center;gap:5px">
            <i class="bi bi-printer-fill"></i> Print / PDF
        </button>
    </div>
</div>

{{-- ── Alerts ─────────────────────────────────────────────────────────────── --}}
@if($stats['abnormal_results'] > 0)
    <div class="rpt-alert rpt-alert-warning no-print">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <strong>{{ $stats['abnormal_results'] }} abnormal lab result(s)</strong> found in this patient's history.
    </div>
@endif
@if($stats['critical_reports'] > 0)
    <div class="rpt-alert rpt-alert-danger no-print">
        <i class="bi bi-radioactive fs-5"></i>
        <strong>{{ $stats['critical_reports'] }} critical radiology finding(s)</strong> — doctor notification required.
    </div>
@endif

{{-- ── Patient Hero Card ───────────────────────────────────────────────────── --}}
<div class="patient-hero">
    <div class="d-flex align-items-start gap-3 flex-wrap">
        <div class="patient-avatar">
            <i class="bi bi-person-fill"></i>
        </div>
        <div style="flex:1;min-width:200px">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h4>{{ $patient->name }}</h4>
                <span class="badge-{{ $patient->status }}">{{ $patient->status }}</span>
                <span class="badge-{{ $patient->patient_type }}">{{ $patient->patient_type }}</span>
            </div>
            <div style="font-size:12px;color:#94a3b8;margin-top:1px">
                {{ $patient->mrn }} &nbsp;·&nbsp; Registered {{ $patient->created_at->format('d M Y') }}
            </div>
            <div class="patient-meta-row">
                <div class="meta-chip"><strong>Age:</strong>
                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} yrs ({{ $patient->gender }})
                </div>
                @if($patient->blood_group)
                    <div class="meta-chip"><strong>Blood Group:</strong>
                        <span style="color:#dc2626;font-weight:700">{{ $patient->blood_group }}</span>
                    </div>
                @endif
                @if($patient->cnic)
                    <div class="meta-chip"><strong>CNIC:</strong> {{ $patient->cnic }}</div>
                @endif
                <div class="meta-chip"><strong>Phone:</strong> {{ $patient->phone }}</div>
                @if($patient->city)
                    <div class="meta-chip"><strong>City:</strong> {{ $patient->city }}</div>
                @endif
                @if($patient->emergency_contact)
                    <div class="meta-chip"><strong>Emergency:</strong> {{ $patient->emergency_contact }}
                        @if($patient->emergency_relation)({{ $patient->emergency_relation }})@endif
                    </div>
                @endif
            </div>
        </div>
        @if($patient->doctor)
            <div style="text-align:right;min-width:140px">
                <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.04em">
                    Assigned Doctor
                </div>
                <div style="font-size:14px;font-weight:600;color:#1e293b;margin-top:3px">
                    {{ $patient->doctor->employee->first_name }} {{ $patient->doctor->employee->last_name }}
                </div>
                <div style="font-size:12px;color:#64748b">{{ $patient->doctor->specialization }}</div>
            </div>
        @endif
    </div>

    @if($activeBed)
        <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9;
                    background:#eff6ff;border-radius:8px;padding:10px 14px;display:inline-flex;gap:10px;align-items:center">
            <i class="bi bi-hospital-fill text-primary"></i>
            <span style="font-size:13px;color:#1e293b">
                Currently admitted in <strong>{{ $activeBed->ward->name }}</strong> ·
                Bed <strong>{{ $activeBed->bed_number }}</strong> ·
                Since {{ \Carbon\Carbon::parse($activeBed->admitted_at)->format('d M Y') }}
            </span>
        </div>
    @endif
</div>

{{-- ── Summary Stats (clickable, switch tabs) ────────────────────────────── --}}
<div class="row g-3 mb-4 no-print">
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill active" onclick="switchTab('timeline', this)" id="pill-timeline">
            <div class="stat-pill-label">Timeline</div>
            <div class="stat-pill-value" style="color:#1d4ed8">{{ $timeline->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('appointments', this)" id="pill-appointments">
            <div class="stat-pill-label">Appointments</div>
            <div class="stat-pill-value" style="color:#0891b2">{{ $stats['appointments'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('lab', this)" id="pill-lab">
            <div class="stat-pill-label">Lab
                @if($stats['abnormal_results'] > 0)
                    <span style="color:#dc2626">⚠ {{ $stats['abnormal_results'] }} abnormal</span>
                @endif
            </div>
            <div class="stat-pill-value" style="color:#d97706">{{ $stats['lab_orders'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('radiology', this)" id="pill-radiology">
            <div class="stat-pill-label">Radiology
                @if($stats['critical_reports'] > 0)
                    <span style="color:#dc2626">⚠ Critical</span>
                @endif
            </div>
            <div class="stat-pill-value" style="color:#7c3aed">{{ $stats['radiology_orders'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('pharmacy', this)" id="pill-pharmacy">
            <div class="stat-pill-label">Prescriptions</div>
            <div class="stat-pill-value" style="color:#059669">{{ $stats['prescriptions'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('ot', this)" id="pill-ot">
            <div class="stat-pill-label">Surgeries</div>
            <div class="stat-pill-value" style="color:#dc2626">{{ $stats['ot_surgeries'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('blood', this)" id="pill-blood">
            <div class="stat-pill-label">Blood Bank</div>
            <div class="stat-pill-value" style="color:#be123c">{{ $stats['blood_requests'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-pill" onclick="switchTab('billing', this)" id="pill-billing">
            <div class="stat-pill-label">Total Billed</div>
            <div class="stat-pill-value" style="color:{{ $stats['total_due'] > 0 ? '#dc2626' : '#16a34a' }};font-size:17px">
                Rs {{ number_format($stats['total_billed'], 0) }}
            </div>
        </div>
    </div>
</div>

{{-- ── Tab Navigation ──────────────────────────────────────────────────────── --}}
<div class="report-tabs no-print">
    <button class="report-tab active" onclick="switchTab('timeline', null)" data-tab="timeline">
        <i class="bi bi-clock-history"></i> Timeline
        <span class="tab-count">{{ $timeline->count() }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('appointments', null)" data-tab="appointments">
        <i class="bi bi-calendar-check"></i> Appointments
        <span class="tab-count">{{ $stats['appointments'] }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('lab', null)" data-tab="lab">
        <i class="bi bi-flask"></i> Lab Results
        <span class="tab-count">{{ $stats['lab_orders'] }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('radiology', null)" data-tab="radiology">
        <i class="bi bi-radioactive"></i> Radiology
        <span class="tab-count">{{ $stats['radiology_orders'] }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('pharmacy', null)" data-tab="pharmacy">
        <i class="bi bi-capsule-pill"></i> Pharmacy
        <span class="tab-count">{{ $stats['prescriptions'] }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('ot', null)" data-tab="ot">
        <i class="bi bi-activity"></i> OT / Surgery
        <span class="tab-count">{{ $stats['ot_surgeries'] }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('blood', null)" data-tab="blood">
        <i class="bi bi-droplet"></i> Blood Bank
        <span class="tab-count">{{ $stats['blood_requests'] }}</span>
    </button>
    <button class="report-tab" onclick="switchTab('billing', null)" data-tab="billing">
        <i class="bi bi-receipt"></i> Billing
        <span class="tab-count">{{ $stats['bills'] }}</span>
    </button>
    @if($bedHistory->count())
        <button class="report-tab" onclick="switchTab('admission', null)" data-tab="admission">
            <i class="bi bi-hospital"></i> Admission
        </button>
    @endif
    @if($mortuaryRecord)
        <button class="report-tab" onclick="switchTab('mortuary', null)" data-tab="mortuary">
            <i class="bi bi-file-earmark-medical"></i> Mortuary
        </button>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 1 — TIMELINE
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section active" id="tab-timeline">
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-clock-history me-2 text-primary"></i>Complete Patient History — Chronological
            </span>
            <span style="font-size:12px;color:#94a3b8">{{ $timeline->count() }} events total</span>
        </div>
        <div class="p-4">
            @forelse($timeline as $event)
                <div class="tl-item">
                    <div class="tl-dot" style="color:{{ $event['color'] }};background:{{ $event['color'] }}"></div>
                    <div class="tl-card">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <div class="tl-card-title">
                                    <i class="bi {{ $event['icon'] }} me-1" style="color:{{ $event['color'] }}"></i>
                                    {{ $event['title'] }}
                                </div>
                                <div class="tl-card-date">
                                    {{ $event['datetime'] ? \Carbon\Carbon::parse($event['datetime'])->format('d M Y · h:i A') : '—' }}
                                </div>
                                @if($event['subtitle'])
                                    <div class="tl-card-sub">{{ $event['subtitle'] }}</div>
                                @endif
                                @if(!empty($event['badges']))
                                    <div class="tl-badges">
                                        @foreach(array_filter($event['badges']) as $b)
                                            <span class="tl-badge">{{ $b }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4" style="color:#94a3b8;font-size:13px">
                    No history found for this patient.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 2 — APPOINTMENTS
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-appointments">
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-calendar-check-fill me-2 text-primary"></i>All Appointments
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date / Time</th>
                        <th>Doctor</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Token</th>
                        <th>Reason</th>
                        <th>Follow-up</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $a)
                        <tr>
                            <td><code style="font-size:12px;color:#6366f1">APT-{{ str_pad($a->id, 4, '0', STR_PAD_LEFT) }}</code></td>
                            <td style="font-size:12px;color:#64748b">
                                {{ \Carbon\Carbon::parse($a->appointment_date)->format('d M Y') }}
                                @if($a->appointment_time)
                                    <br><span style="color:#6366f1">{{ substr($a->appointment_time, 0, 5) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($a->doctor)
                                    <div style="font-weight:500;color:#1e293b">{{ $a->doctor->employee->first_name }} {{ $a->doctor->employee->last_name }}</div>
                                    <div style="font-size:11px;color:#94a3b8">{{ $a->doctor->specialization }}</div>
                                @else
                                    <span style="color:#94a3b8">—</span>
                                @endif
                            </td>
                            <td><span class="badge-{{ $a->type }}">{{ $a->type }}</span></td>
                            <td><span class="badge-{{ str_replace(' ', '', $a->status) }}">{{ $a->status }}</span></td>
                            <td style="text-align:center">
                                @if($a->token_number)
                                    <span style="background:#eff6ff;color:#1d4ed8;font-size:12px;padding:2px 8px;border-radius:8px;font-weight:600">
                                        #{{ $a->token_number }}
                                    </span>
                                @else —
                                @endif
                            </td>
                            <td style="font-size:12px;color:#64748b;max-width:160px">
                                {{ $a->reason ? \Str::limit($a->reason, 60) : '—' }}
                            </td>
                            <td style="font-size:12px;color:#64748b">
                                {{ $a->follow_up_date ? \Carbon\Carbon::parse($a->follow_up_date)->format('d M Y') : '—' }}
                            </td>
                            <td style="font-size:12px;color:#64748b;max-width:140px">
                                {{ $a->notes ? \Str::limit($a->notes, 50) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-4" style="color:#94a3b8">No appointments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 3 — LAB RESULTS
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-lab">
    @forelse($labOrders as $lo)
        <div class="card-section mb-3">
            <div class="card-section-header">
                <div>
                    <code style="font-size:13px;color:#6366f1;font-weight:600">{{ $lo->order_number }}</code>
                    <span class="ms-2 priority-{{ $lo->priority }}">{{ $lo->priority }}</span>
                    <span class="ms-2 badge-{{ str_replace(' ', '', $lo->status) }}">{{ $lo->status }}</span>
                </div>
                <span style="font-size:12px;color:#94a3b8">
                    {{ \Carbon\Carbon::parse($lo->order_date)->format('d M Y · h:i A') }}
                    @if($lo->doctor) · {{ $lo->doctor->employee->first_name }} {{ $lo->doctor->employee->last_name }} @endif
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Category</th>
                            <th>Sample</th>
                            <th>Result</th>
                            <th>Unit</th>
                            <th>Normal Range</th>
                            <th>Flag</th>
                            <th>Reported</th>
                            <th>Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lo->items as $item)
                            <tr class="{{ $item->result && $item->result->is_abnormal ? 'table-warning' : '' }}">
                                <td style="font-weight:500;color:#1e293b">{{ $item->labTest->name }}</td>
                                <td style="font-size:12px;color:#64748b">{{ $item->labTest->category->name ?? '—' }}</td>
                                <td style="font-size:12px;color:#64748b">{{ $item->labSample->sampleType->name ?? '—' }}</td>
                                <td>
                                    @if($item->result)
                                        <span style="font-weight:600;{{ $item->result->is_abnormal ? 'color:#dc2626' : 'color:#16a34a' }}">
                                            {{ $item->result->result_value ?? '—' }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8">Pending</span>
                                    @endif
                                </td>
                                <td style="font-size:12px;color:#64748b">{{ $item->result->unit ?? $item->labTest->unit ?? '—' }}</td>
                                <td style="font-size:12px;color:#64748b">{{ $item->result->normal_range ?? $item->labTest->normal_range ?? '—' }}</td>
                                <td>
                                    @if($item->result && $item->result->flag)
                                        <span class="flag-{{ str_replace(' ', '-', $item->result->flag) }}">
                                            {{ $item->result->flag }}
                                        </span>
                                    @else —
                                    @endif
                                </td>
                                <td style="font-size:11px;color:#94a3b8">
                                    {{ $item->result?->reported_at ? \Carbon\Carbon::parse($item->result->reported_at)->format('d M Y') : '—' }}
                                </td>
                                <td style="font-size:11px">
                                    @if($item->result?->is_verified)
                                        <span style="color:#16a34a;font-weight:600">✓ Yes</span>
                                    @else
                                        <span style="color:#94a3b8">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card-section">
            <div class="text-center py-5" style="color:#94a3b8">
                <i class="bi bi-flask" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                No lab orders found.
            </div>
        </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 4 — RADIOLOGY
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-radiology">
    @forelse($radiologyOrders as $ro)
        <div class="card-section mb-3">
            <div class="card-section-header">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <code style="font-size:13px;color:#6366f1;font-weight:600">{{ $ro->order_number }}</code>
                    <span class="priority-{{ $ro->priority }}">{{ $ro->priority }}</span>
                    <span class="badge-{{ str_replace(' ', '', $ro->status) }}">{{ $ro->status }}</span>
                    @if($ro->items->contains(fn($i) => $i->report && $i->report->is_critical))
                        <span style="background:#fee2e2;color:#dc2626;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:700">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Critical
                        </span>
                    @endif
                </div>
                <span style="font-size:12px;color:#94a3b8">
                    {{ \Carbon\Carbon::parse($ro->order_date)->format('d M Y · h:i A') }}
                    @if($ro->doctor) · {{ $ro->doctor->employee->first_name }} {{ $ro->doctor->employee->last_name }} @endif
                </span>
            </div>

            @if($ro->clinical_history)
                <div style="padding:10px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:12px;color:#64748b">
                    <strong>Clinical History:</strong> {{ $ro->clinical_history }}
                </div>
            @endif

            @foreach($ro->items as $item)
                <div style="padding:16px 18px;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9' : '' }}">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span style="background:#f1f5f9;color:#475569;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500">
                            {{ $item->exam->modality->name ?? '—' }}
                        </span>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">{{ $item->exam->name }}</span>
                        @if($item->exam->bodyPart)
                            <span style="font-size:12px;color:#94a3b8">· {{ $item->exam->bodyPart->name }}</span>
                        @endif
                        @if($item->report && $item->report->is_critical)
                            <span style="background:#fee2e2;color:#dc2626;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:700">
                                ⚠ Critical Finding
                            </span>
                        @endif
                    </div>

                    @if($item->report)
                        <div class="row g-3">
                            @if($item->report->findings)
                                <div class="col-md-6">
                                    <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px">
                                        Findings
                                    </div>
                                    <div style="font-size:13px;color:#374151;line-height:1.6;background:#f8fafc;border-radius:8px;padding:10px 12px">
                                        {{ $item->report->findings }}
                                    </div>
                                </div>
                            @endif
                            @if($item->report->impression)
                                <div class="col-md-6">
                                    <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px">
                                        Impression
                                    </div>
                                    <div style="font-size:13px;color:#374151;line-height:1.6;background:#f8fafc;border-radius:8px;padding:10px 12px">
                                        {{ $item->report->impression }}
                                    </div>
                                </div>
                            @endif
                            @if($item->report->recommendations)
                                <div class="col-12">
                                    <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px">
                                        Recommendations
                                    </div>
                                    <div style="font-size:13px;color:#374151;background:#f0fdf4;border-radius:8px;padding:10px 12px">
                                        {{ $item->report->recommendations }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:8px">
                            @if($item->report->reported_at)
                                Reported: {{ \Carbon\Carbon::parse($item->report->reported_at)->format('d M Y h:i A') }}
                            @endif
                            @if($item->report->is_verified)
                                &nbsp;·&nbsp; <span style="color:#16a34a;font-weight:600">✓ Verified</span>
                            @endif
                        </div>
                        @if($item->report->is_critical && $item->report->critical_notes)
                            <div style="background:#fee2e2;border-radius:8px;padding:8px 12px;margin-top:8px;font-size:12px;color:#991b1b">
                                <strong>Critical Note:</strong> {{ $item->report->critical_notes }}
                            </div>
                        @endif
                    @else
                        <div style="font-size:12px;color:#94a3b8;padding:4px 0">Report not yet available.</div>
                    @endif

                    {{-- Radiology Images --}}
                    @if($item->images->count())
                        <div style="margin-top:10px">
                            <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px">
                                Images ({{ $item->images->count() }})
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($item->images as $img)
                                    <a href="{{ asset('storage/' . $img->file_path) }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:5px;background:#eff6ff;
                                              color:#1d4ed8;font-size:12px;padding:4px 10px;border-radius:8px;
                                              text-decoration:none;border:1px solid #bfdbfe">
                                        <i class="bi bi-image"></i>
                                        {{ $img->file_name ?? 'Image ' . $loop->iteration }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @empty
        <div class="card-section">
            <div class="text-center py-5" style="color:#94a3b8">
                <i class="bi bi-radioactive" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                No radiology orders found.
            </div>
        </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 5 — PHARMACY
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-pharmacy">
    @forelse($prescriptions as $rx)
        <div class="card-section mb-3">
            <div class="card-section-header">
                <div class="d-flex align-items-center gap-2">
                    <code style="font-size:13px;color:#6366f1;font-weight:600">{{ $rx->prescription_number }}</code>
                    <span class="badge-{{ $rx->status }}">{{ $rx->status }}</span>
                </div>
                <span style="font-size:12px;color:#94a3b8">
                    {{ \Carbon\Carbon::parse($rx->prescribed_date)->format('d M Y') }}
                    @if($rx->doctor) · Dr. {{ $rx->doctor->employee->first_name }} {{ $rx->doctor->employee->last_name }} @endif
                    @if($rx->valid_until) · Valid till {{ \Carbon\Carbon::parse($rx->valid_until)->format('d M Y') }} @endif
                </span>
            </div>
            @if($rx->diagnosis)
                <div style="padding:8px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:12px;color:#64748b">
                    <strong>Diagnosis:</strong> {{ $rx->diagnosis }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Generic</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Qty Rx</th>
                            <th>Dispensed</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rx->items as $item)
                            <tr>
                                <td style="font-weight:500;color:#1e293b">{{ $item->medicine->name }}</td>
                                <td style="font-size:12px;color:#64748b">{{ $item->medicine->generic_name ?? '—' }}</td>
                                <td style="font-weight:500">{{ $item->dosage }}</td>
                                <td style="font-size:12px;color:#374151">{{ $item->frequency }}</td>
                                <td style="font-size:12px;color:#64748b">{{ $item->duration_days }} days</td>
                                <td style="text-align:center;font-weight:600">{{ $item->quantity }}</td>
                                <td style="text-align:center">
                                    @if($item->dispensed_qty >= $item->quantity)
                                        <span style="color:#16a34a;font-weight:600">{{ $item->dispensed_qty }} ✓</span>
                                    @elseif($item->dispensed_qty > 0)
                                        <span style="color:#d97706;font-weight:600">{{ $item->dispensed_qty }} / {{ $item->quantity }}</span>
                                    @else
                                        <span style="color:#dc2626">0</span>
                                    @endif
                                </td>
                                <td style="font-size:12px;color:#64748b">{{ $item->instructions ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card-section">
            <div class="text-center py-5" style="color:#94a3b8">
                <i class="bi bi-capsule-pill" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                No prescriptions found.
            </div>
        </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 6 — OT / SURGERY
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-ot">
    @forelse($otSchedules as $ot)
        <div class="card-section mb-3">
            <div class="card-section-header">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <code style="font-size:13px;color:#6366f1;font-weight:600">{{ $ot->surgery_id }}</code>
                    <span class="badge-{{ str_replace(' ', '', $ot->status) }}">{{ $ot->status }}</span>
                    <span class="priority-{{ $ot->priority }}">{{ $ot->priority }}</span>
                </div>
                <span style="font-size:12px;color:#94a3b8">
                    {{ \Carbon\Carbon::parse($ot->scheduled_date)->format('d M Y') }}
                    @if($ot->actual_start_time) · Started {{ \Carbon\Carbon::parse($ot->actual_start_time)->format('h:i A') }} @endif
                </span>
            </div>
            <div class="p-4">
                <div class="ot-detail-grid">
                    <div>
                        <div class="ot-label">Procedure</div>
                        <div class="ot-value">{{ $ot->procedure_name }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Diagnosis</div>
                        <div class="ot-value">{{ $ot->diagnosis }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Surgeon</div>
                        <div class="ot-value">
                            {{ $ot->surgeon ? $ot->surgeon->employee->first_name . ' ' . $ot->surgeon->employee->last_name : '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="ot-label">Anesthesiologist</div>
                        <div class="ot-value">
                            {{ $ot->anesthesiologist ? $ot->anesthesiologist->employee->first_name . ' ' . $ot->anesthesiologist->employee->last_name : '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="ot-label">Anesthesia Type</div>
                        <div class="ot-value">{{ $ot->anesthesia_type ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="ot-label">OT Room</div>
                        <div class="ot-value">{{ $ot->otRoom ? $ot->otRoom->name : '—' }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Surgery Type</div>
                        <div class="ot-value">{{ $ot->surgery_type }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Estimated Duration</div>
                        <div class="ot-value">{{ $ot->estimated_duration_mins }} min</div>
                    </div>
                    @if($ot->actual_start_time && $ot->actual_end_time)
                        <div>
                            <div class="ot-label">Actual Duration</div>
                            <div class="ot-value">
                                {{ \Carbon\Carbon::parse($ot->actual_start_time)->diffInMinutes($ot->actual_end_time) }} min
                            </div>
                        </div>
                    @endif
                    <div>
                        <div class="ot-label">Consent</div>
                        <div class="ot-value">
                            @if($ot->consent_obtained)
                                <span style="color:#16a34a;font-weight:600">✓ Obtained</span>
                                @if($ot->consent_by)({{ $ot->consent_by }})@endif
                            @else
                                <span style="color:#dc2626">Not obtained</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="ot-label">Post-op Destination</div>
                        <div class="ot-value">{{ $ot->post_op_destination ?? '—' }}</div>
                    </div>
                    @if($ot->post_op_notes)
                        <div class="ot-full">
                            <div class="ot-label">Post-op Notes</div>
                            <div style="font-size:13px;color:#374151;background:#f8fafc;border-radius:8px;padding:10px 12px;margin-top:4px;line-height:1.6">
                                {{ $ot->post_op_notes }}
                            </div>
                        </div>
                    @endif
                    @if($ot->complications)
                        <div class="ot-full">
                            <div class="ot-label" style="color:#dc2626">Complications</div>
                            <div style="font-size:13px;color:#991b1b;background:#fee2e2;border-radius:8px;padding:10px 12px;margin-top:4px">
                                {{ $ot->complications }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- OT Team --}}
                @if($ot->teamMembers->count())
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
                        <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px">
                            OT Team
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($ot->teamMembers as $tm)
                                <span style="background:#f1f5f9;color:#475569;font-size:12px;padding:4px 10px;border-radius:8px">
                                    <strong>{{ $tm->role }}:</strong>
                                    @if($tm->doctor)
                                        Dr. {{ $tm->doctor->employee->first_name }}
                                    @elseif($tm->employee)
                                        {{ $tm->employee->first_name }} {{ $tm->employee->last_name }}
                                    @else —
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- OT Billing breakdown --}}
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9;font-size:12px;color:#64748b">
                    <strong style="color:#1e293b">Charges:</strong>
                    Surgeon Rs {{ number_format($ot->surgeon_fee, 0) }} ·
                    Anesthesia Rs {{ number_format($ot->anesthesia_fee, 0) }} ·
                    OT Room Rs {{ number_format($ot->ot_room_charges, 0) }} ·
                    Consumables Rs {{ number_format($ot->consumables_charges, 0) }}
                    &nbsp;·&nbsp; Billing: <span class="badge-{{ $ot->billing_status }}">{{ $ot->billing_status }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="card-section">
            <div class="text-center py-5" style="color:#94a3b8">
                <i class="bi bi-activity" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                No surgery records found.
            </div>
        </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 7 — BLOOD BANK
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-blood">
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-droplet-fill me-2" style="color:#be123c"></i>Blood Bank Records
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Blood Group</th>
                        <th>Component</th>
                        <th>Units Req.</th>
                        <th>Units Approved</th>
                        <th>Urgency</th>
                        <th>Status</th>
                        <th>Crossmatch</th>
                        <th>Indication</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bloodRequests as $br)
                        <tr>
                            <td><code style="font-size:12px;color:#6366f1">{{ $br->request_id }}</code></td>
                            <td style="font-size:12px;color:#64748b">{{ $br->created_at->format('d M Y') }}</td>
                            <td style="font-size:12px;color:#64748b">
                                {{ $br->doctor ? $br->doctor->employee->first_name . ' ' . $br->doctor->employee->last_name : '—' }}
                            </td>
                            <td>
                                <span style="background:#fee2e2;color:#991b1b;font-size:12px;padding:2px 8px;border-radius:8px;font-weight:700">
                                    {{ $br->blood_group }}
                                </span>
                            </td>
                            <td style="font-size:12px;color:#374151">{{ $br->component }}</td>
                            <td style="text-align:center;font-weight:600">{{ $br->units_required }}</td>
                            <td style="text-align:center;font-weight:600;color:#16a34a">{{ $br->units_approved }}</td>
                            <td><span class="priority-{{ $br->urgency }}">{{ $br->urgency }}</span></td>
                            <td><span class="badge-{{ str_replace(' ', '', $br->status) }}">{{ $br->status }}</span></td>
                            <td>
                                @foreach($br->crossmatches as $cx)
                                    <span style="background:{{ $cx->result === 'Compatible' ? '#dcfce7' : ($cx->result === 'Incompatible' ? '#fee2e2' : '#fef9c3') }};
                                                 color:{{ $cx->result === 'Compatible' ? '#166534' : ($cx->result === 'Incompatible' ? '#991b1b' : '#854d0e') }};
                                                 font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500">
                                        {{ $cx->result }}
                                    </span>
                                @endforeach
                                @if($br->crossmatches->isEmpty()) <span style="color:#94a3b8">—</span> @endif
                            </td>
                            <td style="font-size:12px;color:#64748b;max-width:140px">{{ \Str::limit($br->indication, 50) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4" style="color:#94a3b8">No blood bank requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 8 — BILLING
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="tab-section" id="tab-billing">

    {{-- Overall summary --}}
    <div class="card-section mb-3">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-wallet2 me-2 text-primary"></i>Overall Billing Summary
            </span>
        </div>
        <div class="p-4" style="max-width:500px">
            <div class="bill-line"><span>Total Subtotal</span><span>Rs {{ number_format($billingTotals['subtotal'], 2) }}</span></div>
            <div class="bill-line"><span>Total Discounts</span><span style="color:#16a34a">- Rs {{ number_format($billingTotals['discount'], 2) }}</span></div>
            <div class="bill-line"><span>Total Tax</span><span>Rs {{ number_format($billingTotals['tax'], 2) }}</span></div>
            <div class="bill-total"><span>Net Amount</span><span>Rs {{ number_format($billingTotals['net'], 2) }}</span></div>
            <div class="bill-line" style="margin-top:8px"><span>Total Paid</span><span style="color:#16a34a">Rs {{ number_format($billingTotals['paid'], 2) }}</span></div>
            <div class="bill-line" style="font-weight:600">
                <span>Outstanding Due</span>
                <span style="color:{{ $billingTotals['due'] > 0 ? '#dc2626' : '#16a34a' }}">
                    Rs {{ number_format($billingTotals['due'], 2) }}
                    @if($billingTotals['due'] <= 0) <span class="badge-Paid ms-1">Cleared</span> @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Per-bill cards --}}
    @forelse($bills as $bill)
        <div class="card-section mb-3">
            <div class="card-section-header">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <code style="font-size:13px;color:#6366f1;font-weight:600">{{ $bill->bill_number }}</code>
                    <span class="badge-{{ $bill->bill_type }}">{{ $bill->bill_type }}</span>
                    <span class="badge-{{ $bill->status }}">{{ $bill->status }}</span>
                    <span class="badge-{{ $bill->payment_status }}">{{ $bill->payment_status }}</span>
                </div>
                <span style="font-size:12px;color:#94a3b8">
                    {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') }}
                    @if($bill->createdBy) · {{ $bill->createdBy->name }} @endif
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th>Description</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:right">Unit Price</th>
                            <th style="text-align:right">Discount</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->items as $bi)
                            <tr>
                                <td>
                                    <span style="background:#f1f5f9;color:#475569;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500">
                                        {{ $bi->service_type }}
                                    </span>
                                </td>
                                <td style="color:#374151">{{ $bi->description }}</td>
                                <td style="text-align:center;font-size:12px">{{ $bi->quantity }}</td>
                                <td style="text-align:right;font-size:12px">Rs {{ number_format($bi->unit_price, 0) }}</td>
                                <td style="text-align:right;font-size:12px;color:#16a34a">
                                    {{ $bi->discount > 0 ? '- Rs ' . number_format($bi->discount, 0) : '—' }}
                                </td>
                                <td style="text-align:right;font-weight:600">Rs {{ number_format($bi->total_price, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc">
                            <td colspan="4"></td>
                            <td style="text-align:right;font-size:12px;color:#64748b;padding:8px 16px !important">Net Amount</td>
                            <td style="text-align:right;font-weight:700;font-size:14px;padding:8px 16px !important;color:#1e293b">
                                Rs {{ number_format($bill->net_amount, 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {{-- Payments --}}
            @if($bill->payments->count())
                <div style="padding:10px 18px;background:#f8fafc;border-top:1px solid #e2e8f0">
                    <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px">
                        Payment Receipts
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($bill->payments as $pay)
                            <span style="background:#dcfce7;color:#166534;font-size:12px;padding:4px 10px;border-radius:8px">
                                Rs {{ number_format($pay->amount, 0) }}
                                · {{ $pay->payment_method }}
                                · {{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}
                                @if($pay->reference_number)({{ $pay->reference_number }})@endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="card-section">
            <div class="text-center py-5" style="color:#94a3b8">
                <i class="bi bi-receipt" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                No billing records found.
            </div>
        </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 9 — ADMISSION
     ══════════════════════════════════════════════════════════════════════════ --}}
@if($bedHistory->count())
    <div class="tab-section" id="tab-admission">
        <div class="card-section">
            <div class="card-section-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-hospital-fill me-2 text-primary"></i>Ward / Bed Admission History
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Ward</th>
                            <th>Bed No.</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Admitted</th>
                            <th>Discharged</th>
                            <th>LOS</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bedHistory as $bed)
                            <tr>
                                <td style="font-weight:500;color:#1e293b">{{ $bed->ward->name }}</td>
                                <td>
                                    <span style="background:#eff6ff;color:#1d4ed8;font-size:12px;padding:2px 8px;border-radius:8px;font-weight:600">
                                        {{ $bed->bed_number }}
                                    </span>
                                </td>
                                <td style="font-size:12px;color:#64748b">{{ $bed->type }}</td>
                                <td>
                                    @php
                                        $bc = ['Available'=>'badge-Active','Occupied'=>'badge-Admitted','Reserved'=>'badge-Scheduled','Maintenance'=>'badge-Cancelled'];
                                    @endphp
                                    <span class="{{ $bc[$bed->status] ?? 'badge-Pending' }}">{{ $bed->status }}</span>
                                </td>
                                <td style="font-size:12px;color:#64748b">
                                    {{ $bed->admitted_at ? \Carbon\Carbon::parse($bed->admitted_at)->format('d M Y') : '—' }}
                                </td>
                                <td style="font-size:12px;color:#64748b">
                                    {{ $bed->discharged_at ? \Carbon\Carbon::parse($bed->discharged_at)->format('d M Y') : '—' }}
                                </td>
                                <td style="font-size:12px;font-weight:600;color:#1e293b">
                                    @if($bed->admitted_at && $bed->discharged_at)
                                        {{ \Carbon\Carbon::parse($bed->admitted_at)->diffInDays($bed->discharged_at) }} days
                                    @elseif($bed->admitted_at && $bed->status === 'Occupied')
                                        <span style="color:#1d4ed8">
                                            {{ \Carbon\Carbon::parse($bed->admitted_at)->diffInDays(now()) }} days (ongoing)
                                        </span>
                                    @else —
                                    @endif
                                </td>
                                <td style="font-size:12px;color:#64748b;max-width:160px">{{ $bed->notes ? \Str::limit($bed->notes, 60) : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 10 — MORTUARY (only for deceased patients)
     ══════════════════════════════════════════════════════════════════════════ --}}
@if($mortuaryRecord)
    <div class="tab-section" id="tab-mortuary">
        <div class="card-section">
            <div class="card-section-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-file-earmark-medical-fill me-2" style="color:#374151"></i>Mortuary Record
                </span>
                <code style="font-size:13px;color:#6366f1">{{ $mortuaryRecord->mortuary_id }}</code>
            </div>
            <div class="p-4">
                <div class="ot-detail-grid">
                    <div>
                        <div class="ot-label">Death Date & Time</div>
                        <div class="ot-value">{{ \Carbon\Carbon::parse($mortuaryRecord->death_datetime)->format('d M Y · h:i A') }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Death Location</div>
                        <div class="ot-value">{{ $mortuaryRecord->death_location }}{{ $mortuaryRecord->ward ? ' — ' . $mortuaryRecord->ward : '' }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Immediate Cause</div>
                        <div class="ot-value">{{ $mortuaryRecord->immediate_cause }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Manner of Death</div>
                        <div class="ot-value">{{ $mortuaryRecord->manner_of_death }}</div>
                    </div>
                    @if($mortuaryRecord->underlying_cause)
                        <div>
                            <div class="ot-label">Underlying Cause</div>
                            <div class="ot-value">{{ $mortuaryRecord->underlying_cause }}</div>
                        </div>
                    @endif
                    <div>
                        <div class="ot-label">Declared By</div>
                        <div class="ot-value">
                            {{ $mortuaryRecord->declaredBy ? 'Dr. ' . $mortuaryRecord->declaredBy->employee->first_name . ' ' . $mortuaryRecord->declaredBy->employee->last_name : '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="ot-label">Status</div>
                        <div class="ot-value">{{ $mortuaryRecord->status }}</div>
                    </div>
                    <div>
                        <div class="ot-label">Medico-Legal Case</div>
                        <div class="ot-value">{{ $mortuaryRecord->is_medico_legal ? '⚠ Yes — MLC: ' . $mortuaryRecord->mlc_number : 'No' }}</div>
                    </div>
                    @if($mortuaryRecord->nok_name)
                        <div>
                            <div class="ot-label">Next of Kin</div>
                            <div class="ot-value">{{ $mortuaryRecord->nok_name }} ({{ $mortuaryRecord->nok_relation }}) · {{ $mortuaryRecord->nok_phone }}</div>
                        </div>
                    @endif
                </div>

                {{-- Death Certificates --}}
                @if($mortuaryRecord->deathCertificates?->count())
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
                        <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px">
                            Death Certificates Issued
                        </div>
                        @foreach($mortuaryRecord->deathCertificates as $dc)
                            <div style="background:#f8fafc;border-radius:8px;padding:10px 14px;margin-bottom:8px;font-size:13px">
                                <strong>{{ $dc->certificate_number }}</strong> ·
                                {{ $dc->certificate_type }} ·
                                Issued to: {{ $dc->issued_to_name }} ({{ $dc->issued_to_relation }}) ·
                                {{ \Carbon\Carbon::parse($dc->issued_at)->format('d M Y') }}
                                @if($dc->is_verified)
                                    <span style="color:#16a34a;font-weight:600;margin-left:6px">✓ Verified</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
function switchTab(name, pillEl) {
    // hide all sections
    document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
    // deactivate all tabs
    document.querySelectorAll('.report-tab').forEach(t => t.classList.remove('active'));
    // deactivate all pills
    document.querySelectorAll('.stat-pill').forEach(p => p.classList.remove('active'));

    // activate section
    const sec = document.getElementById('tab-' + name);
    if (sec) sec.classList.add('active');

    // activate tab button
    const tab = document.querySelector('[data-tab="' + name + '"]');
    if (tab) tab.classList.add('active');

    // activate pill (either passed directly or look up by id)
    if (pillEl) {
        pillEl.classList.add('active');
    } else {
        const pill = document.getElementById('pill-' + name);
        if (pill) pill.classList.add('active');
    }

    // scroll to top smoothly
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endpush

@extends('layouts.master')

@section('title', 'Appointment — ' . $appointment->patient->name)
@section('page-title', 'Appointment Detail')
@section('breadcrumb', 'Home / Appointments / Detail')

@push('styles')
<style>
    .show-wrapper { max-width: 860px; margin: 0 auto; }

    /* ── Hero Banner ── */
    .appt-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .appt-hero::after {
        content: '';
        position: absolute; right: -30px; top: -30px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .appt-hero::before {
        content: '';
        position: absolute; right: 60px; bottom: -40px;
        width: 100px; height: 100px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .hero-token {
        width: 64px; height: 64px; border-radius: 14px;
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,.2);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hero-token-label { font-size: 9px; opacity: .6; letter-spacing: .05em; text-transform: uppercase; }
    .hero-token-num   { font-size: 22px; font-weight: 800; line-height: 1; }
    .hero-main { flex: 1; }
    .hero-patient-name { font-size: 20px; font-weight: 700; }
    .hero-meta { font-size: 12px; opacity: .75; margin-top: 4px; }
    .hero-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
    .hero-date-block {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 10px;
        padding: 8px 16px; text-align: center;
    }
    .hero-date-day   { font-size: 26px; font-weight: 800; line-height: 1; }
    .hero-date-month { font-size: 11px; opacity: .7; text-transform: uppercase; letter-spacing: .05em; }
    .hero-time       { font-size: 13px; font-weight: 500; opacity: .85; }

    /* ── Info Card ── */
    .info-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .info-card-header {
        padding: 13px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;
        display: flex; align-items: center; gap: 10px;
    }
    .section-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
    .section-title { font-size: 13px; font-weight: 600; color: #1e293b; }
    .info-card-body { padding: 20px; }

    /* ── Key-value grid ── */
    .kv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .kv-item {}
    .kv-label { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .kv-value { font-size: 13px; font-weight: 500; color: #1e293b; }
    .kv-value.muted { color: #94a3b8; font-weight: 400; }

    /* ── Badges ── */
    .badge-status { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; white-space: nowrap; }
    .badge-Scheduled   { background: #eff6ff; color: #1d4ed8; }
    .badge-Confirmed   { background: #f0fdf4; color: #15803d; }
    .badge-In-Progress { background: #fef9c3; color: #854d0e; }
    .badge-Completed   { background: #dcfce7; color: #166534; }
    .badge-Cancelled   { background: #fee2e2; color: #991b1b; }
    .badge-No-show     { background: #f3f4f6; color: #6b7280; }

    .type-pill { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
    .type-OPD       { background: #eff6ff; color: #1d4ed8; }
    .type-IPD       { background: #f0fdf4; color: #15803d; }
    .type-Emergency { background: #fff7ed; color: #c2410c; }
    .type-Follow-up { background: #faf5ff; color: #7e22ce; }

    /* ── Quick Status Form ── */
    .status-form { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .status-select-large {
        height: 38px; padding: 0 14px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 13px; background: #f8fafc; color: #1e293b;
        cursor: pointer;
    }
    .status-select-large:focus { outline: none; border-color: #3b82f6; }

    /* ── Overdue alert ── */
    .overdue-bar {
        background: #fff7ed; border: 1px solid #fed7aa;
        border-radius: 10px; padding: 10px 16px;
        font-size: 13px; color: #c2410c;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 20px;
    }

    /* ── Action buttons ── */
    .btn-edit {
        height: 38px; padding: 0 20px;
        background: #f59e0b; color: #fff; border: none; border-radius: 8px;
        font-size: 13px; font-weight: 500;
        display: flex; align-items: center; gap: 6px;
        text-decoration: none; transition: opacity .15s;
    }
    .btn-edit:hover { opacity: .88; color: #fff; }
    .btn-back {
        height: 38px; padding: 0 16px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        background: #fff; color: #475569;
        font-size: 13px; font-weight: 500;
        display: flex; align-items: center; gap: 6px;
        text-decoration: none; transition: background .15s;
    }
    .btn-back:hover { background: #f8fafc; color: #1e293b; }
    .btn-delete {
        height: 38px; padding: 0 16px;
        border: 1px solid #fecaca; border-radius: 8px;
        background: #fff; color: #dc2626;
        font-size: 13px; font-weight: 500;
        display: flex; align-items: center; gap: 6px;
        cursor: pointer; transition: background .15s;
    }
    .btn-delete:hover { background: #fff5f5; }

    /* ── Patient mini card ── */
    .patient-mini {
        display: flex; align-items: center; gap: 14px;
        padding: 14px; background: #f8fafc;
        border: 1px solid #e2e8f0; border-radius: 10px;
        text-decoration: none; transition: background .15s;
    }
    .patient-mini:hover { background: #eff6ff; border-color: #bfdbfe; }
    .patient-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: #dbeafe; color: #1d4ed8;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="show-wrapper">

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4"
         style="border-radius:10px;font-size:13px">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Overdue warning --}}
    @if($appointment->is_overdue)
    <div class="overdue-bar">
        <i class="bi bi-exclamation-triangle-fill"></i>
        This appointment is <strong>overdue</strong> — status has not been updated.
    </div>
    @endif

    {{-- Hero --}}
    <div class="appt-hero">
        <div class="hero-token">
            <div class="hero-token-label">Token</div>
            <div class="hero-token-num">
                {{ $appointment->token_number ? '#'.$appointment->token_number : '—' }}
            </div>
        </div>
        <div class="hero-main">
            <div class="hero-patient-name">{{ $appointment->patient->name ?? 'Unknown Patient' }}</div>
            <div class="hero-meta">
                {{ $appointment->patient->mrn ?? '' }}
                @if($appointment->patient)
                    &nbsp;·&nbsp; {{ $appointment->patient->age }} yrs
                    &nbsp;·&nbsp; {{ $appointment->patient->gender }}
                @endif
                &nbsp;·&nbsp;
                <span class="type-pill type-{{ $appointment->type }}"
                      style="font-size:10px">{{ $appointment->type }}</span>
            </div>
            @if($appointment->reason)
            <div style="margin-top:8px;font-size:13px;opacity:.85">
                <i class="bi bi-chat-left-text me-1"></i>{{ $appointment->reason }}
            </div>
            @endif
        </div>
        <div class="hero-right">
            <div class="hero-date-block">
                <div class="hero-date-day">{{ $appointment->appointment_date->format('d') }}</div>
                <div class="hero-date-month">
                    {{ $appointment->appointment_date->format('M Y') }}
                </div>
            </div>
            @if($appointment->appointment_time)
            <div class="hero-time">
                <i class="bi bi-clock me-1"></i>{{ $appointment->formatted_time }}
                @if($appointment->duration_minutes)
                    · {{ $appointment->duration_minutes }}min
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Status Update --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="section-icon" style="background:#fef9c3;color:#854d0e">
                <i class="bi bi-lightning-fill"></i>
            </div>
            <span class="section-title">Quick Status Update</span>
        </div>
        <div class="info-card-body">
            <form method="POST" action="{{ route('appointments.status', $appointment->id) }}"
                  class="status-form">
                @csrf @method('PATCH')
                <span style="font-size:13px;color:#64748b">Current status:</span>
                <span class="badge-status badge-{{ $appointment->status }}">{{ $appointment->status }}</span>
                <i class="bi bi-arrow-right" style="color:#cbd5e1"></i>
                <select name="status" class="status-select-large">
                    @foreach(['Scheduled','Confirmed','In-Progress','Completed','Cancelled','No-show'] as $s)
                        <option value="{{ $s }}" {{ $appointment->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary px-3" style="height:38px;font-size:13px">
                    <i class="bi bi-check-lg me-1"></i>Update
                </button>
            </form>

            @if($appointment->consulted_at)
            <div style="margin-top:10px;font-size:12px;color:#64748b">
                <i class="bi bi-clock-history me-1"></i>
                Consultation started: {{ $appointment->consulted_at->format('d M Y, h:i A') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Patient Info --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="section-icon" style="background:#dbeafe;color:#1d4ed8">
                <i class="bi bi-person-fill"></i>
            </div>
            <span class="section-title">Patient</span>
        </div>
        <div class="info-card-body">
            @if($appointment->patient)
            <a href="{{ route('patients.show', $appointment->patient_id) }}" class="patient-mini">
                <div class="patient-avatar">{{ $appointment->patient->initials }}</div>
                <div>
                    <div style="font-weight:600;color:#1e293b">{{ $appointment->patient->name }}</div>
                    <div style="font-size:12px;color:#64748b">
                        {{ $appointment->patient->mrn }}
                        &nbsp;·&nbsp; {{ $appointment->patient->phone }}
                        &nbsp;·&nbsp; {{ $appointment->patient->city }}
                    </div>
                </div>
                <i class="bi bi-arrow-right ms-auto" style="color:#94a3b8"></i>
            </a>
            @else
            <span style="color:#94a3b8;font-size:13px">Patient not found</span>
            @endif
        </div>
    </div>

    {{-- Appointment Details --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="section-icon" style="background:#dcfce7;color:#15803d">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <span class="section-title">Appointment Details</span>
        </div>
        <div class="info-card-body">
            <div class="kv-grid">
                <div class="kv-item">
                    <div class="kv-label">Doctor</div>
                    <div class="kv-value">{{ $appointment->doctor->name ?? '—' }}</div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Date</div>
                    <div class="kv-value">{{ $appointment->formatted_date }}</div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Time</div>
                    <div class="kv-value">{{ $appointment->formatted_time }}</div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Duration</div>
                    <div class="kv-value">
                        {{ $appointment->duration_minutes ? $appointment->duration_minutes . ' minutes' : '—' }}
                    </div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Type</div>
                    <div class="kv-value">
                        <span class="type-pill type-{{ $appointment->type }}">{{ $appointment->type }}</span>
                    </div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Status</div>
                    <div class="kv-value">
                        <span class="badge-status badge-{{ $appointment->status }}">{{ $appointment->status }}</span>
                    </div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Token</div>
                    <div class="kv-value">
                        {{ $appointment->token_number ? '#' . $appointment->token_number : '—' }}
                    </div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Follow-up Date</div>
                    <div class="kv-value {{ !$appointment->follow_up_date ? 'muted' : '' }}">
                        {{ $appointment->follow_up_date?->format('d M Y') ?? '—' }}
                    </div>
                </div>
            </div>

            @if($appointment->reason)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9">
                <div class="kv-label">Chief Complaint / Reason</div>
                <div class="kv-value" style="margin-top:4px">{{ $appointment->reason }}</div>
            </div>
            @endif

            @if($appointment->notes)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9">
                <div class="kv-label">Notes</div>
                <div style="font-size:13px;color:#374151;margin-top:4px;line-height:1.6">
                    {{ $appointment->notes }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Cancellation Details (only if cancelled) --}}
    @if($appointment->status === 'Cancelled' && ($appointment->cancelled_by || $appointment->cancellation_reason))
    <div class="info-card">
        <div class="info-card-header">
            <div class="section-icon" style="background:#fee2e2;color:#991b1b">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <span class="section-title">Cancellation Details</span>
        </div>
        <div class="info-card-body">
            <div class="kv-grid">
                <div class="kv-item">
                    <div class="kv-label">Cancelled By</div>
                    <div class="kv-value">{{ $appointment->cancelled_by ?? '—' }}</div>
                </div>
                <div class="kv-item">
                    <div class="kv-label">Reason</div>
                    <div class="kv-value">{{ $appointment->cancellation_reason ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Action Bar --}}
    <div class="info-card" style="padding: 16px 20px">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex gap-2">
                <a href="{{ route('appointments.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> All Appointments
                </a>
                @if($appointment->patient)
                <a href="{{ route('patients.show', $appointment->patient_id) }}" class="btn-back">
                    <i class="bi bi-person"></i> Patient Profile
                </a>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn-edit">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form method="POST" action="{{ route('appointments.destroy', $appointment->id) }}"
                      onsubmit="return confirm('Delete this appointment?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
@extends('layouts.master')

@section('title', $ot->surgery_id . ' — Surgery Detail')
@section('page-title', 'Surgery Detail')
@section('breadcrumb', 'Home / OT / ' . $ot->surgery_id)

@push('styles')
<style>
    .detail-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; margin-bottom:20px;
    }
    .section-title {
        font-size:12px; font-weight:600; color:#374151; text-transform:uppercase;
        letter-spacing:.05em; padding-bottom:12px; margin-bottom:16px;
        border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;
    }
    .section-title i { color:#6366f1; }

    .detail-row { display:flex; justify-content:space-between; align-items:flex-start;
                  padding:8px 0; border-bottom:1px solid #f8fafc; font-size:13px; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { color:#94a3b8; font-size:12px; min-width:150px; }
    .detail-value { color:#1e293b; font-weight:500; text-align:right; }

    .status-badge {
        font-size:12px; padding:4px 12px; border-radius:20px;
        font-weight:600; display:inline-flex; align-items:center; gap:5px;
    }
    .status-Scheduled    { background:#dbeafe; color:#1e40af; }
    .status-Confirmed    { background:#e0f2fe; color:#0c4a6e; }
    .status-Preparing    { background:#fef9c3; color:#854d0e; }
    .status-In-Progress  { background:#dcfce7; color:#166534; }
    .status-Completed    { background:#f1f5f9; color:#475569; }
    .status-Postponed    { background:#fff7ed; color:#9a3412; }
    .status-Cancelled    { background:#fee2e2; color:#991b1b; }

    .priority-Emergency  { background:#fee2e2; color:#b91c1c; }
    .priority-Urgent     { background:#fff7ed; color:#c2410c; }
    .priority-Priority   { background:#e0f2fe; color:#0369a1; }
    .priority-Routine    { background:#f1f5f9; color:#64748b; }

    .info-banner {
        background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;
        padding:16px 20px; margin-bottom:20px;
    }

    .team-member-row {
        display:flex; align-items:center; gap:12px;
        padding:10px 14px; background:#f8fafc; border-radius:8px; margin-bottom:6px;
    }
    .member-avatar {
        width:34px; height:34px; border-radius:50%;
        background:#dbeafe; color:#1d4ed8; font-size:12px;
        font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .role-tag {
        font-size:10px; padding:2px 8px; border-radius:6px;
        background:#f5f3ff; color:#6d28d9; font-weight:600;
    }

    .check-indicator {
        font-size:11px; padding:3px 10px; border-radius:6px; font-weight:600;
    }
    .check-yes { background:#dcfce7; color:#166534; }
    .check-no  { background:#fee2e2; color:#991b1b; }
</style>
@endpush

@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div style="font-size:11px;color:#94a3b8;font-family:monospace;margin-bottom:4px">
            {{ $ot->surgery_id }}
        </div>
        <h5 style="font-size:18px;font-weight:700;color:#1e293b;margin:0">
            {{ $ot->procedure_name }}
        </h5>
        <div style="font-size:13px;color:#64748b;margin-top:2px">
            {{ $ot->patient->name }}
            <span style="color:#94a3b8">({{ $ot->patient->mrn }})</span>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">
        {{-- Status badge --}}
        <span class="status-badge status-{{ str_replace(' ', '-', $ot->status) }}">
            {{ $ot->status }}
        </span>
        <span class="status-badge priority-{{ $ot->priority }}" style="border-radius:6px">
            <i class="bi bi-flag-fill" style="font-size:9px"></i>
            {{ $ot->priority }}
        </span>

        @if($ot->isEditable())
        <a href="{{ route('ot.schedules.edit', $ot->id) }}"
           class="btn btn-sm btn-warning px-3" style="height:34px;font-size:13px">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endif
        <a href="{{ route('ot.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:34px;font-size:13px">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- ── LEFT COLUMN ──────────────────────────────────────────────────── --}}
    <div class="col-12 col-xl-8">

        {{-- Surgery Overview --}}
        <div class="info-banner">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em">Date</div>
                    <div style="font-size:14px;font-weight:700;color:#1e293b;margin-top:4px">
                        {{ $ot->scheduled_date->format('d M Y') }}
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em">Time</div>
                    <div style="font-size:14px;font-weight:700;color:#1e293b;margin-top:4px">
                        {{ \Carbon\Carbon::parse($ot->scheduled_time)->format('h:i A') }}
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em">Est. Duration</div>
                    <div style="font-size:14px;font-weight:700;color:#1e293b;margin-top:4px">
                        {{ $ot->estimated_duration_mins }} min
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em">OT Room</div>
                    <div style="font-size:14px;font-weight:700;color:#1e293b;margin-top:4px">
                        {{ $ot->otRoom?->room_code ?? '—' }}
                    </div>
                </div>
            </div>

            @if($ot->actual_start_time || $ot->actual_end_time)
            <hr style="border-color:#e2e8f0;margin:14px 0">
            <div class="row g-3 text-center">
                <div class="col-md-4">
                    <div style="font-size:11px;color:#94a3b8">Actual Start</div>
                    <div style="font-size:13px;font-weight:600;color:#166534">
                        {{ $ot->actual_start_time?->format('h:i A d M') ?? '—' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="font-size:11px;color:#94a3b8">Actual End</div>
                    <div style="font-size:13px;font-weight:600;color:#991b1b">
                        {{ $ot->actual_end_time?->format('h:i A d M') ?? '—' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="font-size:11px;color:#94a3b8">Actual Duration</div>
                    <div style="font-size:13px;font-weight:600;color:#1e293b">
                        {{ $ot->actual_duration_mins ? $ot->actual_duration_mins . ' min' : '—' }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Clinical Details --}}
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-clipboard2-pulse"></i>
                Clinical Details
            </div>
            <div class="detail-row">
                <span class="detail-label">Diagnosis</span>
                <span class="detail-value">{{ $ot->diagnosis }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Surgery Type</span>
                <span class="detail-value">{{ $ot->surgery_type }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Anesthesia</span>
                <span class="detail-value">{{ $ot->anesthesia_type ?? '—' }}</span>
            </div>
            @if($ot->procedure_details)
            <div class="mt-3">
                <div style="font-size:11px;color:#94a3b8;margin-bottom:6px">Procedure Details</div>
                <div style="background:#f8fafc;border-radius:8px;padding:12px;font-size:13px;color:#374151;line-height:1.6">
                    {!! nl2br(e($ot->procedure_details)) !!}
                </div>
            </div>
            @endif
            @if($ot->pre_op_instructions)
            <div class="mt-3">
                <div style="font-size:11px;color:#94a3b8;margin-bottom:6px">Pre-op Instructions</div>
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px;font-size:13px;color:#92400e;line-height:1.6">
                    {!! nl2br(e($ot->pre_op_instructions)) !!}
                </div>
            </div>
            @endif
        </div>

        {{-- Post-op (if completed) --}}
        @if($ot->status === 'Completed' && ($ot->post_op_notes || $ot->complications))
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-heart-pulse"></i>
                Post-operative Notes
            </div>
            @if($ot->post_op_notes)
            <div class="mb-3">
                <div style="font-size:11px;color:#94a3b8;margin-bottom:6px">Post-op Notes</div>
                <div style="background:#f0fdf4;border-radius:8px;padding:12px;font-size:13px;color:#166534;line-height:1.6">
                    {!! nl2br(e($ot->post_op_notes)) !!}
                </div>
            </div>
            @endif
            @if($ot->complications)
            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:6px">Complications</div>
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;font-size:13px;color:#991b1b;line-height:1.6">
                    {!! nl2br(e($ot->complications)) !!}
                </div>
            </div>
            @endif
            @if($ot->post_op_destination)
            <div class="detail-row mt-3">
                <span class="detail-label">Post-op Destination</span>
                <span class="detail-value">{{ $ot->post_op_destination }}</span>
            </div>
            @endif
        </div>
        @endif

        {{-- OT Team --}}
        @if($ot->teamMembers->count())
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-people"></i>
                OT Team ({{ $ot->teamMembers->count() }} members)
            </div>
            @foreach($ot->teamMembers as $member)
            <div class="team-member-row">
                <div class="member-avatar">
                    {{ strtoupper(substr($member->member_name, 0, 2)) }}
                </div>
                <div class="flex-grow-1">
                    <div style="font-size:13px;font-weight:600;color:#1e293b">{{ $member->member_name }}</div>
                    @if($member->doctor)
                        <div style="font-size:11px;color:#6366f1">{{ $member->doctor->specialization }}</div>
                    @elseif($member->employee)
                        <div style="font-size:11px;color:#64748b">{{ $member->employee->designation }}</div>
                    @endif
                </div>
                <span class="role-tag">{{ $member->role }}</span>
            </div>
            @endforeach
        </div>
        @endif

    </div>

    {{-- ── RIGHT COLUMN ─────────────────────────────────────────────────── --}}
    <div class="col-12 col-xl-4">

        {{-- Patient --}}
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-person"></i>
                Patient
            </div>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ $ot->patient->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">MRN</span>
                <span class="detail-value" style="font-family:monospace">{{ $ot->patient->mrn }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Gender</span>
                <span class="detail-value">{{ $ot->patient->gender }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Blood Group</span>
                <span class="detail-value">{{ $ot->patient->blood_group ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone</span>
                <span class="detail-value">{{ $ot->patient->phone }}</span>
            </div>
        </div>

        {{-- Surgical Team --}}
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-person-badge"></i>
                Surgical Team
            </div>
            <div class="detail-row">
                <span class="detail-label">Surgeon</span>
                <span class="detail-value">
                    Dr. {{ $ot->surgeon->employee?->first_name }}
                    {{ $ot->surgeon->employee?->last_name }}
                </span>
            </div>
            <div style="font-size:11px;color:#94a3b8;text-align:right;margin-top:-6px;margin-bottom:8px">
                {{ $ot->surgeon->specialization }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Anesthesiologist</span>
                <span class="detail-value">
                    @if($ot->anesthesiologist)
                        Dr. {{ $ot->anesthesiologist->employee?->first_name }}
                        {{ $ot->anesthesiologist->employee?->last_name }}
                    @else
                        —
                    @endif
                </span>
            </div>
        </div>

        {{-- OT Room --}}
        @if($ot->otRoom)
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-door-open"></i>
                OT Room
            </div>
            <div class="detail-row">
                <span class="detail-label">Room</span>
                <span class="detail-value">{{ $ot->otRoom->room_code }} – {{ $ot->otRoom->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Type</span>
                <span class="detail-value">{{ $ot->otRoom->room_type }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Floor / Block</span>
                <span class="detail-value">
                    {{ $ot->otRoom->floor ?? '—' }}
                    @if($ot->otRoom->block) / {{ $ot->otRoom->block }} @endif
                </span>
            </div>
        </div>
        @endif

        {{-- Consent & Pre-op --}}
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-shield-check"></i>
                Consent & Pre-op
            </div>
            <div class="d-flex gap-2 flex-wrap mb-3">
                <span class="check-indicator {{ $ot->consent_obtained ? 'check-yes' : 'check-no' }}">
                    <i class="bi bi-{{ $ot->consent_obtained ? 'check' : 'x' }}-circle me-1"></i>
                    Consent {{ $ot->consent_obtained ? 'Obtained' : 'Pending' }}
                </span>
                <span class="check-indicator {{ $ot->pre_op_assessment_done ? 'check-yes' : 'check-no' }}">
                    <i class="bi bi-{{ $ot->pre_op_assessment_done ? 'check' : 'clock' }}-circle me-1"></i>
                    Pre-op {{ $ot->pre_op_assessment_done ? 'Done' : 'Pending' }}
                </span>
            </div>
            @if($ot->consent_by)
            <div class="detail-row">
                <span class="detail-label">Consent By</span>
                <span class="detail-value">{{ $ot->consent_by }}</span>
            </div>
            @endif
        </div>

        {{-- Booked By --}}
        <div class="detail-card">
            <div class="section-title">
                <i class="bi bi-info-circle"></i>
                Record Info
            </div>
            <div class="detail-row">
                <span class="detail-label">Booked By</span>
                <span class="detail-value">{{ $ot->bookedBy?->name ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created</span>
                <span class="detail-value">{{ $ot->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Updated</span>
                <span class="detail-value">{{ $ot->updated_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

    </div>
</div>
@endsection
@extends('layouts.master')
@section('title', 'Disciplinary Action')
@section('page-title', 'Disciplinary Action')
@section('breadcrumb', 'HR / Disciplinary / Detail')

@push('styles')
<style>
    .detail-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;margin-bottom:1rem}
    .detail-header{padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .detail-body{padding:1.5rem}
    .detail-row{display:flex;padding:.55rem 0;border-bottom:1px solid #f8fafc}
    .detail-row:last-child{border-bottom:none}
    .detail-label{font-size:12px;font-weight:600;color:#94a3b8;width:160px;flex-shrink:0}
    .detail-val{font-size:13px;color:#1e293b;font-weight:500}
    .action-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:1.25rem;margin-bottom:1rem}
    .action-title{font-size:13px;font-weight:600;color:#1e293b;margin-bottom:.75rem;display:flex;align-items:center;gap:6px}
    .timeline-item{display:flex;gap:12px;padding:.6rem 0;border-bottom:1px solid #f8fafc}
    .timeline-item:last-child{border-bottom:none}
    .timeline-dot{width:8px;height:8px;border-radius:50%;background:#1d4ed8;margin-top:5px;flex-shrink:0}
    .timeline-text{font-size:12px;color:#64748b}
    .timeline-text strong{color:#1e293b}
    .status-badge{font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
    .action-type-badge{font-size:12px;font-weight:600;padding:4px 14px;border-radius:20px}
</style>
@endpush

@section('content')
@php
    $statusColor = match($disciplinary->status) {
        'Resolved'     => '#dcfce7;color:#16a34a',
        'Closed'       => '#f1f5f9;color:#64748b',
        'Escalated'    => '#fee2e2;color:#dc2626',
        'Acknowledged' => '#dbeafe;color:#1d4ed8',
        'Under Review' => '#ede9fe;color:#7c3aed',
        default        => '#fef3c7;color:#b45309',
    };
    $actionColor = match($disciplinary->action_type) {
        'Verbal Warning'   => '#dbeafe;color:#1d4ed8',
        'Written Warning'  => '#fef3c7;color:#b45309',
        'Show Cause Notice'=> '#ede9fe;color:#7c3aed',
        'Suspension'       => '#fee2e2;color:#dc2626',
        'Salary Deduction' => '#dcfce7;color:#16a34a',
        'Termination'      => '#fce7f3;color:#db2777',
        default            => '#f1f5f9;color:#64748b',
    };
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Disciplinary Action</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">
            {{ $disciplinary->action_number }} &middot;
            {{ $disciplinary->employee->first_name ?? '' }} {{ $disciplinary->employee->last_name ?? '' }}
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="action-type-badge" style="background:{{ $actionColor }}">{{ $disciplinary->action_type }}</span>
        <span class="status-badge" style="background:{{ $statusColor }}">{{ $disciplinary->status }}</span>
        @if(!in_array($disciplinary->status, ['Resolved','Closed']))
            <a href="{{ route('hr.disciplinary.edit', $disciplinary) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
        @endif
        <a href="{{ route('hr.disciplinary.index') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- LEFT --}}
    <div class="col-12 col-lg-7">

        {{-- Incident --}}
        <div class="detail-card">
            <div class="detail-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-exclamation-triangle me-2" style="color:#dc2626"></i>Incident Details
                </span>
                <span style="font-size:12px;color:#94a3b8">
                    {{ \Carbon\Carbon::parse($disciplinary->incident_date)->format('d M Y') }}
                </span>
            </div>
            <div class="detail-body">
                <div class="detail-row">
                    <span class="detail-label">Employee</span>
                    <span class="detail-val">
                        {{ $disciplinary->employee->first_name ?? '' }}
                        {{ $disciplinary->employee->last_name ?? '' }}
                        ({{ $disciplinary->employee->employee_id ?? '' }})
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Incident Type</span>
                    <span class="detail-val">{{ $disciplinary->incident_type }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Incident Date</span>
                    <span class="detail-val">{{ \Carbon\Carbon::parse($disciplinary->incident_date)->format('l, d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Description</span>
                    <span class="detail-val">{{ $disciplinary->incident_description }}</span>
                </div>
            </div>
        </div>

        {{-- Action --}}
        <div class="detail-card">
            <div class="detail-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-shield-exclamation me-2" style="color:#b45309"></i>Action Taken
                </span>
            </div>
            <div class="detail-body">
                <div class="detail-row">
                    <span class="detail-label">Action Type</span>
                    <span class="detail-val">{{ $disciplinary->action_type }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Action Date</span>
                    <span class="detail-val">{{ \Carbon\Carbon::parse($disciplinary->action_date)->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Details</span>
                    <span class="detail-val">{{ $disciplinary->action_details }}</span>
                </div>
                @if($disciplinary->response_deadline)
                <div class="detail-row">
                    <span class="detail-label">Response Deadline</span>
                    <span class="detail-val">{{ \Carbon\Carbon::parse($disciplinary->response_deadline)->format('d M Y') }}</span>
                </div>
                @endif

                {{-- Suspension --}}
                @if($disciplinary->action_type === 'Suspension' && $disciplinary->suspension_from)
                <div class="detail-row">
                    <span class="detail-label">Suspension Period</span>
                    <span class="detail-val">
                        {{ \Carbon\Carbon::parse($disciplinary->suspension_from)->format('d M Y') }}
                        &rarr; {{ \Carbon\Carbon::parse($disciplinary->suspension_to)->format('d M Y') }}
                        ({{ $disciplinary->suspension_days }} days)
                        — {{ $disciplinary->suspension_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>
                @endif

                {{-- Deduction --}}
                @if($disciplinary->action_type === 'Salary Deduction' && $disciplinary->deduction_amount > 0)
                <div class="detail-row">
                    <span class="detail-label">Deduction</span>
                    <span class="detail-val">
                        PKR {{ number_format($disciplinary->deduction_amount) }}
                        @if($disciplinary->deduction_month) — {{ $disciplinary->deduction_month }} @endif
                    </span>
                </div>
                @endif

                @if($disciplinary->document_path)
                <div class="detail-row">
                    <span class="detail-label">Document</span>
                    <span class="detail-val">
                        <a href="{{ asset('storage/' . $disciplinary->document_path) }}"
                           target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:11px">
                            <i class="bi bi-paperclip me-1"></i>View Document
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Employee Response --}}
        @if($disciplinary->employee_response)
        <div class="detail-card">
            <div class="detail-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-chat-text me-2" style="color:#1d4ed8"></i>Employee Response
                </span>
                @if($disciplinary->response_received_date)
                    <span style="font-size:12px;color:#94a3b8">
                        {{ \Carbon\Carbon::parse($disciplinary->response_received_date)->format('d M Y') }}
                    </span>
                @endif
            </div>
            <div class="detail-body">
                <p style="font-size:13px;color:#374151;margin:0">{{ $disciplinary->employee_response }}</p>
            </div>
        </div>
        @endif

        {{-- Appeal --}}
        @if($disciplinary->is_appealed)
        <div class="detail-card" style="border-color:#bfdbfe">
            <div class="detail-header" style="background:#eff6ff">
                <span style="font-size:14px;font-weight:600;color:#1d4ed8">
                    <i class="bi bi-flag me-2"></i>Appeal
                </span>
                @if($disciplinary->appeal_outcome)
                <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;
                    background:{{ $disciplinary->appeal_outcome === 'Overturned' ? '#dcfce7;color:#16a34a' : '#fef3c7;color:#b45309' }}">
                    {{ $disciplinary->appeal_outcome }}
                </span>
                @endif
            </div>
            <div class="detail-body">
                <p style="font-size:13px;color:#374151;margin:0">{{ $disciplinary->appeal_details }}</p>
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-5">

        {{-- Record employee response --}}
        @if(!$disciplinary->response_received && in_array($disciplinary->status, ['Issued','Acknowledged']))
        <div class="action-card" style="border-color:#bfdbfe">
            <div class="action-title" style="color:#1d4ed8">
                <i class="bi bi-chat-square-text-fill"></i>Record Employee Response
            </div>
            <form action="{{ route('hr.disciplinary.response', $disciplinary) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Response <span class="text-danger">*</span></label>
                    <textarea name="employee_response" class="form-control form-control-sm" rows="3"
                              placeholder="Employee's written explanation…" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-save me-1"></i>Save Response
                </button>
            </form>
        </div>
        @endif

        {{-- Resolve --}}
        @if(!in_array($disciplinary->status, ['Resolved','Closed']))
        <div class="action-card" style="border-color:#bbf7d0">
            <div class="action-title" style="color:#16a34a">
                <i class="bi bi-check-circle-fill"></i>Resolve Case
            </div>
            <form action="{{ route('hr.disciplinary.resolve', $disciplinary) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Resolution Notes <span class="text-danger">*</span></label>
                    <textarea name="review_notes" class="form-control form-control-sm" rows="2"
                              placeholder="How was this resolved?" required></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-sm w-100">
                    <i class="bi bi-check-lg me-1"></i>Mark Resolved
                </button>
            </form>
        </div>

        {{-- Appeal --}}
        @if(!$disciplinary->is_appealed)
        <div class="action-card">
            <div class="action-title" style="color:#7c3aed">
                <i class="bi bi-flag-fill"></i>Record Appeal
            </div>
            <form action="{{ route('hr.disciplinary.appeal', $disciplinary) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Appeal Details <span class="text-danger">*</span></label>
                    <textarea name="appeal_details" class="form-control form-control-sm" rows="2"
                              placeholder="Grounds for appeal…" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Outcome <span class="text-danger">*</span></label>
                    <select name="appeal_outcome" class="form-select form-select-sm" required>
                        <option value="">Select…</option>
                        <option value="Upheld">Upheld — action stands</option>
                        <option value="Overturned">Overturned — action removed</option>
                        <option value="Modified">Modified — action changed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-flag me-1"></i>Record Appeal
                </button>
            </form>
        </div>
        @endif
        @endif

        {{-- Timeline --}}
        <div class="action-card">
            <div class="action-title">
                <i class="bi bi-clock-history" style="color:#1d4ed8"></i>Activity Timeline
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-text">
                    <strong>Action issued</strong> — {{ $disciplinary->action_type }}<br>
                    {{ $disciplinary->created_at->format('d M Y, h:i A') }}
                    @if($disciplinary->issuedBy)
                        by {{ $disciplinary->issuedBy->name }}
                    @endif
                </div>
            </div>
            @if($disciplinary->response_received)
            <div class="timeline-item">
                <div class="timeline-dot" style="background:#1d4ed8"></div>
                <div class="timeline-text">
                    <strong>Employee response received</strong><br>
                    {{ \Carbon\Carbon::parse($disciplinary->response_received_date)->format('d M Y') }}
                </div>
            </div>
            @endif
            @if($disciplinary->is_appealed)
            <div class="timeline-item">
                <div class="timeline-dot" style="background:#7c3aed"></div>
                <div class="timeline-text">
                    <strong>Appeal recorded</strong> — {{ $disciplinary->appeal_outcome }}<br>
                </div>
            </div>
            @endif
            @if(in_array($disciplinary->status, ['Resolved','Closed']))
            <div class="timeline-item">
                <div class="timeline-dot" style="background:#16a34a"></div>
                <div class="timeline-text">
                    <strong>Case {{ $disciplinary->status }}</strong><br>
                    {{ $disciplinary->updated_at->format('d M Y, h:i A') }}
                </div>
            </div>
            @endif
        </div>

        {{-- Meta --}}
        <div class="action-card">
            <div class="action-title"><i class="bi bi-info-circle" style="color:#94a3b8"></i>Case Info</div>
            <div style="font-size:12px;color:#64748b">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span>Case #</span><strong>{{ $disciplinary->action_number }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span>Issued By</span>
                    <strong>{{ optional($disciplinary->issuedBy)->name }}</strong>
                </div>
                @if($disciplinary->reviewedBy)
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span>Reviewed By</span>
                    <strong>{{ $disciplinary->reviewedBy->name }}</strong>
                </div>
                @endif
                <div class="d-flex justify-content-between py-1">
                    <span>Created</span>
                    <strong>{{ $disciplinary->created_at->format('d M Y') }}</strong>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

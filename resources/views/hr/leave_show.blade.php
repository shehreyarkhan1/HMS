@extends('layouts.master')
@section('title', 'Leave Request')
@section('page-title', 'Leave Request')
@section('breadcrumb', 'HR / Leaves / Detail')

@push('styles')
    <style>
        .detail-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 1rem
        }

        .detail-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .detail-body {
            padding: 1.5rem
        }

        .detail-row {
            display: flex;
            padding: .55rem 0;
            border-bottom: 1px solid #f8fafc
        }

        .detail-row:last-child {
            border-bottom: none
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            width: 160px;
            flex-shrink: 0
        }

        .detail-val {
            font-size: 13px;
            color: #1e293b;
            font-weight: 500
        }

        .status-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309
        }

        .badge-approved {
            background: #dcfce7;
            color: #16a34a
        }

        .badge-rejected {
            background: #fee2e2;
            color: #dc2626
        }

        .badge-cancelled {
            background: #f1f5f9;
            color: #64748b
        }

        .action-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
            margin-bottom: 1rem
        }

        .action-title {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: .75rem;
            display: flex;
            align-items: center;
            gap: 6px
        }

        .timeline-item {
            display: flex;
            gap: 12px;
            padding: .6rem 0;
            border-bottom: 1px solid #f8fafc
        }

        .timeline-item:last-child {
            border-bottom: none
        }

        .timeline-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1d4ed8;
            margin-top: 5px;
            flex-shrink: 0
        }

        .timeline-text {
            font-size: 12px;
            color: #64748b
        }

        .timeline-text strong {
            color: #1e293b
        }

        .days-big {
            font-size: 36px;
            font-weight: 800;
            color: #1d4ed8;
            line-height: 1
        }
    </style>
@endpush

@section('content')
    @php
        $statusClass = match ($leave->status) {
            'Approved' => 'badge-approved',
            'Rejected' => 'badge-rejected',
            'Cancelled' => 'badge-cancelled',
            default => 'badge-pending',
        };
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">Leave Request</h5>
            <p class="mb-0" style="font-size:13px;color:#94a3b8">
                {{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}
                &middot; {{ $leave->leaveType->name ?? '—' }}
            </p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="status-badge {{ $statusClass }}">{{ $leave->status }}</span>
            <a href="{{ route('hr.leaves.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </div>

    <div class="row g-3">
        {{-- LEFT --}}
        <div class="col-12 col-lg-7">

            {{-- Details --}}
            <div class="detail-card">
                <div class="detail-header">
                    <span style="font-size:14px;font-weight:600;color:#1e293b"><i class="bi bi-calendar-heart me-2"
                            style="color:#1d4ed8"></i>Leave Information</span>
                    <div style="text-align:right">
                        <div class="days-big">{{ $leave->total_days }}</div>
                        <div style="font-size:11px;color:#94a3b8;text-align:center">day(s)</div>
                    </div>
                </div>
                <div class="detail-body">
                    <div class="detail-row"><span class="detail-label">Employee</span><span
                            class="detail-val">{{ $leave->employee->first_name ?? '' }}
                            {{ $leave->employee->last_name ?? '' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Leave Type</span><span
                            class="detail-val">{{ $leave->leaveType->name ?? '—' }}</span></div>
                    <div class="detail-row"><span class="detail-label">From</span><span
                            class="detail-val">{{ \Carbon\Carbon::parse($leave->from_date)->format('l, d F Y') }}</span>
                    </div>
                    <div class="detail-row"><span class="detail-label">To</span><span
                            class="detail-val">{{ \Carbon\Carbon::parse($leave->to_date)->format('l, d F Y') }}</span>
                    </div>
                    <div class="detail-row"><span class="detail-label">Half Day</span><span
                            class="detail-val">{{ $leave->half_day ? 'Yes — ' . $leave->half_day_type : 'No' }}</span>
                    </div>
                    <div class="detail-row"><span class="detail-label">Reason</span><span
                            class="detail-val">{{ $leave->reason }}</span></div>
                    @if ($leave->document_path)
                        <div class="detail-row">
                            <span class="detail-label">Document</span>
                            <span class="detail-val">
                                <a href="{{ asset('storage/' . $leave->document_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:11px">
                                    <i class="bi bi-paperclip me-1"></i>View Document
                                </a>
                            </span>
                        </div>
                    @endif
                    <div class="detail-row"><span class="detail-label">Submitted</span><span
                            class="detail-val">{{ $leave->created_at->format('d M Y, h:i A') }}</span></div>
                </div>
            </div>

            {{-- Review info --}}
            @if ($leave->reviewedBy)
                <div class="detail-card">
                    <div class="detail-header"><span style="font-size:14px;font-weight:600;color:#1e293b"><i
                                class="bi bi-person-check me-2" style="color:#16a34a"></i>Review Information</span></div>
                    <div class="detail-body">
                        <div class="detail-row"><span class="detail-label">Reviewed By</span><span
                                class="detail-val">{{ $leave->reviewedBy->name ?? '' }}</span></div>
                        <div class="detail-row"><span class="detail-label">Reviewed At</span><span
                                class="detail-val">{{ $leave->reviewed_at?->format('d M Y, h:i A') ?? '—' }}</span></div>
                        @if ($leave->review_notes)
                            <div class="detail-row"><span class="detail-label">Notes</span><span
                                    class="detail-val">{{ $leave->review_notes }}</span></div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Cancellation --}}
            @if ($leave->status === 'Cancelled')
                <div class="detail-card" style="border-color:#fca5a5">
                    <div class="detail-header"><span style="font-size:14px;font-weight:600;color:#dc2626"><i
                                class="bi bi-x-circle me-2"></i>Cancellation</span></div>
                    <div class="detail-body">
                        <div class="detail-row"><span class="detail-label">Reason</span><span
                                class="detail-val">{{ $leave->cancellation_reason }}</span></div>
                        <div class="detail-row"><span class="detail-label">At</span><span
                                class="detail-val">{{ $leave->cancelled_at?->format('d M Y') }}</span></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT — Actions --}}
        <div class="col-12 col-lg-5">

            @if ($leave->status === 'Pending')
                {{-- Approve --}}
                <div class="action-card" style="border-color:#bbf7d0">
                    <div class="action-title" style="color:#16a34a"><i class="bi bi-check-circle-fill"></i> Approve Leave
                    </div>
                    <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Notes
                                (optional)</label>
                            <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Approval note…"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-lg me-1"></i> Approve
                        </button>
                    </form>
                </div>

                {{-- Reject --}}
                <div class="action-card" style="border-color:#fca5a5">
                    <div class="action-title" style="color:#dc2626"><i class="bi bi-x-circle-fill"></i> Reject Leave</div>
                    <form action="{{ route('hr.leaves.reject', $leave) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Reason <span
                                    class="text-danger">*</span></label>
                            <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Rejection reason…"
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-x-lg me-1"></i> Reject
                        </button>
                    </form>
                </div>
            @endif

            @if (in_array($leave->status, ['Pending', 'Approved']))
                {{-- Cancel --}}
                <div class="action-card">
                    <div class="action-title" style="color:#64748b"><i class="bi bi-slash-circle"></i> Cancel Request
                    </div>
                    <form action="{{ route('hr.leaves.cancel', $leave) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Reason <span
                                    class="text-danger">*</span></label>
                            <textarea name="cancellation_reason" class="form-control form-control-sm" rows="2"
                                placeholder="Cancellation reason…" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100"
                            onclick="return confirm('Cancel this leave request?')">
                            Cancel Request
                        </button>
                    </form>
                </div>
            @endif

            {{-- Timeline --}}
            <div class="action-card">
                <div class="action-title"><i class="bi bi-clock-history" style="color:#1d4ed8"></i> Activity</div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-text"><strong>Request
                            submitted</strong><br>{{ $leave->created_at->format('d M Y, h:i A') }}</div>
                </div>
                @if ($leave->reviewed_at)
                    <div class="timeline-item">
                        <div class="timeline-dot"
                            style="background:{{ $leave->status === 'Approved' ? '#16a34a' : '#dc2626' }}"></div>
                        <div class="timeline-text"><strong>{{ $leave->status }}</strong> by
                            {{ $leave->reviewedBy?->name }}<br>{{ $leave->reviewed_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                @endif
                @if ($leave->cancelled_at)
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background:#94a3b8"></div>
                        <div class="timeline-text">
                            <strong>Cancelled</strong><br>{{ $leave->cancelled_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('title', 'Radiology Order — ' . $radiologyOrder->order_number)
@section('page-title', 'Radiology — ' . $radiologyOrder->order_number)
@section('breadcrumb', 'Home / Radiology / Orders / ' . $radiologyOrder->order_number)

@push('styles')
<style>
    .info-card {
        background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px
    }
    .info-card-title {
        font-size:12px;font-weight:600;color:#94a3b8;
        text-transform:uppercase;letter-spacing:.06em;
        margin-bottom:14px;
        display:flex;align-items:center;gap:6px
    }
    .info-row {
        display:flex;justify-content:space-between;
        padding:7px 0;border-bottom:1px solid #f8fafc;
        font-size:13px
    }
    .info-row:last-child { border-bottom:none }
    .info-label { color:#94a3b8;min-width:130px }
    .info-value { color:#1e293b;font-weight:500;text-align:right }

    .section-card {
        background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:20px
    }
    .section-header {
        padding:14px 20px;border-bottom:1px solid #e2e8f0;
        display:flex;align-items:center;justify-content:space-between
    }
    .section-body { padding:20px }

    .exam-item {
        border:1px solid #e2e8f0;border-radius:10px;
        padding:16px;margin-bottom:14px
    }
    .exam-item:last-child { margin-bottom:0 }
    .exam-item.completed { border-left:3px solid #22c55e }
    .exam-item.in-progress { border-left:3px solid #f59e0b }
    .exam-item.pending { border-left:3px solid #e2e8f0 }
    .exam-item.cancelled { border-left:3px solid #ef4444;opacity:.7 }

    .report-box {
        background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-top:10px
    }
    .report-label {
        font-size:11px;font-weight:600;color:#64748b;
        text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px
    }
    .report-text { font-size:13px;color:#1e293b;line-height:1.6 }

    .critical-banner {
        background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;
        padding:12px 16px;margin-bottom:16px;
        display:flex;align-items:start;gap:10px
    }
    .form-section {
        background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-top:14px
    }
    .form-label-sm { font-size:12px;font-weight:600;color:#374151;margin-bottom:4px }
    .form-control-sm2 {
        border:1px solid #e2e8f0;border-radius:8px;font-size:13px;
        padding:8px 12px;width:100%;background:#fff;color:#374151
    }
    .form-control-sm2:focus { outline:none;border-color:#93c5fd }

    .badge-item-Pending      { background:#fef9c3;color:#854d0e }
    .badge-item-Scheduled    { background:#dbeafe;color:#1e40af }
    .badge-item-In-Progress  { background:#fef3c7;color:#92400e }
    .badge-item-Scan-Completed{ background:#e0f2fe;color:#0369a1 }
    .badge-item-Reported     { background:#dcfce7;color:#166534 }
    .badge-item-Cancelled    { background:#fee2e2;color:#991b1b }

    .img-thumb {
        width:72px;height:72px;object-fit:cover;
        border-radius:6px;border:1px solid #e2e8f0;cursor:pointer
    }
    .timeline {
        display:flex;gap:0;margin:16px 0;overflow-x:auto;padding-bottom:4px
    }
    .timeline-step {
        flex:1;min-width:90px;text-align:center;position:relative
    }
    .timeline-step::before {
        content:'';position:absolute;top:14px;left:50%;right:-50%;
        height:2px;background:#e2e8f0;z-index:0
    }
    .timeline-step:last-child::before { display:none }
    .timeline-dot {
        width:28px;height:28px;border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 6px;position:relative;z-index:1;font-size:12px
    }
    .timeline-dot.done     { background:#22c55e;color:#fff }
    .timeline-dot.active   { background:#6366f1;color:#fff;box-shadow:0 0 0 4px #e0e7ff }
    .timeline-dot.pending  { background:#e2e8f0;color:#94a3b8 }
    .timeline-label        { font-size:10px;color:#94a3b8;line-height:1.2 }
    .timeline-label.active { color:#6366f1;font-weight:600 }
    .timeline-label.done   { color:#16a34a }
</style>
@endpush

@section('content')

@php
    $statusOrder = ['Pending','Scheduled','In Progress','Scan Completed','Reporting','Reported','Verified','Delivered'];
    $currentIdx  = array_search($radiologyOrder->status, $statusOrder);
    $statusIcons = ['bi-clock','bi-calendar2-check','bi-camera-video','bi-camera-fill','bi-pencil-square','bi-file-earmark-medical','bi-patch-check-fill','bi-box-arrow-right'];
@endphp

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4"
        style="border-radius:10px;font-size:13px">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- Critical finding banner --}}
@if($radiologyOrder->hasCriticalFindings())
    <div class="critical-banner mb-4">
        <i class="bi bi-exclamation-circle-fill text-danger fs-5 mt-1"></i>
        <div>
            <div style="font-weight:700;color:#991b1b;font-size:13px">Critical Radiological Findings</div>
            @foreach($radiologyOrder->items as $item)
                @if($item->report && $item->report->is_critical)
                    <div style="font-size:12px;color:#7f1d1d;margin-top:4px">
                        <strong>{{ $item->exam->name }}</strong> —
                        {{ $item->report->critical_notes }}
                        @if($item->report->critical_notified_to)
                            <span style="color:#94a3b8">(Notified: {{ $item->report->critical_notified_to }})</span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif

{{-- Status Timeline --}}
<div class="info-card mb-4">
    <div class="timeline">
        @foreach($statusOrder as $idx => $step)
            @php
                $isDone   = $currentIdx !== false && $idx < $currentIdx;
                $isActive = $currentIdx !== false && $idx === $currentIdx;
                $cls      = $isDone ? 'done' : ($isActive ? 'active' : 'pending');
            @endphp
            <div class="timeline-step">
                <div class="timeline-dot {{ $cls }}">
                    <i class="bi {{ $statusIcons[$idx] }}"></i>
                </div>
                <div class="timeline-label {{ $cls }}">{{ $step }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Left: Order info --}}
    <div class="col-md-4">
        <div class="info-card h-100">
            <div class="info-card-title"><i class="bi bi-radioactive text-primary"></i> Order Info</div>
            <div class="info-row">
                <span class="info-label">Order No.</span>
                <span class="info-value"><code style="color:#6366f1">{{ $radiologyOrder->order_number }}</code></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="badge-{{ str_replace(' ','-',$radiologyOrder->status) }}"
                        style="font-size:11px;padding:3px 10px;border-radius:20px">
                        {{ $radiologyOrder->status }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Priority</span>
                <span class="info-value">
                    <span class="priority-{{ $radiologyOrder->priority }}">{{ $radiologyOrder->priority }}</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Date</span>
                <span class="info-value">{{ $radiologyOrder->order_date->format('d M Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Scheduled</span>
                <span class="info-value">
                    {{ $radiologyOrder->scheduled_at ? $radiologyOrder->scheduled_at->format('d M Y H:i') : '—' }}
                </span>
            </div>
            @if($radiologyOrder->notes)
                <div class="info-row">
                    <span class="info-label">Notes</span>
                    <span class="info-value" style="max-width:200px;word-break:break-word;text-align:right">
                        {{ $radiologyOrder->notes }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- Center: Patient & Doctor --}}
    <div class="col-md-4">
        <div class="info-card h-100">
            <div class="info-card-title"><i class="bi bi-person-circle text-primary"></i> Patient</div>
            <div class="info-row">
                <span class="info-label">Name</span>
                <span class="info-value">{{ $radiologyOrder->patient->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">MRN</span>
                <span class="info-value"><code>{{ $radiologyOrder->patient->mrn }}</code></span>
            </div>
            <div class="info-row">
                <span class="info-label">Gender</span>
                <span class="info-value">{{ $radiologyOrder->patient->gender ?? '—' }}</span>
            </div>
            <div style="border-top:1px solid #e2e8f0;margin:12px 0"></div>
            <div class="info-card-title"><i class="bi bi-person-badge text-primary"></i> Referring Doctor</div>
            <div class="info-row">
                <span class="info-label">Name</span>
                <span class="info-value">{{ $radiologyOrder->doctor->name ?? '—' }}</span>
            </div>
            @if($radiologyOrder->clinical_indication)
                <div class="info-row">
                    <span class="info-label">Indication</span>
                    <span class="info-value" style="max-width:180px;word-break:break-word;text-align:right">
                        {{ $radiologyOrder->clinical_indication }}
                    </span>
                </div>
            @endif
            @if($radiologyOrder->clinical_history)
                <div style="margin-top:8px">
                    <div class="report-label">Clinical History</div>
                    <div class="report-text" style="color:#64748b">{{ $radiologyOrder->clinical_history }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Right: Billing --}}

    <div class="col-md-4">
        <div class="info-card h-100">
            <div class="info-card-title"><i class="bi bi-receipt text-primary"></i> Billing</div>
            <div class="info-row">
                <span class="info-label">Total Amount</span>
                <span class="info-value">Rs {{ number_format($radiologyOrder->total_amount, 0) }}</span>
            </div>
            @if($radiologyOrder->discount > 0)
                <div class="info-row">
                    <span class="info-label">Discount</span>
                    <span class="info-value" style="color:#dc2626">-Rs {{ number_format($radiologyOrder->discount, 0) }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Net Amount</span>
                <span class="info-value" style="font-size:15px">Rs {{ number_format($radiologyOrder->net_amount, 0) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Paid</span>
                <span class="info-value" style="color:#16a34a">Rs {{ number_format($radiologyOrder->paid_amount, 0) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Balance</span>
                <span class="info-value" style="color:{{ $radiologyOrder->balance_due > 0 ? '#dc2626' : '#16a34a' }}">
                    Rs {{ number_format($radiologyOrder->balance_due, 0) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment</span>
                <span class="info-value">
                    <span class="pay-{{ $radiologyOrder->payment_status }}">{{ $radiologyOrder->payment_status }}</span>
                </span>
            </div>

            @if($radiologyOrder->balance_due > 0)
                <form method="POST" action="{{ route('radiology.orders.recordPayment', $radiologyOrder->id) }}"
                    class="mt-3 d-flex gap-2">
                    @csrf
                    <input type="number" name="paid_amount" placeholder="Amount (Rs)"
                        class="form-control form-control-sm" min="1" step="1" style="font-size:13px">
                    <button type="submit" class="btn btn-sm btn-outline-success px-3" style="font-size:12px;white-space:nowrap">
                        <i class="bi bi-cash me-1"></i>Pay
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- ── Exam Items ── --}}
<div class="section-card" id="scan">
    <div class="section-header">
        <span style="font-size:14px;font-weight:600;color:#1e293b">
            <i class="bi bi-camera-fill me-2 text-primary"></i>Exams & Scans
        </span>
    </div>
    <div class="section-body">
        @foreach($radiologyOrder->items as $item)
            @php
                $itemCls = match($item->status) {
                    'Scan Completed','Reported' => 'completed',
                    'In Progress'               => 'in-progress',
                    'Cancelled'                 => 'cancelled',
                    default                     => 'pending'
                };
            @endphp
            <div class="exam-item {{ $itemCls }}">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                    <div>
                        <span style="font-weight:600;color:#1e293b;font-size:14px">{{ $item->exam->name }}</span>
                        <span class="badge-item-{{ str_replace(' ','-',$item->status) }}"
                            style="font-size:10px;padding:2px 8px;border-radius:20px;margin-left:8px">
                            {{ $item->status }}
                        </span>
                        <div style="font-size:12px;color:#94a3b8;margin-top:2px">
                            {{ $item->exam->modality->name ?? '' }}
                            @if($item->exam->bodyPart) · {{ $item->exam->bodyPart->name }} @endif
                            · Rs {{ number_format($item->final_price, 0) }}
                        </div>
                    </div>
                    @if($item->scanned_at)
                        <div style="text-align:right;font-size:11px;color:#94a3b8">
                            Scanned: {{ $item->scanned_at->format('d M Y H:i') }}<br>
                            @if($item->technician_name)By: {{ $item->technician_name }}@endif
                        </div>
                    @endif
                </div>

                {{-- Images thumbs --}}
                @if($item->images->count())
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        @foreach($item->images as $img)
                            @if(in_array($img->file_type, ['image']))
                                <img src="{{ Storage::url($img->file_path) }}"
                                    class="img-thumb"
                                    alt="{{ $img->description ?? $img->file_name }}"
                                    onclick="window.open(this.src,'_blank')">
                            @else
                                <a href="{{ Storage::url($img->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size:11px">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>{{ $img->file_name }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Report block --}}
                @if($item->report)
                    <div class="report-box" id="report-{{ $item->id }}">
                        @if($item->report->is_critical)
                            <div style="background:#fee2e2;border-radius:6px;padding:8px 12px;margin-bottom:10px;font-size:12px;color:#991b1b;font-weight:600">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> CRITICAL FINDING
                                — {{ $item->report->critical_notes }}
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="report-label">Findings</div>
                                <div class="report-text">{{ $item->report->findings }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="report-label">Impression</div>
                                <div class="report-text">{{ $item->report->impression }}</div>
                            </div>
                            @if($item->report->recommendations)
                                <div class="col-md-12">
                                    <div class="report-label">Recommendations</div>
                                    <div class="report-text">{{ $item->report->recommendations }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                            <span style="font-size:11px;color:#94a3b8">
                                Reported by {{ optional($item->report->reportedBy)->name ?? 'Staff' }}
                                at {{ $item->report->reported_at?->format('d M Y H:i') }}
                            </span>
                            @if($item->report->is_verified)
                                <span style="font-size:11px;color:#16a34a">
                                    <i class="bi bi-patch-check-fill"></i>
                                    Verified by {{ optional($item->report->verifiedBy)->name }}
                                    at {{ $item->report->verified_at?->format('d M H:i') }}
                                </span>
                            @else
                                <form method="POST"
                                    action="{{ route('radiology.reports.verify', $item->report->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success py-0 px-3" style="font-size:12px">
                                        <i class="bi bi-patch-check me-1"></i>Verify Report
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- START SCAN FORM --}}
                @if(in_array($item->status, ['Pending','Scheduled']))
                    <div class="form-section" id="scan-form-{{ $item->id }}">
                        <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:12px">
                            <i class="bi bi-camera-fill me-1 text-warning"></i> Start Scan
                        </div>
                        <form method="POST" action="{{ route('radiology.orders.startScan', $radiologyOrder->id) }}">
                            @csrf
                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label class="form-label-sm">Technician Name *</label>
                                    <input type="text" name="technician_name" class="form-control-sm2"
                                        placeholder="Tech. name" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label-sm">Equipment / Room</label>
                                    <input type="text" name="equipment_used" class="form-control-sm2"
                                        placeholder="Machine / Room no.">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-sm btn-warning w-100" style="font-size:12px">
                                        <i class="bi bi-play-fill me-1"></i>Start
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- COMPLETE SCAN FORM --}}
                @if($item->status === 'In Progress')
                    <div class="form-section">
                        <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:12px">
                            <i class="bi bi-upload me-1 text-primary"></i> Complete Scan & Upload Images
                        </div>
                        <form method="POST"
                            action="{{ route('radiology.orders.completeScan', $radiologyOrder->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label-sm">
                                        <input type="checkbox" name="contrast_used" value="1" class="me-1"
                                            onchange="toggleContrast(this, {{ $item->id }})">
                                        Contrast Used
                                    </label>
                                </div>
                                <div class="col-md-9 contrast-fields-{{ $item->id }}" style="display:none">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input type="text" name="contrast_agent" placeholder="Agent (e.g. Omnipaque)"
                                                class="form-control-sm2">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="contrast_dose_ml" placeholder="Dose ml"
                                                class="form-control-sm2" min="0" step="0.5">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label-sm">
                                                <input type="checkbox" name="contrast_reaction" value="1" class="me-1">
                                                Reaction occurred
                                            </label>
                                            <input type="text" name="contrast_reaction_notes"
                                                placeholder="Reaction details" class="form-control-sm2 mt-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-sm">Upload Images / Files</label>
                                    <input type="file" name="images[]" multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.dcm"
                                        class="form-control form-control-sm" style="font-size:12px">
                                    <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                                        Accepted: JPG, PNG, PDF, DICOM · Max 20MB each
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-primary px-4" style="font-size:12px">
                                        <i class="bi bi-check-lg me-1"></i>Mark Scan Completed
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- REPORT ENTRY FORM --}}
                @if(in_array($item->status, ['Scan Completed']) && !$item->report)
                    <div class="form-section" id="report">
                        <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:12px">
                            <i class="bi bi-file-earmark-medical me-1 text-success"></i> Enter Radiology Report
                        </div>
                        <form method="POST" action="{{ route('radiology.orders.storeReport', $radiologyOrder->id) }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-sm">Comparison (prior studies)</label>
                                    <input type="text" name="comparison" class="form-control-sm2"
                                        placeholder="e.g. Compared with CT chest dated 01 Jan 2025">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-sm">Findings *</label>
                                    <textarea name="findings" class="form-control-sm2" rows="5"
                                        placeholder="Detailed radiological findings..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-sm">Impression / Conclusion *</label>
                                    <textarea name="impression" class="form-control-sm2" rows="3"
                                        placeholder="Final diagnosis / impression..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-sm">Recommendations</label>
                                    <textarea name="recommendations" class="form-control-sm2" rows="2"
                                        placeholder="Follow-up, further investigations..."></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <label style="font-size:12px;cursor:pointer">
                                            <input type="checkbox" name="is_critical" value="1" class="me-1"
                                                onchange="toggleCritical(this)">
                                            <span style="color:#dc2626;font-weight:600">⚠ Mark as Critical Finding</span>
                                        </label>
                                    </div>
                                    <div id="critical-fields" style="display:none;margin-top:10px">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label-sm">Critical Notes *</label>
                                                <textarea name="critical_notes" class="form-control-sm2" rows="2"
                                                    placeholder="Describe critical finding..."></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-sm">Notify Doctor *</label>
                                                <input type="text" name="critical_notified_to" class="form-control-sm2"
                                                    placeholder="Doctor name notified">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-success px-4" style="font-size:13px">
                                        <i class="bi bi-send me-1"></i>Submit Report
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Schedule form --}}
        @if($radiologyOrder->status === 'Pending')
            <div class="form-section mt-2" id="schedule">
                <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:10px">
                    <i class="bi bi-calendar2-plus me-1 text-secondary"></i> Schedule Appointment
                </div>
                <form method="POST" action="{{ route('radiology.orders.schedule', $radiologyOrder->id) }}"
                    class="d-flex gap-2 align-items-end">
                    @csrf
                    <div>
                        <label class="form-label-sm">Date & Time</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control-sm2" style="width:220px" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary px-3" style="font-size:12px">
                        <i class="bi bi-calendar2-check me-1"></i>Schedule
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- Cancel --}}
@if($radiologyOrder->isCancellable())
    <div class="d-flex justify-content-end">
        <form method="POST" action="{{ route('radiology.orders.cancel', $radiologyOrder->id) }}"
            onsubmit="return confirm('Cancel this radiology order?')">
            @csrf
            <button class="btn btn-sm btn-outline-danger px-3" style="font-size:12px">
                <i class="bi bi-x-circle me-1"></i>Cancel Order
            </button>
        </form>
    </div>
@endif

@endsection

@push('scripts')
<script>
    function toggleContrast(cb, id) {
        document.querySelector('.contrast-fields-' + id).style.display = cb.checked ? 'block' : 'none';
    }
    function toggleCritical(cb) {
        document.getElementById('critical-fields').style.display = cb.checked ? 'block' : 'none';
    }
</script>
@endpush
@extends('layouts.master')

@section('title', $request->request_id . ' — Blood Request')
@section('page-title', 'Blood Request Detail')
@section('breadcrumb', 'Home / Blood Bank / Requests / ' . $request->request_id)

@push('styles')
    <style>
        .detail-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #ef4444;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 8px 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 13px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #94a3b8;
            font-size: 12px;
            min-width: 140px;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
        }

        /* Status badges */
        .status-badge {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .rs-Pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .rs-Under-Review {
            background: #e0f2fe;
            color: #0c4a6e;
        }

        .rs-Crossmatch {
            background: #dbeafe;
            color: #1e40af;
        }

        .rs-Approved {
            background: #dcfce7;
            color: #166534;
        }

        .rs-Partially-Fulfilled {
            background: #e0f2fe;
            color: #0c4a6e;
        }

        .rs-Fulfilled {
            background: #f1f5f9;
            color: #475569;
        }

        .rs-Rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .rs-Cancelled {
            background: #f1f5f9;
            color: #475569;
        }

        .urgency-badge {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .urg-Emergency {
            background: #fee2e2;
            color: #b91c1c;
        }

        .urg-Urgent {
            background: #fff7ed;
            color: #c2410c;
        }

        .urg-Routine {
            background: #f1f5f9;
            color: #475569;
        }

        .blood-badge-lg {
            font-size: 26px;
            font-weight: 900;
            padding: 10px 18px;
            border-radius: 12px;
            background: #fee2e2;
            color: #b91c1c;
            line-height: 1;
        }

        /* Available Bags table */
        .bag-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bag-table thead th {
            background: #f8fafc;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 9px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .bag-table tbody td {
            padding: 10px 14px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8fafc;
        }

        .bag-table tbody tr:last-child td {
            border-bottom: none;
        }

        .bag-table tbody tr:hover td {
            background: #fafafa;
        }

        /* Crossmatch result */
        .cx-compatible {
            background: #dcfce7;
            color: #166534;
        }

        .cx-incompatible {
            background: #fee2e2;
            color: #991b1b;
        }

        .cx-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        /* Issue history */
        .issue-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 13px;
        }

        .issue-row:last-child {
            border-bottom: none;
        }

        /* Status update bar */
        .status-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }

        /* Reaction badge */
        .reaction-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .rxn-None {
            background: #f1f5f9;
            color: #475569;
        }

        .rxn-Febrile {
            background: #fef9c3;
            color: #854d0e;
        }

        .rxn-Allergic {
            background: #fff7ed;
            color: #c2410c;
        }

        .rxn-Haemolytic {
            background: #fee2e2;
            color: #991b1b;
        }

        .rxn-TACO,
        .rxn-TRALI {
            background: #fee2e2;
            color: #991b1b;
        }

        .modal-body .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            height: 36px;
            padding: 0 12px;
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: #93c5fd;
            background: #fff;
            box-shadow: none;
        }

        .modal-body textarea.form-control {
            height: auto;
            padding: 8px 12px;
        }
    </style>
@endpush

@section('content')

    {{-- ── ALERTS ───────────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── PAGE HEADER ──────────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div style="font-size:11px;color:#94a3b8;font-family:monospace;margin-bottom:4px">{{ $request->request_id }}
            </div>
            <h5 style="font-size:18px;font-weight:700;color:#1e293b;margin:0">{{ $request->patient->name }}</h5>
            <div style="font-size:13px;color:#64748b;margin-top:2px">
                {{ $request->patient->mrn }} · {{ $request->indication }}
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">
            <span class="blood-badge-lg">{{ $request->blood_group }}</span>
            <span class="status-badge rs-{{ str_replace(' ', '-', $request->status) }}">
                {{ $request->status }}
            </span>
            <span class="urgency-badge urg-{{ $request->urgency }}">{{ $request->urgency }}</span>
            <a href="{{ route('blood-bank.requests.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                style="height:34px;font-size:13px">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    {{-- ── STATUS UPDATE BAR ────────────────────────────────────────────── --}}
    @if(!in_array($request->status, ['Fulfilled', 'Cancelled', 'Rejected']))
        <div class="status-bar">
            <form method="POST" action="{{ route('blood-bank.requests.status', $request->id) }}"
                class="d-flex align-items-center gap-3 flex-wrap">
                @csrf @method('PATCH')
                <span style="font-size:13px;font-weight:500;color:#374151">Update Status:</span>
                <select name="status" class="form-select"
                    style="width:180px;height:34px;font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:0 10px">
                    @foreach(['Pending', 'Under Review', 'Crossmatch', 'Approved', 'Partially Fulfilled', 'Fulfilled', 'Cancelled', 'Rejected'] as $s)
                        <option value="{{ $s }}" {{ $request->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <div>
                    <label style="font-size:11px;color:#94a3b8;display:block;margin-bottom:2px">Units Approved</label>
                    <input type="number" name="units_approved" value="{{ $request->units_approved }}" min="0"
                        max="{{ $request->units_required }}"
                        style="width:80px;height:34px;font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:0 10px;background:#f8fafc">
                </div>
                <input type="hidden" name="processed_by" value="{{ auth()->id() }}">
                <button type="submit" class="btn btn-sm btn-primary px-3" style="height:34px;font-size:13px">
                    <i class="bi bi-check2 me-1"></i>Update
                </button>
            </form>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── LEFT COLUMN ───────────────────────────────────────────────── --}}
        <div class="col-12 col-xl-8">

            {{-- Available Bags Section ─ MOST IMPORTANT ──────────────────── --}}
            @if(in_array($request->status, ['Approved', 'Crossmatch', 'Under Review']) && $availableBags->count())
                <div class="detail-card" style="border-color:#dbeafe">
                    <div class="section-title">
                        <i class="bi bi-droplet-fill" style="color:#2563eb"></i>
                        Available Matching Bags ({{ $availableBags->count() }})
                        <span style="font-size:11px;color:#94a3b8;font-weight:400;text-transform:none;letter-spacing:0">
                            — {{ $request->blood_group }} · {{ $request->component }}
                        </span>
                    </div>

                    <table class="bag-table">
                        <thead>
                            <tr>
                                <th>Donation ID</th>
                                <th>Donor</th>
                                <th>Volume</th>
                                <th>Expiry</th>
                                <th>Crossmatch</th>
                                <th>Issue Blood</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableBags as $bag)
                                @php
                                    $days = $bag->daysUntilExpiry();
                                    $expCls = $days <= 3 ? 'color:#d97706;font-weight:600' : '';
                                @endphp
                                <tr>
                                    <td style="font-family:monospace;font-size:12px;color:#6366f1">{{ $bag->donation_id }}</td>
                                    <td>
                                        <div style="font-weight:500">{{ $bag->donor->name }}</div>
                                        <div style="font-size:11px;color:#94a3b8">{{ $bag->bag_number ?? 'No bag #' }}</div>
                                    </td>
                                    <td>{{ $bag->volume_ml }} ml</td>
                                    <td style="{{ $expCls }}">
                                        {{ $bag->expiry_date->format('d M Y') }}
                                        @if($days <= 5)
                                            <div style="font-size:10px">({{ $days }}d left)</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Crossmatch button --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 cx-btn"
                                            style="font-size:11px" data-bs-toggle="modal" data-bs-target="#crossmatchModal"
                                            data-donation-id="{{ $bag->id }}" data-donation-label="{{ $bag->donation_id }}">
                                            <i class="bi bi-arrow-left-right me-1"></i>Cross-match
                                        </button>
                                    </td>
                                    <td>
                                        {{-- Issue button --}}
                                        <button type="button" class="btn btn-sm btn-success py-0 px-2 issue-btn"
                                            style="font-size:11px" data-bs-toggle="modal" data-bs-target="#issueModal"
                                            data-donation-id="{{ $bag->id }}" data-donation-label="{{ $bag->donation_id }}"
                                            data-bag-number="{{ $bag->bag_number }}" data-volume="{{ $bag->volume_ml }}">
                                            <i class="bi bi-droplet-fill me-1"></i>Issue
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Smart Alert Condition --}}
            @elseif(($availableBags->count() + $request->crossmatches->where('result', 'Compatible')->count()) < $request->units_approved && !in_array($request->status, ['Fulfilled', 'Cancelled', 'Rejected']))
                <div class="detail-card" style="border-color:#fecaca;background:#fff5f5">
                    <div style="display:flex;align-items:center;gap:12px">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:24px;color:#dc2626;flex-shrink:0"></i>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#991b1b">Stock Shortage</div>
                            <div style="font-size:12px;color:#b91c1c;margin-top:3px">
                                Need {{ $request->units_approved }} units, but only
                                {{ $availableBags->count() + $request->crossmatches->where('result', 'Compatible')->count() }}
                                are available or ready.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Crossmatch History --}}
            @if($request->crossmatches->count())
                <div class="detail-card">
                    <div class="section-title">
                        <i class="bi bi-arrow-left-right" style="color:#6366f1"></i>
                        Cross-match Records ({{ $request->crossmatches->count() }})
                    </div>
                    <table class="bag-table">
                        <thead>
                            <tr>
                                <th>CRM ID</th>
                                <th>Bag</th>
                                <th>Method</th>
                                <th>Result</th>
                                <th>Performed By</th>
                                <th>Time</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request->crossmatches as $cx)
                                @php $rClass = strtolower($cx->result); @endphp
                                <tr>
                                    <td style="font-family:monospace;font-size:12px">{{ $cx->crossmatch_id }}</td>
                                    <td>
                                        <div>{{ $cx->donation->donation_id }}</div>
                                        <div style="font-size:11px;color:#94a3b8">{{ $cx->donation->donor->name }}</div>
                                    </td>
                                    <td style="font-size:12px">{{ $cx->method }}</td>
                                    <td>
                                        <span class="status-badge cx-{{ $rClass }}" style="font-size:10px;padding:2px 8px">
                                            {{ $cx->result }}
                                        </span>
                                    </td>
                                    <td style="font-size:12px">{{ $cx->performedBy?->first_name ?? '—' }}</td>
                                    <td style="font-size:11px;color:#94a3b8">
                                        {{ $cx->performed_at?->format('d M, h:i A') ?? '—' }}
                                    </td>
                                    <td>
                                        @if($cx->result === 'Pending')
                                            <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 cx-result-btn"
                                                style="font-size:11px" data-bs-toggle="modal" data-bs-target="#cxResultModal"
                                                data-cx-id="{{ $cx->id }}" data-cx-label="{{ $cx->crossmatch_id }}">
                                                Enter Result
                                            </button>
                                        @elseif($cx->result === 'Compatible')
                                            {{-- Yahan check karenge ke kya yeh bag pehle hi issue ho chuka hai? --}}
                                            @php
                                                $isAlreadyIssued = $request->issues->where('blood_donation_id', $cx->blood_donation_id)->first();
                                            @endphp

                                            @if(!$isAlreadyIssued)
                                                {{-- Agar compatible hai aur abhi issue nahi hua, toh Issue button dikhao --}}
                                                <button type="button" class="btn btn-sm btn-success py-0 px-2 issue-btn"
                                                    style="font-size:11px" data-bs-toggle="modal" data-bs-target="#issueModal"
                                                    data-donation-id="{{ $cx->blood_donation_id }}"
                                                    data-donation-label="{{ $cx->donation->donation_id }}"
                                                    data-bag-number="{{ $cx->donation->bag_number }}"
                                                    data-volume="{{ $cx->donation->volume_ml }}">
                                                    <i class="bi bi-droplet-fill me-1"></i>Issue
                                                </button>
                                            @else
                                                {{-- Agar issue ho chuka hai toh badge dikhao --}}
                                                <span class="badge bg-light text-success border" style="font-size:10px">Already
                                                    Issued</span>
                                            @endif
                                        @else
                                            <span style="font-size:11px;color:#ef4444;font-weight:600">Incompatible</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Issue History --}}
            @if($request->issues->count())
                <div class="detail-card">
                    <div class="section-title">
                        <i class="bi bi-droplet-fill" style="color:#ef4444"></i>
                        Issued Blood ({{ $request->issues->count() }} units)
                    </div>
                    @foreach($request->issues as $issue)
                        <div class="issue-row">
                            <div
                                style="width:36px;height:36px;border-radius:50%;background:#fee2e2;color:#b91c1c;
                                                    display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0">
                                {{ $issue->blood_group }}
                            </div>
                            <div style="flex:1;min-width:0">
                                <div style="font-weight:600;color:#1e293b">{{ $issue->issue_id }}</div>
                                <div style="font-size:11px;color:#94a3b8">
                                    Bag: {{ $issue->donation->donation_id }}
                                    · {{ $issue->volume_ml ? $issue->volume_ml . 'ml' : '' }}
                                    · Issued: {{ $issue->issued_at->format('d M, h:i A') }}
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="reaction-badge rxn-{{ str_replace(' ', '-', $issue->reaction_type) }}">
                                    {{ $issue->reaction_type === 'None' ? 'No Reaction' : $issue->reaction_type }}
                                </span>
                                @if($issue->transfusion_completed_at)
                                    <div style="font-size:10px;color:#16a34a;margin-top:2px">Transfusion complete</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- ── RIGHT COLUMN ──────────────────────────────────────────────── --}}
        <div class="col-12 col-xl-4">

            {{-- Request Details --}}
            <div class="detail-card">
                <div class="section-title"><i class="bi bi-clipboard-heart"></i>Request Details</div>
                <div class="detail-row">
                    <span class="detail-label">Blood Group</span>
                    <span class="detail-value"
                        style="font-size:16px;font-weight:800;color:#b91c1c">{{ $request->blood_group }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Component</span>
                    <span class="detail-value">{{ $request->component }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Units Required</span>
                    <span class="detail-value">{{ $request->units_required }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Units Approved</span>
                    <span class="detail-value" style="{{ $request->units_approved > 0 ? 'color:#16a34a' : '' }}">
                        {{ $request->units_approved ?: '—' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Units Issued</span>
                    <span class="detail-value">{{ $request->issues->count() }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Urgency</span>
                    <span class="detail-value">
                        <span class="urgency-badge urg-{{ $request->urgency }}">{{ $request->urgency }}</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Indication</span>
                    <span class="detail-value"
                        style="max-width:160px;text-align:right;word-break:break-word">{{ $request->indication }}</span>
                </div>
                @if($request->patient_hemoglobin)
                    <div class="detail-row">
                        <span class="detail-label">Patient Hb</span>
                        <span class="detail-value">{{ $request->patient_hemoglobin }} g/dL</span>
                    </div>
                @endif
            </div>

            {{-- Patient --}}
            <div class="detail-card">
                <div class="section-title"><i class="bi bi-person"></i>Patient</div>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $request->patient->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">MRN</span>
                    <span class="detail-value" style="font-family:monospace">{{ $request->patient->mrn }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Blood Group</span>
                    <span class="detail-value">{{ $request->patient->blood_group ?? '—' }}</span>
                </div>
                @if($request->ward)
                    <div class="detail-row">
                        <span class="detail-label">Ward / Bed</span>
                        <span class="detail-value">{{ $request->ward }}@if($request->bed_number) /
                        {{ $request->bed_number }}@endif</span>
                    </div>
                @endif
            </div>

            {{-- Requesting Doctor --}}
            @if($request->doctor)
                <div class="detail-card">
                    <div class="section-title"><i class="bi bi-person-badge"></i>Requesting Doctor</div>
                    <div class="detail-row">
                        <span class="detail-label">Doctor</span>
                        <span class="detail-value">
                            Dr. {{ $request->doctor->employee?->first_name }}
                            {{ $request->doctor->employee?->last_name }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Specialization</span>
                        <span class="detail-value">{{ $request->doctor->specialization }}</span>
                    </div>
                </div>
            @endif

            {{-- Timeline --}}
            <div class="detail-card">
                <div class="section-title"><i class="bi bi-clock-history"></i>Timeline</div>
                <div class="detail-row">
                    <span class="detail-label">Requested</span>
                    <span class="detail-value">{{ $request->created_at->format('d M Y, h:i A') }}</span>
                </div>
                @if($request->approved_at)
                    <div class="detail-row">
                        <span class="detail-label">Approved</span>
                        <span class="detail-value">{{ $request->approved_at->format('d M Y, h:i A') }}</span>
                    </div>
                @endif
                @if($request->fulfilled_at)
                    <div class="detail-row">
                        <span class="detail-label">Fulfilled</span>
                        <span class="detail-value"
                            style="color:#16a34a">{{ $request->fulfilled_at->format('d M Y, h:i A') }}</span>
                    </div>
                @endif
                @if($request->processedBy)
                    <div class="detail-row">
                        <span class="detail-label">Processed By</span>
                        <span class="detail-value">{{ $request->processedBy->first_name }}
                            {{ $request->processedBy->last_name }}</span>
                    </div>
                @endif
            </div>

        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════════
    MODALS
    ════════════════════════════════════════════════════════════════════════ --}}

    {{-- ── CROSSMATCH MODAL ────────────────────────────────────────────── --}}
    <div class="modal fade" id="crossmatchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">
                        Initiate Cross-match
                        <span id="cx-bag-label" style="color:#6366f1;font-size:13px;margin-left:6px"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('blood-bank.crossmatch.store') }}">
                    @csrf
                    <input type="hidden" name="blood_request_id" value="{{ $request->id }}">
                    <input type="hidden" name="blood_donation_id" id="cx-donation-id">
                    <div class="modal-body" style="padding:24px">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Cross-match Method <span style="color:#ef4444">*</span></label>
                                <select name="method" class="form-select" required>
                                    @foreach(['Immediate Spin', 'AHG', 'Electronic', 'Saline'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Performed By</label>
                                <select name="performed_by" class="form-select">
                                    <option value="">— Select Staff —</option>
                                    @foreach(\App\Models\Employee::where('employment_status', 'Active')->orderBy('first_name')->get() as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"
                                    style="height:auto;padding:8px 12px"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-arrow-left-right me-1"></i>Start Cross-match
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── CROSSMATCH RESULT MODAL ─────────────────────────────────────── --}}
    <div class="modal fade" id="cxResultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">
                        Record Result — <span id="cx-result-label" style="color:#6366f1"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="cxResultForm" action="">
                    @csrf @method('PATCH')
                    <div class="modal-body" style="padding:24px">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Result <span style="color:#ef4444">*</span></label>
                                <select name="result" class="form-select" required>
                                    <option value="Compatible">✅ Compatible</option>
                                    <option value="Incompatible">❌ Incompatible</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" style="height:auto;padding:8px 12px"
                                    placeholder="Any observations..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">
                            <i class="bi bi-check-lg me-1"></i>Save Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── ISSUE BLOOD MODAL ───────────────────────────────────────────── --}}
    <div class="modal fade" id="issueModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">
                        Issue Blood
                        <span id="issue-bag-label" style="color:#ef4444;font-size:13px;margin-left:6px"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('blood-bank.issues.store') }}">
                    @csrf
                    <input type="hidden" name="blood_request_id" value="{{ $request->id }}">
                    <input type="hidden" name="blood_donation_id" id="issue-donation-id">
                    <input type="hidden" name="patient_id" value="{{ $request->patient_id }}">
                    <input type="hidden" name="blood_group" value="{{ $request->blood_group }}">
                    <input type="hidden" name="component" value="{{ $request->component }}">
                    <div class="modal-body" style="padding:24px">

                        {{-- Confirmation box --}}
                        <div
                            style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px;margin-bottom:16px">
                            <div style="font-size:13px;font-weight:600;color:#991b1b;margin-bottom:6px">
                                ⚠️ Confirm before issuing
                            </div>
                            <div style="font-size:12px;color:#7f1d1d;line-height:1.6">
                                Patient: <strong>{{ $request->patient->name }}</strong>
                                ({{ $request->patient->blood_group ?? 'blood group not set' }})<br>
                                Requesting: <strong>{{ $request->blood_group }}</strong> {{ $request->component }}
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Bag Number</label>
                                <input type="text" name="bag_number" id="issue-bag-number" class="form-control" readonly
                                    style="background:#f1f5f9">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Volume (ml)</label>
                                <input type="number" name="volume_ml" id="issue-volume" class="form-control" step="0.1"
                                    min="1">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Issued By</label>
                                <select name="issued_by" class="form-select">
                                    <option value="">— Select Staff —</option>
                                    @foreach(\App\Models\Employee::where('employment_status', 'Active')->orderBy('first_name')->get() as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"
                                    style="height:auto;padding:8px 12px"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4">
                            <i class="bi bi-droplet-fill me-1"></i>Confirm Issue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Crossmatch modal populate ─────────────────────────────────────────
        document.querySelectorAll('.cx-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('cx-donation-id').value = this.dataset.donationId;
                document.getElementById('cx-bag-label').textContent = '— ' + this.dataset.donationLabel;
            });
        });

        // ── Crossmatch result modal ───────────────────────────────────────────
        document.querySelectorAll('.cx-result-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = document.getElementById('cxResultForm');
                form.action = `/blood-bank/crossmatch/${this.dataset.cxId}/result`;
                document.getElementById('cx-result-label').textContent = this.dataset.cxLabel;
            });
        });

        // ── Issue modal populate ──────────────────────────────────────────────
        document.querySelectorAll('.issue-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('issue-donation-id').value = this.dataset.donationId;
                document.getElementById('issue-bag-label').textContent = '— ' + this.dataset.donationLabel;
                document.getElementById('issue-bag-number').value = this.dataset.bagNumber || '';
                document.getElementById('issue-volume').value = this.dataset.volume || '';
            });
        });
    </script>
@endpush
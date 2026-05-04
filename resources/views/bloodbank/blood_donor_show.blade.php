@extends('layouts.master')

@section('title', $donor->name . ' — Donor Detail')
@section('page-title', 'Donor Detail')
@section('breadcrumb', 'Home / Blood Bank / Donors / ' . $donor->donor_id)

@push('styles')
<style>
    .detail-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; margin-bottom:20px; }
    .section-title {
        font-size:12px; font-weight:600; color:#374151; text-transform:uppercase;
        letter-spacing:.05em; padding-bottom:12px; margin-bottom:16px;
        border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;
    }
    .section-title i { color:#ef4444; }
    .detail-row { display:flex; justify-content:space-between; align-items:flex-start; padding:8px 0; border-bottom:1px solid #f8fafc; font-size:13px; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { color:#94a3b8; font-size:12px; min-width:150px; }
    .detail-value { color:#1e293b; font-weight:500; text-align:right; }

    .blood-badge-lg {
        font-size:28px; font-weight:900; padding:12px 20px; border-radius:12px;
        background:#fee2e2; color:#b91c1c; line-height:1; display:inline-block;
    }

    .eligibility-pill { font-size:12px; padding:4px 12px; border-radius:20px; font-weight:600; }
    .elig-yes { background:#dcfce7; color:#166534; }
    .elig-no  { background:#fee2e2; color:#991b1b; }

    .type-pill { font-size:11px; padding:2px 10px; border-radius:6px; background:#f5f3ff; color:#6d28d9; font-weight:600; }

    /* Donation history table */
    .table-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead th { background:#f8fafc; font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.05em; padding:10px 16px; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
    tbody td { padding:11px 16px; font-size:13px; color:#374151; border-bottom:1px solid #f8fafc; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#fafafa; }

    .status-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; }
    .s-Available { background:#dcfce7; color:#166534; }
    .s-Reserved  { background:#dbeafe; color:#1e40af; }
    .s-Issued    { background:#f1f5f9; color:#475569; }
    .s-Expired   { background:#fef9c3; color:#854d0e; }
    .s-Discarded { background:#fee2e2; color:#991b1b; }
    .sc-Passed   { background:#dcfce7; color:#166534; }
    .sc-Pending  { background:#fef9c3; color:#854d0e; }
    .sc-Failed   { background:#fee2e2; color:#991b1b; }

    /* Stat pills */
    .mini-stat { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; text-align:center; }
    .mini-stat-value { font-size:20px; font-weight:800; color:#1e293b; }
    .mini-stat-label { font-size:10px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; margin-top:2px; }
</style>
@endpush

@section('content')

{{-- ── PAGE HEADER ──────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:54px;height:54px;border-radius:50%;background:#fef2f2;color:#b91c1c;
                    font-size:18px;font-weight:700;display:flex;align-items:center;justify-content:center">
            {{ strtoupper(substr($donor->name, 0, 2)) }}
        </div>
        <div>
            <h5 style="font-size:18px;font-weight:700;color:#1e293b;margin:0">{{ $donor->name }}</h5>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px">
                {{ $donor->donor_id }}
                <span class="type-pill ms-1">{{ $donor->donor_type }}</span>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap justify-content-end">
        <span class="blood-badge-lg">{{ $donor->blood_group }}</span>
        <span class="eligibility-pill {{ $donor->canDonateNow() ? 'elig-yes' : 'elig-no' }}">
            <i class="bi bi-{{ $donor->canDonateNow() ? 'check' : 'x' }}-circle me-1"></i>
            {{ $donor->canDonateNow() ? 'Eligible to Donate' : 'Not Eligible' }}
        </span>
        <a href="{{ route('blood-bank.donors.edit', $donor->id) }}"
           class="btn btn-sm btn-warning px-3" style="height:34px;font-size:13px">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('blood-bank.donors.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:34px;font-size:13px">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- ── LEFT COLUMN ───────────────────────────────────────────────── --}}
    <div class="col-12 col-xl-8">

        {{-- Donation Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="mini-stat">
                    <div class="mini-stat-value" style="color:#b91c1c">{{ $donor->total_donations }}</div>
                    <div class="mini-stat-label">Total Donations</div>
                </div>
            </div>
            <div class="col-4">
                <div class="mini-stat">
                    <div class="mini-stat-value">
                        {{ $donor->last_donation_date ? $donor->last_donation_date->format('d M Y') : '—' }}
                    </div>
                    <div class="mini-stat-label">Last Donation</div>
                </div>
            </div>
            <div class="col-4">
                <div class="mini-stat">
                    <div class="mini-stat-value"
                         style="color:{{ $donor->next_eligible_date?->isFuture() ? '#d97706' : '#16a34a' }}">
                        {{ $donor->next_eligible_date
                            ? ($donor->next_eligible_date->isFuture()
                                ? $donor->next_eligible_date->format('d M Y')
                                : 'Now Eligible')
                            : 'Eligible' }}
                    </div>
                    <div class="mini-stat-label">Next Eligible</div>
                </div>
            </div>
        </div>

        {{-- Donation History Table --}}
        <div class="detail-card p-0" style="padding:0!important">
            <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                <div style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;gap:8px">
                    <i class="bi bi-droplet" style="color:#ef4444"></i>
                    Donation History ({{ $donor->donations->count() }})
                </div>
            </div>

            @if($donor->donations->count())
            <div class="table-card" style="border:none;border-radius:0">
                <table>
                    <thead>
                        <tr>
                            <th>Donation ID</th>
                            <th>Date</th>
                            <th>Component</th>
                            <th>Volume</th>
                            <th>Bag #</th>
                            <th>Expiry</th>
                            <th>Screening</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donor->donations as $don)
                        @php
                            $days    = $don->daysUntilExpiry();
                            $expCls  = $days === 0 ? 'color:#dc2626;font-weight:600' : ($days <= 3 ? 'color:#d97706;font-weight:600' : '');
                            $scClass = 'sc-' . $don->screening_status;
                            $sClass  = 's-' . $don->status;
                        @endphp
                        <tr>
                            <td style="font-family:monospace;font-size:12px;color:#6366f1">{{ $don->donation_id }}</td>
                            <td>{{ $don->donation_date->format('d M Y') }}</td>
                            <td style="font-size:12px">{{ $don->component }}</td>
                            <td style="font-size:12px">{{ $don->volume_ml }} ml</td>
                            <td style="font-size:12px;color:#94a3b8">{{ $don->bag_number ?? '—' }}</td>
                            <td style="font-size:12px;{{ $expCls }}">{{ $don->expiry_date->format('d M Y') }}</td>
                            <td><span class="status-badge {{ $scClass }}">{{ $don->screening_status }}</span></td>
                            <td><span class="status-badge {{ $sClass }}">{{ $don->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="padding:40px;text-align:center;color:#94a3b8">
                <i class="bi bi-droplet" style="font-size:32px;color:#f1f5f9;display:block;margin-bottom:8px"></i>
                <div style="font-size:13px">No donations recorded yet</div>
            </div>
            @endif
        </div>

    </div>

    {{-- ── RIGHT COLUMN ──────────────────────────────────────────────── --}}
    <div class="col-12 col-xl-4">

        {{-- Personal Info --}}
        <div class="detail-card">
            <div class="section-title"><i class="bi bi-person"></i>Personal Info</div>
            <div class="detail-row">
                <span class="detail-label">Father's Name</span>
                <span class="detail-value">{{ $donor->father_name ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date of Birth</span>
                <span class="detail-value">{{ $donor->date_of_birth->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Age</span>
                <span class="detail-value">{{ $donor->age }} years</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Gender</span>
                <span class="detail-value">{{ $donor->gender }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Weight</span>
                <span class="detail-value">{{ $donor->weight_kg ? $donor->weight_kg . ' kg' : '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">CNIC</span>
                <span class="detail-value" style="font-family:monospace">{{ $donor->cnic ?? '—' }}</span>
            </div>
        </div>

        {{-- Contact --}}
        <div class="detail-card">
            <div class="section-title"><i class="bi bi-telephone"></i>Contact</div>
            <div class="detail-row">
                <span class="detail-label">Phone</span>
                <span class="detail-value">{{ $donor->phone }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $donor->email ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">City</span>
                <span class="detail-value">{{ $donor->city ?? '—' }}</span>
            </div>
            @if($donor->address)
            <div class="mt-2" style="font-size:12px;color:#64748b">
                <span style="color:#94a3b8">Address:</span> {{ $donor->address }}
            </div>
            @endif
        </div>

        {{-- Linked Patient (if replacement donor) --}}
        @if($donor->patient)
        <div class="detail-card">
            <div class="section-title"><i class="bi bi-person-heart"></i>Linked Patient</div>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ $donor->patient->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">MRN</span>
                <span class="detail-value" style="font-family:monospace">{{ $donor->patient->mrn }}</span>
            </div>
        </div>
        @endif

        {{-- Eligibility Status --}}
        @if(!$donor->is_eligible)
        <div class="detail-card" style="border-color:#fecaca">
            <div class="section-title"><i class="bi bi-exclamation-triangle" style="color:#dc2626"></i>Ineligibility</div>
            <div style="font-size:13px;color:#374151">{{ $donor->ineligibility_reason ?? 'Reason not specified' }}</div>
            @if($donor->eligible_from)
            <div style="font-size:12px;color:#94a3b8;margin-top:8px">
                Eligible from: <strong style="color:#374151">{{ $donor->eligible_from->format('d M Y') }}</strong>
            </div>
            @endif
        </div>
        @endif

        {{-- Notes --}}
        @if($donor->notes)
        <div class="detail-card">
            <div class="section-title"><i class="bi bi-journal-text"></i>Notes</div>
            <div style="font-size:13px;color:#374151;line-height:1.6">{!! nl2br(e($donor->notes)) !!}</div>
        </div>
        @endif

        {{-- Record Info --}}
        <div class="detail-card">
            <div class="section-title"><i class="bi bi-info-circle"></i>Record Info</div>
            <div class="detail-row">
                <span class="detail-label">Registered</span>
                <span class="detail-value">{{ $donor->created_at->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Updated</span>
                <span class="detail-value">{{ $donor->updated_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

    </div>
</div>

@endsection
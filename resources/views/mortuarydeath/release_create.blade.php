@extends('layouts.master')

@section('title', 'Release Body — ' . $mortuary->mortuary_id)
@section('page-title', 'Body Release')
@section('breadcrumb', 'Home / Mortuary / ' . $mortuary->mortuary_id . ' / Release Body')

@push('styles')
<style>
    .card-section { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; }
    .card-section-header { padding:14px 20px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:10px; }
    .header-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
    .section-body { padding:20px; }
    .form-label-sm { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-bottom:5px; }
    .form-control-clean, .form-select-clean {
        border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#374151;
        background:#f8fafc; padding:8px 12px; width:100%; outline:none; transition:border-color .2s;
    }
    .form-control-clean:focus, .form-select-clean:focus { border-color:#93c5fd; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.08); }
    .required-star { color:#dc2626; }

    /* Patient bar */
    .patient-bar {
        background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;
        padding:14px 16px; display:flex; align-items:center; gap:14px; margin-bottom:24px;
    }
    .patient-bar-avatar {
        width:40px; height:40px; border-radius:50%; background:#fee2e2; color:#991b1b;
        display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; flex-shrink:0;
    }

    /* Checklist items */
    .checklist-item {
        display:flex; align-items:flex-start; gap:10px; padding:12px 14px;
        border:1px solid #e2e8f0; border-radius:8px; margin-bottom:8px;
        cursor:pointer; transition:border-color .15s;
    }
    .checklist-item:hover { border-color:#93c5fd; }
    .checklist-item input[type="checkbox"] { width:16px; height:16px; margin-top:1px; flex-shrink:0; }
    .checklist-item .item-label { font-size:13px; font-weight:500; color:#1e293b; }
    .checklist-item .item-desc  { font-size:11px; color:#94a3b8; margin-top:2px; }

    /* Cert summary */
    .cert-summary {
        background:#f0fdf4; border:1px solid #86efac; border-radius:10px;
        padding:12px 14px; font-size:13px; color:#166534; margin-bottom:16px;
    }
    .cert-summary strong { font-weight:700; }
</style>
@endpush

@section('content')

    @if($errors->any())
    <div class="alert mb-4 d-flex align-items-start gap-2"
         style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Patient Summary Bar --}}
    <div class="patient-bar">
        <div class="patient-bar-avatar">{{ strtoupper(substr($mortuary->patient->name, 0, 2)) }}</div>
        <div>
            <div style="font-size:14px;font-weight:600;color:#1e293b">{{ $mortuary->patient->name }}</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px">
                {{ $mortuary->patient->mrn }} &nbsp;·&nbsp;
                {{ $mortuary->patient->age }} yrs / {{ $mortuary->patient->gender }} &nbsp;·&nbsp;
                Died: {{ \Carbon\Carbon::parse($mortuary->death_datetime)->format('d M Y') }}
            </div>
        </div>
        <div class="ms-auto">
            <code style="font-size:12px;color:#6366f1">{{ $mortuary->mortuary_id }}</code>
        </div>
    </div>

    {{-- Certificate confirmation --}}
    @if($mortuary->latestCertificate)
    <div class="cert-summary">
        <i class="bi bi-check-circle-fill me-2"></i>
        Death Certificate <strong>{{ $mortuary->latestCertificate->certificate_number }}</strong> issued on
        {{ \Carbon\Carbon::parse($mortuary->latestCertificate->issued_at)->format('d M Y') }} —
        Ready for release.
    </div>
    @endif

    {{-- MLC warning --}}
    @if($mortuary->is_medico_legal)
    <div style="background:#fef2f2;border:1px solid #fca5a5;border-left:4px solid #dc2626;border-radius:10px;padding:12px 16px;font-size:13px;color:#991b1b;margin-bottom:20px">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>MLC Case</strong> — Police clearance certificate is required before releasing the body.
    </div>
    @endif

    <form action="{{ route('mortuary.release.store', $mortuary->id) }}" method="POST"
          onsubmit="return confirm('Release body of {{ $mortuary->patient->name }} to the next of kin? This action cannot be undone.')">
        @csrf

        <div class="row g-4">
            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Released To --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#dbeafe;color:#1e40af"><i class="bi bi-person-check"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Released To</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Full Name <span class="required-star">*</span></div>
                                <input type="text" name="released_to_name" class="form-control-clean"
                                       value="{{ old('released_to_name', $mortuary->nok_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Relation to Deceased <span class="required-star">*</span></div>
                                <input type="text" name="released_to_relation" class="form-control-clean"
                                       value="{{ old('released_to_relation', $mortuary->nok_relation) }}"
                                       placeholder="Son, Wife, Father..." required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">CNIC <span class="required-star">*</span></div>
                                <input type="text" name="released_to_cnic" class="form-control-clean"
                                       value="{{ old('released_to_cnic', $mortuary->nok_cnic) }}"
                                       placeholder="XXXXX-XXXXXXX-X" required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Phone <span class="required-star">*</span></div>
                                <input type="text" name="released_to_phone" class="form-control-clean"
                                       value="{{ old('released_to_phone', $mortuary->nok_phone) }}" required>
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Address</div>
                                <input type="text" name="released_to_address" class="form-control-clean"
                                       value="{{ old('released_to_address') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Witnesses --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i class="bi bi-people"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Witnesses</span>
                        <span style="font-size:11px;color:#94a3b8;margin-left:4px">(Optional but recommended)</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Witness 1 — Name</div>
                                <input type="text" name="witness_1_name" class="form-control-clean"
                                       value="{{ old('witness_1_name') }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Witness 1 — CNIC</div>
                                <input type="text" name="witness_1_cnic" class="form-control-clean"
                                       value="{{ old('witness_1_cnic') }}" placeholder="XXXXX-XXXXXXX-X">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Witness 2 — Name</div>
                                <input type="text" name="witness_2_name" class="form-control-clean"
                                       value="{{ old('witness_2_name') }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Witness 2 — CNIC</div>
                                <input type="text" name="witness_2_cnic" class="form-control-clean"
                                       value="{{ old('witness_2_cnic') }}" placeholder="XXXXX-XXXXXXX-X">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transport --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fff7ed;color:#c2410c"><i class="bi bi-truck"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Transport & Destination</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Transport Type</div>
                                <select name="transport_type" class="form-select-clean">
                                    <option value="">— Select —</option>
                                    @foreach(['Hospital Ambulance','Private Ambulance','Private Vehicle','On Foot','Other'] as $t)
                                        <option value="{{ $t }}" {{ old('transport_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Vehicle Number</div>
                                <input type="text" name="vehicle_number" class="form-control-clean"
                                       value="{{ old('vehicle_number') }}" placeholder="e.g. LEA-1234">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Destination <span class="required-star">*</span></div>
                                <input type="text" name="destination" class="form-control-clean"
                                       value="{{ old('destination') }}"
                                       placeholder="Home address or graveyard name" required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Release Date & Time <span class="required-star">*</span></div>
                                <input type="datetime-local" name="released_at" class="form-control-clean"
                                       value="{{ old('released_at', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Released By (Staff) <span class="required-star">*</span></div>
                                <select name="released_by" class="form-select-clean" required>
                                    <option value="">— Select Employee —</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('released_by') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->designation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card-section">
                    <div class="section-body">
                        <div class="form-label-sm">Notes</div>
                        <textarea name="notes" class="form-control-clean" rows="2"
                                  placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- RIGHT --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:16px">

                    {{-- Release Checklist --}}
                    <div class="card-section">
                        <div class="card-section-header">
                            <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i class="bi bi-clipboard-check"></i></div>
                            <span style="font-size:14px;font-weight:600;color:#1e293b">Release Checklist</span>
                        </div>
                        <div class="section-body">

                            <label class="checklist-item">
                                <input type="checkbox" name="death_certificate_provided" value="1"
                                       {{ old('death_certificate_provided', '1') ? 'checked' : '' }}>
                                <div>
                                    <div class="item-label">Death Certificate Provided</div>
                                    <div class="item-desc">Original certificate handed to next of kin</div>
                                </div>
                            </label>

                            <div style="margin-bottom:8px">
                                <div class="form-label-sm">Certificate Number</div>
                                <input type="text" name="death_certificate_number" class="form-control-clean"
                                       value="{{ old('death_certificate_number', $mortuary->latestCertificate?->certificate_number) }}"
                                       placeholder="DC-2026-XXXXX">
                            </div>

                            <label class="checklist-item">
                                <input type="checkbox" name="belongings_returned" value="1"
                                       {{ old('belongings_returned') ? 'checked' : '' }}>
                                <div>
                                    <div class="item-label">Belongings Returned</div>
                                    <div class="item-desc">Personal items returned to family</div>
                                </div>
                            </label>

                            <div style="margin-bottom:8px">
                                <div class="form-label-sm">Belongings List</div>
                                <textarea name="belongings_list" class="form-control-clean" rows="2"
                                          placeholder="e.g. Watch, wallet, phone...">{{ old('belongings_list') }}</textarea>
                            </div>

                            <label class="checklist-item">
                                <input type="checkbox" name="valuables_returned" value="1"
                                       {{ old('valuables_returned') ? 'checked' : '' }}>
                                <div>
                                    <div class="item-label">Valuables / Cash Returned</div>
                                    <div class="item-desc">Any cash or valuables returned</div>
                                </div>
                            </label>

                            <div style="margin-bottom:8px">
                                <div class="form-label-sm">Valuables Amount (Rs.)</div>
                                <input type="number" name="valuables_amount" class="form-control-clean"
                                       value="{{ old('valuables_amount', 0) }}" min="0" step="0.01">
                            </div>

                            @if($mortuary->is_medico_legal)
                            <label class="checklist-item" style="border-color:#fca5a5">
                                <input type="checkbox" name="police_clearance_obtained" value="1"
                                       {{ old('police_clearance_obtained') ? 'checked' : '' }}>
                                <div>
                                    <div class="item-label" style="color:#991b1b">Police Clearance Obtained</div>
                                    <div class="item-desc">Required for MLC cases</div>
                                </div>
                            </label>

                            <div style="margin-bottom:8px">
                                <div class="form-label-sm">Police Clearance No. <span class="required-star">*</span></div>
                                <input type="text" name="police_clearance_number" class="form-control-clean"
                                       value="{{ old('police_clearance_number') }}">
                            </div>
                            @endif

                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success"
                                style="padding:11px;font-size:14px;font-weight:600;border-radius:10px">
                            <i class="bi bi-box-arrow-right me-2"></i>Release Body
                        </button>
                        <a href="{{ route('mortuary.show', $mortuary->id) }}"
                           class="btn btn-outline-secondary"
                           style="padding:10px;font-size:13px;border-radius:10px">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </form>

@endsection

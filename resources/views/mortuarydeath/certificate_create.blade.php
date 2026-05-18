@extends('layouts.master')

@section('title', 'Issue Certificate — ' . $mortuary->mortuary_id)
@section('page-title', 'Issue Death Certificate')
@section('breadcrumb', 'Home / Mortuary / ' . $mortuary->mortuary_id . ' / Issue Certificate')

@push('styles')
    <style>
        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .section-body {
            padding: 20px;
        }

        .form-label-sm {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 5px;
        }

        .form-control-clean,
        .form-select-clean {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            background: #f8fafc;
            padding: 8px 12px;
            width: 100%;
            outline: none;
            transition: border-color .2s;
        }

        .form-control-clean:focus,
        .form-select-clean:focus {
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .08);
        }

        .required-star {
            color: #dc2626;
        }

        /* Patient summary bar */
        .patient-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .patient-bar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1e40af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .patient-bar-info .name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .patient-bar-info .meta {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Info pill */
        .info-pill {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #374151;
        }

        .info-pill .label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
            margin-bottom: 3px;
        }
    </style>
@endpush

@section('content')

    @if ($errors->any())
        <div class="alert mb-4 d-flex align-items-start gap-2"
            style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <ul class="mb-0 ps-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Patient Summary Bar --}}
    <div class="patient-bar">
        <div class="patient-bar-avatar">{{ strtoupper(substr($mortuary->patient->name, 0, 2)) }}</div>
        <div class="patient-bar-info">
            <div class="name">{{ $mortuary->patient->name }}</div>
            <div class="meta">
                {{ $mortuary->patient->mrn }} &nbsp;·&nbsp;
                {{ $mortuary->patient->age }} yrs / {{ $mortuary->patient->gender }} &nbsp;·&nbsp;
                Died: {{ \Carbon\Carbon::parse($mortuary->death_datetime)->format('d M Y, h:i A') }} &nbsp;·&nbsp;
                <strong style="color:#991b1b">{{ $mortuary->immediate_cause }}</strong>
            </div>
        </div>
        <div class="ms-auto">
            <code style="font-size:12px;color:#6366f1">{{ $mortuary->mortuary_id }}</code>
        </div>
    </div>

    <form action="{{ route('mortuary.certificates.store', $mortuary->id) }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Certificate Details --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#dbeafe;color:#1e40af"><i
                                class="bi bi-file-earmark-text"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Certificate Details</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Certificate Type <span class="required-star">*</span></div>
                                <select name="certificate_type" class="form-select-clean" required>
                                    @foreach (['Hospital Death Certificate', 'Medico Legal Certificate', 'Stillbirth Certificate', 'Duplicate'] as $t)
                                        <option value="{{ $t }}"
                                            {{ old('certificate_type', 'Hospital Death Certificate') == $t ? 'selected' : '' }}>
                                            {{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Purpose <span class="required-star">*</span></div>
                                <select name="purpose" class="form-select-clean" required>
                                    @foreach (['Burial / Funeral', 'NADRA Registration', 'Legal Proceedings', 'Insurance Claim', 'Embassy / Visa', 'Other'] as $p)
                                        <option value="{{ $p }}"
                                            {{ old('purpose', 'Burial / Funeral') == $p ? 'selected' : '' }}>
                                            {{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Issue Date & Time <span class="required-star">*</span></div>
                                <input type="datetime-local" name="issued_at" class="form-control-clean"
                                    value="{{ old('issued_at', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">No. of Copies</div>
                                <input type="number" name="total_copies" class="form-control-clean"
                                    value="{{ old('total_copies', 1) }}" min="1" max="10">
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Fee (Rs.)</div>
                                <input type="number" name="fee_charged" class="form-control-clean"
                                    value="{{ old('fee_charged', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Issued To --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i class="bi bi-person-check"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Issued To (Next of Kin)</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Full Name <span class="required-star">*</span></div>
                                <input type="text" name="issued_to_name" class="form-control-clean"
                                    value="{{ old('issued_to_name', $mortuary->nok_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Relation to Deceased <span class="required-star">*</span></div>
                                <input type="text" name="issued_to_relation" class="form-control-clean"
                                    value="{{ old('issued_to_relation', $mortuary->nok_relation) }}"
                                    placeholder="Son, Daughter, Wife, Father..." required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">CNIC</div>
                                <input type="text" name="issued_to_cnic" class="form-control-clean"
                                    value="{{ old('issued_to_cnic', $mortuary->nok_cnic) }}" placeholder="XXXXX-XXXXXXX-X">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Phone</div>
                                <input type="text" name="issued_to_phone" class="form-control-clean"
                                    value="{{ old('issued_to_phone', $mortuary->nok_phone) }}">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Address</div>
                                <input type="text" name="issued_to_address" class="form-control-clean"
                                    value="{{ old('issued_to_address') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Signatory --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-pen"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Signatory</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Certifying Doctor <span class="required-star">*</span></div>
                                <select name="signed_by_doctor" class="form-select-clean" required>
                                    <option value="">— Select Doctor —</option>
                                    @foreach ($doctors as $doc)
                                        <option value="{{ $doc->id }}"
                                            {{ old('signed_by_doctor', $mortuary->declared_by) == $doc->id ? 'selected' : '' }}>
                                            Dr. {{ $doc->employee->first_name }} {{ $doc->employee->last_name }}
                                            @if ($doc->specialization)
                                                — {{ $doc->specialization }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Verified By (Medical Superintendent)</div>
                                <select name="verified_by" class="form-select-clean">
                                    <option value="">— Optional —</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ old('verified_by') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->designation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Issued By (Counter Staff)</div>
                                <select name="issued_by" class="form-select-clean">
                                    <option value="">— Optional —</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ old('issued_by') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-label-sm">Fee Paid?</div>
                                <div class="d-flex gap-3 mt-2">
                                    <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:6px">
                                        <input type="radio" name="fee_paid" value="1"
                                            {{ old('fee_paid', '0') == '1' ? 'checked' : '' }}> Yes
                                    </label>
                                    <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:6px">
                                        <input type="radio" name="fee_paid" value="0"
                                            {{ old('fee_paid', '0') == '0' ? 'checked' : '' }}> No
                                    </label>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="card-section">
                    <div class="section-body">
                        <div class="form-label-sm">Remarks (Optional)</div>
                        <textarea name="remarks" class="form-control-clean" rows="2" placeholder="Any special notes...">{{ old('remarks') }}</textarea>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- RIGHT --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:16px">

                    {{-- Death Summary --}}
                    <div class="card-section">
                        <div class="card-section-header">
                            <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i
                                    class="bi bi-info-circle"></i></div>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">Death Summary</span>
                        </div>
                        <div class="section-body" style="display:flex;flex-direction:column;gap:10px">
                            <div class="info-pill">
                                <div class="label">Immediate Cause</div>
                                {{ $mortuary->immediate_cause }}
                            </div>
                            @if ($mortuary->underlying_cause)
                                <div class="info-pill">
                                    <div class="label">Underlying Cause</div>
                                    {{ $mortuary->underlying_cause }}
                                </div>
                            @endif
                            <div class="info-pill">
                                <div class="label">Manner of Death</div>
                                {{ $mortuary->manner_of_death }}
                            </div>
                            <div class="info-pill">
                                <div class="label">Death Location</div>
                                {{ $mortuary->death_location }}
                                @if ($mortuary->ward)
                                    — {{ $mortuary->ward }}
                                @endif
                            </div>
                            @if ($mortuary->is_medico_legal)
                                <div
                                    style="background:#fee2e2;border-radius:8px;padding:10px 14px;font-size:12px;color:#991b1b;font-weight:500">
                                    <i class="bi bi-shield-exclamation me-2"></i>MLC Case — {{ $mortuary->mlc_number }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Certificates Count --}}
                    @if ($mortuary->deathCertificates->count() > 0)
                        <div
                            style="background:#fef9c3;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;font-size:13px;color:#854d0e">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>{{ $mortuary->deathCertificates->count() }}</strong> certificate(s) already issued for
                            this record.
                            This will be copy #{{ $mortuary->deathCertificates->count() + 1 }}.
                        </div>
                    @endif

                    {{-- Submit --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"
                            style="padding:11px;font-size:14px;font-weight:600;border-radius:10px">
                            <i class="bi bi-file-earmark-check me-2"></i>Issue Certificate
                        </button>
                        <a href="{{ route('mortuary.show', $mortuary->id) }}" class="btn btn-outline-secondary"
                            style="padding:10px;font-size:13px;border-radius:10px">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </form>

@endsection

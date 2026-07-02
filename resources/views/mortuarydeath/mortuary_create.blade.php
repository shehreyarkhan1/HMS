@extends('layouts.master')

@section('title', 'New Mortuary Record')
@section('page-title', 'Mortuary — New Record')
@section('breadcrumb', 'Home / Mortuary / New Record')

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
    </style>
@endpush

@section('content')

    <form action="{{ route('mortuary.store') }}" method="POST" id="mortuaryForm">
        @csrf

        <div class="row g-4">

            {{-- ── LEFT col-lg-8 ──────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- 1. Patient & Death Info --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i class="bi bi-person-x"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Patient & Death Information</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">

                            {{-- Patient --}}
                            <div class="col-md-6">
                                {{-- <div class="form-label-sm">Patient <span class="required-star">*</span></div> --}}
                                @if ($patient)
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div
                                        style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 14px;font-size:13px;color:#1e40af">
                                        <i class="bi bi-person-check-fill me-2"></i>
                                        <strong>{{ $patient->name }}</strong>
                                        <span
                                            style="color:#94a3b8;font-size:11px;margin-left:6px">{{ $patient->mrn }}</span>
                                    </div>
                                @else
                                    <div class="patient-search-wrapper">
                                        <x-patient-search :patient="$selectedPatient" />
                                    </div>
                                @endif
                            </div>

                            {{-- Death DateTime --}}
                            <div class="col-md-6">
                                <div class="form-label-sm">Date & Time of Death <span class="required-star">*</span></div>
                                <input type="datetime-local" name="death_datetime" class="form-control-clean"
                                    value="{{ old('death_datetime') }}" required>
                            </div>

                            {{-- Death Location --}}
                            <div class="col-md-4">
                                <div class="form-label-sm">Death Location <span class="required-star">*</span></div>
                                <select name="death_location" class="form-select-clean" required>
                                    @foreach (['Ward', 'ICU', 'CCU', 'Emergency', 'OT', 'DOA', 'Outside Hospital'] as $loc)
                                        <option value="{{ $loc }}"
                                            {{ old('death_location') == $loc ? 'selected' : '' }}>{{ $loc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <div class="form-label-sm">Ward</div>
                                <input type="text" name="ward" class="form-control-clean" value="{{ old('ward') }}"
                                    placeholder="e.g. Medical A">
                            </div>

                            <div class="col-md-4">
                                <div class="form-label-sm">Bed Number</div>
                                <input type="text" name="bed_number" class="form-control-clean"
                                    value="{{ old('bed_number') }}" placeholder="e.g. 12A">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- 2. Cause of Death --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fef9c3;color:#854d0e"><i
                                class="bi bi-clipboard2-pulse"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Cause of Death</span>
                        <span style="font-size:11px;color:#94a3b8;margin-left:4px">ICD-10 Standard</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-label-sm">Part I (a) — Immediate Cause <span class="required-star">*</span>
                                </div>
                                <input type="text" name="immediate_cause" class="form-control-clean"
                                    value="{{ old('immediate_cause') }}" placeholder="e.g. Cardiorespiratory Arrest"
                                    required>
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Part I (b) — Intermediate Cause</div>
                                <input type="text" name="intermediate_cause" class="form-control-clean"
                                    value="{{ old('intermediate_cause') }}" placeholder="e.g. Acute Myocardial Infarction">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Part I (c) — Underlying Cause</div>
                                <input type="text" name="underlying_cause" class="form-control-clean"
                                    value="{{ old('underlying_cause') }}" placeholder="e.g. Coronary Artery Disease">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Part II — Contributing Cause</div>
                                <input type="text" name="contributing_cause" class="form-control-clean"
                                    value="{{ old('contributing_cause') }}" placeholder="e.g. Type 2 Diabetes Mellitus">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Manner of Death <span class="required-star">*</span></div>
                                <select name="manner_of_death" class="form-select-clean" required>
                                    @foreach (['Natural', 'Accidental', 'Homicidal', 'Suicidal', 'Undetermined'] as $m)
                                        <option value="{{ $m }}"
                                            {{ old('manner_of_death', 'Natural') == $m ? 'selected' : '' }}>
                                            {{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Declaring Doctor</div>
                                <select name="declared_by" class="form-select-clean">
                                    <option value="">— Select Doctor —</option>
                                    @foreach ($doctors as $doc)
                                        <option value="{{ $doc->id }}"
                                            {{ old('declared_by') == $doc->id ? 'selected' : '' }}>
                                            Dr. {{ $doc->employee->first_name }} {{ $doc->employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Declared At</div>
                                <input type="datetime-local" name="declared_at" class="form-control-clean"
                                    value="{{ old('declared_at') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Body Information --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i class="bi bi-box-seam"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Body Information</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-label-sm">Locker Number</div>
                                <input type="text" name="locker_number" class="form-control-clean"
                                    value="{{ old('locker_number') }}" placeholder="e.g. L-07">
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Body Condition</div>
                                <select name="body_condition" class="form-select-clean">
                                    <option value="">— Select —</option>
                                    @foreach (['Normal', 'Decomposed', 'Burned', 'Traumatic', 'Other'] as $c)
                                        <option value="{{ $c }}"
                                            {{ old('body_condition') == $c ? 'selected' : '' }}>{{ $c }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Body Weight (kg)</div>
                                <input type="number" name="body_weight_kg" class="form-control-clean"
                                    value="{{ old('body_weight_kg') }}" step="0.1" min="0" max="300">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Identification Marks</div>
                                <textarea name="identification_marks" class="form-control-clean" rows="2"
                                    placeholder="Scars, tattoos, birthmarks...">{{ old('identification_marks') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Postmortem --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-hospital"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Postmortem</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Postmortem Required?</div>
                                <div class="d-flex gap-3 mt-2">
                                    <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:6px">
                                        <input type="radio" name="postmortem_required" value="1"
                                            {{ old('postmortem_required') == '1' ? 'checked' : '' }}
                                            onchange="showPostmortem(true)"> Yes
                                    </label>
                                    <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:6px">
                                        <input type="radio" name="postmortem_required" value="0"
                                            {{ old('postmortem_required', '0') == '0' ? 'checked' : '' }}
                                            onchange="showPostmortem(false)"> No
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6" id="postmortemOrderedBy" style="display:none">
                                <div class="form-label-sm">Ordered By</div>
                                <select name="postmortem_ordered_by" class="form-select-clean">
                                    <option value="">— Select —</option>
                                    @foreach (['Doctor', 'Police', 'Court', 'Hospital'] as $o)
                                        <option value="{{ $o }}"
                                            {{ old('postmortem_ordered_by') == $o ? 'selected' : '' }}>{{ $o }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. MLC --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i
                                class="bi bi-shield-exclamation"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Medico Legal Case (MLC)</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:8px">
                                    <input type="checkbox" name="is_medico_legal" value="1" id="mlcCheckbox"
                                        {{ old('is_medico_legal') ? 'checked' : '' }} onchange="toggleMlc(this.checked)"
                                        style="width:16px;height:16px">
                                    <span style="font-weight:500">This is a Medico Legal Case</span>
                                </label>
                            </div>

                            <div id="mlcFields" style="display:none" class="col-12">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-label-sm">MLC Number</div>
                                        <input type="text" name="mlc_number" class="form-control-clean"
                                            value="{{ old('mlc_number') }}" placeholder="MLC-2026-001">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-label-sm">Police Station</div>
                                        <input type="text" name="police_station" class="form-control-clean"
                                            value="{{ old('police_station') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-label-sm">FIR Number</div>
                                        <input type="text" name="fir_number" class="form-control-clean"
                                            value="{{ old('fir_number') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-sm">Investigating Officer</div>
                                        <input type="text" name="investigating_officer" class="form-control-clean"
                                            value="{{ old('investigating_officer') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-sm">Police Informed At</div>
                                        <input type="datetime-local" name="police_informed_at" class="form-control-clean"
                                            value="{{ old('police_informed_at') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card-section">
                    <div class="section-body">
                        <div class="form-label-sm">Notes / Remarks</div>
                        <textarea name="notes" class="form-control-clean" rows="3" placeholder="Any additional observations...">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ── RIGHT col-lg-4 ──────────────────────────────────── --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:16px">

                    {{-- Next of Kin --}}
                    <div class="card-section">
                        <div class="card-section-header">
                            <div class="header-icon" style="background:#dbeafe;color:#1e40af"><i
                                    class="bi bi-people"></i></div>
                            <span style="font-size:14px;font-weight:600;color:#1e293b">Next of Kin (Waris)</span>
                        </div>
                        <div class="section-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-label-sm">Name</div>
                                    <input type="text" name="nok_name" class="form-control-clean"
                                        value="{{ old('nok_name') }}">
                                </div>
                                <div class="col-6">
                                    <div class="form-label-sm">Relation</div>
                                    <input type="text" name="nok_relation" class="form-control-clean"
                                        value="{{ old('nok_relation') }}" placeholder="Son, Wife...">
                                </div>
                                <div class="col-6">
                                    <div class="form-label-sm">Phone</div>
                                    <input type="text" name="nok_phone" class="form-control-clean"
                                        value="{{ old('nok_phone') }}">
                                </div>
                                <div class="col-12">
                                    <div class="form-label-sm">CNIC</div>
                                    <input type="text" name="nok_cnic" class="form-control-clean"
                                        value="{{ old('nok_cnic') }}" placeholder="XXXXX-XXXXXXX-X">
                                </div>
                                <div class="col-12">
                                    <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:8px">
                                        <input type="checkbox" name="nok_informed" value="1"
                                            {{ old('nok_informed') ? 'checked' : '' }} style="width:15px;height:15px">
                                        Family Informed
                                    </label>
                                </div>
                                <div class="col-12">
                                    <div class="form-label-sm">Informed At</div>
                                    <input type="datetime-local" name="nok_informed_at" class="form-control-clean"
                                        value="{{ old('nok_informed_at') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Processed By --}}
                    <div class="card-section">
                        <div class="card-section-header">
                            <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i
                                    class="bi bi-person-badge"></i></div>
                            <span style="font-size:14px;font-weight:600;color:#1e293b">Processed By</span>
                        </div>
                        <div class="section-body">
                            <div class="form-label-sm">Staff Member</div>
                            <select name="admitted_by" class="form-select-clean">
                                <option value="">— Select Employee —</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ old('admitted_by') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->designation }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger"
                            style="padding:11px;font-size:14px;font-weight:600;border-radius:10px">
                            <i class="bi bi-check-circle me-2"></i>Create Mortuary Record
                        </button>
                        <a href="{{ route('mortuary.index') }}" class="btn btn-outline-secondary"
                            style="padding:10px;font-size:13px;border-radius:10px">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </a>
                    </div>

                </div>
            </div>{{-- end col-lg-4 --}}

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function toggleMlc(show) {
            document.getElementById('mlcFields').style.display = show ? 'block' : 'none';
            if (show) {
                // MLC case mein postmortem required hota hai
                const pmYes = document.querySelector('[name="postmortem_required"][value="1"]');
                if (pmYes) {
                    pmYes.checked = true;
                    showPostmortem(true);
                }
            }
        }

        function showPostmortem(show) {
            document.getElementById('postmortemOrderedBy').style.display = show ? 'block' : 'none';
        }

        // Page load par old values restore karein
        document.addEventListener('DOMContentLoaded', () => {
            const mlcCheck = document.getElementById('mlcCheckbox');
            if (mlcCheck?.checked) toggleMlc(true);

            const pmChecked = document.querySelector('[name="postmortem_required"]:checked');
            if (pmChecked?.value === '1') showPostmortem(true);
        });
    </script>
@endpush

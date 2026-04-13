@extends('layouts.master')

@section('title', 'Edit Patient — ' . $patient->name)
@section('page-title', 'Edit Patient')
@section('breadcrumb', 'Home / Patients / Edit')

@push('styles')
<style>
    /* ── Layout ── */
    .edit-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    /* ── Patient hero banner ── */
    .patient-hero {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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
    .patient-hero::after {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .patient-hero::before {
        content: '';
        position: absolute;
        right: 60px; bottom: -40px;
        width: 100px; height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .hero-avatar {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,.2);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 700;
        flex-shrink: 0;
        border: 2px solid rgba(255,255,255,.35);
    }
    .hero-mrn {
        font-size: 11px; opacity: .75; letter-spacing: .05em;
        text-transform: uppercase; margin-bottom: 2px;
    }
    .hero-name {
        font-size: 20px; font-weight: 700; line-height: 1.2;
    }
    .hero-meta {
        font-size: 12px; opacity: .8; margin-top: 4px;
    }
    .hero-badge {
        margin-left: auto;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 12px; font-weight: 600;
        backdrop-filter: blur(4px);
    }

    /* ── Form Card ── */
    .form-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .form-card-header {
        padding: 14px 22px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex; align-items: center; gap: 10px;
    }
    .form-card-header .section-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
    }
    .form-card-header .section-title {
        font-size: 13px; font-weight: 600; color: #1e293b;
    }
    .form-card-body {
        padding: 22px;
    }

    /* ── Form controls ── */
    .form-label-custom {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 5px;
    }
    .form-control-custom,
    .form-select-custom {
        height: 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        color: #1e293b;
        background: #f8fafc;
        padding: 0 12px;
        width: 100%;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .form-control-custom:focus,
    .form-select-custom:focus {
        outline: none;
        border-color: #3b82f6;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }
    textarea.form-control-custom {
        height: auto;
        padding: 10px 12px;
    }
    .form-control-custom.is-invalid,
    .form-select-custom.is-invalid {
        border-color: #f87171;
        background: #fff5f5;
    }
    .field-error {
        font-size: 11px; color: #dc2626; margin-top: 4px;
    }

    /* ── Radio group (gender) ── */
    .radio-group {
        display: flex; gap: 8px; flex-wrap: wrap;
    }
    .radio-pill input[type="radio"] { display: none; }
    .radio-pill label {
        display: flex; align-items: center; gap: 6px;
        height: 40px; padding: 0 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px; color: #374151;
        cursor: pointer;
        background: #f8fafc;
        transition: all .15s;
        white-space: nowrap;
    }
    .radio-pill input[type="radio"]:checked + label {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 500;
    }

    /* ── Status select coloring ── */
    .status-Active     { color: #166534 !important; }
    .status-Admitted   { color: #1e40af !important; }
    .status-Discharged { color: #374151 !important; }
    .status-Deceased   { color: #991b1b !important; }

    /* ── Action bar ── */
    .action-bar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; flex-wrap: wrap;
    }
    .btn-save {
        height: 42px;
        padding: 0 28px;
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        color: #fff;
        border: none; border-radius: 9px;
        font-size: 13px; font-weight: 600;
        display: flex; align-items: center; gap: 7px;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        box-shadow: 0 2px 8px rgba(59,130,246,.35);
    }
    .btn-save:hover { opacity: .92; transform: translateY(-1px); }
    .btn-cancel {
        height: 42px; padding: 0 20px;
        border: 1px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #475569;
        font-size: 13px; font-weight: 500;
        display: flex; align-items: center; gap: 7px;
        text-decoration: none;
        transition: background .15s, border-color .15s;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #1e293b; }

    /* ── Alert success ── */
    .alert-success-custom {
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 10px; padding: 12px 16px;
        font-size: 13px; color: #166534;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="edit-wrapper">

    {{-- Success flash --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Validation errors summary --}}
    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4"
         style="border-radius:10px;font-size:13px">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Patient Hero Banner --}}
    <div class="patient-hero">
        <div class="hero-avatar">{{ $patient->initials }}</div>
        <div>
            <div class="hero-mrn">MRN · {{ $patient->mrn }}</div>
            <div class="hero-name">{{ $patient->name }}</div>
            <div class="hero-meta">
                {{ $patient->age }} yrs &nbsp;·&nbsp; {{ $patient->gender }}
                @if($patient->blood_group)
                    &nbsp;·&nbsp; {{ $patient->blood_group }}
                @endif
                &nbsp;·&nbsp; Registered {{ $patient->created_at->format('d M Y') }}
            </div>
        </div>
        <span class="hero-badge">{{ $patient->patient_type }}</span>
    </div>

    {{-- Edit Form --}}
    <form method="POST" action="{{ route('patients.update', $patient->id) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- ① Personal Information --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dbeafe;color:#1d4ed8">
                    <i class="bi bi-person-fill"></i>
                </div>
                <span class="section-title">Personal Information</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="form-label-custom">Full Name <span class="text-danger">*</span></div>
                        <input type="text" name="name"
                               class="form-control-custom @error('name') is-invalid @enderror"
                               value="{{ old('name', $patient->name) }}"
                               placeholder="Patient full name">
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-label-custom">Father / Guardian Name</div>
                        <input type="text" name="father_name"
                               class="form-control-custom @error('father_name') is-invalid @enderror"
                               value="{{ old('father_name', $patient->father_name) }}"
                               placeholder="Father or guardian name">
                        @error('father_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Date of Birth <span class="text-danger">*</span></div>
                        <input type="date" name="date_of_birth"
                               class="form-control-custom @error('date_of_birth') is-invalid @enderror"
                               value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Blood Group</div>
                        <select name="blood_group"
                                class="form-select-custom @error('blood_group') is-invalid @enderror">
                            <option value="">— Select —</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}"
                                    {{ old('blood_group', $patient->blood_group) == $bg ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_group')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">CNIC</div>
                        <input type="text" name="cnic"
                               class="form-control-custom @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic', $patient->cnic) }}"
                               placeholder="13 digits, no dashes" maxlength="13">
                        @error('cnic')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-label-custom">Gender <span class="text-danger">*</span></div>
                        <div class="radio-group">
                            @foreach(['Male','Female','Other'] as $g)
                            <div class="radio-pill">
                                <input type="radio" id="gender_{{ $g }}" name="gender" value="{{ $g }}"
                                    {{ old('gender', $patient->gender) == $g ? 'checked' : '' }}>
                                <label for="gender_{{ $g }}">
                                    <i class="bi bi-{{ $g == 'Male' ? 'gender-male' : ($g == 'Female' ? 'gender-female' : 'gender-ambiguous') }}"></i>
                                    {{ $g }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('gender')<div class="field-error mt-1">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ② Contact & Location --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dcfce7;color:#15803d">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <span class="section-title">Contact &amp; Location</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="form-label-custom">Phone <span class="text-danger">*</span></div>
                        <input type="text" name="phone"
                               class="form-control-custom @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $patient->phone) }}"
                               placeholder="+92 3xx xxxxxxx">
                        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Emergency Contact</div>
                        <input type="text" name="emergency_contact"
                               class="form-control-custom @error('emergency_contact') is-invalid @enderror"
                               value="{{ old('emergency_contact', $patient->emergency_contact) }}"
                               placeholder="Emergency phone">
                        @error('emergency_contact')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Emergency Relation</div>
                        <input type="text" name="emergency_relation"
                               class="form-control-custom @error('emergency_relation') is-invalid @enderror"
                               value="{{ old('emergency_relation', $patient->emergency_relation) }}"
                               placeholder="e.g. Father, Spouse">
                        @error('emergency_relation')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8">
                        <div class="form-label-custom">Address</div>
                        <textarea name="address" rows="2"
                                  class="form-control-custom @error('address') is-invalid @enderror"
                                  placeholder="Street, area, neighbourhood…">{{ old('address', $patient->address) }}</textarea>
                        @error('address')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">City</div>
                        <input type="text" name="city"
                               class="form-control-custom @error('city') is-invalid @enderror"
                               value="{{ old('city', $patient->city) }}"
                               placeholder="City">
                        @error('city')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ③ Clinical Details --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fef3c7;color:#b45309">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                </div>
                <span class="section-title">Clinical Details</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="form-label-custom">Patient Type <span class="text-danger">*</span></div>
                        <select name="patient_type"
                                class="form-select-custom @error('patient_type') is-invalid @enderror">
                            <option value="">— Select —</option>
                            @foreach(['OPD','IPD','Emergency'] as $pt)
                                <option value="{{ $pt }}"
                                    {{ old('patient_type', $patient->patient_type) == $pt ? 'selected' : '' }}>
                                    {{ $pt }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_type')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Status <span class="text-danger">*</span></div>
                        <select name="status"
                                class="form-select-custom @error('status') is-invalid @enderror">
                            @foreach(['Active','Admitted','Discharged','Deceased'] as $st)
                                <option value="{{ $st }}"
                                    {{ old('status', $patient->status) == $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Assigned Doctor</div>
                        <select name="doctor_id"
                                class="form-select-custom @error('doctor_id') is-invalid @enderror">
                            <option value="">— No doctor —</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ old('doctor_id', $patient->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-label-custom">Clinical Notes</div>
                        <textarea name="notes" rows="3"
                                  class="form-control-custom @error('notes') is-invalid @enderror"
                                  placeholder="Diagnosis, symptoms, allergies, or other relevant notes…">{{ old('notes', $patient->notes) }}</textarea>
                        @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="form-card" style="padding:16px 22px">
            <div class="action-bar">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('patients.show', $patient->id) }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i> Back to Profile
                    </a>
                    <a href="{{ route('patients.index') }}" class="btn-cancel">
                        <i class="bi bi-list"></i> All Patients
                    </a>
                </div>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
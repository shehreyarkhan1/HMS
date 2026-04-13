@extends('layouts.master')

@section('title', 'Register Patient')
@section('page-title', 'Register New Patient')
@section('breadcrumb', 'Home / Patients / New')

@push('styles')
<style>
    .form-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .form-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .form-card-header h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    .form-card-body { padding: 20px; }

    .form-label {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 5px;
    }
    .form-control, .form-select {
        font-size: 13px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        color: #1e293b;
        background: #fff;
    }
    .form-control:focus, .form-select:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #f87171;
    }
    .invalid-feedback { font-size: 11px; }

    .required-star { color: #ef4444; margin-left: 2px; }

    .type-btn {
        flex: 1;
        padding: 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        text-align: center;
        transition: all 0.15s;
    }
    .type-btn:hover { border-color: #93c5fd; color: #1d4ed8; }
    .type-btn.selected-opd       { border-color: #1d4ed8; background: #eff6ff; color: #1d4ed8; }
    .type-btn.selected-ipd       { border-color: #16a34a; background: #f0fdf4; color: #16a34a; }
    .type-btn.selected-emergency { border-color: #ea580c; background: #fff7ed; color: #ea580c; }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('patients.store') }}">
@csrf

<div class="row g-3">

    {{-- LEFT COLUMN --}}
    <div class="col-12 col-lg-8">

        {{-- Patient Type --}}
        <div class="form-card mb-3">
            <div class="form-card-header">
                <h6><i class="bi bi-person-plus me-2 text-primary"></i>Patient type</h6>
            </div>
            <div class="form-card-body">
                <div class="d-flex gap-2">
                    <button type="button" class="type-btn {{ old('patient_type','OPD') == 'OPD' ? 'selected-opd' : '' }}"
                            onclick="selectType('OPD', this)">
                        <i class="bi bi-person-walking d-block mb-1" style="font-size:20px"></i>
                        OPD
                    </button>
                    <button type="button" class="type-btn {{ old('patient_type') == 'IPD' ? 'selected-ipd' : '' }}"
                            onclick="selectType('IPD', this)">
                        <i class="bi bi-hospital d-block mb-1" style="font-size:20px"></i>
                        IPD
                    </button>
                    <button type="button" class="type-btn {{ old('patient_type') == 'Emergency' ? 'selected-emergency' : '' }}"
                            onclick="selectType('Emergency', this)">
                        <i class="bi bi-activity d-block mb-1" style="font-size:20px"></i>
                        Emergency
                    </button>
                </div>
                <input type="hidden" name="patient_type" id="patient_type"
                       value="{{ old('patient_type', 'OPD') }}">
                @error('patient_type')
                    <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Personal Info --}}
        <div class="form-card mb-3">
            <div class="form-card-header">
                <h6><i class="bi bi-person me-2 text-primary"></i>Personal information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full name <span class="required-star">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Ahmed Khan">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Father's name</label>
                        <input type="text" name="father_name"
                               class="form-control @error('father_name') is-invalid @enderror"
                               value="{{ old('father_name') }}" placeholder="e.g. Muhammad Khan">
                        @error('father_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Date of birth <span class="required-star">*</span></label>
                        <input type="date" name="date_of_birth"
                               class="form-control @error('date_of_birth') is-invalid @enderror"
                               value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d') }}">
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="required-star">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select gender</option>
                            <option value="Male"   {{ old('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other"  {{ old('gender') == 'Other'  ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Blood group</label>
                        <select name="blood_group"
                                class="form-select @error('blood_group') is-invalid @enderror">
                            <option value="">Unknown</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">CNIC <span style="color:#94a3b8">(13 digits)</span></label>
                        <input type="text" name="cnic"
                               class="form-control @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic') }}" placeholder="3520112345678" maxlength="13">
                        @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" name="city"
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city') }}" placeholder="e.g. Peshawar">
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Full home address">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="form-card mb-3">
            <div class="form-card-header">
                <h6><i class="bi bi-telephone me-2 text-success"></i>Contact information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="required-star">*</span></label>
                        <input type="text" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="03xx-xxxxxxx">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Emergency contact</label>
                        <input type="text" name="emergency_contact"
                               class="form-control @error('emergency_contact') is-invalid @enderror"
                               value="{{ old('emergency_contact') }}" placeholder="03xx-xxxxxxx">
                        @error('emergency_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Relation</label>
                        <input type="text" name="emergency_relation"
                               class="form-control @error('emergency_relation') is-invalid @enderror"
                               value="{{ old('emergency_relation') }}" placeholder="e.g. Father, Wife">
                        @error('emergency_relation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-journal-text me-2 text-warning"></i>Notes</h6>
            </div>
            <div class="form-card-body">
                <textarea name="notes" rows="3"
                          class="form-control @error('notes') is-invalid @enderror"
                          placeholder="Any additional notes, allergies, medical history...">{{ old('notes') }}</textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-12 col-lg-4">

        {{-- Assign Doctor --}}
        <div class="form-card mb-3">
            <div class="form-card-header">
                <h6><i class="bi bi-person-badge me-2 text-info"></i>Assign doctor</h6>
            </div>
            <div class="form-card-body">
                <label class="form-label">Doctor</label>
                <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                    <option value="">— Not assigned —</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Summary Card --}}
        <div class="form-card mb-3" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <h6 style="color:#1d4ed8"><i class="bi bi-info-circle me-2"></i>Quick info</h6>
            </div>
            <div class="form-card-body" style="font-size:13px;color:#374151;line-height:2">
                <div><i class="bi bi-check-circle text-success me-2"></i>MRN auto-generated</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Age calculated from DOB</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Soft delete enabled</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>CNIC must be unique</div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-person-check me-2"></i>Register patient
            </button>
            <a href="{{ route('patients.index') }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">
                Cancel
            </a>
        </div>

    </div>
</div>

</form>
@endsection

@push('scripts')
<script>
    function selectType(type, btn) {
        document.querySelectorAll('.type-btn').forEach(b => {
            b.className = 'type-btn';
        });
        const map = { OPD: 'selected-opd', IPD: 'selected-ipd', Emergency: 'selected-emergency' };
        btn.classList.add(map[type]);
        document.getElementById('patient_type').value = type;
    }
</script>
@endpush
@extends('layouts.master')

@section('title', 'Add Doctor')
@section('page-title', 'Add New Doctor')
@section('breadcrumb', 'Home / Doctors / New')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<style>
    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:16px; }
    .form-card-header { padding:13px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc; }
    .form-card-header h6 { margin:0; font-size:13px; font-weight:600; color:#374151; }
    .form-card-body { padding:20px; }
    .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
    .form-control, .form-select {
        font-size:13px; border:1px solid #e2e8f0; border-radius:8px;
        padding:8px 12px; color:#1e293b;
    }
    .form-control:focus, .form-select:focus {
        border-color:#93c5fd; box-shadow:0 0 0 3px rgba(59,130,246,.1);
    }
    .form-control.is-invalid, .form-select.is-invalid { border-color:#f87171; }
    .invalid-feedback { font-size:11px; }
    .required-star { color:#ef4444; margin-left:2px; }

    /* Select2 styling match karna */
    .select2-container .select2-selection--single {
        height:38px; border:1px solid #e2e8f0; border-radius:8px;
        display:flex; align-items:center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size:13px; color:#1e293b; padding-left:12px; line-height:38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height:36px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color:#93c5fd; box-shadow:0 0 0 3px rgba(59,130,246,.1);
    }
    .select2-dropdown { border:1px solid #e2e8f0; border-radius:8px; font-size:13px; }
    .select2-results__option--highlighted { background:#eff6ff !important; color:#1e293b !important; }

    /* Employee preview card */
    #emp-preview {
        display:none;
        background:#f8fafc; border:1px solid #e2e8f0;
        border-radius:10px; padding:14px; margin-top:10px;
    }
    .emp-preview-avatar {
        width:44px; height:44px; border-radius:50%;
        background:#dbeafe; color:#1d4ed8; font-size:15px; font-weight:700;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .emp-preview-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }

    /* Days checkboxes */
    .day-check { display:none; }
    .day-label {
        display:inline-flex; align-items:center; justify-content:center;
        width:40px; height:32px; border:1px solid #e2e8f0; border-radius:6px;
        font-size:12px; font-weight:500; color:#64748b; cursor:pointer;
        transition:all 0.15s; user-select:none;
    }
    .day-check:checked + .day-label {
        background:#eff6ff; border-color:#3b82f6; color:#1d4ed8; font-weight:600;
    }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('doctors.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-12 col-lg-8">

        {{-- Employee Selection --}}
        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <h6 style="color:#1d4ed8">
                    <i class="bi bi-person-check me-2"></i>Select Employee
                </h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Employee <span class="required-star">*</span></label>
                        <select name="employee_id" id="employeeSelect"
                                class="form-select @error('employee_id') is-invalid @enderror"
                                style="width:100%">
                            <option value="">-- Koi employee select karein --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                        data-name="{{ $emp->first_name }} {{ $emp->last_name }}"
                                        data-id="{{ $emp->employee_id }}"
                                        data-dept="{{ $emp->department }}"
                                        data-designation="{{ $emp->designation }}"
                                        data-phone="{{ $emp->personal_phone }}"
                                        data-email="{{ $emp->office_email ?? $emp->personal_email }}"
                                        data-shift="{{ $emp->shift }}"
                                        data-photo="{{ $emp->photo ? asset('storage/'.$emp->photo) : '' }}"
                                        {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                    ({{ $emp->employee_id }} — {{ $emp->department }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Employee Preview Card --}}
                    <div class="col-12">
                        <div id="emp-preview">
                            <div class="d-flex align-items-center gap-3">
                                <div class="emp-preview-avatar" id="prev-avatar">AB</div>
                                <div>
                                    <div style="font-size:14px;font-weight:600;color:#1e293b" id="prev-name">—</div>
                                    <div style="font-size:12px;color:#6366f1" id="prev-designation">—</div>
                                    <div style="font-size:11px;color:#94a3b8" id="prev-emp-id">—</div>
                                </div>
                                <div class="ms-auto text-end">
                                    <div style="font-size:12px;color:#64748b"><i class="bi bi-building me-1"></i><span id="prev-dept">—</span></div>
                                    <div style="font-size:12px;color:#64748b"><i class="bi bi-telephone me-1"></i><span id="prev-phone">—</span></div>
                                    <div style="font-size:12px;color:#64748b"><i class="bi bi-clock me-1"></i><span id="prev-shift">—</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Clinical Information --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-clipboard2-pulse me-2 text-danger"></i>Clinical information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Specialization <span class="required-star">*</span></label>
                        <input type="text" name="specialization"
                               class="form-control @error('specialization') is-invalid @enderror"
                               value="{{ old('specialization') }}" placeholder="e.g. Cardiologist">
                        @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Qualification <span class="required-star">*</span></label>
                        <input type="text" name="qualification"
                               class="form-control @error('qualification') is-invalid @enderror"
                               value="{{ old('qualification') }}" placeholder="e.g. MBBS, FCPS">
                        @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Doctor type <span class="required-star">*</span></label>
                        <select name="doctor_type" class="form-select @error('doctor_type') is-invalid @enderror">
                            @foreach(['Consultant','Medical Officer','House Officer','Visiting','Specialist'] as $type)
                                <option value="{{ $type }}" {{ old('doctor_type','Medical Officer') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">PMDC number</label>
                        <input type="text" name="pmdc_number"
                               class="form-control @error('pmdc_number') is-invalid @enderror"
                               value="{{ old('pmdc_number') }}" placeholder="e.g. PMDC-12345">
                        @error('pmdc_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sub department</label>
                        <input type="text" name="sub_department"
                               class="form-control @error('sub_department') is-invalid @enderror"
                               value="{{ old('sub_department') }}" placeholder="e.g. Interventional Cardiology">
                        @error('sub_department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Consultation fee (Rs)</label>
                        <input type="number" name="consultation_fee"
                               class="form-control @error('consultation_fee') is-invalid @enderror"
                               value="{{ old('consultation_fee', 0) }}" min="0" step="50">
                        @error('consultation_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Avg. consultation (mins)</label>
                        <input type="number" name="avg_consultation_mins"
                               class="form-control @error('avg_consultation_mins') is-invalid @enderror"
                               value="{{ old('avg_consultation_mins', 15) }}" min="5" max="120">
                        @error('avg_consultation_mins') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Availability <span class="required-star">*</span></label>
                        <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                            @foreach(['Available','In Consultation','On Leave','Off Duty'] as $av)
                                <option value="{{ $av }}" {{ old('availability','Available') == $av ? 'selected' : '' }}>
                                    {{ $av }}
                                </option>
                            @endforeach
                        </select>
                        @error('availability') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Available Days --}}
                    <div class="col-12">
                        <label class="form-label">Available days</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                <div>
                                    <input type="checkbox" name="available_days[]"
                                           value="{{ $day }}" id="day_{{ $day }}"
                                           class="day-check"
                                           {{ in_array($day, old('available_days', ['Mon','Tue','Wed','Thu','Fri'])) ? 'checked' : '' }}>
                                    <label for="day_{{ $day }}" class="day-label">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Accepts new patients --}}
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="accepts_new_patients" id="accepts_new_patients"
                                   class="form-check-input" value="1"
                                   {{ old('accepts_new_patients', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="accepts_new_patients" style="font-size:13px">
                                Accepts new patients
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Bio / About</label>
                        <textarea name="bio" rows="3"
                                  class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Brief doctor profile, experience...">{{ old('bio') }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Clinical notes</label>
                        <textarea name="clinical_notes" rows="2"
                                  class="form-control @error('clinical_notes') is-invalid @enderror"
                                  placeholder="Internal clinical notes (not shown to patients)...">{{ old('clinical_notes') }}</textarea>
                        @error('clinical_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">

        {{-- Info Card --}}
        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <h6 style="color:#1d4ed8"><i class="bi bi-info-circle me-2"></i>Quick info</h6>
            </div>
            <div class="form-card-body" style="font-size:13px;color:#374151;line-height:2">
                <div><i class="bi bi-check-circle text-success me-2"></i>Doctor ID auto-generated</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Employee se naam, shift, photo milega</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Sirf active employees dikhte hain</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Ek employee — ek hi doctor record</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Soft delete enabled</div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-person-check me-2"></i>Save doctor
            </button>
            <a href="{{ route('doctors.index') }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">
                Cancel
            </a>
        </div>

    </div>
</div>

</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // Select2 init
    $('#employeeSelect').select2({
        placeholder: '-- Koi employee select karein --',
        allowClear: true,
        width: '100%',
    });

    // Employee preview
    $('#employeeSelect').on('change', function () {
        const sel = $(this).find(':selected');
        const id  = sel.val();

        if (!id) {
            $('#emp-preview').hide();
            return;
        }

        const name        = sel.data('name');
        const empId       = sel.data('id');
        const dept        = sel.data('dept');
        const designation = sel.data('designation');
        const phone       = sel.data('phone');
        const shift       = sel.data('shift');
        const photo       = sel.data('photo');
        const initials    = name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();

        $('#prev-name').text('Dr. ' + name);
        $('#prev-designation').text(designation);
        $('#prev-emp-id').text(empId);
        $('#prev-dept').text(dept);
        $('#prev-phone').text(phone || '—');
        $('#prev-shift').text(shift || '—');

        const avatar = $('#prev-avatar');
        if (photo) {
            avatar.html('<img src="' + photo + '" alt="">');
        } else {
            avatar.html(initials);
            avatar.css({'background': '#dbeafe', 'color': '#1d4ed8'});
        }

        $('#emp-preview').show();
    });

    // Trigger agar old value ha
    if ($('#employeeSelect').val()) {
        $('#employeeSelect').trigger('change');
    }
});
</script>
@endpush
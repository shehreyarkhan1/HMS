@extends('layouts.master')

@section('title', 'Edit Doctor')
@section('page-title', 'Edit Doctor')
@section('breadcrumb', 'Home / Doctors / ' . $doctor->doctor_id . ' / Edit')

@push('styles')
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
    .form-control:focus, .form-select:focus { border-color:#93c5fd; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    .form-control.is-invalid, .form-select.is-invalid { border-color:#f87171; }
    .invalid-feedback { font-size:11px; }
    .required-star { color:#ef4444; margin-left:2px; }

    .readonly-field {
        background:#f8fafc; color:#94a3b8; cursor:not-allowed;
        border:1px solid #e2e8f0; border-radius:8px;
        padding:8px 12px; font-size:13px;
    }

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

@php
    $emp = $doctor->employee;
    $fullName = $emp ? $emp->first_name . ' ' . $emp->last_name : '—';
    $initials = $emp ? strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) : 'DR';
    $photo    = $emp?->photo ? asset('storage/'.$emp->photo) : null;
    $currentDays = $doctor->available_days ?? [];
@endphp

<form method="POST" action="{{ route('doctors.update', $doctor->id) }}">
@csrf @method('PUT')

<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-12 col-lg-8">

        {{-- Employee Info (read-only) --}}
        <div class="form-card" style="border-color:#fde68a">
            <div class="form-card-header" style="background:#fffbeb">
                <h6 style="color:#92400e">
                    <i class="bi bi-person-badge me-2"></i>Linked employee
                    <span style="font-size:11px;font-weight:400;color:#d97706">(edit karna ho toh Employee module mein jayein)</span>
                </h6>
            </div>
            <div class="form-card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:52px;height:52px;border-radius:50%;overflow:hidden;flex-shrink:0;
                                background:#dbeafe;color:#1d4ed8;font-size:16px;font-weight:700;
                                display:flex;align-items:center;justify-content:center;border:2px solid #e2e8f0">
                        @if($photo)
                            <img src="{{ $photo }}" alt="{{ $fullName }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:600;color:#1e293b">Dr. {{ $fullName }}</div>
                        <div style="font-size:12px;color:#6366f1">{{ $emp?->designation ?? '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $emp?->employee_id }}</div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <div class="readonly-field">{{ $emp?->department ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <div class="readonly-field">{{ $emp?->personal_phone ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Shift</label>
                        <div class="readonly-field">{{ $emp?->shift ?? '—' }}</div>
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
                               value="{{ old('specialization', $doctor->specialization) }}">
                        @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Qualification <span class="required-star">*</span></label>
                        <input type="text" name="qualification"
                               class="form-control @error('qualification') is-invalid @enderror"
                               value="{{ old('qualification', $doctor->qualification) }}">
                        @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Doctor type <span class="required-star">*</span></label>
                        <select name="doctor_type" class="form-select @error('doctor_type') is-invalid @enderror">
                            @foreach(['Consultant','Medical Officer','House Officer','Visiting','Specialist'] as $type)
                                <option value="{{ $type }}" {{ old('doctor_type', $doctor->doctor_type) == $type ? 'selected' : '' }}>
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
                               value="{{ old('pmdc_number', $doctor->pmdc_number) }}">
                        @error('pmdc_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sub department</label>
                        <input type="text" name="sub_department"
                               class="form-control @error('sub_department') is-invalid @enderror"
                               value="{{ old('sub_department', $doctor->sub_department) }}">
                        @error('sub_department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Consultation fee (Rs)</label>
                        <input type="number" name="consultation_fee"
                               class="form-control @error('consultation_fee') is-invalid @enderror"
                               value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0" step="50">
                        @error('consultation_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Avg. consultation (mins)</label>
                        <input type="number" name="avg_consultation_mins"
                               class="form-control @error('avg_consultation_mins') is-invalid @enderror"
                               value="{{ old('avg_consultation_mins', $doctor->avg_consultation_mins) }}" min="5" max="120">
                        @error('avg_consultation_mins') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Availability <span class="required-star">*</span></label>
                        <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                            @foreach(['Available','In Consultation','On Leave','Off Duty'] as $av)
                                <option value="{{ $av }}" {{ old('availability', $doctor->availability) == $av ? 'selected' : '' }}>
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
                                           {{ in_array($day, old('available_days', $currentDays)) ? 'checked' : '' }}>
                                    <label for="day_{{ $day }}" class="day-label">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-4">
                        <div class="form-check">
                            <input type="checkbox" name="accepts_new_patients" id="accepts_new_patients"
                                   class="form-check-input" value="1"
                                   {{ old('accepts_new_patients', $doctor->accepts_new_patients) ? 'checked' : '' }}>
                            <label class="form-check-label" for="accepts_new_patients" style="font-size:13px">
                                Accepts new patients
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active"
                                   class="form-check-input" value="1"
                                   {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active" style="font-size:13px">
                                Doctor is active
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Bio / About</label>
                        <textarea name="bio" rows="3"
                                  class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $doctor->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Clinical notes</label>
                        <textarea name="clinical_notes" rows="2"
                                  class="form-control @error('clinical_notes') is-invalid @enderror">{{ old('clinical_notes', $doctor->clinical_notes) }}</textarea>
                        @error('clinical_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">

        {{-- Doctor ID --}}
        <div class="form-card" style="border-color:#fde68a">
            <div class="form-card-body" style="background:#fffbeb">
                <div style="font-size:12px;color:#92400e;font-weight:600">Doctor ID</div>
                <div style="font-size:20px;font-weight:700;color:#b45309;letter-spacing:.05em">
                    {{ $doctor->doctor_id }}
                </div>
                <div style="font-size:11px;color:#d97706;margin-top:2px">Cannot be changed</div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-save me-2"></i>Update doctor
            </button>
            <a href="{{ route('doctors.show', $doctor->id) }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">
                Cancel
            </a>
        </div>

    </div>
</div>

</form>
@endsection
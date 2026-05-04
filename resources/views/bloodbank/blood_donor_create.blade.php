{{-- ════════════════════════════════════════════════════════════
     CREATE: resources/views/blood-bank/donors/create.blade.php
     ════════════════════════════════════════════════════════════ --}}
@extends('layouts.master')
@section('title','Register Donor')
@section('page-title','Register Donor')
@section('breadcrumb','Home / Blood Bank / Donors / Register')

@push('styles')
<style>
    .form-section { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; margin-bottom:20px; }
    .section-title { font-size:13px; font-weight:600; color:#374151; text-transform:uppercase; letter-spacing:.05em; padding-bottom:12px; margin-bottom:20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px; }
    .section-title i { color:#ef4444; }
    .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:4px; }
    .form-control, .form-select { font-size:13px; border:1px solid #e2e8f0; border-radius:8px; color:#374151; background:#f8fafc; height:38px; padding:0 12px; }
    .form-control:focus, .form-select:focus { border-color:#93c5fd; background:#fff; box-shadow:none; }
    .required-star { color:#ef4444; }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('blood-bank.donors.store') }}">
@csrf

<div class="row g-4">
<div class="col-12 col-xl-8">

    {{-- Personal Info --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-person"></i>Personal Information</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="required-star">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Father's Name</label>
                <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Date of Birth <span class="required-star">*</span></label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                @error('date_of_birth')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender <span class="required-star">*</span></label>
                <select name="gender" class="form-select" required>
                    <option value="">— Select —</option>
                    @foreach(['Male','Female','Other'] as $g)
                        <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Weight (kg)</label>
                <input type="number" name="weight_kg" class="form-control" value="{{ old('weight_kg') }}" step="0.1" min="45" placeholder="Min 45kg">
            </div>
            <div class="col-md-6">
                <label class="form-label">CNIC</label>
                <input type="text" name="cnic" class="form-control" value="{{ old('cnic') }}" placeholder="12345-1234567-1">
                @error('cnic')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-telephone"></i>Contact</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Phone <span class="required-star">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                @error('phone')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-journal-text"></i>Notes</div>
        <textarea name="notes" class="form-control" rows="3" style="height:auto;padding:10px 12px"
                  placeholder="Any medical history or notes...">{{ old('notes') }}</textarea>
    </div>

</div>

<div class="col-12 col-xl-4">

    {{-- Donation Info --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-droplet"></i>Blood & Donation</div>
        <div class="mb-3">
            <label class="form-label">Blood Group <span class="required-star">*</span></label>
            <select name="blood_group" class="form-select" required>
                <option value="">— Select Blood Group —</option>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
            @error('blood_group')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="form-label">Donor Type <span class="required-star">*</span></label>
            <select name="donor_type" class="form-select" required>
                @foreach(['Voluntary','Replacement','Autologous','Directed'] as $t)
                    <option value="{{ $t }}" {{ old('donor_type','Voluntary') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Linked Patient (for Replacement/Directed) --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-person-heart"></i>Linked Patient</div>
        <p style="font-size:12px;color:#94a3b8;margin-bottom:12px">
            Required for Replacement or Directed donations
        </p>
        <select name="patient_id" class="form-select">
            <option value="">— No Linked Patient —</option>
            @foreach($patients as $patient)
                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name }} ({{ $patient->mrn }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Buttons --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px">
        <button type="submit" class="btn btn-success w-100 mb-2" style="height:40px;font-size:14px;font-weight:500">
            <i class="bi bi-person-plus me-2"></i>Register Donor
        </button>
        <a href="{{ route('blood-bank.donors.index') }}"
           class="btn btn-outline-secondary w-100" style="height:40px;font-size:14px">Cancel</a>
    </div>

</div>
</div>
</form>
@endsection
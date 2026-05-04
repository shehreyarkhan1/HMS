@extends('layouts.master')

@section('title', 'Edit Donor — ' . $donor->name)
@section('page-title', 'Edit Donor')
@section('breadcrumb', 'Home / Blood Bank / Donors / ' . $donor->donor_id . ' / Edit')

@push('styles')
<style>
    .form-section { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; margin-bottom:20px; }
    .section-title { font-size:13px; font-weight:600; color:#374151; text-transform:uppercase; letter-spacing:.05em; padding-bottom:12px; margin-bottom:20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px; }
    .section-title i { color:#ef4444; }
    .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:4px; }
    .form-control, .form-select { font-size:13px; border:1px solid #e2e8f0; border-radius:8px; color:#374151; background:#f8fafc; height:38px; padding:0 12px; }
    .form-control:focus, .form-select:focus { border-color:#93c5fd; background:#fff; box-shadow:none; }
    .required-star { color:#ef4444; }
    .check-card { display:flex; align-items:center; gap:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; cursor:pointer; }
    .check-card input[type=checkbox] { width:16px; height:16px; accent-color:#ef4444; }
    .check-card-label { font-size:13px; color:#374151; font-weight:500; }
    .check-card-sub { font-size:11px; color:#94a3b8; }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('blood-bank.donors.update', $donor->id) }}">
@csrf @method('PUT')

<div class="row g-4">
<div class="col-12 col-xl-8">

    {{-- Personal Info --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-person"></i>Personal Information</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="required-star">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $donor->name) }}" required>
                @error('name')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Father's Name</label>
                <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $donor->father_name) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Date of Birth <span class="required-star">*</span></label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $donor->date_of_birth->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender <span class="required-star">*</span></label>
                <select name="gender" class="form-select" required>
                    @foreach(['Male','Female','Other'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $donor->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Weight (kg)</label>
                <input type="number" name="weight_kg" class="form-control" value="{{ old('weight_kg', $donor->weight_kg) }}" step="0.1" min="45">
            </div>
            <div class="col-md-6">
                <label class="form-label">CNIC</label>
                <input type="text" name="cnic" class="form-control" value="{{ old('cnic', $donor->cnic) }}">
                @error('cnic')<div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $donor->city) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $donor->address) }}">
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-telephone"></i>Contact</div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Phone <span class="required-star">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $donor->phone) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $donor->email) }}">
            </div>
        </div>
    </div>

    {{-- Eligibility (can change on edit) --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-shield-check"></i>Eligibility Status</div>
        <div class="mb-3">
            <label class="check-card" for="is_eligible">
                <input type="checkbox" name="is_eligible" id="is_eligible" value="1"
                       {{ old('is_eligible', $donor->is_eligible) ? 'checked' : '' }}>
                <div>
                    <div class="check-card-label">Donor is Eligible</div>
                    <div class="check-card-sub">Uncheck to defer donor temporarily or permanently</div>
                </div>
            </label>
        </div>
        <div class="row g-3" id="ineligibility-section"
             style="display: {{ old('is_eligible', $donor->is_eligible) ? 'none' : 'flex' }}">
            <div class="col-12">
                <label class="form-label">Ineligibility Reason</label>
                <input type="text" name="ineligibility_reason" class="form-control"
                       value="{{ old('ineligibility_reason', $donor->ineligibility_reason) }}"
                       placeholder="e.g. Recent illness, medication, tattoo within 6 months">
            </div>
            <div class="col-md-6">
                <label class="form-label">Eligible From (re-entry date)</label>
                <input type="date" name="eligible_from" class="form-control"
                       value="{{ old('eligible_from', $donor->eligible_from?->format('Y-m-d')) }}">
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-journal-text"></i>Notes</div>
        <textarea name="notes" class="form-control" rows="3" style="height:auto;padding:10px 12px">{{ old('notes', $donor->notes) }}</textarea>
    </div>

</div>

<div class="col-12 col-xl-4">

    {{-- Blood & Donation Type --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-droplet"></i>Blood & Donation</div>
        <div class="mb-3">
            <label class="form-label">Blood Group <span class="required-star">*</span></label>
            <select name="blood_group" class="form-select" required>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ old('blood_group', $donor->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Donor Type <span class="required-star">*</span></label>
            <select name="donor_type" class="form-select" required>
                @foreach(['Voluntary','Replacement','Autologous','Directed'] as $t)
                    <option value="{{ $t }}" {{ old('donor_type', $donor->donor_type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Linked Patient --}}
    <div class="form-section">
        <div class="section-title"><i class="bi bi-person-heart"></i>Linked Patient</div>
        <select name="patient_id" class="form-select">
            <option value="">— None —</option>
            @foreach($patients as $patient)
                <option value="{{ $patient->id }}" {{ old('patient_id', $donor->patient_id) == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name }} ({{ $patient->mrn }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Action Buttons --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px">
        <button type="submit" class="btn btn-warning w-100 mb-2" style="height:40px;font-size:14px;font-weight:500">
            <i class="bi bi-check-lg me-2"></i>Save Changes
        </button>
        <a href="{{ route('blood-bank.donors.show', $donor->id) }}"
           class="btn btn-outline-secondary w-100" style="height:40px;font-size:14px">Cancel</a>
    </div>

</div>
</div>
</form>

@endsection

@push('scripts')
<script>
// Toggle ineligibility section
document.getElementById('is_eligible').addEventListener('change', function () {
    const section = document.getElementById('ineligibility-section');
    section.style.display = this.checked ? 'none' : 'flex';
});
</script>
@endpush
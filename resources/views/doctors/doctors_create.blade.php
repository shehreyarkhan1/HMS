@extends('layouts.master')

@section('title', 'Add Doctor')
@section('page-title', 'Add New Doctor')
@section('breadcrumb', 'Home / Doctors / New')

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
    .form-control:focus, .form-select:focus {
        border-color:#93c5fd; box-shadow:0 0 0 3px rgba(59,130,246,.1);
    }
    .form-control.is-invalid, .form-select.is-invalid { border-color:#f87171; }
    .invalid-feedback { font-size:11px; }
    .required-star { color:#ef4444; margin-left:2px; }

    .photo-upload {
        border:2px dashed #e2e8f0; border-radius:10px;
        padding:24px; text-align:center; cursor:pointer;
        transition:border-color 0.2s;
    }
    .photo-upload:hover { border-color:#93c5fd; }
    .photo-preview {
        width:80px; height:80px; border-radius:50%; object-fit:cover;
        margin:0 auto 8px; display:none;
    }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('doctors.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-12 col-lg-8">

        {{-- Personal Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-person me-2 text-primary"></i>Personal information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Full name <span class="required-star">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Dr. Kamran Malik">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gender <span class="required-star">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select gender</option>
                            <option value="Male"   {{ old('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other"  {{ old('gender') == 'Other'  ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

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
                        <label class="form-label">CNIC <span style="color:#94a3b8">(13 digits)</span></label>
                        <input type="text" name="cnic"
                               class="form-control @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic') }}" placeholder="3520112345678" maxlength="13">
                        @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="doctor@hospital.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Bio / About</label>
                        <textarea name="bio" rows="3"
                                  class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Brief doctor profile, experience...">{{ old('bio') }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Department & Shift --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-building me-2 text-success"></i>Department & shift</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Department <span class="required-star">*</span></label>
                        <select name="department" class="form-select @error('department') is-invalid @enderror">
                            <option value="">Select department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                        @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Shift <span class="required-star">*</span></label>
                        <select name="shift" class="form-select @error('shift') is-invalid @enderror">
                            <option value="Morning"  {{ old('shift','Morning') == 'Morning'  ? 'selected' : '' }}>Morning</option>
                            <option value="Evening"  {{ old('shift') == 'Evening'  ? 'selected' : '' }}>Evening</option>
                            <option value="Night"    {{ old('shift') == 'Night'    ? 'selected' : '' }}>Night</option>
                            <option value="Full Day" {{ old('shift') == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                        </select>
                        @error('shift') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Shift start time</label>
                        <input type="time" name="shift_start"
                               class="form-control @error('shift_start') is-invalid @enderror"
                               value="{{ old('shift_start') }}">
                        @error('shift_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Shift end time</label>
                        <input type="time" name="shift_end"
                               class="form-control @error('shift_end') is-invalid @enderror"
                               value="{{ old('shift_end') }}">
                        @error('shift_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="required-star">*</span></label>
                        <input type="text" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="03xx-xxxxxxx">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Consultation fee (Rs)</label>
                        <input type="number" name="consultation_fee"
                               class="form-control @error('consultation_fee') is-invalid @enderror"
                               value="{{ old('consultation_fee', 0) }}" min="0" step="50">
                        @error('consultation_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Availability <span class="required-star">*</span></label>
                        <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                            <option value="Available" {{ old('availability','Available') == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="On Leave"  {{ old('availability') == 'On Leave'  ? 'selected' : '' }}>On Leave</option>
                            <option value="Off Duty"  {{ old('availability') == 'Off Duty'  ? 'selected' : '' }}>Off Duty</option>
                        </select>
                        @error('availability') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">

        {{-- Photo Upload --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-image me-2 text-warning"></i>Doctor photo</h6>
            </div>
            <div class="form-card-body">
                <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                    <img id="photoPreview" class="photo-preview" src="" alt="Preview">
                    <i class="bi bi-camera" style="font-size:28px;color:#94a3b8;display:block;margin-bottom:6px"></i>
                    <div style="font-size:13px;color:#64748b">Click to upload photo</div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:4px">JPG, PNG — max 2MB</div>
                </div>
                <input type="file" name="photo" id="photoInput"
                       accept="image/jpg,image/jpeg,image/png"
                       class="d-none @error('photo') is-invalid @enderror"
                       onchange="previewPhoto(this)">
                @error('photo') <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <h6 style="color:#1d4ed8"><i class="bi bi-info-circle me-2"></i>Quick info</h6>
            </div>
            <div class="form-card-body" style="font-size:13px;color:#374151;line-height:2">
                <div><i class="bi bi-check-circle text-success me-2"></i>Doctor ID auto-generated</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Photo stored in storage/</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Soft delete enabled</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Used in patient assignment</div>
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
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('photoPreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.previousElementSibling.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
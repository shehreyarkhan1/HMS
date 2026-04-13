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
    .photo-upload { border:2px dashed #e2e8f0; border-radius:10px; padding:20px; text-align:center; cursor:pointer; transition:border-color 0.2s; }
    .photo-upload:hover { border-color:#93c5fd; }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('doctors.update', $doctor->id) }}" enctype="multipart/form-data">
@csrf @method('PUT')

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
                               value="{{ old('name', $doctor->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender <span class="required-star">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $doctor->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
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
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic"
                               class="form-control @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic', $doctor->cnic) }}" maxlength="13">
                        @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $doctor->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="3"
                                  class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $doctor->bio) }}</textarea>
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
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('department', $doctor->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shift <span class="required-star">*</span></label>
                        <select name="shift" class="form-select @error('shift') is-invalid @enderror">
                            @foreach(['Morning','Evening','Night','Full Day'] as $s)
                                <option value="{{ $s }}" {{ old('shift', $doctor->shift) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('shift') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shift start</label>
                        <input type="time" name="shift_start"
                               class="form-control @error('shift_start') is-invalid @enderror"
                               value="{{ old('shift_start', $doctor->shift_start) }}">
                        @error('shift_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shift end</label>
                        <input type="time" name="shift_end"
                               class="form-control @error('shift_end') is-invalid @enderror"
                               value="{{ old('shift_end', $doctor->shift_end) }}">
                        @error('shift_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="required-star">*</span></label>
                        <input type="text" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $doctor->phone) }}">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Consultation fee</label>
                        <input type="number" name="consultation_fee"
                               class="form-control @error('consultation_fee') is-invalid @enderror"
                               value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0" step="50">
                        @error('consultation_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Availability</label>
                        <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                            @foreach(['Available','On Leave','Off Duty'] as $av)
                                <option value="{{ $av }}" {{ old('availability', $doctor->availability) == $av ? 'selected' : '' }}>{{ $av }}</option>
                            @endforeach
                        </select>
                        @error('availability') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active"
                                   class="form-check-input" value="1"
                                   {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active" style="font-size:13px">
                                Doctor is active
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">

        {{-- Current Photo --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-image me-2 text-warning"></i>Photo</h6>
            </div>
            <div class="form-card-body">
                @if($doctor->photo_url)
                    <img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}"
                         id="photoPreview"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;display:block;margin:0 auto 12px">
                @else
                    <img id="photoPreview" style="width:80px;height:80px;border-radius:50%;display:none;margin:0 auto 12px">
                @endif
                <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                    <i class="bi bi-camera" style="font-size:22px;color:#94a3b8"></i>
                    <div style="font-size:12px;color:#64748b;margin-top:4px">Change photo</div>
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*"
                       class="d-none" onchange="previewPhoto(this)">
                @error('photo') <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Doctor ID Info --}}
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

@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const p = document.getElementById('photoPreview');
                p.src = e.target.result;
                p.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
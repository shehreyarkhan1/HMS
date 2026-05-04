{{-- ============================================================ --}}
{{-- resources/views/employees/edit.blade.php                   --}}
{{-- ============================================================ --}}
@extends('layouts.master')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')
@section('breadcrumb', 'Home / Employees / ' . $employee->employee_id . ' / Edit')

@push('styles')
<style>
    .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:16px}
    .form-card-header{padding:13px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;gap:8px}
    .form-card-header h6{margin:0;font-size:13px;font-weight:600;color:#374151}
    .form-card-body{padding:20px}
    .form-label{font-size:12px;font-weight:500;color:#374151;margin-bottom:5px}
    .form-control,.form-select{font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;color:#1e293b}
    .form-control:focus,.form-select:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
    .form-control.is-invalid{border-color:#f87171}
    .invalid-feedback{font-size:11px}
    .required-star{color:#ef4444;margin-left:2px}
    .section-icon{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
    .day-btn{border:1.5px solid #e2e8f0;border-radius:6px;padding:5px 10px;font-size:12px;font-weight:500;color:#64748b;background:#fff;cursor:pointer;transition:all .15s;user-select:none}
    .day-btn.selected{border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-3">
    <div class="col-12 col-lg-8">

        {{-- Personal --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#eff6ff"><i class="bi bi-person" style="color:#1d4ed8"></i></div>
                <h6>Personal information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First name <span class="required-star">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $employee->first_name) }}">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last name <span class="required-star">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $employee->last_name) }}">
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="required-star">*</span></label>
                        <select name="gender" class="form-select">
                            @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender',$employee->gender) == $g ? 'selected':'' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Marital status</label>
                        <select name="marital_status" class="form-select">
                            @foreach(['Single','Married','Divorced','Widowed'] as $m)
                                <option value="{{ $m }}" {{ old('marital_status',$employee->marital_status) == $m ? 'selected':'' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Unknown</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group',$employee->blood_group) == $bg ? 'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Personal phone <span class="required-star">*</span></label>
                        <input type="text" name="personal_phone" class="form-control @error('personal_phone') is-invalid @enderror"
                               value="{{ old('personal_phone',$employee->personal_phone) }}">
                        @error('personal_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Office email</label>
                        <input type="email" name="office_email" class="form-control @error('office_email') is-invalid @enderror"
                               value="{{ old('office_email',$employee->office_email) }}">
                        @error('office_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city',$employee->city) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Province</label>
                        <select name="province" class="form-select">
                            <option value="">Select</option>
                            @foreach(['KPK','Punjab','Sindh','Balochistan','Gilgit Baltistan','AJK','ICT'] as $p)
                                <option value="{{ $p }}" {{ old('province',$employee->province) == $p ? 'selected':'' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic',$employee->cnic) }}" maxlength="13">
                        @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fff7ed"><i class="bi bi-briefcase" style="color:#d97706"></i></div>
                <h6>Employment details</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="required-star">*</span></label>
                        <select name="department" class="form-select @error('department') is-invalid @enderror">
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('department',$employee->department) == $dept ? 'selected':'' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Designation <span class="required-star">*</span></label>
                        <select name="designation" class="form-select @error('designation') is-invalid @enderror">
                            @foreach($designations as $des)
                                <option value="{{ $des }}" {{ old('designation',$employee->designation) == $des ? 'selected':'' }}>{{ $des }}</option>
                            @endforeach
                        </select>
                        @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employment type</label>
                        <select name="employment_type" class="form-select">
                            @foreach(['Permanent','Contractual','Probationary','Part-Time','Intern','Daily-Wage'] as $t)
                                <option value="{{ $t }}" {{ old('employment_type',$employee->employment_type) == $t ? 'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="employment_status" class="form-select">
                            @foreach(['Active','On Leave','Suspended','Terminated','Resigned','Retired'] as $s)
                                <option value="{{ $s }}" {{ old('employment_status',$employee->employment_status) == $s ? 'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Joining date</label>
                        <input type="date" name="joining_date" class="form-control"
                               value="{{ old('joining_date',$employee->joining_date->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Shift</label>
                        <select name="shift" class="form-select">
                            @foreach(['Morning','Evening','Night','Rotating','Custom'] as $s)
                                <option value="{{ $s }}" {{ old('shift',$employee->shift) == $s ? 'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Salary type</label>
                        <select name="salary_type" class="form-select">
                            @foreach(['Monthly','Daily','Hourly'] as $st)
                                <option value="{{ $st }}" {{ old('salary_type',$employee->salary_type) == $st ? 'selected':'' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Basic salary (Rs)</label>
                        <input type="number" name="basic_salary" class="form-control"
                               value="{{ old('basic_salary',$employee->basic_salary) }}" min="0" step="500">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Working days</label>
                        <div class="d-flex gap-2 flex-wrap mt-1">
                            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                            <label>
                                <input type="checkbox" name="working_days[]" value="{{ $day }}" class="d-none day-check"
                                       {{ in_array($day, old('working_days', $employee->working_days ?? [])) ? 'checked':'' }}>
                                <span class="day-btn {{ in_array($day, old('working_days', $employee->working_days ?? [])) ? 'selected':'' }}"
                                      onclick="toggleDay(this)">{{ $day }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-control">{{ old('notes',$employee->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">

        {{-- Employee ID badge --}}
        <div class="form-card" style="border-color:#fde68a">
            <div class="form-card-body" style="background:#fffbeb;text-align:center">
                <div style="font-size:11px;color:#92400e;font-weight:600;margin-bottom:4px">Employee ID</div>
                <div style="font-size:22px;font-weight:700;color:#b45309;letter-spacing:.05em">
                    {{ $employee->employee_id }}
                </div>
                <div style="font-size:11px;color:#d97706;margin-top:2px">Cannot be changed</div>
            </div>
        </div>

        {{-- Photo --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fffbeb"><i class="bi bi-camera" style="color:#d97706"></i></div>
                <h6>Photo</h6>
            </div>
            <div class="form-card-body text-center">
                @if($employee->photo_url)
                    <img id="photoPreview" src="{{ $employee->photo_url }}"
                         style="width:70px;height:70px;border-radius:50%;object-fit:cover;display:block;margin:0 auto 10px;border:3px solid #e2e8f0">
                @else
                    <img id="photoPreview" style="width:70px;height:70px;border-radius:50%;display:none;margin:0 auto 10px">
                @endif
                <div style="border:2px dashed #e2e8f0;border-radius:8px;padding:14px;cursor:pointer"
                     onclick="document.getElementById('photoInput').click()">
                    <div style="font-size:12px;color:#64748b">Change photo</div>
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*"
                       class="d-none" onchange="previewPhoto(this)">
            </div>
        </div>

        {{-- System Access --}}
        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <div class="section-icon" style="background:#dbeafe"><i class="bi bi-shield-lock" style="color:#1d4ed8"></i></div>
                <h6 style="color:#1e40af">System access</h6>
            </div>
            <div class="form-card-body">
                <div class="form-check">
                    <input type="checkbox" name="has_system_access" id="has_system_access_edit"
                           class="form-check-input" value="1"
                           {{ old('has_system_access',$employee->has_system_access) ? 'checked':'' }}>
                    <label class="form-check-label" for="has_system_access_edit" style="font-size:13px;font-weight:500">
                        Has system access
                    </label>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-save me-2"></i>Update employee
            </button>
            <a href="{{ route('employees.show', $employee->id) }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">Cancel</a>
        </div>
    </div>
</div>

</form>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
            const p = document.getElementById('photoPreview');
            p.src = e.target.result;
            p.style.display = 'block';
        };
        r.readAsDataURL(input.files[0]);
    }
}
function toggleDay(el) {
    el.classList.toggle('selected');
    el.previousElementSibling.checked = el.classList.contains('selected');
}
</script>
@endpush
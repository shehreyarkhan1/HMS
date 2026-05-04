@extends('layouts.master')

@section('title', 'Add Employee')
@section('page-title', 'Add New Employee')
@section('breadcrumb', 'Home / Employees / New')

@push('styles')
<style>
    .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:16px}
    .form-card-header{padding:13px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;gap:8px}
    .form-card-header h6{margin:0;font-size:13px;font-weight:600;color:#374151}
    .form-card-body{padding:20px}
    .form-label{font-size:12px;font-weight:500;color:#374151;margin-bottom:5px}
    .form-control,.form-select{font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;color:#1e293b}
    .form-control:focus,.form-select:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
    .form-control.is-invalid,.form-select.is-invalid{border-color:#f87171}
    .invalid-feedback{font-size:11px}
    .required-star{color:#ef4444;margin-left:2px}

    .section-icon{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}

    .day-btn{border:1.5px solid #e2e8f0;border-radius:6px;padding:5px 10px;font-size:12px;font-weight:500;color:#64748b;background:#fff;cursor:pointer;transition:all .15s;user-select:none}
    .day-btn:hover{border-color:#93c5fd;color:#1d4ed8}
    .day-btn.selected{border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}

    .photo-upload{border:2px dashed #e2e8f0;border-radius:10px;padding:24px;text-align:center;cursor:pointer;transition:border-color .2s}
    .photo-upload:hover{border-color:#93c5fd}
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-3">

    {{-- ── LEFT COLUMN ── --}}
    <div class="col-12 col-lg-8">

        {{-- Personal Information --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#eff6ff">
                    <i class="bi bi-person" style="color:#1d4ed8"></i>
                </div>
                <h6>Personal information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First name <span class="required-star">*</span></label>
                        <input type="text" name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}" placeholder="e.g. Ahmed">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last name <span class="required-star">*</span></label>
                        <input type="text" name="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}" placeholder="e.g. Khan">
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Father's name</label>
                        <input type="text" name="father_name" class="form-control"
                               value="{{ old('father_name') }}" placeholder="e.g. Muhammad Khan">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date of birth</label>
                        <input type="date" name="date_of_birth" class="form-control"
                               value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender <span class="required-star">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select</option>
                            @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender') == $g ? 'selected':'' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Marital status</label>
                        <select name="marital_status" class="form-select">
                            @foreach(['Single','Married','Divorced','Widowed'] as $m)
                                <option value="{{ $m }}" {{ old('marital_status','Single') == $m ? 'selected':'' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Blood group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Unknown</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CNIC <span style="color:#94a3b8">(13 digits)</span></label>
                        <input type="text" name="cnic"
                               class="form-control @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic') }}" placeholder="3520112345678" maxlength="13">
                        @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control"
                               value="{{ old('nationality','Pakistani') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control"
                               value="{{ old('religion') }}" placeholder="e.g. Islam">
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#f0fdf4">
                    <i class="bi bi-telephone" style="color:#16a34a"></i>
                </div>
                <h6>Contact information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Personal phone <span class="required-star">*</span></label>
                        <input type="text" name="personal_phone"
                               class="form-control @error('personal_phone') is-invalid @enderror"
                               value="{{ old('personal_phone') }}" placeholder="03xx-xxxxxxx">
                        @error('personal_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Office phone</label>
                        <input type="text" name="office_phone" class="form-control"
                               value="{{ old('office_phone') }}" placeholder="Optional">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Personal email</label>
                        <input type="email" name="personal_email" class="form-control"
                               value="{{ old('personal_email') }}" placeholder="personal@gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Office email</label>
                        <input type="email" name="office_email"
                               class="form-control @error('office_email') is-invalid @enderror"
                               value="{{ old('office_email') }}" placeholder="name@hospital.com">
                        @error('office_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Emergency contact</label>
                        <input type="text" name="emergency_contact_phone" class="form-control"
                               value="{{ old('emergency_contact_phone') }}" placeholder="03xx-xxxxxxx">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Relation</label>
                        <input type="text" name="emergency_contact_relation" class="form-control"
                               value="{{ old('emergency_contact_relation') }}" placeholder="Father, Wife...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emergency contact name</label>
                        <input type="text" name="emergency_contact_name" class="form-control"
                               value="{{ old('emergency_contact_name') }}" placeholder="Full name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Present address</label>
                        <textarea name="present_address" rows="2" class="form-control"
                                  placeholder="Current address">{{ old('present_address') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Permanent address</label>
                        <textarea name="permanent_address" rows="2" class="form-control"
                                  placeholder="Permanent address">{{ old('permanent_address') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city') }}" placeholder="Peshawar">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Province</label>
                        <select name="province" class="form-select">
                            <option value="">Select</option>
                            @foreach(['KPK','Punjab','Sindh','Balochistan','Gilgit Baltistan','AJK','ICT'] as $p)
                                <option value="{{ $p }}" {{ old('province') == $p ? 'selected':'' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Postal code</label>
                        <input type="text" name="postal_code" class="form-control"
                               value="{{ old('postal_code') }}" placeholder="25000">
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Details --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fff7ed">
                    <i class="bi bi-briefcase" style="color:#d97706"></i>
                </div>
                <h6>Employment details</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="required-star">*</span></label>
                        <select name="department" class="form-select @error('department') is-invalid @enderror">
                            <option value="">Select department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected':'' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Designation <span class="required-star">*</span></label>
                        <select name="designation" class="form-select @error('designation') is-invalid @enderror">
                            <option value="">Select designation</option>
                            @foreach($designations as $des)
                                <option value="{{ $des }}" {{ old('designation') == $des ? 'selected':'' }}>{{ $des }}</option>
                            @endforeach
                        </select>
                        @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employment type <span class="required-star">*</span></label>
                        <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror">
                            @foreach(['Permanent','Contractual','Probationary','Part-Time','Intern','Daily-Wage'] as $t)
                                <option value="{{ $t }}" {{ old('employment_type','Permanent') == $t ? 'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('employment_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="required-star">*</span></label>
                        <select name="employment_status" class="form-select">
                            @foreach(['Active','On Leave','Suspended','Terminated','Resigned','Retired'] as $s)
                                <option value="{{ $s }}" {{ old('employment_status','Active') == $s ? 'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Job grade</label>
                        <input type="text" name="job_grade" class="form-control"
                               value="{{ old('job_grade') }}" placeholder="BPS-16, Grade A">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Joining date <span class="required-star">*</span></label>
                        <input type="date" name="joining_date"
                               class="form-control @error('joining_date') is-invalid @enderror"
                               value="{{ old('joining_date') }}">
                        @error('joining_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirmation date</label>
                        <input type="date" name="confirmation_date" class="form-control"
                               value="{{ old('confirmation_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contract end date</label>
                        <input type="date" name="contract_end_date" class="form-control"
                               value="{{ old('contract_end_date') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Shift & Schedule --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#ecfeff">
                    <i class="bi bi-clock" style="color:#0e7490"></i>
                </div>
                <h6>Shift & schedule</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Shift <span class="required-star">*</span></label>
                        <select name="shift" class="form-select">
                            @foreach(['Morning','Evening','Night','Rotating','Custom'] as $s)
                                <option value="{{ $s }}" {{ old('shift','Morning') == $s ? 'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start time</label>
                        <input type="time" name="shift_start" class="form-control"
                               value="{{ old('shift_start') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End time</label>
                        <input type="time" name="shift_end" class="form-control"
                               value="{{ old('shift_end') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Weekly hours</label>
                        <input type="number" name="weekly_hours" class="form-control"
                               value="{{ old('weekly_hours',48) }}" min="1" max="168">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Working days</label>
                        <div class="d-flex gap-2 flex-wrap mt-1">
                            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                            <label>
                                <input type="checkbox" name="working_days[]" value="{{ $day }}"
                                       class="d-none day-check"
                                       {{ in_array($day, old('working_days', ['Mon','Tue','Wed','Thu','Fri','Sat'])) ? 'checked':'' }}>
                                <span class="day-btn {{ in_array($day, old('working_days', ['Mon','Tue','Wed','Thu','Fri','Sat'])) ? 'selected':'' }}"
                                      onclick="toggleDay(this)">{{ $day }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Education & Experience --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fdf4ff">
                    <i class="bi bi-mortarboard" style="color:#7e22ce"></i>
                </div>
                <h6>Education & experience</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Highest qualification</label>
                        <input type="text" name="highest_qualification" class="form-control"
                               value="{{ old('highest_qualification') }}" placeholder="MBBS, MBA, BSc">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Field / Specialization</label>
                        <input type="text" name="specialization" class="form-control"
                               value="{{ old('specialization') }}" placeholder="Medicine, Finance">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institution</label>
                        <input type="text" name="institution" class="form-control"
                               value="{{ old('institution') }}" placeholder="University name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Graduation year</label>
                        <input type="number" name="graduation_year" class="form-control"
                               value="{{ old('graduation_year') }}" min="1970" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total experience (years)</label>
                        <input type="number" name="total_experience_years" class="form-control"
                               value="{{ old('total_experience_years',0) }}" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Previous employer</label>
                        <input type="text" name="previous_employer" class="form-control"
                               value="{{ old('previous_employer') }}" placeholder="Hospital / Company">
                    </div>
                </div>
            </div>
        </div>

        {{-- Bank & Salary --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#f0fdf4">
                    <i class="bi bi-bank" style="color:#15803d"></i>
                </div>
                <h6>Bank & salary</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Salary type</label>
                        <select name="salary_type" class="form-select">
                            @foreach(['Monthly','Daily','Hourly'] as $st)
                                <option value="{{ $st }}" {{ old('salary_type','Monthly') == $st ? 'selected':'' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Basic salary (Rs)</label>
                        <input type="number" name="basic_salary" class="form-control"
                               value="{{ old('basic_salary',0) }}" min="0" step="500">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bank name</label>
                        <input type="text" name="bank_name" class="form-control"
                               value="{{ old('bank_name') }}" placeholder="HBL, MCB, UBL">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account number</label>
                        <input type="text" name="bank_account_number" class="form-control"
                               value="{{ old('bank_account_number') }}" placeholder="Account no.">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch</label>
                        <input type="text" name="bank_branch" class="form-control"
                               value="{{ old('bank_branch') }}" placeholder="Branch name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">IBAN</label>
                        <input type="text" name="iban" class="form-control"
                               value="{{ old('iban') }}" placeholder="PK00XXXX0000000000000000">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NTN number</label>
                        <input type="text" name="ntn_number" class="form-control"
                               value="{{ old('ntn_number') }}" placeholder="National Tax No.">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">EOBI number</label>
                        <input type="text" name="eobi_number" class="form-control"
                               value="{{ old('eobi_number') }}" placeholder="EOBI Reg. No.">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="is_tax_filer" id="is_tax_filer"
                                   class="form-check-input" value="1"
                                   {{ old('is_tax_filer') ? 'checked':'' }}>
                            <label class="form-check-label" for="is_tax_filer" style="font-size:13px">
                                Tax filer
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#f8fafc">
                    <i class="bi bi-journal-text" style="color:#64748b"></i>
                </div>
                <h6>Notes</h6>
            </div>
            <div class="form-card-body">
                <textarea name="notes" rows="3" class="form-control"
                          placeholder="Any additional notes about the employee...">{{ old('notes') }}</textarea>
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div class="col-12 col-lg-4">

        {{-- Photo Upload --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fffbeb">
                    <i class="bi bi-camera" style="color:#d97706"></i>
                </div>
                <h6>Employee photo</h6>
            </div>
            <div class="form-card-body">
                <img id="photoPreview" style="width:80px;height:80px;border-radius:50%;object-fit:cover;display:none;margin:0 auto 12px;border:3px solid #e2e8f0">
                <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                    <i class="bi bi-camera" style="font-size:28px;color:#94a3b8;display:block;margin-bottom:6px"></i>
                    <div style="font-size:13px;color:#64748b">Click to upload photo</div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:4px">JPG, PNG — max 2MB</div>
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*"
                       class="d-none" onchange="previewPhoto(this)">
                @error('photo') <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- System Access --}}
        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <div class="section-icon" style="background:#dbeafe">
                    <i class="bi bi-shield-lock" style="color:#1d4ed8"></i>
                </div>
                <h6 style="color:#1e40af">System access</h6>
            </div>
            <div class="form-card-body">
                <div style="font-size:13px;color:#374151;margin-bottom:10px;line-height:1.6">
                    If this employee needs to login to HMS, enable system access. A user account can be created separately from Users module.
                </div>
                <div class="form-check">
                    <input type="checkbox" name="has_system_access" id="has_system_access"
                           class="form-check-input" value="1"
                           {{ old('has_system_access') ? 'checked':'' }}>
                    <label class="form-check-label" for="has_system_access" style="font-size:13px;font-weight:500">
                        Has system access
                    </label>
                </div>
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#f8fafc">
                    <i class="bi bi-info-circle" style="color:#64748b"></i>
                </div>
                <h6>Quick info</h6>
            </div>
            <div class="form-card-body" style="font-size:13px;color:#374151;line-height:2.2">
                <div><i class="bi bi-check-circle text-success me-2"></i>Employee ID auto-generated</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Photo stored in storage/</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Soft delete enabled</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>CNIC must be unique</div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-person-check me-2"></i>Add employee
            </button>
            <a href="{{ route('employees.index') }}"
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
        const reader = new FileReader();
        reader.onload = e => {
            const p = document.getElementById('photoPreview');
            p.src = e.target.result;
            p.style.display = 'block';
            p.previousElementSibling.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleDay(el) {
    el.classList.toggle('selected');
    el.previousElementSibling.checked = el.classList.contains('selected');
}
</script>
@endpush
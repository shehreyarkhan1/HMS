@extends('layouts.master')

@section('title', 'Add New User')
@section('page-title', 'Add New User')
@section('breadcrumb', 'Home / Users / New')

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

        .form-card-body {
            padding: 20px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            color: #1e293b;
            background: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #f87171;
        }

        .invalid-feedback {
            font-size: 11px;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-hint {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            transition: color 0.15s;
        }

        .toggle-password-btn:hover {
            color: #64748b;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-badge.super_admin {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .role-badge.doctor {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .role-badge.nurse {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .role-badge.receptionist {
            background: #fefce8;
            color: #ca8a04;
            border: 1px solid #fef08a;
        }

        .role-badge.accountant {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .role-badge.pharmacist {
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .role-badge.lab_technician {
            background: #ecfeff;
            color: #0891b2;
            border: 1px solid #a5f3fc;
        }

        .role-badge.radiologist {
            background: #f0fdf4;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .role-badge.hr_manager {
            background: #fdf4ff;
            color: #9333ea;
            border: 1px solid #e9d5ff;
        }

        .form-check-input:checked {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        /* Employee select option style */
        .emp-select-info {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #16a34a;
            margin-top: 6px;
            display: none;
        }
    </style>
@endpush

@section('content')

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="row g-3">

            {{-- LEFT COLUMN --}}
            <div class="col-12 col-lg-8">

                {{-- Account Information --}}
                <div class="form-card mb-3">
                    <div class="form-card-header">
                        <h6><i class="bi bi-person-plus me-2 text-primary"></i>Account Information</h6>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="required-star">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="e.g. Dr. Ahmed Khan" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Username <span class="required-star">*</span></label>
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}" placeholder="e.g. ahmed_khan" required>
                                <div class="form-hint">Only letters, numbers, and underscores.</div>
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email Address <span class="required-star">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="e.g. ahmed.khan@hospital.com" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security --}}
                <div class="form-card mb-3">
                    <div class="form-card-header">
                        <h6><i class="bi bi-shield-lock me-2 text-success"></i>Security</h6>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="required-star">*</span></label>
                                <div style="position:relative;">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 characters" required>
                                    <button type="button" onclick="togglePass('password','eye1')"
                                        class="toggle-password-btn">
                                        <i id="eye1" class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="required-star">*</span></label>
                                <div style="position:relative;">
                                    <input type="password" name="password_confirmation" id="password_conf"
                                        class="form-control" placeholder="Repeat password" required>
                                    <button type="button" onclick="togglePass('password_conf','eye2')"
                                        class="toggle-password-btn">
                                        <i id="eye2" class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Role, Status & Employee Link --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <h6><i class="bi bi-person-badge me-2 text-info"></i>Role, Status & Employee Link</h6>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Role <span class="required-star">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select a role…</option>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account Status</label>
                                <div style="padding-top:8px;">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                            id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active"
                                            style="color:#64748b; font-size:13px; margin-left:6px;">Active</label>
                                    </div>
                                    <div class="form-hint">Inactive users cannot log in.</div>
                                </div>
                            </div>

                            {{-- Employee Link --}}
                            <div class="col-12">
                                <label class="form-label">
                                    Link to Employee
                                    <span style="color:#94a3b8; font-weight:400;">(optional)</span>
                                </label>
                                <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror"
                                    id="employeeSelect">
                                    <option value="">— None (System User / Super Admin) —</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{-- Yahan first_name aur employee_id use karein --}}
                                            data-name="{{ $emp->first_name }} {{ $emp->last_name }}"
                                            data-code="{{ $emp->employee_id }}" {{ (old('employee_id') ?? ($user->employee_id ?? '')) == $emp->id ? 'selected' : '' }}>

                                            {{ $emp->first_name }} {{ $emp->last_name }}

                                            @if($emp->employee_id)
                                                ({{ $emp->employee_id }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-hint">
                                    Only employees without an existing system account are listed.
                                </div>
                                <div class="emp-select-info" id="empInfo">
                                    <i class="bi bi-person-check me-1"></i>
                                    <span id="empInfoText"></span>
                                </div>
                                @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-12 col-lg-4">

                {{-- Role Guide --}}
                <div class="form-card mb-3">
                    <div class="form-card-header">
                        <h6><i class="bi bi-info-circle me-2 text-warning"></i>Role Guide</h6>
                    </div>
                    <div class="form-card-body" style="padding:0;">
                        @foreach($roles as $value => $label)
                            <div style="padding:10px 20px; border-bottom:1px solid #e2e8f0;">
                                <span class="role-badge {{ $value }}">
                                    <i class="bi bi-dot"></i>{{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="form-card mb-3" style="border-color:#bfdbfe;">
                    <div class="form-card-header" style="background:#eff6ff;">
                        <h6 style="color:#1d4ed8;"><i class="bi bi-lightbulb me-2"></i>Quick Info</h6>
                    </div>
                    <div class="form-card-body" style="font-size:13px; color:#374151; line-height:2;">
                        <div><i class="bi bi-check-circle text-success me-2"></i>Unique username required</div>
                        <div><i class="bi bi-check-circle text-success me-2"></i>Email must be unique</div>
                        <div><i class="bi bi-check-circle text-success me-2"></i>Password min 8 characters</div>
                        <div><i class="bi bi-check-circle text-success me-2"></i>Employee link is optional</div>
                        <div><i class="bi bi-info-circle text-primary me-2"></i>Super Admin = no employee needed</div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" style="height:42px; font-size:14px; font-weight:500;">
                        <i class="bi bi-person-plus me-2"></i>Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"
                        style="height:42px; font-size:14px;">
                        Cancel
                    </a>
                </div>

            </div>
        </div>

    </form>

@endsection

@push('scripts')
    <script>
        function togglePass(id, eyeId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(eyeId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Show selected employee info below the select
        const empSelect = document.getElementById('employeeSelect');
        const empInfo = document.getElementById('empInfo');
        const empText = document.getElementById('empInfoText');

        empSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (this.value) {
                const code = opt.dataset.code ? ` (${opt.dataset.code})` : '';
                empText.textContent = `Linked to: ${opt.dataset.name}${code}`;
                empInfo.style.display = 'block';
            } else {
                empInfo.style.display = 'none';
            }
        });

        // Restore on validation error
        if (empSelect.value) empSelect.dispatchEvent(new Event('change'));
    </script>
@endpush
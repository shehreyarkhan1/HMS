@extends('layouts.master')

@section('title', 'Edit User — ' . $user->name)
@section('breadcrumb', 'Users / Edit')

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
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #f87171;
        }

        .invalid-feedback {
            font-size: 11px;
            color: #ef4444;
            margin-top: 4px;
            display: block;
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

        .btn-primary-white {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-primary-white:hover {
            background: #1e40af;
            color: #fff;
        }

        .btn-ghost-white {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: transparent;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #64748b;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.12s;
        }

        .btn-ghost-white:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .btn-danger-white {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 9px 16px;
            background: transparent;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #dc2626;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.12s;
        }

        .btn-danger-white:hover {
            background: #fef2f2;
            border-color: #f87171;
        }

        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }

        .form-check-input:checked {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        /* Employee linked chip in summary */
        .emp-linked-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .emp-linked-none {
            font-size: 12px;
            color: #94a3b8;
            font-style: italic;
        }

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

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 style="font-size:22px; font-weight:700; color:#1e293b; letter-spacing:-0.4px; margin:0;">
                Edit User
            </h1>
            <p style="color:#94a3b8; font-size:13px; margin-top:3px;">
                Updating account for <strong style="color:#1e293b;">{{ $user->name }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.show', $user) }}" class="btn-ghost-white">
                <i class="bi bi-eye"></i> View Profile
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost-white">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-3">

        {{-- LEFT COLUMN --}}
        <div class="col-12 col-lg-8">

            <div class="form-card">
                <div class="form-card-header">
                    <h6><i class="bi bi-person-gear me-2 text-primary"></i>Account Information</h6>
                    <span style="font-size:12px; color:#94a3b8; font-family:monospace;">ID #{{ $user->id }}</span>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf @method('PUT')

                        {{-- Basic Info --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="required-star">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Username <span class="required-star">*</span></label>
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" required>
                                <div class="form-hint">Only letters, numbers, and underscores.</div>
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email Address <span class="required-star">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <hr class="divider">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-info-circle" style="color:#93c5fd; font-size:13px;"></i>
                            <span style="font-size:12px; color:#94a3b8;">
                                Leave password fields empty to keep the current password.
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div style="position:relative;">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Leave blank to keep current">
                                    <button type="button" onclick="togglePass('password','eye1')"
                                        class="toggle-password-btn">
                                        <i id="eye1" class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <div style="position:relative;">
                                    <input type="password" name="password_confirmation" id="password_conf"
                                        class="form-control" placeholder="Repeat new password">
                                    <button type="button" onclick="togglePass('password_conf','eye2')"
                                        class="toggle-password-btn">
                                        <i id="eye2" class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Role, Status & Employee --}}
                        <hr class="divider">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Role <span class="required-star">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
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
                                            id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active"
                                            style="color:#64748b; font-size:13px; margin-left:6px;">Active</label>
                                    </div>
                                    <div class="form-hint">Inactive users cannot log in.</div>
                                </div>
                            </div>

                            {{-- Employee Link --}}
                            <div class="col-12">
                                <label class="form-label">
                                    Linked Employee
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
                                    Only employees without an existing account are listed (plus the current link).
                                </div>
                                <div class="emp-select-info" id="empInfo">
                                    <i class="bi bi-person-check me-1"></i>
                                    <span id="empInfoText"></span>
                                </div>
                                @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>

                        {{-- Form Actions --}}
                        <div class="d-flex align-items-center gap-2 mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                            <button type="submit" class="btn-primary-white">
                                <i class="bi bi-check-lg"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn-ghost-white">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-12 col-lg-4">

            {{-- User Summary --}}
            <div class="form-card mb-3">
                <div class="form-card-header">
                    <h6><i class="bi bi-person-circle me-2 text-info"></i>User Summary</h6>
                </div>
                <div class="form-card-body" style="font-size:13px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                        <div style="width:44px; height:44px; border-radius:10px; background:#eff6ff;
                                        color:#1d4ed8; font-size:18px; font-weight:700;
                                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600; color:#1e293b; font-size:14px;">{{ $user->name }}</div>
                            <div style="color:#94a3b8; font-size:12px;">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div style="border-top:1px solid #e2e8f0; padding-top:12px;
                                    display:flex; flex-direction:column; gap:10px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#94a3b8; font-size:12px;">Username</span>
                            <span style="font-family:monospace; color:#374151; font-size:12px;">
                                {{ $user->username }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#94a3b8; font-size:12px;">Joined</span>
                            <span style="color:#374151; font-size:12px;">
                                {{ $user->created_at->format('d M Y') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#94a3b8; font-size:12px;">Status</span>
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:2px 10px;
                                             border-radius:6px; font-size:11.5px; font-weight:500;
                                             {{ $user->is_active
        ? 'background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;'
        : 'background:#fef2f2; color:#dc2626; border:1px solid #fecaca;' }}">
                                <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#94a3b8; font-size:12px;">Employee</span>
                            <span>
                                @if($user->employee)
                                    <span class="emp-linked-chip">
                                        <i class="bi bi-person-badge" style="font-size:11px;"></i>
                                        {{ $user->employee->name }}
                                    </span>
                                @else
                                    <span class="emp-linked-none">Not linked</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="form-card" style="border-color:#fecaca;">
                <div class="form-card-header" style="background:#fef2f2;">
                    <h6 style="color:#dc2626;">
                        <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                </div>
                <div class="form-card-body">
                    <p style="font-size:13px; color:#64748b; margin-bottom:16px;">
                        Deleting this user is permanent and cannot be undone.
                    </p>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                        onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-white">
                            <i class="bi bi-trash"></i> Delete This User
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

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

        if (empSelect.value) empSelect.dispatchEvent(new Event('change'));
    </script>
@endpush
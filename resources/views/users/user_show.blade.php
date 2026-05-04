@extends('layouts.master')

@section('title', $user->name . ' — Profile')
@section('breadcrumb', 'Users / Profile')

@push('styles')
    <style>
        .page-card-white {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .page-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .page-card-header h6 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .profile-header-wrap {
            padding: 32px 20px 24px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .profile-avatar-large {
            width: 86px;
            height: 86px;
            border-radius: 16px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 32px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid #fff;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15);
        }

        .profile-name { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .profile-username { font-size: 13px; font-family: monospace; color: #64748b; margin-bottom: 16px; }

        .profile-actions-wrap {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #f8fafc;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }

        .detail-row:hover { background: #f8fafc; }
        .detail-row:last-child { border-bottom: none; }

        .detail-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 13.5px;
            font-weight: 500;
            color: #1e293b;
            text-align: right;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-super_admin    { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .role-doctor         { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
        .role-nurse          { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
        .role-receptionist   { background:#fefce8; color:#ca8a04; border:1px solid #fef08a; }
        .role-accountant     { background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }
        .role-pharmacist     { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; }
        .role-lab_technician { background:#ecfeff; color:#0891b2; border:1px solid #a5f3fc; }
        .role-radiologist    { background:#f0fdf4; color:#059669; border:1px solid #a7f3d0; }
        .role-hr_manager     { background:#fdf4ff; color:#9333ea; border:1px solid #e9d5ff; }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-status.active   { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
        .badge-status.inactive { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }

        /* Employee card inside show */
        .emp-linked-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
        }

        .emp-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f0fdf4;
            color: #16a34a;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #bbf7d0;
        }

        .btn-primary-white {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-primary-white:hover {
            background: #1e40af;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.2);
        }

        .btn-ghost-white {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-ghost-white:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .btn-danger-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            background: #fff;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #dc2626;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-danger-outline:hover {
            background: #fef2f2;
            border-color: #f87171;
            color: #b91c1c;
        }
    </style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 style="font-size:22px; font-weight:700; color:#1e293b; letter-spacing:-0.4px; margin:0;">
                User Profile
            </h1>
            <p style="color:#94a3b8; font-size:13px; margin-top:3px;">
                Detailed view of account information and settings.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn-ghost-white">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary-white">
                <i class="bi bi-pencil"></i> Edit User
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column: Profile Summary --}}
        <div class="col-lg-4">
            <div class="page-card-white">

                <div class="profile-header-wrap">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-username">{{ $user->username }}</div>

                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <span class="badge-role role-{{ $user->role }}">
                            {{ $roles[$user->role] ?? ucfirst($user->role) }}
                        </span>
                        <span class="badge-status {{ $user->is_active ? 'active' : 'inactive' }}">
                            <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="profile-actions-wrap">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary-white w-100">
                        <i class="bi bi-person-gear"></i> Update Profile Settings
                    </a>

                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf @method('PATCH')
                        @if($user->is_active)
                            <button type="submit" class="btn-danger-outline w-100">
                                <i class="bi bi-pause-circle"></i> Suspend Account
                            </button>
                        @else
                            <button type="submit" class="btn-ghost-white w-100"
                                style="color:#16a34a; border-color:#bbf7d0; background:#f0fdf4;">
                                <i class="bi bi-play-circle"></i> Reactivate Account
                            </button>
                        @endif
                    </form>
                </div>

            </div>
        </div>

        {{-- Right Column: Detailed Info --}}
        <div class="col-lg-8">

            {{-- Card 1: Personal Information --}}
            <div class="page-card-white mb-4">
                <div class="page-card-header">
                    <h6><i class="bi bi-person-lines-fill me-2" style="color:#94a3b8;"></i>Personal Information</h6>
                </div>
                <div>
                    <div class="detail-row">
                        <div class="detail-label">System ID</div>
                        <div class="detail-value" style="font-family:monospace; color:#64748b;">
                            #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Full Legal Name</div>
                        <div class="detail-value">{{ $user->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Username</div>
                        <div class="detail-value" style="font-family:monospace; color:#64748b;">
                            {{ $user->username }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">
                            <a href="mailto:{{ $user->email }}" style="color:#1d4ed8; text-decoration:none;">
                                {{ $user->email }}
                            </a>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Account Created</div>
                        <div class="detail-value">
                            {{ $user->created_at->format('F d, Y \a\t h:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Security & Status --}}
            <div class="page-card-white mb-4">
                <div class="page-card-header">
                    <h6><i class="bi bi-shield-check me-2" style="color:#94a3b8;"></i>Security & Status</h6>
                </div>
                <div>
                    <div class="detail-row">
                        <div class="detail-label">Email Verification</div>
                        <div class="detail-value">
                            @if($user->email_verified_at)
                                <span class="badge-status active">
                                    <i class="bi bi-check2-circle"></i>
                                    Verified on {{ $user->email_verified_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="badge-status inactive">
                                    <i class="bi bi-exclamation-circle"></i> Unverified
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Assigned Role</div>
                        <div class="detail-value">
                            <span class="badge-role role-{{ $user->role }}">
                                {{ $roles[$user->role] ?? ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value">
                            @if($user->is_active)
                                <span style="color:#16a34a; font-weight:600; font-size:13px;">
                                    Currently Active &amp; Permitted
                                </span>
                            @else
                                <span style="color:#dc2626; font-weight:600; font-size:13px;">
                                    Suspended / Access Revoked
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Linked Employee --}}
            <div class="page-card-white">
                <div class="page-card-header d-flex justify-content-between align-items-center">
                    <h6><i class="bi bi-person-badge me-2" style="color:#94a3b8;"></i>Linked Employee Record</h6>
                    @if($user->employee)
                        <a href="{{ route('admin.users.edit', $user) }}"
                            style="font-size:12px; color:#1d4ed8; text-decoration:none;">
                            <i class="bi bi-pencil me-1"></i>Change
                        </a>
                    @endif
                </div>

                @if($user->employee)
                    <div class="emp-linked-card">
                        <div class="emp-avatar">
                            {{ strtoupper(substr($user->employee->first_name, 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:14px; color:#1e293b;">
                                {{ $user->employee->first_name }}
                            </div>
                            @if($user->employee->employee_id)
                                <div style="font-family:monospace; font-size:12px; color:#64748b; margin-top:2px;">
                                    {{ $user->employee->employee_id }}
                                </div>
                            @endif
                        </div>
                        <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 12px;
                                     border-radius:6px; font-size:11.5px; font-weight:500;
                                     background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">
                            <i class="bi bi-link-45deg"></i> Linked
                        </span>
                    </div>
                @else
                    <div style="padding:32px 20px; text-align:center;">
                        <i class="bi bi-person-x"
                            style="font-size:32px; color:#cbd5e1; display:block; margin-bottom:10px;"></i>
                        <div style="font-size:14px; color:#64748b; font-weight:500;">No employee linked</div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:4px;">
                            This is a system-only user (e.g. Super Admin) with no employee record.
                        </div>
                        <a href="{{ route('admin.users.edit', $user) }}"
                            style="display:inline-flex; align-items:center; gap:6px; margin-top:14px;
                                   padding:7px 16px; border-radius:8px; font-size:13px; font-weight:500;
                                   background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;
                                   text-decoration:none;">
                            <i class="bi bi-link-45deg"></i> Link an Employee
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection
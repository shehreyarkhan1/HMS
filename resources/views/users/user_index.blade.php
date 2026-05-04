@extends('layouts.master')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@push('styles')
    <style>
        .page-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .page-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-card-header h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .stat-card-white {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
        }

        .stat-label {
            font-size: 11.5px;
            font-weight: 500;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .stat-icon {
            font-size: 22px;
            opacity: 0.7;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .form-control-white,
        .form-select-white {
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px;
            color: #1e293b;
            background: #fff;
            height: 36px;
        }

        .form-control-white:focus,
        .form-select-white:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .table-white {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-white thead tr {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-white thead th {
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .table-white tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.12s;
        }

        .table-white tbody tr:hover {
            background: #f8fafc;
        }

        .table-white tbody tr:last-child {
            border-bottom: none;
        }

        .table-white tbody td {
            padding: 12px 16px;
            color: #1e293b;
            vertical-align: middle;
        }

        .user-avatar-white {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 500;
        }

        .role-super_admin {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .role-doctor {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .role-nurse {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .role-receptionist {
            background: #fefce8;
            color: #ca8a04;
            border: 1px solid #fef08a;
        }

        .role-accountant {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .role-pharmacist {
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .role-lab_technician {
            background: #ecfeff;
            color: #0891b2;
            border: 1px solid #a5f3fc;
        }

        .role-radiologist {
            background: #f0fdf4;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .role-hr_manager {
            background: #fdf4ff;
            color: #9333ea;
            border: 1px solid #e9d5ff;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 500;
        }

        .badge-status.active {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .badge-status.inactive {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Employee link chip */
        .emp-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 500;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            white-space: nowrap;
        }

        .emp-chip-none {
            font-size: 11.5px;
            color: #cbd5e1;
            font-style: italic;
        }

        .btn-primary-white {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn-primary-white:hover {
            background: #1e40af;
            color: #fff;
        }

        .btn-ghost-white {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            background: transparent;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
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
            padding: 5px 10px;
            background: transparent;
            border: 1px solid #fecaca;
            border-radius: 7px;
            color: #dc2626;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.12s;
        }

        .btn-danger-white:hover {
            background: #fef2f2;
            border-color: #f87171;
        }

        .btn-clear-white {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 7px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #64748b;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.12s;
        }

        .btn-clear-white:hover {
            background: #e2e8f0;
            color: #374151;
        }

        .pagination .page-link {
            border-radius: 7px !important;
            border: 1px solid #e2e8f0;
            color: #374151;
            font-size: 13px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }
    </style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 style="font-size:22px; font-weight:700; color:#1e293b; letter-spacing:-0.4px; margin:0;">
                User Management
            </h1>
            <p style="color:#94a3b8; font-size:13px; margin-top:3px;">
                Manage all hospital staff accounts and their roles.
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary-white">
            <i class="bi bi-plus-lg"></i> Add New User
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                    </div>
                    <i class="bi bi-people stat-icon" style="color:#1d4ed8;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Active</div>
                        <div class="stat-value" style="color:#16a34a;">{{ $stats['active'] }}</div>
                    </div>
                    <i class="bi bi-check-circle stat-icon" style="color:#16a34a;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Inactive</div>
                        <div class="stat-value" style="color:#dc2626;">{{ $stats['inactive'] }}</div>
                    </div>
                    <i class="bi bi-slash-circle stat-icon" style="color:#dc2626;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Doctors</div>
                        <div class="stat-value" style="color:#1d4ed8;">{{ $stats['doctors'] }}</div>
                    </div>
                    <i class="bi bi-heart-pulse stat-icon" style="color:#1d4ed8;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="page-card">

        {{-- Card Header: Search & Filters --}}
        <div class="page-card-header">
            <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
                <input type="text" name="search" class="form-control-white" placeholder="Search name, username, email…"
                    value="{{ request('search') }}" style="min-width:220px;">

                <select name="role" class="form-select-white" style="min-width:150px;">
                    <option value="">All Roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="form-select-white" style="min-width:130px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="btn-primary-white">
                    <i class="bi bi-search"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn-clear-white">
                        <i class="bi bi-x"></i> Clear
                    </a>
                @endif
            </form>

            <div style="font-size:12px; color:#94a3b8; white-space:nowrap;">
                {{ $users->total() }} result{{ $users->total() != 1 ? 's' : '' }}
            </div>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table class="table-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Username</th>
                        <th>Linked Employee</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td style="color:#94a3b8; font-family:monospace; font-size:12px;">
                                {{ $user->id }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600; font-size:13.5px; color:#1e293b;">{{ $user->name }}</div>
                                        <div style="color:#94a3b8; font-size:12px;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-family:monospace; font-size:12.5px; color:#64748b;">
                                    {{ $user->username }}
                                </span>
                            </td>
                            <td>
                                @if($user->employee)
                                    <span class="emp-chip">
                                        <i class="bi bi-person-badge" style="font-size:11px;"></i>
                                        {{ $user->employee->name }}
                                        @if($user->employee->employee_code)
                                            <span style="opacity:0.6; font-size:10.5px;">({{ $user->employee->employee_code }})</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="emp-chip-none">— System User</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-role role-{{ $user->role }}">
                                    {{ $roles[$user->role] ?? $user->role }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status {{ $user->is_active ? 'active' : 'inactive' }}">
                                    <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="color:#64748b; font-size:12.5px;">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-ghost-white" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-ghost-white" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-ghost-white"
                                            style="color:{{ $user->is_active ? '#f59e0b' : '#16a34a' }};"
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Delete user {{ $user->name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-white" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:50px 20px;">
                                <i class="bi bi-people"
                                    style="font-size:36px; color:#cbd5e1; display:block; margin-bottom:10px;"></i>
                                <div style="color:#64748b; font-size:14px;">No users found</div>
                                <div style="color:#94a3b8; font-size:12px; margin-top:4px;">
                                    Try adjusting your filters or
                                    <a href="{{ route('admin.users.create') }}" style="color:#1d4ed8;">add a new user</a>.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div style="padding:16px 20px; border-top:1px solid #e2e8f0;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

@endsection
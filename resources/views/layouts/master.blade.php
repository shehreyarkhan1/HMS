<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediCare HMS — @yield('title', 'Dashboard')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fb;
            color: #1e293b;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: width 0.3s ease;
        }

        .sidebar-brand {
            padding: 18px 16px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: #1d4ed8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon i {
            color: white;
            font-size: 18px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 11px;
            color: #94a3b8;
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 0px;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 12px 16px 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            margin: 1px 8px;
            border-radius: 8px;
            font-size: 13.5px;
            color: #64748b;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            cursor: pointer;
        }

        .nav-item:hover {
            background: #f1f5f9;
            color: #1e293b;
            text-decoration: none;
        }

        .nav-item.active {
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 500;
        }

        .nav-item i {
            font-size: 16px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .nav-badge.info {
            background: #3b82f6;
        }

        /* ===== SIDEBAR FOOTER ===== */
        .sidebar-footer {
            border-top: 1px solid #e2e8f0;
            padding: 12px 8px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .topbar-breadcrumb {
            font-size: 12px;
            color: #94a3b8;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748b;
            position: relative;
            transition: background 0.15s;
        }

        .topbar-icon-btn:hover {
            background: #f1f5f9;
        }

        .notif-dot {
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            position: absolute;
            top: 7px;
            right: 7px;
            border: 1.5px solid white;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px 4px 4px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: background 0.15s;
        }

        .user-pill:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
        }

        .user-role {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            padding: 24px;
            flex: 1;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .sidebar.open {
                width: 240px;
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>

    {{-- Extra CSS from child pages --}}
    @stack('styles')
</head>

<body>

    {{-- ==================== SIDEBAR ==================== --}}
    <div class="sidebar" id="sidebar">

        {{-- Brand / Logo --}}
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-hospital"></i>
            </div>
            <div>
                <div class="brand-name">MediCare HMS</div>
                <div class="brand-sub">Hospital System</div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="sidebar-scroll">

            {{-- Main --}}
            <div class="nav-section-label">Main</div>
            @if (Route::has('dashboard'))
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    Dashboard
                </a>
            @endif


            {{-- Doctors --}}
            <div class="nav-section-label">Doctors & Staff</div>

            @if (Route::has('doctors.index'))
                <a href="{{ route('doctors.index') }}"
                    class="nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    Doctors
                </a>
            @endif

            {{-- Patients --}}
            <div class="nav-section-label">Patients</div>
            @if (Route::has('patients.index'))
                <a href="{{ route('patients.index') }}"
                    class="nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Patients
                    {{-- @if($pendingPatients > 0)
                    <span class="nav-badge">{{ $pendingPatients }}</span>
                    @endif --}}
                </a>
            @endif

            @if (Route::has('appointments.index'))
                <a href="{{ route('appointments.index') }}"
                    class="nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    Appointments
                </a>
            @endif

            @if (Route::has('staff.index'))
                <a href="{{ route('staff.index') }}" class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                    <i class="bi bi-person-workspace"></i>
                    Staff / HR
                </a>
            @endif


            {{-- Clinical --}}
            <div class="nav-section-label">Clinical</div>

            @if (Route::has('wards.index'))
                <a href="{{ route('wards.index') }}" class="nav-item {{ request()->routeIs('wards.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    Wards & Beds
                </a>
            @endif

            {{-- Pharmacy --}}
            <div class="nav-section-label">Pharmacy</div>

            @if (Route::has('pharmacy.medicines.index'))
                <a href="{{ route('pharmacy.medicines.index') }}"
                    class="nav-item {{ request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
                    <i class="bi bi-capsule"></i>
                    Add Medicines
                </a>
            @endif

            @if (Route::has('pharmacy.prescriptions.index'))
                <a href="{{ route('pharmacy.prescriptions.index') }}"
                    class="nav-item {{ request()->routeIs('pharmacy.prescriptions.*') ? 'active' : '' }}">
                    <i class="bi bi-file-medical"></i>
                    Prescriptions
                </a>
            @endif

            @if (Route::has('pharmacy.prescriptions.create'))
                <a href="{{ route('pharmacy.prescriptions.create') }}"
                    class="nav-item {{ request()->routeIs('pharmacy.prescriptions.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    New Prescription
                </a>
            @endif

            {{-- Laboratory --}}
            <div class="nav-section-label">Laboratory</div>

            @if (Route::has('lab.orders.index'))
                <a href="{{ route('lab.orders.index') }}"
                    class="nav-item {{ request()->routeIs('lab.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2"></i>
                    Lab Orders
                </a>
            @endif

            @if (Route::has('lab.orders.create'))
                <a href="{{ route('lab.orders.create') }}"
                    class="nav-item {{ request()->routeIs('lab.orders.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    New Order
                </a>
            @endif

            @if (Route::has('lab.tests.index'))
                <a href="{{ route('lab.tests.index') }}"
                    class="nav-item {{ request()->routeIs('lab.tests.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse"></i>
                    Manage Tests
                </a>
            @endif


            {{-- Lab Settings --}}
            <div class="nav-section-label">Lab Settings</div>

            @if (Route::has('lab.tests.create'))
                <a href="{{ route('lab.tests.create') }}"
                    class="nav-item {{ request()->routeIs('lab.tests.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-square"></i>
                    Add Test
                </a>
            @endif

            @if (Route::has('lab.categories.index'))
                <a href="{{ route('lab.categories.index') }}"
                    class="nav-item {{ request()->routeIs('lab.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    Test Categories
                </a>
            @endif

            @if (Route::has('lab.sample-types.index'))
                <a href="{{ route('lab.sample-types.index') }}"
                    class="nav-item {{ request()->routeIs('lab.sample-types.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet-fill"></i>
                    Sample Types
                </a>
            @endif

            <div class="nav-section-label">Radiology</div>
            @if (Route::has('radiology.orders.index'))
                <a href="{{ route('radiology.orders.index') }}"
                    class="nav-item {{ request()->routeIs('radiology.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-radioactive"></i>
                    Radiology
                </a>
            @endif

            <div class="nav-section-label">Radiology settings</div>
            @if (Route::has('radiology.exams.index'))
                <a href="{{ route('radiology.exams.index') }}"
                    class="nav-item {{ request()->routeIs('radiology.exams.*') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i>
                    Manage Exams
                </a>
            @endif

            @if (Route::has('radiology.modalities.index'))
                <a href="{{ route('radiology.modalities.index') }}"
                    class="nav-item {{ request()->routeIs('radiology.modalities.*') ? 'active' : '' }}">
                    <i class="bi bi-camera"></i>
                    Modalities
                </a>
            @endif

            @if (Route::has('radiology.body-parts.index'))
                <a href="{{ route('radiology.body-parts.index') }}"
                    class="nav-item {{ request()->routeIs('radiology.body-parts.*') ? 'active' : '' }}">
                    <i class="bi bi-person-bounding-box"></i>
                    Body Parts
                </a>
            @endif



            {{-- Finance --}}
            <div class="nav-section-label">Finance</div>

            @if (Route::has('billing.index'))
                <a href="{{ route('billing.index') }}"
                    class="nav-item {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    Billing
                </a>
            @endif

            @if (Route::has('reports.index'))
                <a href="{{ route('reports.index') }}"
                    class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    Reports
                </a>
            @endif

            {{-- System --}}
            <div class="nav-section-label">System</div>

            @if (Route::has('settings.index'))
                <a href="{{ route('settings.index') }}"
                    class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            @endif

            @if (Route::has('users.index'))
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i>
                    Users & Roles
                </a>
            @endif

        </div>

        {{-- Sidebar Footer --}}
        <div class="sidebar-footer">
            @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item w-100 border-0 bg-transparent text-start" style="color:#ef4444">
                        <i class="bi bi-box-arrow-left" style="color:#ef4444"></i>
                        Logout
                    </button>
                </form>
            @endif
        </div>

    </div>
    {{-- ==================== END SIDEBAR ==================== --}}


    {{-- ==================== MAIN WRAPPER ==================== --}}
    <div class="main-wrapper" id="mainWrapper">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="topbar-left">
                <div>
                    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                    <div class="topbar-breadcrumb">@yield('breadcrumb', 'Home / Dashboard')</div>
                </div>
            </div>

            <div class="topbar-right">

                {{-- Notification Bell --}}
                <button class="topbar-icon-btn">
                    <i class="bi bi-bell" style="font-size:16px"></i>
                    <span class="notif-dot"></span>
                </button>

                {{-- Search --}}
                <button class="topbar-icon-btn">
                    <i class="bi bi-search" style="font-size:15px"></i>
                </button>

                {{-- User Pill --}}
                <div class="user-pill">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="user-role">{{ auth()->user()->role ?? 'Administrator' }}</div>
                    </div>
                </div>

            </div>
        </div>
        {{-- END TOPBAR --}}

        {{-- PAGE CONTENT --}}
        <div class="page-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Child Page Content Yahan Aayega --}}
            @yield('content')

        </div>
        {{-- END PAGE CONTENT --}}

    </div>
    {{-- ==================== END MAIN WRAPPER ==================== --}}


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Extra JS from child pages --}}
    @stack('scripts')

</body>

</html>
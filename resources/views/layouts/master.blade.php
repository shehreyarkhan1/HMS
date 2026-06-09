<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hospital Management System — @yield('title', 'Dashboard')</title>
    {{-- ── App Config for JavaScript ── --}}
    <script>
        window.APP_URL = '{{ rtrim(url('/'), '/') }}';
        window.CSRF = '{{ csrf_token() }}';
    </script>
    {{-- favorite icon --}}
    <link rel="icon" href="{{ asset('fav.png') }}" type="image/x-icon">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Turbo — SPA-like navigation (no full page reload) --}}
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017.umd.js"></script>

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

        /* ===== TURBO PROGRESS BAR ===== */
        .turbo-progress-bar {
            height: 3px;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6);
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

        /* ===== PAGE TRANSITION ANIMATION ===== */
        @keyframes pageFadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-content {
            animation: pageFadeIn 0.18s ease;
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
    {{-- data-turbo-permanent: Sidebar Turbo navigation mein preserve hoti hai, re-render nahi hoti --}}
    <div class="sidebar" id="sidebar" data-turbo-permanent>

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
        <div class="sidebar-scroll" id="sidebarScrollArea">
            @php $user = auth()->user(); @endphp

            {{-- ── MAIN ── --}}
            <div class="nav-section-label">Main</div>

            {{-- Main Dashboard — doctors ka apna dashboard hai, unhe yeh nahi dikhana --}}
            @if (!($user && $user->hasRole('doctor')))
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            @endif

            {{-- Doctor dashboard — doctor role ya super_admin dono dekh sakte hain --}}
            @if ($user && ($user->hasRole('doctor') || $user->isSuperAdmin()))
                <a href="{{ route('doctor.dashboard') }}"
                    class="nav-item {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Doctor Dashboard
                </a>
            @endif


            {{-- ── PATIENTS & APPOINTMENTS ── --}}
            @if ($user && ($user->canAccess('patients') || $user->canAccess('appointments') || $user->canAccess('doctors')))
                <div class="nav-section-label">Patients & Doctors</div>

                @if ($user->canAccess('doctors'))
                    <a href="{{ route('doctors.index') }}"
                        class="nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Doctors
                    </a>
                @endif

                @if ($user->canAccess('patients'))
                    <a href="{{ route('patients.index') }}"
                        class="nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Patients
                    </a>
                @endif

                @if ($user->canAccess('appointments'))
                    <a href="{{ route('appointments.index') }}"
                        class="nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Appointments
                    </a>
                @endif
            @endif

            {{-- ── CLINICAL ── --}}
            @if ($user && $user->canAccess('wards'))
                <div class="nav-section-label">Clinical</div>

                <a href="{{ route('wards.index') }}"
                    class="nav-item {{ request()->routeIs('wards.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Wards & Beds
                </a>
            @endif

            {{-- ── PHARMACY ── --}}
            @if ($user && $user->canAccess('pharmacy'))
                <div class="nav-section-label">Pharmacy</div>

                {{-- Medicines Inventory — sirf pharmacist --}}
                @if ($user->hasRole('pharmacist') || $user->isSuperAdmin())
                    <a href="{{ route('pharmacy.medicines.index') }}"
                        class="nav-item {{ request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
                        <i class="bi bi-capsule"></i> Medicines Inventory
                    </a>
                @endif

                {{-- Prescriptions index — sirf pharmacist --}}
                @if ($user->hasRole('pharmacist') || $user->isSuperAdmin())
                    <a href="{{ route('pharmacy.prescriptions.index') }}"
                        class="nav-item {{ request()->routeIs('pharmacy.prescriptions.index') ? 'active' : '' }}">
                        <i class="bi bi-file-medical"></i> Prescriptions
                    </a>
                @endif

                {{-- New Prescription — sirf doctor --}}
                @if ($user->hasRole('doctor') || $user->isSuperAdmin())
                    <a href="{{ route('pharmacy.prescriptions.create') }}"
                        class="nav-item {{ request()->routeIs('pharmacy.prescriptions.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> New Prescription
                    </a>
                @endif

                {{-- Dispensing — sirf pharmacist --}}
                @if ($user->hasRole('pharmacist') || $user->isSuperAdmin())
                    <a href="{{ route('pharmacy.dispensings.index') }}"
                        class="nav-item {{ request()->routeIs('pharmacy.dispensings.*') ? 'active' : '' }}">
                        <i class="bi bi-bag-check"></i> Dispensing
                    </a>
                @endif
            @endif

            {{-- ── LABORATORY ── --}}
            @if ($user && $user->canAccess('lab'))
                <div class="nav-section-label">Laboratory</div>

                {{-- Lab Orders index — doctors ke liye nahi --}}
                @if (!$user->hasRole('doctor'))
                    <a href="{{ route('lab.orders.index') }}"
                        class="nav-item {{ request()->routeIs('lab.orders.index') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2"></i> Lab Orders
                    </a>
                @endif

                {{-- New Order — sabke liye --}}
                <a href="{{ route('lab.orders.create') }}"
                    class="nav-item {{ request()->routeIs('lab.orders.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> New Lab Order
                </a>

                {{-- Manage Tests & Settings — doctors ke liye nahi --}}
                @if (!$user->hasRole('doctor'))
                    <a href="{{ route('lab.tests.index') }}"
                        class="nav-item {{ request()->routeIs('lab.tests.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-pulse"></i> Manage Tests
                    </a>

                    <div class="nav-section-label">Lab Settings</div>
                    <a href="{{ route('lab.categories.index') }}"
                        class="nav-item {{ request()->routeIs('lab.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Test Categories
                    </a>
                    <a href="{{ route('lab.sample-types.index') }}"
                        class="nav-item {{ request()->routeIs('lab.sample-types.*') ? 'active' : '' }}">
                        <i class="bi bi-droplet-fill"></i> Sample Types
                    </a>
                @endif
            @endif

            {{-- ── RADIOLOGY ── --}}
            @if ($user && $user->canAccess('radiology'))
                <div class="nav-section-label">Radiology</div>

                {{-- Orders index — doctor nahi dekhe --}}
                @if (!$user->hasRole('doctor'))
                    <a href="{{ route('radiology.orders.index') }}"
                        class="nav-item {{ request()->routeIs('radiology.orders.index') ? 'active' : '' }}">
                        <i class="bi bi-radioactive"></i> Radiology Orders
                    </a>
                @endif

                {{-- New Radiology Order — doctor ke liye --}}
                @if ($user->hasRole('doctor'))
                    <a href="{{ route('radiology.orders.create') }}"
                        class="nav-item {{ request()->routeIs('radiology.orders.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> New Radiology Order
                    </a>
                @endif

                {{-- Settings — doctor nahi dekhe --}}
                @if (!$user->hasRole('doctor'))
                    <div class="nav-section-label">Radiology Settings</div>
                    <a href="{{ route('radiology.exams.index') }}"
                        class="nav-item {{ request()->routeIs('radiology.exams.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> Manage Exams
                    </a>
                    <a href="{{ route('radiology.modalities.index') }}"
                        class="nav-item {{ request()->routeIs('radiology.modalities.*') ? 'active' : '' }}">
                        <i class="bi bi-camera"></i> Modalities
                    </a>
                    <a href="{{ route('radiology.body-parts.index') }}"
                        class="nav-item {{ request()->routeIs('radiology.body-parts.*') ? 'active' : '' }}">
                        <i class="bi bi-person-bounding-box"></i> Body Parts
                    </a>
                @endif
            @endif

            {{-- ── OPERATION THEATER ── --}}
            @if ($user && ($user->isSuperAdmin() || $user->hasAnyRole(['doctor', 'nurse'])))
                <div class="nav-section-label">Operation Theater</div>

                {{-- OT Rooms — doctor nahi dekhe --}}
                @if (!$user->hasRole('doctor'))
                    <a href="{{ route('ot.rooms.index') }}"
                        class="nav-item {{ request()->routeIs('ot.rooms.*') ? 'active' : '' }}">
                        <i class="bi bi-hospital"></i> OT Rooms
                    </a>
                @endif

                <a href="{{ route('ot.index') }}"
                    class="nav-item {{ request()->routeIs('ot.index') || request()->routeIs('ot.schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-week"></i> OT Schedules
                </a>
            @endif

            {{-- ── BLOOD BANK ── (super_admin only) ── --}}
            @if ($user && $user->isSuperAdmin())
                <div class="nav-section-label">Blood Bank</div>

                <a href="{{ route('blood-bank.index') }}"
                    class="nav-item {{ request()->routeIs('blood-bank.index') ? 'active' : '' }}">
                    <i class="bi bi-droplet"></i> Blood Bank Dashboard
                </a>
                <a href="{{ route('blood-bank.donors.index') }}"
                    class="nav-item {{ request()->routeIs('blood-bank.donors.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Donors
                </a>
                <a href="{{ route('blood-bank.donations.index') }}"
                    class="nav-item {{ request()->routeIs('blood-bank.donations.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet"></i> Donations
                </a>
                <a href="{{ route('blood-bank.requests.index') }}"
                    class="nav-item {{ request()->routeIs('blood-bank.requests.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet"></i> Blood Requests
                </a>
            @endif

            {{-- ── MORTUARY ── (super_admin only) ── --}}
            @if ($user && $user->isSuperAdmin())
                <div class="nav-section-label">Mortuary</div>

                <a href="{{ route('mortuary.index') }}"
                    class="nav-item {{ request()->routeIs('mortuary.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-medical"></i> Mortuary Records
                </a>
            @endif

            {{-- ── HR / EMPLOYEES ──
            @if ($user && $user->canAccess('staff'))
                <div class="nav-section-label">Employee Management</div>

                <a href="{{ route('employees.index') }}"
                    class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Employees
                </a>
            @endif --}}





            @if ($user->canAccess('staff'))
                <div class="nav-section-label">HR Management</div>

                <a href="{{ route('employees.index') }}"
                    class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Employees
                </a>
                <a href="{{ route('hr.attendance.index') }}"
                    class="nav-item {{ request()->routeIs('hr.attendance.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Attendance
                </a>
                <a href="{{ route('hr.leaves.index') }}"
                    class="nav-item {{ request()->routeIs('hr.leaves.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-x"></i> Leave Management
                </a>
                <a href="{{ route('hr.salary.index') }}"
                    class="nav-item {{ request()->routeIs('hr.salary.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i> Salary Structures
                </a>
                <a href="{{ route('hr.payroll.index') }}"
                    class="nav-item {{ request()->routeIs('hr.payroll.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> Payroll
                </a>
                <a href="{{ route('hr.disciplinary.index') }}"
                    class="nav-item {{ request()->routeIs('hr.disciplinary.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-exclamation"></i> Disciplinary
                </a>
                <a href="{{ route('hr.holidays.index') }}"
                    class="nav-item {{ request()->routeIs('hr.holidays.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-heart"></i> Holidays
                </a>
                <a href="{{ route('hr.leave-types.index') }}"
                    class="nav-item {{ request()->routeIs('hr.leave-types.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Leave Types
                </a>
            @endif














            {{-- ── FINANCE ── --}}
            @if ($user && $user->canAccess('billing'))
                <div class="nav-section-label">Finance</div>

                <a href="{{ route('billing.index') }}"
                    class="nav-item {{ request()->routeIs('billing.index') ? 'active' : '' }}">
                    <i class="bi bi-currency-dollar"></i> Billing
                </a>
                <a href="{{ route('billing.service-charges.index') }}"
                    class="nav-item {{ request()->routeIs('billing.service-charges.*') ? 'active' : '' }}">
                    <i class="bi bi-list-task"></i> Service Charges
                </a>
            @endif

            {{-- ── REPORTS ── --}}
            @if ($user && $user->canAccess('reports'))
                <div class="nav-section-label">Reports</div>

                <a href="{{ route('reports.patients.index') }}"
                    class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Patient Reports
                </a>
            @endif

            {{-- ── USER MANAGEMENT ── (super_admin only) ── --}}
            @if ($user && $user->isSuperAdmin())
                <div class="nav-section-label">System</div>

                <a href="{{ route('admin.users.index') }}"
                    class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i> Users & Roles
                </a>
            @endif

        </div>

        {{-- Sidebar Footer --}}
        <div class="sidebar-footer">
            @if (Route::has('logout'))
                {{-- Logout form: data-turbo="false" kyunke logout full page request chahti hai --}}
                <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                    @csrf
                    <button type="submit" class="nav-item w-100 border-0 bg-transparent text-start"
                        style="color:#ef4444">
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
        {{-- data-turbo-permanent: Topbar bhi preserve hoti hai --}}
        <div class="topbar" data-turbo-permanent id="topbar">
            <div class="topbar-left">
                <div>
                    <div class="topbar-title" id="topbarTitle">@yield('page-title', 'Dashboard')</div>
                    <div class="topbar-breadcrumb" id="topbarBreadcrumb">@yield('breadcrumb', 'Home / Dashboard')</div>
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
                        {{ strtoupper(substr(auth()->user() ? auth()->user()->name : 'Admin', 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Admin' }}</div>
                        <div class="user-role">{{ auth()->user() ? auth()->user()->role : 'Administrator' }}</div>
                    </div>
                </div>

            </div>
        </div>
        {{-- END TOPBAR --}}

        {{-- PAGE CONTENT --}}
        <div class="page-content">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Child Page Content --}}
            @yield('content')

        </div>
        {{-- END PAGE CONTENT --}}

    </div>
    {{-- ==================== END MAIN WRAPPER ==================== --}}


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ================================================================
        // SIDEBAR SCROLL PERSISTENCE
        // Turbo navigation par sidebar scroll position preserve karo
        // ================================================================
        (function() {
            const SCROLL_KEY = 'sidebarScrollPos';
            const scrollArea = document.getElementById('sidebarScrollArea');

            // Pehli baar ya direct visit par bhi restore karo
            function restoreScroll() {
                const pos = sessionStorage.getItem(SCROLL_KEY);
                if (pos && scrollArea) {
                    scrollArea.scrollTop = parseInt(pos, 10);
                }
            }

            // Turbo page load event (normal page load + turbo navigation dono cover karta hai)
            document.addEventListener('turbo:load', restoreScroll);

            // Fallback: agar Turbo na ho ya first load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', restoreScroll);
            } else {
                restoreScroll();
            }

            // Har nav-item click par position save karo
            document.addEventListener('click', function(e) {
                const navItem = e.target.closest('.nav-item');
                if (navItem && scrollArea) {
                    sessionStorage.setItem(SCROLL_KEY, scrollArea.scrollTop);
                }
            });
        })();


        // ================================================================
        // ACTIVE NAV ITEM — Turbo navigation par highlight update karo
        // (Sidebar permanent hai isliye manually active class update karni hai)
        // ================================================================
        document.addEventListener('turbo:load', function() {
            const currentPath = window.location.pathname;

            document.querySelectorAll('.nav-item[href]').forEach(function(link) {
                link.classList.remove('active');
                // Exact match ya sub-path match
                const href = link.getAttribute('href');
                if (href && (currentPath === href || currentPath.startsWith(href + '/'))) {
                    link.classList.add('active');
                }
            });
        });


        // ================================================================
        // BOOTSTRAP ALERTS — Turbo par auto-dismiss karo
        // ================================================================
        document.addEventListener('turbo:load', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) bsAlert.close();
                }, 4000);
            });
        });
    </script>

    {{-- Extra JS from child pages --}}
    @stack('scripts')

</body>

</html>

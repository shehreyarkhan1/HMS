<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — MediCore HMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 900px;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            background: #fff;
        }

        /* ── LEFT PANEL ── */
        .panel-left {
            background: #0a1628;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo { display: flex; align-items: center; gap: 12px; margin-bottom: 3rem; }
        .logo-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: #1e88e5;
            display: grid; place-items: center;
        }
        .logo-icon svg { width: 22px; height: 22px; fill: white; }
        .logo-name { font-family: 'Playfair Display', serif; font-size: 15px; color: #fff; }
        .logo-sub  { font-size: 10px; color: rgba(255,255,255,.4); letter-spacing: .1em; text-transform: uppercase; margin-top: 2px; }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 30px; font-weight: 500;
            color: #fff; line-height: 1.35;
            margin-bottom: 1rem;
        }
        .hero p { font-size: 13px; color: rgba(255,255,255,.5); line-height: 1.75; }

        .tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 2.5rem; }
        .tag {
            font-size: 11px; padding: 4px 10px;
            border-radius: 20px;
            border: 0.5px solid rgba(255,255,255,.12);
            color: rgba(255,255,255,.4);
        }

        /* ── RIGHT PANEL ── */
        .panel-right { padding: 3rem 2.5rem; display: flex; flex-direction: column; justify-content: center; }
        .panel-right h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 500; color: #0a1628; }
        .panel-right > p { font-size: 13px; color: #888; margin-top: .3rem; margin-bottom: 2.25rem; }

        /* Alert */
        .alert {
            font-size: 13px; padding: 10px 14px;
            border-radius: 8px; margin-bottom: 1.25rem;
            background: #fef2f2; color: #b91c1c;
            border: 0.5px solid #fecaca;
        }

        /* Fields */
        .field { margin-bottom: 1.25rem; }
        label { display: block; font-size: 11px; font-weight: 500; color: #888; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 6px; }

        .input-wrap { position: relative; }
        .input-wrap .icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #bbb; width: 16px; }
        .input-wrap input {
            width: 100%; height: 44px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0 12px 0 38px;
            font-size: 14px; font-family: 'DM Sans', sans-serif;
            color: #111;
            outline: none;
            transition: border-color .15s;
        }
        .input-wrap input:focus { border-color: #1e88e5; }

        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem; font-size: 13px;
        }
        .remember-row label { text-transform: none; letter-spacing: 0; color: #555; font-size: 13px; font-weight: 400; display: flex; align-items: center; gap: 6px; cursor: pointer; }
        .remember-row a { color: #1e88e5; text-decoration: none; font-size: 13px; }

        .btn-submit {
            width: 100%; height: 44px;
            background: #0a1628; color: #fff;
            border: none; border-radius: 8px;
            font-size: 14px; font-family: 'DM Sans', sans-serif;
            font-weight: 500; cursor: pointer;
            letter-spacing: .02em;
            transition: background .2s;
        }
        .btn-submit:hover { background: #1e88e5; }

        /* Role chips */
        .role-section { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f3f4f6; }
        .role-section p { font-size: 11px; color: #bbb; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .75rem; }
        .chips { display: flex; flex-wrap: wrap; gap: 6px; }
        .chip {
            font-size: 11px; padding: 3px 9px;
            border-radius: 4px;
            background: #f9fafb; color: #6b7280;
            border: 0.5px solid #e5e7eb;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .card { grid-template-columns: 1fr; }
            .panel-left { display: none; }
        }
    </style>
</head>
<body>

<div class="card">

    {{-- ── LEFT PANEL ── --}}
    <div class="panel-left">
        <div class="logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24"><path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 14H8v-2h4v2zm4-4H8v-2h8v2zm0-4H8V7h8v2z"/></svg>
            </div>
            <div>
                <div class="logo-name">MediCore HMS</div>
                <div class="logo-sub">Hospital Management</div>
            </div>
        </div>

        <div class="hero">
            <h1>Managing care,<br>simplified.</h1>
            <p>A unified platform for your entire hospital — patients, doctors, lab, pharmacy, and billing in one place.</p>
            <div class="tags">
                @foreach(['Patients','Doctors','Lab','Radiology','Pharmacy','Blood Bank','HR','Billing','OT','Mortuary'] as $mod)
                    <span class="tag">{{ $mod }}</span>
                @endforeach
            </div>
        </div>

        <p style="font-size:11px; color:rgba(255,255,255,.25); margin-top:2rem;">
            &copy; {{ date('Y') }} MediCore HMS. All rights reserved.
        </p>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="panel-right">
        <h2>Welcome back</h2>
        <p>Sign in to access your dashboard</p>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Session status (e.g. password reset success) --}}
        @if (session('status'))
            <div class="alert" style="background:#f0fdf4; color:#15803d; border-color:#bbf7d0;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Username / Email --}}
            <div class="field">
                <label for="login">Username or Email</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                    <input type="text" id="login" name="login"
                           value="{{ old('login') }}"
                           placeholder="username or email@hospital.com"
                           autofocus required>
                </div>
            </div>

            {{-- Password --}}
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                </div>
            </div>

            {{-- Remember me & Forgot --}}
            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
                {{-- Uncomment when password reset is set up --}}
                {{-- <a href="{{ route('password.request') }}">Forgot password?</a> --}}
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div class="role-section">
            <p>Role-based access</p>
            <div class="chips">
                @foreach(['Super Admin','Doctor','Receptionist','Nurse','Lab Tech','Radiologist','Pharmacist','HR Manager','Accountant'] as $role)
                    <span class="chip">{{ $role }}</span>
                @endforeach
            </div>
        </div>
    </div>

</div>

</body>
</html>

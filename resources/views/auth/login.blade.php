@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — MediCore HMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #d4e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 880px;
            width: 100%;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.10);
            background: #fff;
        }

        /* ── LEFT PANEL ── */
        .panel-left {
            background: linear-gradient(160deg, #eaf6f0 0%, #c2e8d8 55%, #a0d9c8 100%);
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            min-height: 480px;
            overflow: hidden;
        }

        /* decorative circle top-left */
        .panel-left::before {
            content: '';
            position: absolute;
            top: -55px;
            left: -55px;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            pointer-events: none;
        }

        /* decorative circle bottom-right */
        .panel-left::after {
            content: '';
            position: absolute;
            bottom: 60px;
            right: 20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.4);
            pointer-events: none;
        }

        .dot-yellow {
            position: absolute;
            top: 40px;
            right: 60px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #f8d57e;
            opacity: 0.9;
            z-index: 3;
        }

        .dot-yellow2 {
            position: absolute;
            bottom: 130px;
            left: 28px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #f8d57e;
            opacity: 0.7;
            z-index: 3;
        }

        /* Text block — top portion, left-aligned with padding */
        .hero-text {
            position: relative;
            z-index: 4;
            padding: 2.5rem 2rem 0 2rem;
            flex-shrink: 0;
        }

        .hero-text h1 {
            font-family: 'DM Sans', sans-serif;
            font-size: 42px;
            font-weight: 700;
            color: #1a2e2a;
            line-height: 1.1;
            margin-bottom: 0.85rem;
            letter-spacing: -0.5px;
        }

        .hero-text h1 span {
            color: #3cb89a;
        }

        .hero-text p {
            font-size: 14px;
            color: #4a6b62;
            line-height: 1.7;
            max-width: 180px;
        }

        /* Doctor image — bottom portion, centered */
        .doctor-wrap {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 0;
        }

        .doctor-wrap img {
            height: 300px;
            object-fit: contain;
            display: block;
        }

        /* fallback SVG doctor shown if no image */
        .doctor-svg {
            height: 300px;
            width: auto;
        }

        /* ── RIGHT PANEL ── */
        .panel-right {
            padding: 3rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        /* Logo */
        .logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .logo-row img {
            height: 36px;
            width: 36px;
            object-fit: contain;
            border-radius: 8px;
        }

        .logo-name {
            font-size: 20px;
            font-weight: 600;
            color: #1a2e2a;
        }

        .logo-name span {
            color: #3cb89a;
        }

        .panel-right h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
            color: #1a2e2a;
            text-align: center;
            margin-bottom: 0.3rem;
        }

        .panel-right>.subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* Alert */
        .alert-error {
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            background: #fef2f2;
            color: #b91c1c;
            border: 0.5px solid #fecaca;
        }

        .alert-success {
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            background: #f0fdf4;
            color: #15803d;
            border: 0.5px solid #bbf7d0;
        }

        /* Fields */
        .field {
            margin-bottom: 1.15rem;
        }

        label.field-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #444;
            margin-bottom: 5px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            width: 16px;
        }

        .input-wrap input {
            width: 100%;
            height: 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            padding: 0 12px 0 38px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: #111;
            outline: none;
            transition: border-color .15s;
            background: #fff;
        }

        .input-wrap input:focus {
            border-color: #3cb89a;
        }

        /* Login button */
        .btn-submit {
            width: 100%;
            height: 46px;
            background: #3cb89a;
            color: #fff;
            border: none;
            border-radius: 50px;
            font-size: 15px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.6rem;
            letter-spacing: 0.02em;
            transition: background .2s;
        }

        .btn-submit:hover {
            background: #2da888;
        }

        /* Links */
        .link-teal {
            color: #3cb89a;
            text-decoration: none;
            font-size: 13px;
        }

        .link-teal:hover {
            text-decoration: underline;
        }

        .forgot-row {
            text-align: center;
            margin-top: 1rem;
        }

        .no-account {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-top: 0.6rem;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .card {
                grid-template-columns: 1fr;
            }

            .panel-left {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        {{-- ── LEFT PANEL ── --}}
        <div class="panel-left">
            <div class="dot-yellow"></div>
            <div class="dot-yellow2"></div>

            <div class="hero-text">
                <h1>HELLO <span>!</span></h1>
                <p>Please enter your details to continue</p>
            </div>

            <div class="doctor-wrap">
                {{--
                    Put a 3D doctor PNG at: public/images/doctor-3d.png
                    e.g. from: https://www.freepik.com/search?query=3d+doctor+character
                    If file doesn't exist, the SVG fallback below is shown instead.
                --}}
                @if (file_exists(public_path('images/doctor-3d.png')))
                    <img src="{{ asset('images/doctor-3d.png') }}" alt="Doctor illustration">
                @else
                    {{-- Inline SVG fallback doctor --}}
                    <svg class="doctor-svg" viewBox="0 0 200 380" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <ellipse cx="100" cy="372" rx="58" ry="10" fill="rgba(0,0,0,0.06)" />
                        <circle cx="100" cy="70" r="48" fill="#f5c5a0" />
                        <path d="M60 74 Q60 38 100 36 Q140 38 140 74" fill="#5a3220" />
                        <rect x="68" y="95" width="64" height="18" rx="9" fill="#f5c5a0" />
                        <rect x="55" y="113" width="90" height="132" rx="20" fill="#fff" />
                        <rect x="65" y="113" width="70" height="60" rx="4" fill="#3b7dd8" />
                        <path d="M75 143 h20 m-10-10 v20" stroke="#fff" stroke-width="4" stroke-linecap="round" />
                        <rect x="40" y="120" width="22" height="82" rx="11" fill="#f5c5a0" />
                        <rect x="138" y="120" width="22" height="82" rx="11" fill="#f5c5a0" />
                        <rect x="55" y="245" width="38" height="120" rx="14" fill="#3b7dd8" />
                        <rect x="107" y="245" width="38" height="120" rx="14" fill="#3b7dd8" />
                        <rect x="55" y="332" width="38" height="20" rx="8" fill="#2a5db0" />
                        <rect x="107" y="332" width="38" height="20" rx="8" fill="#2a5db0" />
                        <circle cx="87" cy="68" r="7" fill="#fff" />
                        <circle cx="113" cy="68" r="7" fill="#fff" />
                        <circle cx="88" cy="69" r="4" fill="#3a2010" />
                        <circle cx="114" cy="69" r="4" fill="#3a2010" />
                        <path d="M90 86 Q100 94 110 86" stroke="#c8845a" stroke-width="2.5" fill="none"
                            stroke-linecap="round" />
                    </svg>
                @endif
            </div>
        </div>

        {{-- ── RIGHT PANEL ── --}}
        <div class="panel-right">

            {{-- Logo --}}
            <div class="logo-row">
                @if (Setting::get('hospital_logo'))
                    <img src="{{ asset('storage/' . Setting::get('hospital_logo')) }}" alt="Logo">
                @endif
                <div class="logo-name">
                    <span>{{ Setting::get('hospital_name', 'MediCore') }}</span>
                </div>
            </div>

            <h2>Welcome Back</h2>
            <p class="subtitle">Sign in to access your dashboard</p>

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Session status (e.g. password reset success) --}}
            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Username / Email --}}
                <div class="field">
                    <label class="field-label" for="login">Username or E-mail</label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                        </svg>
                        <input type="text" id="login" name="login" value="{{ old('login') }}"
                            placeholder="username or email@hospital.com" autofocus required>
                    </div>
                </div>

                {{-- Password --}}
                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Log In</button>
            </form>

            {{-- Forgot password — uncomment when route is ready --}}
            {{-- <div class="forgot-row">
                <a href="{{ route('password.request') }}" class="link-teal">Forget Password?</a>
            </div> --}}

        </div>

    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') — {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('fav.png') }}" type="image/x-icon">

    <!-- Google Fonts (Inter) - Professional Standard -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5; /* Modern Indigo */
            --bg-color: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px); /* Subtle Dot Pattern */
            background-size: 24px 24px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1e293b;
        }

        /* Layout Centering */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Modern Alert Styling */
        .custom-alert {
            border: none;
            border-left: 4px solid;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: #fff;
        }

        /* Transition for interactivity */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom Button Override */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
    </style>
</head>
<body>

    <!-- Notification Toast-style Alerts -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
        @if(session('success'))
            <div class="alert alert-success custom-alert alert-dismissible fade show shadow-sm" role="alert" style="border-left-color: #22c55e;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger custom-alert alert-dismissible fade show shadow-sm" role="alert" style="border-left-color: #ef4444;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <main class="main-content fade-in">
        @yield('content')
    </main>

    <!-- Footer (Standard for Guest Pages) -->
    <footer class="py-4 text-center">
        <p class="text-muted small mb-0">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>

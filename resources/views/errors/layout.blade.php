<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            text-align: center;
            max-width: 500px;
            padding: 40px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: #e2e8f0;
            line-height: 1;
            margin-bottom: -20px;
        }

        .error-icon {
            font-size: 4rem;
            color: #4f46e5;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-code">@yield('code')</div>
            <div class="error-icon">@yield('icon')</div>
            <h2 class="fw-bold">@yield('heading')</h2>
            <p class="text-muted mb-4">@yield('message')</p>

            {{-- errors/layout.blade.php mein "Back to Dashboard" button ke sath yeh add kar sakte ho --}}
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4 py-2 fw-bold me-2"
                style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-2"></i>Go Back
            </a>
            <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2 fw-bold shadow-sm"
                style="border-radius: 8px;">
                <i class="bi bi-house-door me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</body>

</html>

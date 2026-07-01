<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        <!-- Icon Section -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 text-red-500 rounded-full mb-4 shadow-sm">
                <i class="bi bi-shield-lock-fill text-5xl"></i>
            </div>
            <h1 class="text-7xl font-extrabold text-slate-200 tracking-tighter mb-2">403</h1>
            <h2 class="text-2xl font-bold text-slate-800">Security Restriction</h2>
        </div>

        <!-- Content Section -->
        <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 mb-8">
            <p class="text-slate-600 leading-relaxed">
                {{ $friendly_message ?? "Maafi chahte hain! Aapke paas is section ya action ko istemal karne ki ijazat nahi hai. Agar aapko lagta hai ye galti hai, to admin se rabta karein." }}
            </p>

            <div class="mt-8 flex flex-col gap-3">
                <a href="{{ url()->previous() }}" class="flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    <i class="bi bi-arrow-left me-2"></i> Go Back Previous Page
                </a>
                <a href="{{ url('/') }}" class="flex items-center justify-center px-6 py-3 bg-white text-slate-700 border border-slate-200 font-semibold rounded-xl hover:bg-slate-50 transition-all">
                    <i class="bi bi-house-door me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Footer Footer -->
        <p class="text-slate-400 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }} System Support
        </p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center p-8 bg-white shadow-lg rounded-lg border-t-4 border-red-500">
        <h1 class="text-6xl font-bold text-red-500">403</h1>
        <h2 class="text-2xl font-semibold mt-4 text-gray-800">Oops! Access Denied</h2>

        <!-- Yahan wo message show hoga jo humne bootstrap/app.php se bheja hai -->
        <p class="text-gray-600 mt-2">
            {{ $friendly_message ?? "You don't have permission to perform this action." }}
        </p>

        <div class="mt-6">
            <a href="{{ url('/') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                Go Back Home
            </a>
        </div>
    </div>
</body>
</html>

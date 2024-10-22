<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Page</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 font-sans">
    <div id="loading-bar" class="fixed top-0 left-0 w-full h-1 bg-green-200 z-50">
        <div class="h-full bg-green-600 transition-all duration-300 ease-out" style="width: 0%"></div>
    </div>
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- green Branding Section -->
        <div class="bg-green-600 text-white p-8 md:w-2/6">
            <div class="h-full">
                <div>
                    <img src="{{ config('laravel-billing.branding.logo') }}" alt="Company Logo" class="h-10 mb-8">
                    <h1 class="text-4xl font-bold mb-4">{{ config('app.name') }}</h1>
                    <p class="text-xl mb-8">Manage your subscription and billing information</p>
                </div>
                <div>
                    <a href="{{ config('laravel-billing.dashboard_link', '/dashboard') }}"
                        class="inline-block bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-green-100 transition duration-200">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Billing Section -->
        <div class="p-8 md:w-4/6 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
    <script>
        // Initialize the page
        document.addEventListener('DOMContentLoaded', () => {

            // Simulating a page load
            const loadingBar = document.querySelector('#loading-bar > div');
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        document.getElementById('loading-bar').classList.add('opacity-0');
                    }, 300);
                } else {
                    width += Math.random() * 10;
                    loadingBar.style.width = `${Math.min(width, 100)}%`;
                }
            }, 100);
        });
    </script>
</body>

</html>

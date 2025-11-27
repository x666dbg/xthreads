<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-dark-950 via-dark-900 to-dark-800 text-white min-h-screen">
        <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 via-transparent to-secondary-900/10"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(120,119,198,0.1),transparent_50%)]"></div>

            <!-- Floating Elements -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-40 h-40 bg-gradient-to-br from-secondary-500/20 to-accent-500/20 rounded-full blur-xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-10 w-24 h-24 bg-gradient-to-br from-accent-500/20 to-primary-500/20 rounded-full blur-xl animate-pulse delay-500"></div>

            <div class="relative z-10 w-full max-w-md px-6">
                <!-- Logo Section -->
                <div class="text-center mb-8 animate-slide-down">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl shadow-colored mb-4">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent mb-2">
                        X-THREADS
                    </h1>
                    <p class="text-dark-400 text-lg">Connect with the world</p>
                </div>

                <!-- Auth Card -->
                <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-8 shadow-large animate-slide-up">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 text-dark-400 text-sm animate-fade-in">
                    <p>&copy; 2024 X-Threads. Made with ❤️ for connecting people.</p>
                </div>
            </div>
        </div>
    </body>
</html>

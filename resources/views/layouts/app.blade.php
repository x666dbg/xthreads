<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-900 text-gray-100">
        <div class="min-h-screen">
            <div class="max-w-7xl mx-auto flex">
                @include('layouts.partials.sidebar')
                <main class="flex-1 border-x border-gray-700">
                    @if (isset($header))
                        <header class="bg-gray-800/50 backdrop-blur sticky top-0 border-b border-gray-700">
                            <div class="px-4 sm:px-6 lg:px-8 py-4">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
                    {{ $slot }}
                </main>
                <div class="w-1/4 px-4 hidden lg:block">
                    {{-- Placeholder untuk Kolom Kanan --}}
                </div>
            </div>
        </div>

        {{-- Panggil komponen modal baru --}}
        <x-post-modal />

        {{-- Fungsi JavaScript baru --}}
        <script>
            function openPostModal(parentThreadId = null) {
                window.dispatchEvent(new CustomEvent('open-post-modal', {
                    detail: {
                        parentThreadId: parentThreadId
                    }
                }));
            }
        </script>
    </body>
</html>
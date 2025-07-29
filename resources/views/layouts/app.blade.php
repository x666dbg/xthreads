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
    <body class="font-sans antialiased bg-gradient-to-br from-dark-950 via-dark-900 to-dark-800 text-white min-h-screen">
        <div class="min-h-screen relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 via-transparent to-secondary-900/10 pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto flex min-h-screen">

                {{-- 1. Sidebar Navigasi --}}
                @include('layouts.partials.sidebar')

                {{-- 2. Konten Utama --}}
                <main class="flex-1 border-x border-dark-700/50 backdrop-blur-sm bg-dark-900/30">
                    @if (isset($header))
                        <header class="bg-dark-800/80 backdrop-blur-md sticky top-0 border-b border-dark-700/50 z-10">
                            <div class="px-6 py-4">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </main>

                {{-- 3. Kolom Kanan - Trending & Suggestions --}}
                <aside class="w-80 px-6 py-4 hidden lg:block">
                    <div class="sticky top-4 space-y-6">
                        {{-- Search Bar --}}
                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-2xl p-4 border border-dark-700/50">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" placeholder="Search X-Threads..." class="w-full pl-10 pr-4 py-3 bg-dark-700/50 border border-dark-600/50 rounded-xl text-white placeholder-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

                        {{-- Trending Topics --}}
                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-2xl p-6 border border-dark-700/50">
                            <h3 class="text-xl font-bold text-white mb-4">Trending</h3>
                            <div class="space-y-3">
                                <div class="hover:bg-dark-700/30 p-3 rounded-xl cursor-pointer transition-all duration-200">
                                    <p class="text-dark-400 text-sm">Trending in Technology</p>
                                    <p class="text-white font-semibold">#Laravel</p>
                                    <p class="text-dark-400 text-sm">12.5K Threads</p>
                                </div>
                                <div class="hover:bg-dark-700/30 p-3 rounded-xl cursor-pointer transition-all duration-200">
                                    <p class="text-dark-400 text-sm">Trending</p>
                                    <p class="text-white font-semibold">#WebDevelopment</p>
                                    <p class="text-dark-400 text-sm">8.2K Threads</p>
                                </div>
                                <div class="hover:bg-dark-700/30 p-3 rounded-xl cursor-pointer transition-all duration-200">
                                    <p class="text-dark-400 text-sm">Trending in Design</p>
                                    <p class="text-white font-semibold">#UI/UX</p>
                                    <p class="text-dark-400 text-sm">5.8K Threads</p>
                                </div>
                            </div>
                        </div>

                        {{-- Suggested Users --}}
                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-2xl p-6 border border-dark-700/50">
                            <h3 class="text-xl font-bold text-white mb-4">Who to follow</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">JD</span>
                                        </div>
                                        <div>
                                            <p class="text-white font-semibold">John Doe</p>
                                            <p class="text-dark-400 text-sm">@johndoe</p>
                                        </div>
                                    </div>
                                    <button class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-full text-sm font-semibold transition-all duration-200 hover:scale-105">
                                        Follow
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-accent-500 to-secondary-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">JS</span>
                                        </div>
                                        <div>
                                            <p class="text-white font-semibold">Jane Smith</p>
                                            <p class="text-dark-400 text-sm">@janesmith</p>
                                        </div>
                                    </div>
                                    <button class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-full text-sm font-semibold transition-all duration-200 hover:scale-105">
                                        Follow
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>

        {{-- Komponen Modal untuk Quick Reply --}}
        <x-post-modal />

        {{-- Fungsi Global untuk Membuka Modal --}}
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
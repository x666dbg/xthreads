<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/css/mobile.css', 'resources/js/app.js'])
        @auth
        <script>
            window.currentUser = @json(auth()->user());
            window.authToken = "{{ auth()->user()->createToken('notifications')->plainTextToken }}";
        </script>
        @endauth
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-dark-950 via-dark-900 to-dark-800 text-white overflow-hidden lg:overflow-hidden">
        <div x-data="{ sidebarOpen: false }">
            <div class="relative max-w-7xl mx-auto flex h-screen">

                <div class="hidden lg:block">
                    @include('layouts.partials.sidebar')
                </div>

                <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 lg:hidden" x-cloak>
                    <div @click="sidebarOpen = false" x-show="sidebarOpen"
                         x-transition:enter="transition-opacity ease-linear duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity ease-linear duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-dark-950 bg-opacity-75"></div>

                    <div x-show="sidebarOpen"
                         x-transition:enter="transition ease-in-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in-out duration-300 transform"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full"
                         class="relative flex-1 flex flex-col max-w-xs w-full bg-dark-900 border-r border-dark-700/50">
                        @include('layouts.partials.sidebar')
                    </div>
                </div>

                <main class="flex-1 lg:border-x border-dark-700/50 backdrop-blur-sm bg-dark-900/30 h-screen overflow-y-auto no-scrollbar">
                    <header class="bg-dark-800/80 backdrop-blur-md sticky top-0 border-b border-dark-700/50 z-10 safe-top">
                        <div class="relative flex items-center justify-center lg:justify-start h-16 px-4 sm:px-6 lg:px-8">
                            @if(request()->routeIs('dashboard'))
                                <div class="lg:hidden absolute left-4 top-1/2 -translate-y-1/2">
                                    <button @click.prevent="sidebarOpen = true" class="focus:outline-none">
                                        @if(auth()->user()->photo)
                                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                                 alt="{{ auth()->user()->username }}" 
                                                 class="w-8 h-8 rounded-full object-cover shadow-medium">
                                        @else
                                            <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium">
                                                <span class="text-white font-bold text-xs">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </button>
                                </div>
                            @else
                                <div class="lg:hidden absolute left-4 top-1/2 -translate-y-1/2">
                                    <button onclick="history.back()" class="p-2 hover:bg-dark-700/50 rounded-full transition-all duration-200 focus:outline-none">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <div class="flex-1 text-center lg:text-left">
                                @if (isset($header))
                                    {{ $header }}
                                @endif
                            </div>
                        </div>
                    </header>

                    <div class="animate-fade-in p-3 sm:p-4 lg:p-8 safe-bottom">
                        {{ $slot }}
                    </div>
                </main>

                <aside class="w-80 px-6 py-3 hidden lg:block">
                    <div class="sticky top-4 space-y-4">
                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-xl p-3 border border-dark-700/50">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" placeholder="Search X-Threads..." class="w-full pl-9 pr-3 py-2 bg-dark-700/50 border border-dark-600/50 rounded-lg text-sm text-white placeholder-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-xl p-4 border border-dark-700/50">
                            <h3 class="text-lg font-bold text-white mb-3">Trending</h3>
                            <div class="space-y-2">
                                <div class="hover:bg-dark-700/30 p-2 rounded-lg cursor-pointer transition-all duration-200">
                                    <p class="text-dark-400 text-xs">Trending in Technology</p>
                                    <p class="text-white font-semibold text-sm">#Laravel</p>
                                    <p class="text-dark-400 text-xs">12.5K Threads</p>
                                </div>
                                <div class="hover:bg-dark-700/30 p-2 rounded-lg cursor-pointer transition-all duration-200">
                                    <p class="text-dark-400 text-xs">Trending</p>
                                    <p class="text-white font-semibold text-sm">#WebDevelopment</p>
                                    <p class="text-dark-400 text-xs">8.2K Threads</p>
                                </div>
                                <div class="hover:bg-dark-700/30 p-2 rounded-lg cursor-pointer transition-all duration-200">
                                    <p class="text-dark-400 text-xs">Trending in Design</p>
                                    <p class="text-white font-semibold text-sm">#UI/UX</p>
                                    <p class="text-dark-400 text-xs">5.8K Threads</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-xl p-4 border border-dark-700/50">
                            <h3 class="text-lg font-bold text-white mb-3">Who to follow</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-semibold text-xs">JD</span>
                                        </div>
                                        <div>
                                            <p class="text-white font-semibold text-sm">John Doe</p>
                                            <p class="text-dark-400 text-xs">@johndoe</p>
                                        </div>
                                    </div>
                                    <button class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-full text-xs font-semibold transition-all duration-200 hover:scale-105 flex-shrink-0">
                                        Follow
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-accent-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-semibold text-xs">JS</span>
                                        </div>
                                        <div>
                                            <p class="text-white font-semibold text-sm">Jane Smith</p>
                                            <p class="text-dark-400 text-xs">@janesmith</p>
                                        </div>
                                    </div>
                                    <button class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-full text-xs font-semibold transition-all duration-200 hover:scale-105 flex-shrink-0">
                                        Follow
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>

        <x-reply-modal />

        <script>
            function openReplyModal(threadId = null, replyId = null) {
                window.dispatchEvent(new CustomEvent('open-reply-modal', {
                    detail: {
                        threadId: threadId,
                        replyId: replyId
                    }
                }));
            }
        </script>

    </body>
</html>

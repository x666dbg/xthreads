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
        <div x-data="{ sidebarOpen: false }" class="min-h-screen relative">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 via-transparent to-secondary-900/10 pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto flex min-h-screen">

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

                <main class="flex-1 border-x border-dark-700/50 backdrop-blur-sm bg-dark-900/30">
                    <header class="bg-dark-800/80 backdrop-blur-md sticky top-0 border-b border-dark-700/50 z-10">
                        <div class="relative flex items-center justify-center lg:justify-start h-16 px-4 sm:px-6 lg:px-8">
                            <div class="lg:hidden absolute left-4 top-1/2 -translate-y-1/2">
                                <button @click.prevent="sidebarOpen = true" class="text-white focus:outline-none">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium">
                                        <span class="text-white font-bold text-xs">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                                    </div>
                                </button>
                            </div>

                            <div class="flex-1 text-center lg:text-left">
                                @if (isset($header))
                                    {{ $header }}
                                @endif
                            </div>
                        </div>
                    </header>

                    <div class="animate-fade-in p-4 sm:p-6 lg:p-8">
                        {{ $slot }}
                    </div>
                </main>

                <aside class="w-80 px-6 py-4 hidden lg:block">
                    <div class="sticky top-4 space-y-6">
                        <div class="bg-dark-800/50 backdrop-blur-sm rounded-2xl p-4 border border-dark-700/50">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" placeholder="Search X-Threads..." class="w-full pl-10 pr-4 py-3 bg-dark-700/50 border border-dark-600/50 rounded-xl text-white placeholder-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

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

        @if (isset($postModal))
            {{ $postModal }}
        @else
            <x-reply-modal />
        @endif


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

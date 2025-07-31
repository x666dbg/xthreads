<aside class="w-72 px-6 h-full">
    <div class="sticky top-0 h-full flex flex-col py-6">
        {{-- Logo --}}
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center shadow-colored group-hover:scale-105 transition-all duration-300">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <span class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent">X-THREADS</span>
                    <p class="text-dark-400 text-sm">Connect & Share</p>
                </div>
            </a>
        </div>

        <nav class="flex-grow space-y-2">
            {{-- Link Beranda --}}
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 text-white text-lg font-semibold px-6 py-4 hover:bg-dark-700/50 rounded-2xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-primary-600/20 to-secondary-600/20 border border-primary-500/30' : '' }}">
                <div class="w-8 h-8 flex items-center justify-center">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span class="group-hover:translate-x-1 transition-transform duration-200">Home</span>
            </a>

            {{-- Link Explore --}}
            <a href="#" class="flex items-center space-x-4 text-white text-lg font-semibold px-6 py-4 hover:bg-dark-700/50 rounded-2xl transition-all duration-300 group">
                <div class="w-8 h-8 flex items-center justify-center">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <span class="group-hover:translate-x-1 transition-transform duration-200">Explore</span>
            </a>

            {{-- Link Notifications --}}
            <a href="{{ route('notifications.index') }}" class="flex items-center space-x-4 text-white text-lg font-semibold px-6 py-4 hover:bg-dark-700/50 rounded-2xl transition-all duration-300 group {{ request()->routeIs('notifications.index') ? 'bg-gradient-to-r from-primary-600/20 to-secondary-600/20 border border-primary-500/30' : '' }}">
                <div class="w-8 h-8 flex items-center justify-center relative">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.97 4.97a.75.75 0 0 0-1.08 1.05l-3.99 4.99a.75.75 0 0 0 1.08 1.05l3.99-4.99a.75.75 0 0 0 0-1.1zM9.02 8.69a.75.75 0 0 0-1.06 1.06l2.03 2.03a.75.75 0 0 0 1.06-1.06L9.02 8.69z"></path>
                    </svg>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold animate-pulse min-w-5">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </div>
                    @endif
                </div>
                <span class="group-hover:translate-x-1 transition-transform duration-200">Notifications</span>
            </a>

            {{-- Link Messages --}}
            <a href="#" class="flex items-center space-x-4 text-white text-lg font-semibold px-6 py-4 hover:bg-dark-700/50 rounded-2xl transition-all duration-300 group">
                <div class="w-8 h-8 flex items-center justify-center">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <span class="group-hover:translate-x-1 transition-transform duration-200">Messages</span>
            </a>

            {{-- Link Profil --}}
            @php $currentUser = auth()->user(); @endphp
            <a href="{{ route('profile.show', $currentUser->username) }}" class="flex items-center space-x-4 text-white text-lg font-semibold px-6 py-4 hover:bg-dark-700/50 rounded-2xl transition-all duration-300 group {{ request()->routeIs('profile.show') ? 'bg-gradient-to-r from-primary-600/20 to-secondary-600/20 border border-primary-500/30' : '' }}">
                <div class="w-8 h-8 flex items-center justify-center">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="group-hover:translate-x-1 transition-transform duration-200">Profile</span>
            </a>

        </nav>

        {{-- User Profile Section --}}
        <div class="mt-auto">
            <div class="bg-dark-800/50 backdrop-blur-sm rounded-2xl p-4 border border-dark-700/50 hover:bg-dark-700/50 transition-all duration-300 group">
                <div class="flex items-center space-x-3">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium group-hover:scale-105 transition-transform duration-200">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($currentUser->username, 0, 1)) }}</span>
                        </div>
                    </div>
                    {{-- User Info --}}
                    <div class="flex-1 min-w-0">
                        @php $currentUser = auth()->user(); @endphp
                        <p class="font-bold text-white truncate group-hover:text-primary-400 transition-colors duration-200">
                            {{ $currentUser->username }}
                        </p>
                        <p class="text-sm text-dark-400 truncate">
                            @ {{ $currentUser->username }}
                        </p>
                    </div>
                    {{-- More Options --}}
                    <div class="flex-shrink-0">
                        <button class="p-2 hover:bg-dark-600/50 rounded-full transition-all duration-200">
                            <svg class="w-5 h-5 text-dark-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="flex items-center w-full space-x-3 text-dark-300 hover:text-white text-sm font-medium px-3 py-2 hover:bg-dark-600/50 rounded-xl transition-all duration-200 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

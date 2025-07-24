<aside class="w-64 flex-shrink-0 px-4">
    <div class="sticky top-0 h-screen flex flex-col py-4">
        {{-- Logo --}}
        <div class="px-4 mb-4">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                </svg>
                <span class="ml-2 text-white">X-THREADS</span>
            </a>
        </div>

        <nav class="flex-grow">
            <ul>
                {{-- Link Beranda --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-white text-lg font-semibold px-4 py-3 hover:bg-gray-700 rounded-full">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Beranda</span>
                    </a>
                </li>
                {{-- Link Profil --}}
                <li>
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="flex items-center space-x-3 text-white text-lg font-semibold px-4 py-3 hover:bg-gray-700 rounded-full">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Info User & Logout --}}
        <div class="mt-auto">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full space-x-3 text-white text-left text-lg font-semibold px-4 py-3 hover:bg-gray-700 rounded-full">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
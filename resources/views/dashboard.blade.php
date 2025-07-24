<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Timeline') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- FORM UNTUK POSTING THREAD BARU --}}
                    <form method="POST" action="{{ route('threads.store') }}">
                        @csrf
                        <textarea
                            name="content"
                            rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                            placeholder="Apa yang kamu pikirkan, {{ Auth::user()->name }}?"></textarea>
                        
                        {{-- Menampilkan error validasi --}}
                        @error('content')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-primary-button class="mt-4">
                            {{ __('Post Thread') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            {{-- DAFTAR SEMUA THREADS --}}
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-700">
                @foreach ($threads as $thread)
                    <div class="p-6 flex space-x-4">
                        {{-- Avatar Sederhana --}}
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <a href="{{ route('profile.show', $thread->user->username) }}" class="text-gray-800 dark:text-gray-200 font-semibold hover:underline">
                                        {{ $thread->user->username }}
                                    </a>
                                    <small class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $thread->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <a href="{{ route('threads.show', $thread) }}">
                                <p class="mt-2 text-lg text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $thread->content }}
                                </p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
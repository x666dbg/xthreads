<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Timeline') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- FORM UNTUK POSTING THREAD BARU --}}
                    <form method="POST" action="{{ route('threads.store') }}" enctype="multipart/form-data">
                        @csrf
                        <textarea
                            name="content"
                            rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                            placeholder="Apa yang kamu pikirkan, {{ auth()->user()->username }}?"></textarea>
                        @error('content')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        {{-- TAMBAHKAN INPUT UNTUK GAMBAR DI SINI --}}
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Gambar (Opsional)')" />
                            <x-text-input id="image" name="image" type="file" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <x-primary-button class="mt-4">
                            {{ __('Post Thread') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            {{-- DAFTAR SEMUA THREADS --}}
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-700">
                @foreach ($timeline as $item)
                    @php
                        // Kita ambil thread asli, entah itu dari item biasa atau dari repost
                        $thread = $item->original_thread;
                    @endphp

                    <div class="p-6 flex space-x-4 border-b border-gray-200 dark:border-gray-700">
                        {{-- Avatar Sederhana --}}
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            {{-- Tampilkan notifikasi jika ini adalah repost --}}
                            @if ($item->is_repost)
                                <div class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"/></svg>
                                    Direpost oleh <a href="{{ route('profile.show', $item->reposted_by->username) }}" class="font-semibold hover:underline">{{ $item->reposted_by->username }}</a>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <div>
                                    <a href="{{ route('profile.show', $thread->user->username) }}" class="text-gray-800 dark:text-gray-200 font-semibold hover:underline">
                                        {{ $thread->user->username }}
                                    </a>
                                    <small class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</small>
                                </div>
                            </div>

                            {{-- =================== PERUBAHAN PENTING DI SINI =================== --}}

                            {{-- 1. LINK HANYA MEMBUNGKUS KONTEN --}}
                            <a href="{{ route('threads.show', $thread) }}" class="block mt-2">
                                <p class="text-lg text-gray-900 dark:text-gray-100">{{ $thread->content }}</p>

                                @if ($thread->image)
                                    <div class="mt-4 max-w-lg overflow-hidden rounded-lg border dark:border-gray-700">
                                        <img src="{{ Storage::url($thread->image) }}" alt="Gambar Thread" class="w-full">
                                    </div>
                                @endif
                            </a>

                            {{-- 2. TOMBOL AKSI BERADA DI LUAR LINK --}}
                            <x-thread-actions :thread="$thread" />

                            {{-- ================================================================= --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
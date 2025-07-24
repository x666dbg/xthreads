<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Profil Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        {{-- Avatar Sederhana --}}
                        <svg class="h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->username }}</h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Bergabung pada {{ $user->created_at->format('d F Y') }}
                                </p>
                            </div>
                            {{-- Tombol Follow/Unfollow --}}
                            <div>
                                @if (auth()->user()->isNot($user))
                                    @if (auth()->user()->following->contains($user))
                                        <form action="{{ route('profile.unfollow', $user->username) }}" method="POST">
                                            @csrf
                                            <x-secondary-button type="submit">Unfollow</x-secondary-button>
                                        </form>
                                    @else
                                        <form action="{{ route('profile.follow', $user->username) }}" method="POST">
                                            @csrf
                                            <x-primary-button type="submit">Follow</x-primary-button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 flex items-center space-x-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $user->following->count() }}</span> Mengikuti
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $user->followers->count() }}</span> Pengikut
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-700">
                @forelse ($timeline as $item)
                    @php
                        // Ambil thread asli, baik itu dari item biasa atau dari repost
                        $thread = $item->original_thread;
                    @endphp

                    <div class="p-6">
                        {{-- Tampilkan notifikasi jika ini adalah repost --}}
                        @if ($item->is_repost)
                            <div class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"/></svg>
                                Direpost oleh <a href="{{ route('profile.show', $item->reposted_by->username) }}" class="font-semibold hover:underline">{{ $item->reposted_by->username === auth()->user()->username ? 'Anda' : $item->reposted_by->username }}</a>
                            </div>
                        @endif

                        <div class="flex space-x-3">
                            {{-- Avatar kecil untuk thread --}}
                            <div class="flex-shrink-0">
                                <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <a href="{{ route('profile.show', $thread->user->username) }}" class="font-semibold text-gray-800 dark:text-gray-200 hover:underline">{{ $thread->user->username }}</a>
                                        <small class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <a href="{{ route('threads.show', $thread) }}">
                                    <p class="mt-2 text-lg text-gray-900 dark:text-gray-100">{{ $thread->content }}</p>
                                </a>
                                @if ($thread->image)
                                    <div class="mt-4">
                                        <img src="{{ Storage::url($thread->image) }}" alt="Gambar Thread" class="rounded-lg w-full object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>@ {{ $user->username }} belum memposting atau merepost apapun.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
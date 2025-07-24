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
                @forelse ($threads as $thread)
                    <div class="p-6">
                        <a href="{{ route('threads.show', $thread) }}" class="block">
                            <p class="text-lg text-gray-900 dark:text-gray-100">{{ $thread->content }}</p>
                            <small class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $thread->created_at->diffForHumans() }}</small>
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>@ {{ $user->username }} belum memposting thread apapun.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
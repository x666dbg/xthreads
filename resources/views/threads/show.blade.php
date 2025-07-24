<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thread Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $thread->user->username }}</span>
                                <small class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $thread->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="mt-2 text-lg text-gray-900 dark:text-gray-100">
                            {{ $thread->content }}
                        </p>
                        @if ($thread->image)
                            <div class="mt-4">
                                <img src="{{ Storage::url($thread->image) }}" alt="Thread image" class="rounded-lg w-full object-cover">
                            </div>
                        @endif
                        <div class="mt-4">
                            <x-thread-actions :thread="$thread" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('replies.store', $thread) }}">
                        @csrf
                        <textarea name="content" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" placeholder="Tulis balasanmu..."></textarea>
                        @error('content')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <x-primary-button class="mt-4">
                            {{ __('Balas') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-700">
                @foreach ($thread->replies as $reply)
                    <div class="p-6 flex space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $reply->user->username }}</span>
                                    <small class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-900 dark:text-gray-100">
                                {{ $reply->content }}
                            </p>
                            <div class="flex items-center mt-2 text-sm">
                                @if ($reply->isLikedBy(auth()->user()))
                                    <form action="{{ route('replies.unlike', $reply) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">Unlike</button>
                                    </form>
                                @else
                                    <form action="{{ route('replies.like', $reply) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-gray-500 hover:text-gray-600">Like</button>
                                    </form>
                                @endif
                                <span class="ml-2 text-gray-500">{{ $reply->likes->count() }} Likes</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
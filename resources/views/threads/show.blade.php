<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-200 leading-tight">Detail Thread</h2>
    </x-slot>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-3 sm:px-4 lg:px-8">
            @if($thread->parent)
            <div class="mb-4">
                 <x-quoted-thread :thread="$thread->parent" />
                 <div class="h-8 w-0.5 bg-gray-700 ml-5"></div>
            </div>
            @endif

            <div class="bg-gray-800 p-4 sm:p-6 rounded-lg">
                 <div class="flex space-x-3 sm:space-x-4">
                     <div class="flex-shrink-0">
                         <a href="{{ route('profile.show', $thread->user->username) }}" class="block group">
                             @if($thread->user->photo)
                                 <img src="{{ asset('storage/' . $thread->user->photo) }}" 
                                      alt="{{ $thread->user->username }}" 
                                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover shadow-medium group-hover:scale-105 transition-transform duration-200">
                             @else
                                 <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium group-hover:scale-105 transition-transform duration-200">
                                     <span class="text-white font-bold text-xs sm:text-sm">{{ strtoupper(substr($thread->user->username, 0, 1)) }}</span>
                                 </div>
                             @endif
                         </a>
                     </div>
                     <div class="flex-1">
                         <div>
                            {{-- PENAMBAHAN BADGE DI SINI (THREAD UTAMA) --}}
                            <div class="flex items-center">
                                <a href="{{ route('profile.show', $thread->user->username) }}" class="font-semibold text-gray-200 hover:underline">{{ $thread->user->username }}</a>
                                @if ($thread->user->isModerator())
                                    <x-moderator-badge />
                                @endif
                                <span class="text-dark-500">•</span>
                                <small class="ml-1 text-sm text-gray-400">{{ $thread->created_at->diffForHumans() }}</small>
                            </div>
                         </div>
                         <p class="mt-2 text-base sm:text-lg text-gray-100">{{ $thread->content }}</p>
                         @if ($thread->image)
                             <div class="mt-3 sm:mt-4 max-w-lg overflow-hidden rounded-lg border dark:border-gray-700">
                                 <img src="{{ Storage::url($thread->image) }}" alt="Gambar Thread" class="w-full">
                             </div>
                         @endif
                         <div class="mt-3 sm:mt-4">
                             <x-thread-actions :thread="$thread" />
                         </div>
                     </div>
                 </div>
            </div>

            <div class="mt-4 sm:mt-6 bg-gray-800 p-4 sm:p-6 rounded-lg">
                <form method="POST" action="{{ route('threads.store') }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $thread->id }}">
                    <textarea name="content" rows="3" class="w-full bg-gray-900 rounded-md border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600" placeholder="Post balasanmu..."></textarea>
                    <x-primary-button class="mt-2">Balas</x-primary-button>
                </form>
            </div>

            <div class="mt-6 divide-y divide-gray-700">
                @foreach ($thread->children as $reply)
                     <div class="p-3 sm:p-4">
                         <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                            <div class="flex-shrink-0">
                                @if($reply->user->photo)
                                    <img src="{{ asset('storage/' . $reply->user->photo) }}" 
                                         alt="{{ $reply->user->username }}" 
                                         class="w-6 h-6 rounded-full object-cover">
                                @else
                                    <div class="w-6 h-6 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">{{ strtoupper(substr($reply->user->username, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('profile.show', $reply->user->username) }}" class="font-semibold text-gray-200 hover:underline">{{ $reply->user->username }}</a>
                            @if ($reply->user->isModerator())
                                <x-moderator-badge />
                            @endif
                            <span>membalas:</span>
                         </div>
                         <x-quoted-thread :thread="$reply" />
                     </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
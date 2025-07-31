<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Detail Thread</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($thread->parent)
            <div class="mb-4">
                 <x-quoted-thread :thread="$thread->parent" />
                 <div class="h-8 w-0.5 bg-gray-700 ml-5"></div>
            </div>
            @endif

            <div class="bg-gray-800 p-6 rounded-lg">
                 <div class="flex space-x-4">
                     <div class="flex-shrink-0">
                         <a href="{{ route('profile.show', $thread->user->username) }}">
                             <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
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
                         <p class="mt-2 text-lg text-gray-100">{{ $thread->content }}</p>
                         @if ($thread->image)
                             <div class="mt-4 max-w-lg overflow-hidden rounded-lg border dark:border-gray-700">
                                 <img src="{{ Storage::url($thread->image) }}" alt="Gambar Thread" class="w-full">
                             </div>
                         @endif
                         <div class="mt-4">
                             <x-thread-actions :thread="$thread" />
                         </div>
                     </div>
                 </div>
            </div>

            <div class="mt-6 bg-gray-800 p-6 rounded-lg">
                <form method="POST" action="{{ route('threads.store') }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $thread->id }}">
                    <textarea name="content" rows="3" class="w-full bg-gray-900 rounded-md border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600" placeholder="Post balasanmu..."></textarea>
                    <x-primary-button class="mt-2">Balas</x-primary-button>
                </form>
            </div>

            <div class="mt-6 divide-y divide-gray-700">
                @foreach ($thread->children as $reply)
                     <div class="p-4">
                         <div class="text-sm text-gray-500">
                            {{-- PENAMBAHAN BADGE DI SINI (BALASAN) --}}
                            <div class="flex items-center">
                                <a href="{{ route('profile.show', $reply->user->username) }}" class="font-semibold text-gray-200 hover:underline">{{ $reply->user->username }}</a>
                                @if ($reply->user->isModerator())
                                    <x-moderator-badge />
                                @endif
                                <span class="ml-1">membalas:</span>
                            </div>
                         </div>
                         <x-quoted-thread :thread="$reply" />
                     </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
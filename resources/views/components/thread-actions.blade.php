@props(['thread'])

<div class="flex items-center mt-4 text-sm space-x-4">
    {{-- Tombol Komentar --}}
    <button
        @click.prevent="$dispatch('open-reply-modal', { threadId: {{ $thread->id }} })"
        class="flex items-center space-x-1 text-gray-500 hover:text-blue-500"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        <span class="text-xs">{{ $thread->replies()->count() }}</span>
    </button>

    {{-- Tombol Repost --}}
    <div class="flex items-center">
         @if (auth()->user()->reposts->contains($thread))
            <form action="{{ route('threads.repost.destroy', $thread) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center space-x-1 text-green-500 hover:text-green-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 110 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path></svg>
                    <span class="text-xs">{{ $thread->repostedBy->count() }}</span>
                </button>
            </form>
        @else
            <form action="{{ route('threads.repost', $thread) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-1 text-gray-500 hover:text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"></path></svg>
                    <span class="text-xs">{{ $thread->repostedBy->count() }}</span>
                </button>
            </form>
        @endif
    </div>

    {{-- Tombol Like --}}
    <div class="flex items-center">
        @if ($thread->isLikedBy(auth()->user()))
            <form action="{{ route('threads.unlike', $thread) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center space-x-1 text-red-500 hover:text-red-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    <span class="text-xs">{{ $thread->likes->count() }}</span>
                </button>
            </form>
        @else
            <form action="{{ route('threads.like', $thread) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-1 text-gray-500 hover:text-red-500">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    <span class="text-xs">{{ $thread->likes->count() }}</span>
                </button>
            </form>
        @endif
    </div>
</div>
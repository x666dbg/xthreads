@props(['thread'])

<div class="flex items-center mt-4 text-sm space-x-4">
    {{-- Tombol Komentar --}}
    <button type="button" onclick="openReplyModal({{ $thread->id }})" class="flex items-center space-x-1 text-gray-500 hover:text-blue-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        <span class="text-xs">{{ $thread->children?->count() ?? 0 }}</span>
    </button>

    {{-- Tombol Repost --}}
    <div class="flex items-center">
        <form action="{{ route('threads.repost', $thread) }}" method="POST" class="repost-form">
            @csrf
            <button type="submit" class="repost-button flex items-center space-x-1 {{ auth()->user()->reposts->contains($thread) ? 'text-green-500 hover:text-green-600' : 'text-gray-500 hover:text-green-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span class="text-xs repost-count">{{ $thread->repostedBy?->count() ?? 0 }}</span>
            </button>
        </form>
    </div>

    {{-- Tombol Like --}}
    <div class="flex items-center">
        <form action="{{ route('threads.like', $thread) }}" method="POST" class="like-form">
            @csrf
            <button type="submit" class="like-button flex items-center space-x-1 {{ $thread->isLikedBy(auth()->user()) ? 'text-red-500 hover:text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                @if ($thread->isLikedBy(auth()->user()))
                    <svg class="w-5 h-5 fill-current" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                @endif
                <span class="text-xs like-count">{{ $thread->likes?->count() ?? 0 }}</span>
            </button>
        </form>
    </div>

    @can('delete', $thread)
        <form action="{{ route('threads.destroy', $thread) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus thread ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center space-x-1 text-gray-500 hover:text-accent-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </form>
    @endcan
</div>
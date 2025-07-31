@props(['thread'])

<div class="mt-3 p-3 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800/50" onclick="window.location.href='{{ route('threads.show', $thread) }}'">
    <div class="flex items-center space-x-2 text-sm">
        <div class="flex-shrink-0">
            @if($thread->user->photo)
                <img src="{{ asset('storage/' . $thread->user->photo) }}" 
                     alt="{{ $thread->user->username }}" 
                     class="w-6 h-6 rounded-full object-cover">
            @else
                <div class="w-6 h-6 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xs">{{ strtoupper(substr($thread->user->username, 0, 1)) }}</span>
                </div>
            @endif
        </div>
        <a href="{{ route('profile.show', $thread->user->username) }}" class="font-semibold text-white hover:underline">
            {{ $thread->user->username }}
        </a>
        @if ($thread->user->isModerator())
            <x-moderator-badge />
        @endif
        <span class="text-gray-500">{{ $thread->created_at->diffForHumans() }}</span>
    </div>
    <p class="mt-1 text-gray-400 truncate">{{ $thread->content }}</p>
    @if ($thread->image)
        <img src="{{ Storage::url($thread->image) }}" alt="Gambar Thread Induk" class="mt-2 h-24 w-24 rounded-lg object-cover">
    @endif
</div>
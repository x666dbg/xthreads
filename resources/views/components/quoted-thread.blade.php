@props(['thread'])

<div class="mt-3 p-3 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800/50" onclick="window.location.href='{{ route('threads.show', $thread) }}'">
    <div class="flex items-center space-x-2 text-sm">
        <a href="{{ route('profile.show', $thread->user->username) }}" class="font-semibold text-white hover:underline">{{ $thread->user->username }}</a>
        <span class="text-gray-500">{{ $thread->created_at->diffForHumans() }}</span>
    </div>
    <p class="mt-1 text-gray-400 truncate">{{ $thread->content }}</p>
    @if ($thread->image)
        <img src="{{ Storage::url($thread->image) }}" alt="Gambar Thread Induk" class="mt-2 h-24 w-24 rounded-lg object-cover">
    @endif
</div>
@props(['reply'])

<div class="flex space-x-3">
    {{-- Garis Vertikal dan Avatar --}}
    <div class="flex flex-col items-center">
        <a href="{{ route('profile.show', $reply->user->username) }}">
            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </a>
        @if ($reply->children->isNotEmpty())
            <div class="w-0.5 flex-grow bg-gray-700 mt-2"></div>
        @endif
    </div>

    {{-- Konten Balasan --}}
    <div class="flex-1">
        <div>
            <a href="{{ route('profile.show', $reply->user->username) }}" class="font-semibold text-gray-200 hover:underline">{{ $reply->user->username }}</a>
            <small class="ml-2 text-sm text-gray-400">{{ $reply->created_at->diffForHumans() }}</small>
        </div>
        <p class="mt-1 text-gray-100">{{ $reply->content }}</p>
        <div class="mt-2 flex items-center space-x-4 text-sm">
            <button
                type="button"
                onclick="openPostModal({{ $reply->id }})"
                class="font-semibold text-gray-400 hover:underline"
            >
                Balas
            </button>
        </div>

        {{-- Bagian Rekursif untuk menampilkan anak-anaknya --}}
        @if ($reply->children->isNotEmpty())
            <div class="mt-4 space-y-4">
                @foreach ($reply->children as $childReply)
                    <x-nested-reply :reply="$childReply" />
                @endforeach
            </div>
        @endif
    </div>
</div>
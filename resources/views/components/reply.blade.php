@props(['reply'])

<div class="flex space-x-4">
    {{-- Avatar Pengguna --}}
    <div class="flex-shrink-0">
        <a href="{{ route('profile.show', $reply->user->username) }}">
            <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </a>
    </div>

    <div class="flex-1">
        {{-- Info Pengguna dan Konten Balasan --}}
        <div>
            <a href="{{ route('profile.show', $reply->user->username) }}" class="font-semibold text-gray-200 hover:underline">{{ $reply->user->username }}</a>
            <small class="ml-2 text-sm text-gray-400">{{ $reply->created_at->diffForHumans() }}</small>
        </div>
        <p class="mt-1 text-gray-100">{{ $reply->content }}</p>

        {{-- Tombol Aksi untuk Balasan (Like, Balas) --}}
        <div class="mt-2 flex items-center space-x-4 text-sm">
            {{-- Tombol Like (bisa ditambahkan nanti) --}}
            <button
                type="button"
                {{-- Ini akan membuka modal, mengirim ID thread dan ID balasan ini sebagai parentId --}}
                onclick="openReplyModal({{ $reply->thread_id }}, {{ $reply->id }})"
                class="font-semibold text-gray-400 hover:underline"
            >
                Balas
            </button>
        </div>

        {{-- Bagian Rekursif: Tampilkan semua balasan dari balasan ini --}}
        <div class="mt-4 space-y-4 border-l-2 border-gray-700 pl-4">
            @foreach ($reply->children as $childReply)
                {{-- Panggil komponen ini lagi untuk setiap anak --}}
                <x-reply :reply="$childReply" />
            @endforeach
        </div>
    </div>
</div>
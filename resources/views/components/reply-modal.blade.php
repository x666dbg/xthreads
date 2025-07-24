<div
    {{-- 1. Kita hanya butuh dua variabel: untuk menampilkan modal dan menyimpan ID thread --}}
    x-data="{ show: false, threadId: null }"
    x-show="show"
    {{-- 2. Saat sinyal diterima, kita HANYA mengubah dua variabel itu dan fokus ke textarea --}}
    x-on:open-reply-modal.window="console.log('Modal Menerima Sinyal!', $event.detail); show = true; threadId = $event.detail.threadId; $nextTick(() => $refs.content.focus())"
    x-on:keydown.escape.window="show = false"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    {{-- Latar Belakang Gelap (tidak berubah) --}}
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/70"></div>

    {{-- Konten Modal --}}
    <div
        x-show="show"
        x-transition
        @click.outside="show = false"
        class="relative w-full max-w-xl overflow-hidden rounded-lg bg-gray-800 shadow-xl"
    >
        <div class="border-b border-gray-700 p-4">
            <h3 class="text-lg font-semibold">Balas Thread</h3>
        </div>

        {{-- 3. Biarkan 'action' form di-generate secara dinamis berdasarkan 'threadId' --}}
        <form :action="threadId ? `/threads/${threadId}/replies` : '#'" method="POST" class="p-4">
            @csrf
            <textarea
                name="content"
                x-ref="content"
                rows="4"
                class="w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600"
                placeholder="Tulis balasanmu..."
                required
            ></textarea>
            <div class="mt-4 flex justify-end space-x-2">
                {{-- Ganti tipe tombol Batal menjadi 'button' agar tidak men-submit form --}}
                <x-secondary-button type="button" @click.prevent="show = false">Batal</x-secondary-button>
                <x-primary-button type="submit">Balas</x-primary-button>
            </div>
        </form>
    </div>
</div>
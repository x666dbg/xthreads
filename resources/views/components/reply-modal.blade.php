<div
    x-data="{ show: false, threadId: null }"
    x-show="show"
    x-on:open-reply-modal.window="show = true; threadId = $event.detail.threadId; $nextTick(() => $refs.content.focus())"
    x-on:keydown.escape.window="show = false"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    {{-- Latar Belakang Gelap --}}
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

        <form :action="threadId ? `/threads/${threadId}/replies` : '#'" method="POST" class="p-4">
            @csrf
            {{-- Tambahkan input tersembunyi untuk parent_id --}}
            <input type="hidden" name="parent_id" :value="parentId">

            <textarea
                name="content"
                x-ref="content"
                {{-- ... (atribut textarea) ... --}}
            ></textarea>
            <div class="mt-4 flex justify-end space-x-2">
                <x-secondary-button type="button" @click.prevent="show = false">Batal</x-secondary-button>
                <x-primary-button type="submit">Balas</x-primary-button>
            </div>
        </form>
    </div>
</div>
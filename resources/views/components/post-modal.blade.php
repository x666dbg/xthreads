<div
    x-data="{ show: false, parentThreadId: null }"
    x-show="show"
    x-on:open-post-modal.window="show = true; parentThreadId = $event.detail.parentThreadId || null; $nextTick(() => $refs.content.focus())"
    x-on:keydown.escape.window="show = false"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/70"></div>
    <div
        x-show="show"
        x-transition
        @click.outside="show = false"
        class="relative w-full max-w-xl overflow-hidden rounded-lg bg-gray-800 shadow-xl"
    >
        <div class="border-b border-gray-700 p-4">
            <h3 class="text-lg font-semibold">Buat Postingan</h3>
        </div>

        <form action="{{ route('threads.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
            @csrf
            <input type="hidden" name="parent_id" :value="parentThreadId">

            <textarea
                name="content"
                x-ref="content"
                rows="4"
                class="w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600"
                placeholder="Apa yang kamu pikirkan?"
                required
            ></textarea>

            <div class="mt-4">
                <x-input-label for="modal_image" :value="__('Gambar (Opsional)')" />
                <x-text-input id="modal_image" name="image" type="file" class="mt-1 block w-full border-gray-700 bg-gray-900" />
            </div>

            <div class="mt-4 flex justify-end space-x-2">
                <x-secondary-button type="button" @click.prevent="show = false">Batal</x-secondary-button>
                <x-primary-button type="submit">Post</x-primary-button>
            </div>
        </form>
    </div>
</div>
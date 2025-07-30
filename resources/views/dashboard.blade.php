<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-white">Home</h2>
            <p class="text-dark-400 text-sm mt-1">Welcome back, {{ auth()->user()->username }}!</p>
        </div>
    </x-slot>

    <div class="w-full max-w-2xl mx-auto">
        {{-- Form untuk Posting Thread Baru --}}
        <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-6 mb-6 animate-slide-down">
            <form method="POST" action="{{ route('threads.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex items-start space-x-4">
                    {{-- User Avatar --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                        </div>
                    </div>
                    
                    {{-- Content Input --}}
                    <div class="flex-1">
                        <textarea
                            name="content"
                            rows="4"
                            class="w-full bg-transparent border-none text-white text-lg placeholder-dark-400 focus:outline-none resize-none"
                            placeholder="What's happening, {{ auth()->user()->username }}?"
                        ></textarea>
                        @error('content') 
                            <div class="mt-2 text-accent-400 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Image Upload Section --}}
                <div class="ml-16">
                    <div class="relative">
                        <input 
                            id="image" 
                            name="image" 
                            type="file" 
                            accept="image/*"
                            class="hidden" 
                            onchange="previewImage(this)"
                        />
                        <div id="image-preview" class="hidden mt-4 relative">
                            <img id="preview-img" src="" alt="Preview" class="max-w-full h-auto rounded-xl border border-dark-600/50">
                            <button type="button" onclick="removeImage()" class="absolute top-2 right-2 w-8 h-8 bg-dark-900/80 hover:bg-dark-800 rounded-full flex items-center justify-center transition-colors duration-200">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        @error('image') 
                            <div class="mt-2 text-accent-400 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="ml-16 flex items-center justify-between pt-4 border-t border-dark-700/50">
                    <div class="flex items-center space-x-4">
                        {{-- Image Upload Button --}}
                        <button type="button" onclick="document.getElementById('image').click()" class="p-2 hover:bg-primary-600/20 rounded-full transition-all duration-200 group">
                            <svg class="w-5 h-5 text-primary-400 group-hover:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        
                        {{-- Emoji Button --}}
                        <button type="button" class="p-2 hover:bg-secondary-600/20 rounded-full transition-all duration-200 group">
                            <svg class="w-5 h-5 text-secondary-400 group-hover:text-secondary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        
                        {{-- Character Count --}}
                        <div class="text-dark-400 text-sm">
                            <span id="char-count">0</span>/280
                        </div>
                    </div>
                    
                    {{-- Post Button --}}
                    <button type="submit" class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-bold py-2 px-6 rounded-full transition-all duration-300 hover:scale-105 hover:shadow-colored disabled:opacity-50 disabled:cursor-not-allowed">
                        Post
                    </button>
                </div>
            </form>
        </div>

        {{-- Timeline Threads --}}
        <div class="space-y-6">
            @foreach ($timeline as $item)
                <article class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-6 hover:bg-dark-700/30 transition-all duration-300 animate-slide-up">
                    {{-- Repost Indicator --}}
                    @if ($item->is_repost)
                        <div class="mb-4 flex items-center text-dark-400 text-sm">
                            <svg class="w-4 h-4 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.01M4 20v-5h.01M20 4v5h-.01M20 20v-5h-.01M8 12h8m-8 0V8.01M16 12v3.99"></path>
                            </svg>
                            <span>
                                <a href="{{ route('profile.show', $item->reposted_by->username) }}" class="font-semibold text-primary-400 hover:text-primary-300 transition-colors duration-200">{{ $item->reposted_by->username }}</a>
                                reposted
                            </span>
                        </div>
                    @endif

                    <div class="flex space-x-4">
                        {{-- User Avatar --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('profile.show', $item->original_thread->user->username) }}" class="block group">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium group-hover:scale-105 transition-transform duration-200">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($item->original_thread->user->username, 0, 1)) }}</span>
                                </div>
                            </a>
                        </div>

                        {{-- Thread Content --}}
                            <div class="flex-1 min-w-0">
                            {{-- User Info & Timestamp --}}
                            <div class="flex items-center space-x-2 mb-3">
                                @php
                                    $threadUser = $item->original_thread->user;
                                    $username = $threadUser->username;
                                @endphp
                                <a href="{{ route('profile.show', $username) }}" class="font-bold text-white hover:text-primary-400 transition-colors duration-200">
                                    {{ $username }}
                                </a>
                                <span class="text-dark-400">@ {{ $username }}</span>
                                <span class="text-dark-500">•</span>
                                <time class="text-dark-400 text-sm hover:text-dark-300 transition-colors duration-200" title="{{ $item->created_at->format('M j, Y \a\t g:i A') }}">
                                    {{ $item->created_at->diffForHumans() }}
                                </time>
                            </div>


                            {{-- Thread Content --}}
                            <a href="{{ route('threads.show', $item->original_thread) }}" class="block group">
                                <p class="text-white text-lg leading-relaxed mb-4 group-hover:text-gray-100 transition-colors duration-200">
                                    {{ $item->original_thread->content }}
                                </p>

                                {{-- Thread Image --}}
                                @if ($item->original_thread->image)
                                    <div class="mb-4 rounded-2xl overflow-hidden border border-dark-600/50 group-hover:border-dark-500/50 transition-colors duration-200">
                                        <img 
                                            src="{{ Storage::url($item->original_thread->image) }}" 
                                            alt="Thread image" 
                                            class="w-full h-auto object-cover group-hover:scale-[1.02] transition-transform duration-500"
                                            loading="lazy"
                                        >
                                    </div>
                                @endif
                            </a>

                            {{-- Thread Actions --}}
                            <div class="mt-4">
                                <x-thread-actions :thread="$item->original_thread" />
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach

            {{-- Empty State --}}
            @if($timeline->isEmpty())
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Welcome to X-Threads!</h3>
                    <p class="text-dark-400 mb-6">This is where you'll see threads from people you follow.</p>
                    <button class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 hover:scale-105">
                        Find people to follow
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- JavaScript for Image Preview and Character Count --}}
    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const input = document.getElementById('image');
            const preview = document.getElementById('image-preview');
            input.value = '';
            preview.classList.add('hidden');
        }

        // Character count
        document.querySelector('textarea[name="content"]').addEventListener('input', function() {
            const charCount = document.getElementById('char-count');
            const length = this.value.length;
            charCount.textContent = length;

            if (length > 280) {
                charCount.classList.add('text-accent-400');
            } else {
                charCount.classList.remove('text-accent-400');
            }
        });
    </script>
</x-app-layout>

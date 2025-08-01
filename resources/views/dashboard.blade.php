<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-white">Home</h2>
            <p class="text-dark-400 text-sm mt-1">Welcome back, {{ auth()->user()->username }}!</p>
        </div>
    </x-slot>

    <div class="w-full max-w-2xl mx-auto">
        {{-- Form untuk Posting Thread Baru --}}
        <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-4 sm:p-6 mb-4 sm:mb-6 animate-slide-down relative">
            <form method="POST" action="{{ route('threads.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex items-start space-x-3 sm:space-x-4">
                    {{-- User Avatar --}}
                    <div class="flex-shrink-0">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                 alt="{{ auth()->user()->username }}" 
                                 class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover shadow-medium">
                        @else
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium">
                                <span class="text-white font-bold text-sm sm:text-lg">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Content Input --}}
                    <div class="flex-1">
                        <div class="relative">
                            <textarea
                                id="thread-content"
                                name="content"
                                rows="4"
                                class="w-full bg-transparent border-none text-white text-lg placeholder-dark-400 focus:outline-none resize-none"
                                placeholder="What's happening, {{ auth()->user()->username }}?"
                            ></textarea>
                            <div id="mention-dropdown" class="absolute z-50 w-full bg-dark-800 border border-dark-600 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto mt-1">
                                <!-- Mention suggestions will be populated here -->
                            </div>
                        </div>
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
                <div class="ml-10 sm:ml-16 flex items-center justify-between pt-4 border-t border-dark-700/50">
                    <div class="flex items-center space-x-2 sm:space-x-4">
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
                    <button type="submit" class="bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-bold py-2 px-4 sm:px-6 rounded-full transition-all duration-300 hover:scale-105 hover:shadow-colored disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                        Post
                    </button>
                </div>
            </form>
        </div>

        {{-- Timeline Threads --}}
        <div class="space-y-4 sm:space-y-6">
            @foreach ($timeline as $item)
                @php
                    $thread = $item->original_thread;
                    $user = $thread->user;
                @endphp
                <article class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-4 sm:p-6 hover:bg-dark-700/30 transition-all duration-300 animate-slide-up">
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

                    <div class="flex space-x-3 sm:space-x-4" data-thread-id="{{ $item->original_thread->id }}">
                        {{-- User Avatar --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('profile.show', $item->original_thread->user->username) }}" class="block group">
                                @if($item->original_thread->user->photo)
                                    <img src="{{ asset('storage/' . $item->original_thread->user->photo) }}" 
                                         alt="{{ $item->original_thread->user->username }}" 
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover shadow-medium group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium group-hover:scale-105 transition-transform duration-200">
                                        <span class="text-white font-bold text-sm sm:text-lg">{{ strtoupper(substr($item->original_thread->user->username, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </a>
                        </div>

                        {{-- Thread Content --}}
                        <div class="flex-1 min-w-0">
                            {{-- User Info & Timestamp --}}
                            <div class="flex items-center space-x-1 sm:space-x-2 mb-3 flex-wrap">
                                <a href="{{ route('profile.show', $user->username) }}" class="font-bold text-white hover:text-primary-400 transition-colors duration-200 flex items-center">
                                   <span>{{ $user->username }}</span>
                                </a>
                                @if ($user->isModerator())
                                    <x-moderator-badge />
                                @endif
                                <span class="text-dark-400 text-sm">@ {{ $user->username }}</span>
                                <span class="text-dark-500 hidden sm:inline">•</span>
                                <a href="{{ route('threads.show', $thread) }}">
                                    <time class="text-dark-400 text-sm hover:text-dark-300 transition-colors duration-200" title="{{ $item->created_at->format('M j, Y \a\t g:i A') }}">
                                        {{ $item->created_at->diffForHumans() }}
                                    </time>
                                </a>
                            </div>

                            {{-- Thread Content --}}
                            <div class="group cursor-pointer thread-clickable" data-thread-url="{{ route('threads.show', $item->original_thread) }}">
                                <p class="text-white text-base sm:text-lg leading-relaxed mb-3 sm:mb-4 group-hover:text-gray-100 transition-colors duration-200">
                                    {!! app('App\Services\MentionService')->formatMentions($item->original_thread->content) !!}
                                </p>

                                {{-- Thread Image --}}
                                @if ($item->original_thread->image)
                                    <div class="mb-3 sm:mb-4 rounded-2xl overflow-hidden border border-dark-600/50 group-hover:border-dark-500/50 transition-colors duration-200">
                                        <img 
                                            src="{{ Storage::url($item->original_thread->image) }}" 
                                            alt="Thread image" 
                                            class="w-full h-auto object-cover group-hover:scale-[1.02] transition-transform duration-500"
                                            loading="lazy"
                                        >
                                    </div>
                                @endif
                            </div>

                            {{-- Thread Actions --}}
                            <div class="mt-3 sm:mt-4">
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

        // Character count and mention autocomplete
        const textarea = document.getElementById('thread-content');
        const dropdown = document.getElementById('mention-dropdown');
        let currentMention = null;
        let mentionStart = -1;

        textarea.addEventListener('input', function() {
            const charCount = document.getElementById('char-count');
            const length = this.value.length;
            charCount.textContent = length;

            if (length > 280) {
                charCount.classList.add('text-accent-400');
            } else {
                charCount.classList.remove('text-accent-400');
            }

            // Handle mention autocomplete
            const cursorPosition = this.selectionStart;
            const textBeforeCursor = this.value.substring(0, cursorPosition);
            const mentionMatch = textBeforeCursor.match(/@(\w*)$/);

            if (mentionMatch) {
                const query = mentionMatch[1];
                mentionStart = cursorPosition - mentionMatch[0].length;
                currentMention = mentionMatch[0];

                if (query.length >= 1) {
                    searchUsers(query);
                } else {
                    hideDropdown();
                }
            } else {
                hideDropdown();
                currentMention = null;
                mentionStart = -1;
            }
        });

        textarea.addEventListener('keydown', function(e) {
            if (dropdown.classList.contains('hidden')) return;

            const items = dropdown.querySelectorAll('.mention-item');
            let selected = dropdown.querySelector('.mention-item.selected');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (selected) {
                    selected.classList.remove('selected', 'bg-dark-700');
                    const next = selected.nextElementSibling || items[0];
                    next.classList.add('selected', 'bg-dark-700');
                } else if (items.length > 0) {
                    items[0].classList.add('selected', 'bg-dark-700');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (selected) {
                    selected.classList.remove('selected', 'bg-dark-700');
                    const prev = selected.previousElementSibling || items[items.length - 1];
                    prev.classList.add('selected', 'bg-dark-700');
                } else if (items.length > 0) {
                    items[items.length - 1].classList.add('selected', 'bg-dark-700');
                }
            } else if (e.key === 'Enter' || e.key === 'Tab') {
                e.preventDefault();
                if (selected) {
                    selectUser(selected.dataset.username);
                }
            } else if (e.key === 'Escape') {
                hideDropdown();
            }
        });

        function searchUsers(query) {
            fetch(`/api/users/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.users.length > 0) {
                    showDropdown(data.data.users);
                } else {
                    hideDropdown();
                }
            })
            .catch(error => {
                console.error('Error searching users:', error);
                hideDropdown();
            });
        }

        function showDropdown(users) {
            dropdown.innerHTML = '';
            users.forEach((user, index) => {
                const div = document.createElement('div');
                div.className = 'mention-item flex items-center p-3 hover:bg-dark-700 cursor-pointer transition-colors';
                if (index === 0) div.classList.add('selected', 'bg-dark-700');
                div.dataset.username = user.username;
                
                div.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            ${user.photo 
                                ? `<img src="${user.photo}" alt="${user.username}" class="w-8 h-8 rounded-full object-cover">`
                                : `<div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                     <span class="text-white font-bold text-sm">${user.username.charAt(0).toUpperCase()}</span>
                                   </div>`
                            }
                        </div>
                        <div>
                            <p class="text-white font-medium">@${user.username}</p>
                        </div>
                    </div>
                `;
                
                div.addEventListener('click', () => selectUser(user.username));
                dropdown.appendChild(div);
            });
            
            dropdown.classList.remove('hidden');
        }

        function hideDropdown() {
            dropdown.classList.add('hidden');
        }

        function selectUser(username) {
            if (mentionStart !== -1 && currentMention) {
                const before = textarea.value.substring(0, mentionStart);
                const after = textarea.value.substring(mentionStart + currentMention.length);
                textarea.value = before + '@' + username + ' ' + after;
                
                const newPosition = mentionStart + username.length + 2;
                textarea.setSelectionRange(newPosition, newPosition);
                textarea.focus();
            }
            hideDropdown();
            currentMention = null;
            mentionStart = -1;
        }

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!textarea.contains(e.target) && !dropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        // Handle thread click with proper event delegation
        document.addEventListener('click', function(e) {
            const threadClickable = e.target.closest('.thread-clickable');
            if (threadClickable) {
                // Don't navigate if clicking on a link (mention link)
                if (e.target.tagName === 'A' || e.target.closest('a')) {
                    return;
                }
                // Don't navigate if clicking on thread actions
                if (e.target.closest('.thread-actions') || e.target.closest('button')) {
                    return;
                }
                
                const url = threadClickable.dataset.threadUrl;
                if (url) {
                    window.location.href = url;
                }
            }
        });
    </script>
</x-app-layout>

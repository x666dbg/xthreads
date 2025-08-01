<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 sm:space-x-3 ml-10">
            <div class="flex items-center space-x-3">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" 
                         alt="{{ $user->username }}" 
                         class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover shadow-medium">
                @else
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium">
                        <span class="text-white font-bold text-xs sm:text-sm">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">{{ $user->username }}</h2>
                    <p class="text-dark-400 text-xs sm:text-sm">{{ $user->threads->count() }} threads</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        {{-- Profile Header --}}
        <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl overflow-hidden mb-4 sm:mb-6 animate-slide-down">
            {{-- Cover Image --}}
            <div class="h-32 sm:h-48 bg-gradient-to-br from-primary-600 via-secondary-600 to-accent-600 relative">
                @if($user->cover_photo)
                    <img src="{{ asset('storage/' . $user->cover_photo) }}" 
                         alt="{{ $user->username }} cover" 
                         class="w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-dark-900/50 to-transparent"></div>
            </div>

            {{-- Profile Info --}}
            <div class="px-4 sm:px-6 pb-4 sm:pb-6">
                {{-- Avatar & Profile Actions --}}
                <div class="relative -mt-10 sm:-mt-16 mb-6">
                    {{-- Avatar --}}
                    <div class="relative inline-block">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 alt="{{ $user->username }}" 
                                 class="w-20 h-20 sm:w-32 sm:h-32 rounded-full object-cover shadow-large border-4 border-dark-800">
                        @else
                            <div class="w-20 h-20 sm:w-32 sm:h-32 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-large border-4 border-dark-800">
                                <span class="text-white font-bold text-2xl sm:text-4xl">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-1 -right-1 sm:-bottom-2 sm:-right-2 w-6 h-6 sm:w-8 sm:h-8 bg-accent-500 rounded-full border-2 sm:border-4 border-dark-800 flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Profile Actions - Positioned at top right --}}
                    <div class="absolute top-0 right-0">
                        @if (auth()->user()->isNot($user))
                            <div class="flex flex-row space-x-2 items-center mt-[80px]">
                                @can('ban', $user)
                                    @if(!$user->is_banned)
                                        <form action="{{ route('users.ban', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-red-600 hover:bg-red-700 text-white rounded-full font-medium transition-all duration-300 text-xs sm:text-sm">
                                                Ban User
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                @can('unban', $user)
                                    @if($user->is_banned)
                                        <form action="{{ route('users.unban', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-green-600 hover:bg-green-700 text-white rounded-full font-medium transition-all duration-300 text-xs sm:text-sm">
                                                Unban User
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                                
                                @if (auth()->user()->following->contains($user))
                                    <form action="{{ route('profile.unfollow', $user->username) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-dark-700 hover:bg-red-600 text-white border border-dark-600 hover:border-red-500 rounded-full font-medium transition-all duration-300 group text-xs sm:text-sm">
                                            <span class="group-hover:hidden">Following</span>
                                            <span class="hidden group-hover:inline">Unfollow</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('profile.follow', $user->username) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white rounded-full font-medium transition-all duration-300 hover:shadow-colored text-xs sm:text-sm">
                                            Follow
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('profile.edit-full') }}" class="mt-[80px] px-3 sm:px-4 py-1.5 sm:py-2 bg-dark-700 hover:bg-dark-600 text-white border border-dark-600 rounded-full font-medium transition-all duration-300 inline-block text-xs sm:text-sm">
                                Edit Profile
                            </a>
                        @endif
                    </div>
                </div>

                {{-- User Details --}}
                <div class="space-y-3">
                    <div>
                        <div class="flex items-center">
                            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->username }}</h1>
                            @if ($user->isModerator())
                                <x-moderator-badge />
                            @endif
                        </div>
                        <p class="text-dark-400">@ {{ $user->username }}</p>
                    </div>

                    @if($user->bio)
                        <p class="text-white text-base sm:text-lg">
                            {!! app('App\Services\MentionService')->formatMentions($user->bio) !!}
                        </p>
                    @endif

                    <div class="flex items-center space-x-3 sm:space-x-4 text-dark-400 text-sm">
                        @if($user->location)
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $user->location }}</span>
                            </div>
                        @endif
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v8a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                            </svg>
                            <span>Joined <?php echo e($user->created_at->format('M Y')); ?></span>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center space-x-6 pt-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white"><?php echo e($user->following->count()); ?></p>
                            <p class="text-dark-400 text-sm">Following</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white"><?php echo e($user->followers->count()); ?></p>
                            <p class="text-dark-400 text-sm">Followers</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white"><?php echo e($user->threads->count()); ?></p>
                            <p class="text-dark-400 text-sm">Threads</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white">{{ $user->threads->sum(function($thread) { return $thread->likes->count(); }) }}</p>
                            <p class="text-dark-400 text-sm">Likes</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl mb-6">
            <div class="flex">
                <button class="flex-1 py-4 px-6 text-white font-semibold border-b-2 border-primary-500 bg-primary-600/10">
                    Threads
                </button>
                <button class="flex-1 py-4 px-6 text-dark-400 font-semibold hover:text-white hover:bg-dark-700/30 transition-all duration-200">
                    Replies
                </button>
                <button class="flex-1 py-4 px-6 text-dark-400 font-semibold hover:text-white hover:bg-dark-700/30 transition-all duration-200">
                    Media
                </button>
                <button class="flex-1 py-4 px-6 text-dark-400 font-semibold hover:text-white hover:bg-dark-700/30 transition-all duration-200">
                    Likes
                </button>
            </div>
        </div>
        {{-- User Timeline --}}
        <div class="space-y-6">
            @forelse ($timeline as $item)
                @php
                    $thread = $item->original_thread;
                @endphp
                <article class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-2xl p-6 hover:bg-dark-700/30 transition-all duration-300 animate-slide-up">
                    {{-- Repost Indicator --}}
                    @if ($item->is_repost)
                        <div class="mb-4 flex items-center text-dark-400 text-sm">
                            <svg class="w-4 h-4 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.01M4 20v-5h.01M20 4v5h-.01M20 20v-5h-.01M8 12h8m-8 0V8.01M16 12v3.99"></path>
                            </svg>
                            <span>
                                @if($item->reposted_by->username === auth()->user()->username)
                                    <span class="font-semibold text-primary-400">You</span> reposted
                                @else
                                    <a href="{{ route('profile.show', $item->reposted_by->username) }}" class="font-semibold text-primary-400 hover:text-primary-300 transition-colors duration-200"><?php echo e($item->reposted_by->username); ?></a> reposted
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="flex space-x-4">
                        {{-- User Avatar --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('profile.show', $thread->user->username) }}" class="block group">
                                @if($thread->user->photo)
                                    <img src="{{ asset('storage/' . $thread->user->photo) }}" 
                                         alt="{{ $thread->user->username }}" 
                                         class="w-12 h-12 rounded-full object-cover shadow-medium group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-medium group-hover:scale-105 transition-transform duration-200">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($thread->user->username, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </a>
                        </div>

                        {{-- Thread Content --}}
                        <div class="flex-1 min-w-0">
                            {{-- User Info & Timestamp --}}
                            <div class="flex items-center space-x-2 mb-3">
                                @php $threadUser = $thread->user; @endphp
                                <a href="{{ route('profile.show', $threadUser->username) }}" class="font-bold text-white hover:text-primary-400 transition-colors duration-200">
                                    <?php echo e($threadUser->username); ?>
                                </a>
                                <span class="text-dark-400">@<?php echo e($threadUser->username); ?></span>
                                <span class="text-dark-500">•</span>
                                <time class="text-dark-400 text-sm hover:text-dark-300 transition-colors duration-200" title="<?php echo e($item->created_at->format('M j, Y \a\t g:i A')); ?>">
                                    <?php echo e($item->created_at->diffForHumans()); ?>
                                </time>
                            </div>

                            {{-- Thread Content --}}
                            <div class="group">
                                <p class="text-white text-lg leading-relaxed mb-4 group-hover:text-gray-100 transition-colors duration-200">
                                    {!! app('App\Services\MentionService')->formatMentions($thread->content) !!}
                                </p>

                                {{-- Thread Image --}}
                                @if ($thread->image)
                                    <a href="{{ route('threads.show', $thread) }}" class="block">
                                        <div class="mb-4 rounded-2xl overflow-hidden border border-dark-600/50 group-hover:border-dark-500/50 transition-colors duration-200">
                                            <img
                                                src="{{ Storage::url($thread->image) }}"
                                                alt="Thread image"
                                                class="w-full h-auto object-cover group-hover:scale-[1.02] transition-transform duration-500"
                                                loading="lazy"
                                            >
                                        </div>
                                    </a>
                                @endif
                            </div>

                            {{-- Thread Actions --}}
                            <div class="mt-4">
                                <x-thread-actions :thread="$thread" />
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">No threads yet</h3>
                    <p class="text-dark-400 mb-6">
                        @if(auth()->user()->is($user))
                            When you post threads, they'll show up here.
                        @else
                            @<?php echo e($user->username); ?> hasn't posted any threads yet.
                        @endif
                    </p>
                    @if(auth()->user()->is($user))
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-bold rounded-full transition-all duration-300 hover:scale-105 hover:shadow-colored">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create your first thread
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
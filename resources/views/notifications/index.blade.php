<x-app-layout>
    <div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Notifications</h1>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-primary-400 hover:text-primary-300 text-xs sm:text-sm font-medium transition-colors">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-1">
            @forelse($notifications as $notification)
                <div class="bg-dark-800/30 backdrop-blur-sm border border-dark-700/50 rounded-xl p-3 sm:p-4 hover:bg-dark-700/30 transition-all duration-200 
                    {{ is_null($notification->read_at) ? 'border-l-4 border-l-primary-500 bg-dark-700/20' : '' }}">
                    
                    <div class="flex items-start space-x-3 sm:space-x-4">
                        <!-- Notification Icon -->
                        <div class="flex-shrink-0 mt-1">
                            @switch($notification->data['type'] ?? '')
                                @case('thread_liked')
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </div>
                                    @break
                                @case('thread_reply')
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    @break
                                @case('new_follower')
                                    <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    @break
                                @case('thread_repost')
                                    <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </div>
                                    @break
                                @default
                                    <div class="w-10 h-10 bg-gray-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                        </svg>
                                    </div>
                            @endswitch
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-white font-medium">
                                        {{ $notification->data['message'] ?? 'New notification' }}
                                    </p>
                                    
                                    <!-- Additional details based on notification type -->
                                    @if(isset($notification->data['thread_id']))
                                        <a href="{{ route('threads.show', $notification->data['thread_id']) }}" 
                                           class="text-primary-400 hover:text-primary-300 text-sm mt-1 inline-block">
                                            View thread →
                                        </a>
                                    @endif
                                    
                                    <p class="text-dark-400 text-sm mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Mark as read button -->
                                @if(is_null($notification->read_at))
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ml-4">
                                        @csrf
                                        <button type="submit" 
                                                class="text-primary-400 hover:text-primary-300 text-xs font-medium px-3 py-1 border border-primary-500/30 rounded-full hover:bg-primary-500/10 transition-all">
                                            Mark as read
                                        </button>
                                    </form>
                                @else
                                    <span class="text-dark-500 text-xs ml-4">Read</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty state -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-dark-700/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.97 4.97a.75.75 0 0 0-1.08 1.05l-3.99 4.99a.75.75 0 0 0 1.08 1.05l3.99-4.99a.75.75 0 0 0 0-1.1zM9.02 8.69a.75.75 0 0 0-1.06 1.06l2.03 2.03a.75.75 0 0 0 1.06-1.06L9.02 8.69z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No notifications yet</h3>
                    <p class="text-dark-400">When someone likes, replies to, or reposts your threads, you'll see it here.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
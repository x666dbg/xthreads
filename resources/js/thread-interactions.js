// Thread interactions - AJAX like/repost functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const authToken = window.authToken; // From layout

    // Like/Unlike functionality
    window.toggleLike = async function(threadId, element) {
        try {
            const response = await fetch(`/api/threads/${threadId}/toggle-like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            const data = await response.json();

            if (data.success) {
                // Update UI
                updateLikeButton(element, data.data.is_liked, data.data.likes_count);
                
                // Show subtle feedback
                showFeedback(element, data.data.action === 'liked' ? 'Liked!' : 'Unliked!');
            } else {
                console.error('Failed to toggle like:', data.message);
                showError('Failed to update like');
            }
        } catch (error) {
            console.error('Error toggling like:', error);
            showError('Network error');
        }
    };

    // Repost/Unrepost functionality
    window.toggleRepost = async function(threadId, element) {
        try {
            const response = await fetch(`/api/threads/${threadId}/toggle-repost`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            const data = await response.json();

            if (data.success) {
                // Update UI
                updateRepostButton(element, data.data.is_reposted, data.data.reposts_count);
                
                // Show subtle feedback
                showFeedback(element, data.data.action === 'reposted' ? 'Reposted!' : 'Unreposted!');
            } else {
                console.error('Failed to toggle repost:', data.message);
                showError(data.message || 'Failed to update repost');
            }
        } catch (error) {
            console.error('Error toggling repost:', error);
            showError('Network error');
        }
    };

    // Update like button UI
    function updateLikeButton(element, isLiked, likesCount) {
        const button = element.closest('.like-button');
        const icon = button.querySelector('svg');
        const countSpan = button.querySelector('.like-count');
        
        if (isLiked) {
            // Liked state
            icon.classList.remove('text-gray-500');
            icon.classList.add('text-red-500', 'fill-current');
            button.classList.add('text-red-500');
            button.classList.remove('text-gray-500', 'hover:text-red-500');
        } else {
            // Unliked state
            icon.classList.remove('text-red-500', 'fill-current');
            icon.classList.add('text-gray-500');
            button.classList.remove('text-red-500');
            button.classList.add('text-gray-500', 'hover:text-red-500');
        }
        
        // Update count
        if (countSpan) {
            countSpan.textContent = likesCount;
        }
        
        // Add animation
        icon.classList.add('animate-pulse');
        setTimeout(() => {
            icon.classList.remove('animate-pulse');
        }, 300);
    }

    // Update repost button UI
    function updateRepostButton(element, isReposted, repostsCount) {
        const button = element.closest('.repost-button');
        const icon = button.querySelector('svg');
        const countSpan = button.querySelector('.repost-count');
        
        if (isReposted) {
            // Reposted state
            icon.classList.remove('text-gray-500');
            icon.classList.add('text-green-500');
            button.classList.add('text-green-500');
            button.classList.remove('text-gray-500', 'hover:text-green-500');
        } else {
            // Not reposted state
            icon.classList.remove('text-green-500');
            icon.classList.add('text-gray-500');
            button.classList.remove('text-green-500');
            button.classList.add('text-gray-500', 'hover:text-green-500');
        }
        
        // Update count
        if (countSpan) {
            countSpan.textContent = repostsCount;
        }
        
        // Add animation
        icon.classList.add('animate-pulse');
        setTimeout(() => {
            icon.classList.remove('animate-pulse');
        }, 300);
    }

    // Show subtle feedback
    function showFeedback(element, message) {
        const feedback = document.createElement('div');
        feedback.className = 'fixed top-4 right-4 bg-dark-800 border border-primary-500 text-primary-400 px-3 py-2 rounded-lg text-sm z-50 transition-all duration-300';
        feedback.textContent = message;
        
        document.body.appendChild(feedback);
        
        // Auto remove
        setTimeout(() => {
            feedback.style.opacity = '0';
            feedback.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (document.body.contains(feedback)) {
                    document.body.removeChild(feedback);
                }
            }, 300);
        }, 1500);
    }

    // Show error message
    function showError(message) {
        const error = document.createElement('div');
        error.className = 'fixed top-4 right-4 bg-red-600 text-white px-3 py-2 rounded-lg text-sm z-50 transition-all duration-300';
        error.textContent = message;
        
        document.body.appendChild(error);
        
        // Auto remove
        setTimeout(() => {
            error.style.opacity = '0';
            error.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (document.body.contains(error)) {
                    document.body.removeChild(error);
                }
            }, 300);
        }, 3000);
    }

    // Reply/Comment functionality
    window.submitReply = async function(threadId, content, modalElement) {
        try {
            const response = await fetch('/api/threads', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`,
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    content: content,
                    parent_thread_id: threadId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update reply count in thread actions
                updateReplyCount(threadId, data.data.thread.parent_thread_id);
                
                // Show success feedback
                showFeedback(document.body, 'Reply posted!');
                
                // Clear form and close modal
                const form = document.querySelector('form[action*="threads"]');
                if (form) {
                    form.reset();
                }
                
                // Close modal using Alpine.js data
                const modalDiv = document.querySelector('[x-data*="show"]');
                if (modalDiv && modalDiv._x_dataStack) {
                    modalDiv._x_dataStack[0].show = false;
                }
            } else {
                console.error('Failed to post reply:', data.message);
                showError(data.message || 'Failed to post reply');
            }
        } catch (error) {
            console.error('Error posting reply:', error);
            showError('Network error');
        }
    };

    // Update reply count
    function updateReplyCount(threadId, parentThreadId) {
        // Find all thread action elements for this thread
        const threadElements = document.querySelectorAll(`[data-thread-id="${threadId}"]`);
        threadElements.forEach(element => {
            const replyButton = element.querySelector('button[onclick*="openReplyModal"]');
            if (replyButton) {
                const countSpan = replyButton.querySelector('.text-xs');
                if (countSpan) {
                    const currentCount = parseInt(countSpan.textContent) || 0;
                    countSpan.textContent = currentCount + 1;
                }
            }
        });
    }

    // Initialize existing buttons
    initializeExistingButtons();

    function initializeExistingButtons() {
        // Convert existing like buttons to AJAX
        document.querySelectorAll('.like-button').forEach(button => {
            // Remove existing form submission
            const form = button.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const threadId = this.action.split('/').slice(-2, -1)[0]; // Extract thread ID from URL
                    toggleLike(threadId, button);
                });
            }
        });

        // Convert existing repost buttons to AJAX
        document.querySelectorAll('.repost-button').forEach(button => {
            // Remove existing form submission
            const form = button.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const threadId = this.action.split('/').slice(-2, -1)[0]; // Extract thread ID from URL
                    toggleRepost(threadId, button);
                });
            }
        });

        // Convert reply modal form to AJAX
        const replyModal = document.querySelector('[x-data*="show"]');
        if (replyModal) {
            const form = replyModal.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const content = formData.get('content');
                    const threadId = formData.get('parent_id');
                    
                    if (content && threadId) {
                        submitReply(threadId, content, replyModal);
                    }
                });
            }
        }
    }
});
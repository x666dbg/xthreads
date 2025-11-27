// Real-time notifications handler
document.addEventListener('DOMContentLoaded', function() {
    if (window.Echo && window.currentUser) {
        // Listen for notifications on the user's private channel
        window.Echo.private(`App.Models.User.${window.currentUser.id}`)
            .notification((notification) => {
                console.log('New notification:', notification);
                
                // Update notification count
                updateNotificationCount();
                
                // Show toast notification
                showToastNotification(notification);
                
                // Add to notifications list if visible
                addToNotificationsList(notification);
            });
    }
});

function updateNotificationCount() {
    fetch('/api/notifications/unread-count', {
        headers: {
            'Authorization': `Bearer ${window.authToken}`,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = data.count;
            badge.style.display = data.count > 0 ? 'inline' : 'none';
        }
    })
    .catch(error => console.error('Error updating notification count:', error));
}

function showToastNotification(notification) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded shadow-lg z-50 transition-opacity duration-300';
    toast.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">🔔</span>
            <span>${notification.message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 5000);
}

function addToNotificationsList(notification) {
    const notificationsList = document.querySelector('#notifications-list');
    if (notificationsList) {
        const item = document.createElement('div');
        item.className = 'notification-item p-3 border-b hover:bg-gray-50';
        item.innerHTML = `
            <div class="flex items-start">
                <span class="text-blue-500 mr-2">🔔</span>
                <div class="flex-1">
                    <p class="text-sm text-gray-800">${notification.message}</p>
                    <p class="text-xs text-gray-500">Just now</p>
                </div>
            </div>
        `;
        
        notificationsList.prepend(item);
    }
}

// Function to mark notification as read
function markNotificationAsRead(notificationId) {
    fetch(`/api/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${window.authToken}`,
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(() => {
        updateNotificationCount();
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

// Function to mark all notifications as read
function markAllNotificationsAsRead() {
    fetch('/api/notifications/read-all', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${window.authToken}`,
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(() => {
        updateNotificationCount();
        const badges = document.querySelectorAll('.notification-badge');
        badges.forEach(badge => badge.style.display = 'none');
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
}
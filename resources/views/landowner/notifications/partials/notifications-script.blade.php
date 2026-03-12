<script>
// Global variables
let currentFilter = 'all';
let currentTypeFilter = 'all';
let loading = false;
let hasMore = true;
let page = 1;

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initEventListeners();
    
    // Check for initial filter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const filterParam = urlParams.get('filter');
    if (filterParam) {
        filterNotifications(filterParam);
    }
});

// Initialize event listeners
function initEventListeners() {
    // Mark all as read button
    const markAllBtn = document.getElementById('markAllBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', markAllAsRead);
    }
    
    // Load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreNotifications);
    }
    
    // Delete notification buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-notification')) {
            const button = e.target.closest('.btn-delete-notification');
            const notificationItem = button.closest('.notification-item');
            const notificationId = notificationItem?.dataset.id;
            const notificationSource = notificationItem?.dataset.source || 'laravel';
            
            if (notificationId) {
                e.preventDefault();
                e.stopPropagation();
                deleteNotification(notificationId, notificationSource);
            }
        }
    });
}

// Handle notification click
async function handleNotificationClick(notificationId, source, actionUrl = null) {
    try {
        // Mark as read
        const response = await fetch(`/landowner/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Update UI
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                item.classList.remove('unread');
                item.classList.add('read');
                const indicator = item.querySelector('.unread-indicator');
                if (indicator) indicator.remove();
                
                // Update unread count
                updateUnreadCount();
            }
            
            // Redirect if action URL exists
            if (actionUrl) {
                window.location.href = actionUrl;
            }
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Mark all as read
async function markAllAsRead() {
    const button = document.getElementById('markAllBtn');
    const originalText = button?.innerHTML;
    
    try {
        if (button) {
            button.innerHTML = '<span class="loading"></span> Memproses...';
            button.disabled = true;
        }
        
        const response = await fetch('/landowner/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Update all notifications UI
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                item.classList.add('read');
                const indicator = item.querySelector('.unread-indicator');
                if (indicator) indicator.remove();
            });
            
            // Update stats
            updateUnreadCount();
            
            showToast('Semua notifikasi telah ditandai sebagai dibaca', 'success');
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
        showToast('Terjadi kesalahan', 'error');
    } finally {
        if (button) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }
}

// Update unread count
async function updateUnreadCount() {
    try {
        const response = await fetch('/landowner/notifications/unread-count');
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // Update stat
                const statElement = document.querySelector('.stat-number');
                if (statElement) {
                    statElement.textContent = data.count;
                }
                
                // Update badge
                const badge = document.querySelector('.badge-count');
                if (data.count > 0) {
                    if (!badge) {
                        const tabBtn = document.querySelector('#tabUnread');
                        if (tabBtn) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge-count';
                            newBadge.textContent = data.count;
                            tabBtn.appendChild(newBadge);
                        }
                    } else {
                        badge.textContent = data.count;
                    }
                } else if (badge) {
                    badge.remove();
                }
                
                // Show/hide mark all button
                const markAllBtn = document.getElementById('markAllBtn');
                if (markAllBtn) {
                    if (data.count > 0) {
                        markAllBtn.style.display = 'flex';
                    } else {
                        markAllBtn.style.display = 'none';
                    }
                }
            }
        }
    } catch (error) {
        console.error('Error updating unread count:', error);
    }
}

// Filter notifications by read status
function filterNotifications(filterType) {
    currentFilter = filterType;
    
    // Update active tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activeTab = document.getElementById(`tab${capitalizeFirstLetter(filterType)}`);
    if (activeTab) {
        activeTab.classList.add('active');
    }
    
    // Filter items
    const items = document.querySelectorAll('.notification-item');
    items.forEach(item => {
        const isRead = item.classList.contains('read');
        
        if (filterType === 'all') {
            item.style.display = 'flex';
        } else if (filterType === 'unread') {
            item.style.display = isRead ? 'none' : 'flex';
        } else if (filterType === 'read') {
            item.style.display = isRead ? 'flex' : 'none';
        }
        
        // Also apply type filter
        const type = item.dataset.type;
        if (currentTypeFilter !== 'all' && !type.includes(currentTypeFilter)) {
            item.style.display = 'none';
        }
    });
}

// Filter notifications by type
function filterByType(type) {
    currentTypeFilter = type;
    
    // Update active filter button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activeFilter = document.getElementById(`filter${capitalizeFirstLetter(type)}`);
    if (activeFilter) {
        activeFilter.classList.add('active');
    }
    
    // Filter items
    const items = document.querySelectorAll('.notification-item');
    items.forEach(item => {
        const notificationType = item.dataset.type;
        
        if (type === 'all') {
            // Apply read status filter
            if (currentFilter === 'unread' && item.classList.contains('read')) {
                item.style.display = 'none';
            } else if (currentFilter === 'read' && !item.classList.contains('read')) {
                item.style.display = 'none';
            } else {
                item.style.display = 'flex';
            }
        } else {
            // Check if type matches
            const matchesType = notificationType.includes(type);
            
            // Also check read status filter
            let matchesReadStatus = true;
            if (currentFilter === 'unread') {
                matchesReadStatus = !item.classList.contains('read');
            } else if (currentFilter === 'read') {
                matchesReadStatus = item.classList.contains('read');
            }
            
            item.style.display = (matchesType && matchesReadStatus) ? 'flex' : 'none';
        }
    });
}

// Load more notifications
async function loadMoreNotifications() {
    if (loading || !hasMore) return;
    
    loading = true;
    const button = document.getElementById('loadMoreBtn');
    const originalText = button?.innerHTML;
    
    try {
        if (button) {
            button.innerHTML = '<span class="loading"></span> Memuat...';
            button.disabled = true;
        }
        
        page++;
        const response = await fetch(`/landowner/notifications?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success && data.notifications && data.notifications.length > 0) {
                // Append new notifications
                appendNotifications(data.notifications);
                
                // Update hasMore flag
                hasMore = data.has_more || false;
                
                if (!hasMore && button) {
                    button.style.display = 'none';
                }
            } else {
                hasMore = false;
                if (button) button.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Error loading more notifications:', error);
        showToast('Gagal memuat notifikasi', 'error');
        hasMore = false;
    } finally {
        loading = false;
        if (button) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }
}

// Append notifications to list
function appendNotifications(notifications) {
    const list = document.getElementById('notificationsList');
    const emptyState = list.querySelector('.empty-state');
    
    if (emptyState) {
        emptyState.remove();
    }
    
    notifications.forEach(notification => {
        const notificationElement = createNotificationElement(notification);
        list.appendChild(notificationElement);
    });
}

// Create notification element
function createNotificationElement(notification) {
    const element = document.createElement('div');
    element.className = `notification-item ${notification.is_read ? 'read' : 'unread'}`;
    element.dataset.id = notification.id;
    element.dataset.type = notification.type;
    element.dataset.source = notification.source;
    
    const timeAgo = new Date(notification.created_at).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    element.innerHTML = `
        <div class="notification-icon ${notification.type}" style="background: var(--color-${notification.color || 'primary'})">
            <i class="${notification.icon || 'fas fa-bell'}"></i>
        </div>
        <div class="notification-content">
            <div class="notification-header">
                <h4 class="notification-title">${escapeHtml(notification.title)}</h4>
                <span class="notification-time" title="${timeAgo}">
                    ${formatTimeAgo(notification.created_at)}
                </span>
            </div>
            <p class="notification-message">${escapeHtml(notification.message)}</p>
            <div class="notification-actions">
                ${notification.action_url ? `
                    <button class="btn-notification-action" onclick="event.stopPropagation(); window.location.href='${notification.action_url}'">
                        <i class="fas fa-external-link-alt"></i> ${notification.action_text || 'Lihat Detail'}
                    </button>
                ` : ''}
                <button class="btn-delete-notification" onclick="event.stopPropagation(); deleteNotification('${notification.id}', '${notification.source}')" title="Hapus notifikasi">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        ${!notification.is_read ? '<div class="unread-indicator"></div>' : ''}
    `;
    
    element.onclick = () => handleNotificationClick(
        notification.id,
        notification.source,
        notification.action_url
    );
    
    return element;
}

// Delete notification
async function deleteNotification(notificationId, source) {
    if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/landowner/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // Remove from UI
                const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                if (item) {
                    item.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => {
                        item.remove();
                        
                        // Check if list is empty
                        const list = document.getElementById('notificationsList');
                        if (list && list.children.length === 0) {
                            list.innerHTML = `
                                <div class="empty-state">
                                    <i class="fas fa-bell-slash empty-state-icon"></i>
                                    <h3 class="empty-state-title">Belum ada notifikasi</h3>
                                    <p class="empty-state-desc">Notifikasi akan muncul di sini ketika ada update penting</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
                
                // Update unread count if needed
                if (item && !item.classList.contains('read')) {
                    updateUnreadCount();
                }
                
                showToast('Notifikasi berhasil dihapus', 'success');
            }
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menghapus notifikasi', 'error');
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Accept community invitation
async function acceptInvitation(notificationId, communityId) {
    try {
        const response = await fetch(`/buyer/communities/${communityId}/join-invite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Mark notification as read
            await handleNotificationClick(notificationId, 'custom');
            showToast('Undangan berhasil diterima', 'success');
            
            // Hapus tombol aksi secara visual
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                const actions = item.querySelector('.membership-actions');
                if (actions) actions.remove();
                
                // Ubah icon jadi check
                const icon = item.querySelector('.notification-icon');
                if (icon) {
                    icon.className = 'notification-icon';
                    icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                    icon.style.background = 'var(--color-success)';
                }
            }

            // Reload after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menerima undangan', 'error');
        }
    } catch (error) {
        console.error('Error accepting invitation:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Reject community invitation
async function rejectInvitation(notificationId, communityId) {
    try {
        const response = await fetch(`/buyer/communities/${communityId}/reject-invite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Mark notification as read
            await handleNotificationClick(notificationId, 'custom');
            showToast('Undangan berhasil ditolak', 'success');
            
            // Hapus tombol aksi secara visual
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                const actions = item.querySelector('.membership-actions');
                if (actions) actions.remove();
                
                // Ubah icon jadi reject
                const icon = item.querySelector('.notification-icon');
                if (icon) {
                    icon.className = 'notification-icon';
                    icon.innerHTML = '<i class="fas fa-times-circle"></i>';
                    icon.style.background = 'var(--color-danger)';
                }
            }

            // Reload after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menolak undangan', 'error');
        }
    } catch (error) {
        console.error('Error rejecting invitation:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Accept Main Bareng invitation
async function acceptMainBarengInvitation(notificationId, invitationId) {
    try {
        const response = await fetch(`/buyer/main-bareng-saya/invitation/${invitationId}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notification_id: notificationId })
        });
        
        if (response.ok) {
            // Mark notification as read
            await handleNotificationClick(notificationId, 'main-bareng');
            showToast('Undangan berhasil diterima', 'success');
            
            // Update UI status
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                const actions = item.querySelector('.membership-actions');
                if (actions) {
                    actions.innerHTML = '<div class="action-status"><span class="badge bg-success"><i class="fas fa-check"></i> Undangan Diterima</span></div>';
                }
            }

            // Reload after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menerima undangan', 'error');
        }
    } catch (error) {
        console.error('Error accepting main bareng invitation:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Approve Main Bareng join request
async function approveMainBarengJoinRequest(notificationId, playTogetherId, participantId) {
    try {
        const response = await fetch(`/buyer/main-bareng-saya/${playTogetherId}/approve/${participantId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notification_id: notificationId })
        });
        
        if (response.ok) {
            // Mark notification as read
            await handleNotificationClick(notificationId, 'main-bareng-join');
            showToast('Permintaan bergabung diterima', 'success');
            
            // Update UI status
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                const actions = item.querySelector('.membership-actions');
                if (actions) {
                    actions.innerHTML = '<div class="action-status"><span class="badge bg-success"><i class="fas fa-check"></i> Peserta Diterima</span></div>';
                }
            }

            // Reload after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menyetujui permintaan', 'error');
        }
    } catch (error) {
        console.error('Error approving join request:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Reject Main Bareng join request
async function rejectMainBarengJoinRequest(notificationId, playTogetherId, participantId) {
    try {
        const response = await fetch(`/buyer/main-bareng-saya/${playTogetherId}/reject/${participantId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notification_id: notificationId })
        });
        
        if (response.ok) {
            // Mark notification as read
            await handleNotificationClick(notificationId, 'main-bareng-join');
            showToast('Permintaan bergabung ditolak', 'success');
            
            // Update UI status
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                const actions = item.querySelector('.membership-actions');
                if (actions) {
                    actions.innerHTML = '<div class="action-status"><span class="badge bg-danger"><i class="fas fa-times"></i> Peserta Ditolak</span></div>';
                }
            }

            // Reload after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menolak permintaan', 'error');
        }
    } catch (error) {
        console.error('Error rejecting join request:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Reject Main Bareng invitation
async function rejectMainBarengInvitation(notificationId, invitationId) {
    try {
        const response = await fetch(`/buyer/main-bareng-saya/invitation/${invitationId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notification_id: notificationId })
        });
        
        if (response.ok) {
            // Mark notification as read
            await handleNotificationClick(notificationId, 'main-bareng');
            showToast('Undangan berhasil ditolak', 'success');
            
            // Update UI status
            const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (item) {
                const actions = item.querySelector('.membership-actions');
                if (actions) {
                    actions.innerHTML = '<div class="action-status"><span class="badge bg-danger"><i class="fas fa-times"></i> Undangan Ditolak</span></div>';
                }
            }

            // Reload after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const data = await response.json();
            showToast(data.message || 'Gagal menolak undangan', 'error');
        }
    } catch (error) {
        console.error('Error rejecting main bareng invitation:', error);
        showToast('Terjadi kesalahan', 'error');
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${getToastIcon(type)}"></i>
        <span>${message}</span>
    `;
    
    toastContainer.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode === toastContainer) {
                toastContainer.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Create toast container if doesn't exist
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

// Get toast icon based on type
function getToastIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'bell';
}

// Format time ago
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHour = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHour / 24);
    
    if (diffSec < 60) return 'baru saja';
    if (diffMin < 60) return `${diffMin} menit yang lalu`;
    if (diffHour < 24) return `${diffHour} jam yang lalu`;
    if (diffDay < 7) return `${diffDay} hari yang lalu`;
    if (diffDay < 30) return `${Math.floor(diffDay / 7)} minggu yang lalu`;
    if (diffDay < 365) return `${Math.floor(diffDay / 30)} bulan yang lalu`;
    return `${Math.floor(diffDay / 365)} tahun yang lalu`;
}

// Utility functions
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(10px); }
    }
`;
document.head.appendChild(style);
</script>
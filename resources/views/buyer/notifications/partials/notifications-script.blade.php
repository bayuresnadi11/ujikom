<script>
// Global variables
let currentFilter = 'unread';
let currentTypeFilter = 'all';
let loading = false;
let hasMore = true;
let page = 1;

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Loading Loading Functions
function showLoading(message = 'Memproses...') {
    const overlay = document.getElementById('loadingOverlay');
    const text = document.getElementById('loadingText');
    if (overlay && text) {
        text.textContent = message;
        overlay.style.display = 'flex';
    }
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initEventListeners();
    
    // Check for initial filter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const filterParam = urlParams.get('filter');
    if (filterParam) {
        filterNotifications(filterParam);
    } else {
        // Apply default unread filter
        filterNotifications('unread');
    }
});

// Initialize event listeners
function initEventListeners() {
    // Menu dots button
    const openMenuBtn = document.getElementById('openMenuBtn');
    if (openMenuBtn) {
        openMenuBtn.addEventListener('click', openBottomSheet);
    }

    // Bottom sheet overlay
    const bottomSheetOverlay = document.getElementById('bottomSheetOverlay');
    if (bottomSheetOverlay) {
        bottomSheetOverlay.addEventListener('click', closeBottomSheet);
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
        const response = await fetch(`/buyer/notifications/${notificationId}/mark-read`, {
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

// Bottom Sheet Functions
function openBottomSheet() {
    const overlay = document.getElementById('bottomSheetOverlay');
    const sheet = document.getElementById('bottomSheet');
    if (overlay && sheet) {
        overlay.classList.add('active');
        sheet.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scroll
    }
}

function closeBottomSheet() {
    const overlay = document.getElementById('bottomSheetOverlay');
    const sheet = document.getElementById('bottomSheet');
    if (overlay && sheet) {
        overlay.classList.remove('active');
        sheet.classList.remove('active');
        document.body.style.overflow = ''; // Restore scroll
    }
}

function handleMarkAllAsRead() {
    closeBottomSheet();
    markAllAsRead();
}

// Custom Confirm Modal Functions
function showDeleteConfirm() {
    closeBottomSheet();
    const overlay = document.getElementById('confirmModalOverlay');
    if (overlay) {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteConfirm() {
    const overlay = document.getElementById('confirmModalOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function confirmDeleteAll() {
    closeDeleteConfirm();
    handleClearAll();
}

async function handleClearAll() {
    showLoading('Menghapus semua notifikasi...');
    try {
        const response = await fetch('/buyer/notifications', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Clear notifications list
            const list = document.getElementById('notificationsList');
            if (list) {
                list.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-bell-slash empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum ada notifikasi</h3>
                        <p class="empty-state-desc">Notifikasi akan muncul di sini ketika ada update penting</p>
                    </div>
                `;
            }
            
            // Update unread count
            updateUnreadCount();
            
            // Hide load more button
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            if (loadMoreBtn) loadMoreBtn.style.display = 'none';

            showToast('Semua notifikasi berhasil dihapus', 'success');
        }
        hideLoading();
    } catch (error) {
        console.error('Error clearing all notifications:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Mark all as read
async function markAllAsRead() {
    showLoading('Menandai semua sebagai dibaca...');
    try {
        const response = await fetch('/buyer/notifications/mark-all-read', {
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
        hideLoading();
    } catch (error) {
        console.error('Error marking all as read:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Update unread count and badges
function updateUnreadCount() {
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    const readCount = document.querySelectorAll('.notification-item.read').length;
    
    // Update "Belum Dibaca" Badge
    const tabUnread = document.getElementById('tabUnread');
    if (tabUnread) {
        let badge = tabUnread.querySelector('.pill-badge');
        if (unreadCount > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'pill-badge';
                tabUnread.appendChild(badge);
            }
            badge.textContent = unreadCount;
        } else if (badge) {
            badge.remove();
        }
    }

    // Update "Sudah Dibaca" Badge
    const tabRead = document.getElementById('tabRead');
    if (tabRead) {
        let badge = tabRead.querySelector('.pill-badge');
        if (readCount > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'pill-badge';
                tabRead.appendChild(badge);
            }
            badge.textContent = readCount;
        } else if (badge) {
            badge.remove();
        }
    }

    // Optional: Hide/Show actions based on unread count
    // (e.g. if you want to hide "Mark All Read" if unreadCount is 0)
}

// Filter notifications by read status
function filterNotifications(filterType) {
    currentFilter = filterType;
    
    // Update active pill
    document.querySelectorAll('.pill-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activeTab = document.getElementById(`tab${capitalizeFirstLetter(filterType)}`);
    if (activeTab) {
        activeTab.classList.add('active');
    }
    
    refreshFilters();
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
    
    refreshFilters();
}

let currentCommunityId = ''; // Empty means all in that category

// Initialize first community if exists
window.addEventListener('DOMContentLoaded', () => {
    const firstCommunityItem = document.querySelector('.community-context-item');
    if (firstCommunityItem) {
        currentCommunityId = firstCommunityItem.dataset.communityId;
    }
});

// Update function to filter by community
function filterByCommunity(communityId, element) {
    currentCommunityId = communityId;
    
    // Update active state in UI
    document.querySelectorAll('.community-context-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    
    refreshFilters();
}

// Refresh all filters
function refreshFilters() {
    const items = document.querySelectorAll('.notification-item');
    const communityHeader = document.getElementById('communityHeader');
    let visibleCount = 0;

    // Toggle Community Header Visibility
    if (communityHeader) {
        if (currentTypeFilter === 'community' && items.length > 0) {
            communityHeader.style.display = 'flex';
        } else {
            communityHeader.style.display = 'none';
        }
    }

    items.forEach(item => {
        const notificationType = item.dataset.type || '';
        const itemCommunityId = item.dataset.communityId || '';
        const isRead = item.classList.contains('read');
        
        // 1. Check Read Status Filter
        let matchesReadStatus = true;
        if (currentFilter === 'unread') {
            matchesReadStatus = !isRead;
        } else if (currentFilter === 'read') {
            matchesReadStatus = isRead;
        }

        // 2. Check Notification Type Filter
        let matchesType = false;
        const typeLower = notificationType.toLowerCase();
        const isCommunityRelated = typeLower.includes('community') || typeLower.includes('main_bareng');

        if (currentTypeFilter === 'all') {
            matchesType = true;
        } else if (currentTypeFilter === 'community') {
            // Apply double filter: Is community related AND matches selected community ID
            if (currentCommunityId) {
                matchesType = isCommunityRelated && itemCommunityId === currentCommunityId;
            } else {
                matchesType = isCommunityRelated;
            }
        } else if (currentTypeFilter === 'system') {
            matchesType = !isCommunityRelated;
        }

        // Apply visibility
        if (matchesReadStatus && matchesType) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Handle empty state for filtered results
    const list = document.getElementById('notificationsList');
    let emptyMsg = list.querySelector('.empty-state-filtered');
    
    if (visibleCount === 0 && items.length > 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('div');
            emptyMsg.className = 'empty-state empty-state-filtered';
            emptyMsg.innerHTML = `
                <i class="fas fa-search empty-state-icon"></i>
                <h3 class="empty-state-title">Tidak ada notifikasi</h3>
                <p class="empty-state-desc">Tidak ditemukan notifikasi untuk filter ini</p>
            `;
            list.appendChild(emptyMsg);
        }
    } else if (emptyMsg) {
        emptyMsg.remove();
    }
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
        const response = await fetch(`/buyer/notifications?page=${page}`, {
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
    
    const avatarHtml = notification.avatar_url 
        ? `<img src="${notification.avatar_url}" alt="Avatar" class="notification-avatar">`
        : `<div class="notification-icon ${notification.type}" style="background: var(--color-${notification.color || 'primary'})">
                <i class="${notification.icon || 'fas fa-bell'}"></i>
           </div>`;
    
    element.innerHTML = `
        ${avatarHtml}
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
            </div>
        </div>
        <button class="btn-delete-notification" onclick="event.stopPropagation(); deleteNotification('${notification.id}', '${notification.source}')" title="Hapus notifikasi">
            <i class="fas fa-trash-alt"></i>
        </button>
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
        const response = await fetch(`/buyer/notifications/${notificationId}`, {
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
    showLoading('Menerima undangan...');
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
            hideLoading();
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
            hideLoading();
            showToast(data.message || 'Gagal menerima undangan', 'error');
        }
    } catch (error) {
        console.error('Error accepting invitation:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Reject community invitation
async function rejectInvitation(notificationId, communityId) {
    showLoading('Menolak undangan...');
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
            hideLoading();
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
            hideLoading();
            showToast(data.message || 'Gagal menolak undangan', 'error');
        }
    } catch (error) {
        console.error('Error rejecting invitation:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Accept Main Bareng invitation
async function acceptMainBarengInvitation(notificationId, invitationId) {
    showLoading('Menerima undangan...');
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
            hideLoading();
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
            hideLoading();
            showToast(data.message || 'Gagal menerima undangan', 'error');
        }
    } catch (error) {
        console.error('Error accepting main bareng invitation:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Approve Main Bareng join request
async function approveMainBarengJoinRequest(notificationId, playTogetherId, participantId) {
    showLoading('Menyetujui permintaan...');
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
            hideLoading();
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
            hideLoading();
            showToast(data.message || 'Gagal menyetujui permintaan', 'error');
        }
    } catch (error) {
        console.error('Error approving join request:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Reject Main Bareng join request
async function rejectMainBarengJoinRequest(notificationId, playTogetherId, participantId) {
    showLoading('Menolak permintaan...');
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
            hideLoading();
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
            hideLoading();
            showToast(data.message || 'Gagal menolak permintaan', 'error');
        }
    } catch (error) {
        console.error('Error rejecting join request:', error);
        hideLoading();
        showToast('Terjadi kesalahan', 'error');
    }
}

// Reject Main Bareng invitation
async function rejectMainBarengInvitation(notificationId, invitationId) {
    showLoading('Menolak undangan...');
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
            hideLoading();
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
            hideLoading();
            showToast(data.message || 'Gagal menolak undangan', 'error');
        }
    } catch (error) {
        console.error('Error rejecting main bareng invitation:', error);
        hideLoading();
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
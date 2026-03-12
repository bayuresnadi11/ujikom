<script>
    // Chat Data untuk Landowner
    const chatData = [
        {
            id: 'penyewa1',
            name: 'Budi Santoso',
            type: 'booking',
            avatar: '<i class="fas fa-user"></i>',
            avatarColor: 'linear-gradient(135deg, #0A5C36, #27AE60)',
            online: true,
            pinned: true,
            unread: 3,
            lastMessage: 'Halo, saya mau booking lapangan futsal untuk besok jam 3',
            time: '10:45',
            typing: false,
            read: false,
            muted: false,
            venue: 'Futsal Center SCBD',
            lastBooking: '2 hari lalu'
        },
        {
            id: 'penyewa2',
            name: 'Siti Aisyah',
            type: 'booking',
            avatar: '<i class="fas fa-user"></i>',
            avatarColor: 'linear-gradient(135deg, #3498db, #2980b9)',
            online: true,
            pinned: false,
            unread: 1,
            lastMessage: 'Untuk booking badminton hari Minggu jam 4 bisa?',
            time: '10:30',
            typing: true,
            read: false,
            muted: false,
            venue: 'Badminton Arena BSD',
            lastBooking: '3 hari lalu'
        },
        {
            id: 'admin',
            name: 'Admin Support',
            type: 'support',
            avatar: '<i class="fas fa-headset"></i>',
            avatarColor: 'linear-gradient(135deg, #9b59b6, #8e44ad)',
            online: true,
            pinned: false,
            unread: 0,
            lastMessage: 'Invoice untuk bulan Oktober sudah tersedia',
            time: 'Kemarin',
            typing: false,
            read: true,
            muted: false
        },
        {
            id: 'penyewa3',
            name: 'Ahmad Rizki',
            type: 'booking',
            avatar: '<i class="fas fa-user"></i>',
            avatarColor: 'linear-gradient(135deg, #e74c3c, #c0392b)',
            online: false,
            pinned: false,
            unread: 0,
            lastMessage: 'Terima kasih untuk pelayanannya, lapangan bagus!',
            time: '2 hari lalu',
            typing: false,
            read: true,
            muted: false,
            venue: 'Basket Arena Jakarta',
            lastBooking: '1 minggu lalu'
        },
        {
            id: 'komunitas',
            name: 'Landowner Community',
            type: 'community',
            avatar: '<i class="fas fa-users"></i>',
            avatarColor: 'linear-gradient(135deg, #f39c12, #e67e22)',
            online: true,
            pinned: false,
            unread: 12,
            lastMessage: 'Ada yang punya tips naikin harga sewa?',
            time: '1 jam lalu',
            typing: false,
            read: false,
            muted: true
        }
    ];

    // DOM Elements
    let currentFilter = 'all';
    let currentSearch = '';

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        renderChats();
        updateUnreadCount();
        startTypingAnimation();
        updateBottomNav();
    });

    // Render Chats
    function renderChats() {
        const chatsList = document.getElementById('chatsList');
        const emptyState = document.getElementById('emptyState');
        
        let filteredChats = chatData;
        
        // Apply filter
        if (currentFilter === 'unread') {
            filteredChats = chatData.filter(chat => chat.unread > 0);
        } else if (currentFilter === 'booking') {
            filteredChats = chatData.filter(chat => chat.type === 'booking');
        } else if (currentFilter === 'support') {
            filteredChats = chatData.filter(chat => chat.type === 'support');
        }
        
        // Apply search
        if (currentSearch) {
            const searchTerm = currentSearch.toLowerCase();
            filteredChats = filteredChats.filter(chat => 
                chat.name.toLowerCase().includes(searchTerm) ||
                chat.lastMessage.toLowerCase().includes(searchTerm) ||
                (chat.venue && chat.venue.toLowerCase().includes(searchTerm))
            );
        }
        
        // Update UI
        if (filteredChats.length === 0) {
            emptyState.classList.add('active');
            chatsList.innerHTML = '';
        } else {
            emptyState.classList.remove('active');
            chatsList.innerHTML = filteredChats.map(chat => createChatItem(chat)).join('');
        }
    }

    // Create Chat Item HTML untuk Landowner
    function createChatItem(chat) {
        const statusIcon = chat.read ? 
            '<i class="fas fa-check-double message-status"></i>' : '';
        
        const lastMessage = chat.typing ? 
            '<div class="chat-preview typing-indicator">' +
                '<i class="fas fa-circle"></i>' +
                '<i class="fas fa-circle"></i>' +
                '<i class="fas fa-circle"></i>' +
                ' sedang mengetik...' +
            '</div>' :
            `<div class="chat-preview ${chat.unread > 0 ? 'unread' : ''}">` +
                statusIcon +
                chat.lastMessage +
            '</div>';
        
        const venueInfo = chat.venue ? 
            `<div class="chat-desc" style="font-size: 12px; color: var(--text-light); margin-top: 2px;">
                <i class="fas fa-map-marker-alt"></i> ${chat.venue}
            </div>` : '';
        
        return `
            <div class="chat-item ${chat.pinned ? 'pinned' : ''} ${chat.muted ? 'muted' : ''}" 
                 onclick="openChat('${chat.id}')">
                <div class="chat-avatar">
                    <div class="avatar-img" style="background: ${chat.avatarColor}">
                        ${chat.avatar}
                        ${chat.type === 'community' ? '<div class="avatar-badge">GRP</div>' : ''}
                    </div>
                    <div class="online-status ${chat.online ? '' : 'offline'}"></div>
                </div>
                <div class="chat-info">
                    <div class="chat-header-info">
                        <div class="chat-name">${chat.name}</div>
                        <div class="chat-time">${chat.time}</div>
                    </div>
                    ${lastMessage}
                    ${venueInfo}
                </div>
                ${chat.unread > 0 ? `<div class="unread-badge">${chat.unread}</div>` : ''}
            </div>
        `;
    }

    // Tab Switching
    function switchTab(tab) {
        const tabs = document.querySelectorAll('.tab-btn');
        const chatsTab = document.getElementById('chats-tab');
        const discoverTab = document.getElementById('discover-tab');
        
        tabs.forEach(t => t.classList.remove('active'));
        
        if (tab === 'chats') {
            document.getElementById('tabChats').classList.add('active');
            chatsTab.classList.add('active');
            discoverTab.classList.remove('active');
        } else {
            document.getElementById('tabDiscover').classList.add('active');
            chatsTab.classList.remove('active');
            discoverTab.classList.add('active');
        }
    }

    // Filter Chats
    function filterChats(filter) {
        currentFilter = filter;
        const buttons = document.querySelectorAll('.quick-action');
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        renderChats();
    }

    // Search Functionality
    function searchChats() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.querySelector('.search-clear');
        currentSearch = searchInput.value.trim();
        
        if (currentSearch) {
            clearBtn.style.display = 'block';
        } else {
            clearBtn.style.display = 'none';
        }
        
        renderChats();
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        currentSearch = '';
        document.querySelector('.search-clear').style.display = 'none';
        renderChats();
    }

    // Modal Functions
    function showNewChatModal() {
        document.getElementById('newChatModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Chat Actions untuk Landowner
    function openChat(chatId) {
        const chat = chatData.find(c => c.id === chatId);
        if (chat && chat.unread > 0) {
            chat.unread = 0;
            updateUnreadCount();
            renderChats();
        }
        showChatScreen(chatId);
    }

    function openSupportChat() {
        showChatScreen('admin');
    }

    function showPenyewaList() {
        alert('Menampilkan daftar penyewa yang pernah booking di venue Anda\n\nFitur akan tersedia di versi lengkap.');
    }

    function showBroadcast() {
        alert('Menampilkan fitur broadcast ke semua penyewa\n\nFitur akan tersedia di versi lengkap.');
    }

    function showLandownerCommunity() {
        alert('Menampilkan komunitas landowner\n\nFitur akan tersedia di versi lengkap.');
    }

    function startChatWithPenyewa(penyewaId) {
        closeModal('newChatModal');
        if (penyewaId === 'new') {
            alert('Mencari penyewa untuk diajak chat...\n\nFitur akan tersedia di versi lengkap.');
        } else {
            alert(`Memulai chat dengan penyewa: ${penyewaId}\n\nFitur akan tersedia di versi lengkap.`);
        }
    }

    function showBroadcastModal() {
        closeModal('newChatModal');
        alert('Membuka modal broadcast\n\nFitur akan tersedia di versi lengkap.');
    }

    function showNotification() {
        alert('Menampilkan notifikasi landowner\n\nFitur akan tersedia di versi lengkap.');
    }

    // Helper Functions
    function updateUnreadCount() {
        const totalUnread = chatData.reduce((sum, chat) => sum + chat.unread, 0);
        const tabBadge = document.querySelector('.tab-btn .badge');
        
        if (totalUnread > 0) {
            tabBadge.textContent = totalUnread;
            tabBadge.style.display = 'inline-block';
        } else {
            tabBadge.style.display = 'none';
        }
    }

    function startTypingAnimation() {
        setInterval(() => {
            const dots = document.querySelectorAll('.typing-indicator i');
            dots.forEach((dot, i) => {
                setTimeout(() => {
                    dot.style.opacity = dot.style.opacity === '0.3' ? '1' : '0.3';
                }, i * 150);
            });
        }, 1000);
    }

    // Update bottom nav untuk landowner
    function updateBottomNav() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const text = item.querySelector('span');
            if (text && text.textContent === 'Chat') {
                item.classList.add('active');
                const icon = item.querySelector('.nav-icon');
                if (icon) {
                    icon.style.background = 'var(--gradient-primary)';
                    icon.style.color = 'white';
                    icon.style.boxShadow = 'var(--shadow-lg)';
                    icon.style.transform = 'scale(1.1)';
                }
            }
        });
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    };

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Enter key untuk search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchChats();
            }
        });
        
        // Hover effect untuk cards
        const cards = document.querySelectorAll('.discover-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-6px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'success' ? 'var(--primary)' : type === 'error' ? 'var(--danger)' : 'var(--warning)'};
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            z-index: 9999;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        `;
        
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.querySelector('.mobile-container').appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(-10px)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
</script>
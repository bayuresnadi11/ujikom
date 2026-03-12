@extends('layouts.main', ['title' => 'Undang Anggota - ' . $community->name])

@push('styles')
<style>
    /* ================= VARIABLES ================= */
    :root {
        --primary: #0A5C36;
        --primary-dark: #08472a;
        --primary-light: #0e6b40;
        --secondary: #2ecc71;
        --accent: #27ae60;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --info: #3498db;
        --light: #F8F9FA;
        --light-gray: #E9ECEF;
        --border: #E6F7EF;
        --text: #1A3A27;
        --text-light: #6C757D;
        --text-lighter: #8A9C93;
        --shadow-sm: 0 1px 3px rgba(10, 92, 54, 0.1);
        --shadow-md: 0 2px 8px rgba(10, 92, 54, 0.15);
        --shadow-lg: 0 4px 12px rgba(10, 92, 54, 0.2);
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        --gradient-dark: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 10px;
        --radius-xl: 12px;
        --radius-2xl: 16px;
    }

    /* ================= RESET & BASE ================= */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        color: var(--text);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
    }

    /* ================= MOBILE CONTAINER ================= */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        max-width: 100%;
    }

    /* ================= SIMPLE HEADER ================= */
    .simple-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: white;
        z-index: 1100;
        box-shadow: var(--shadow-sm);
        border-bottom: 1px solid var(--border);
    }

    .header-content {
        display: flex;
        align-items: center;
        padding: 14px 16px;
        gap: 16px;
    }

    .back-button-simple {
        background: none;
        border: none;
        font-size: 18px;
        color: var(--primary);
        cursor: pointer;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        transition: all 0.2s ease;
    }

    .back-button-simple:hover {
        background: var(--light);
    }

    .header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        flex: 1;
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding: 70px 16px 90px;
        min-height: 100vh;
    }

    /* ================= PAGE TITLE ================= */
    .page-title {
        padding: 16px 0 20px;
        background: white;
    }

    .page-title p {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
    }

    /* ================= INVITE STYLES ================= */
    .invite-container {
        padding: 0 16px;
    }

    .invite-search-box {
        position: relative;
        margin-bottom: 16px;
    }

    .invite-search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        font-size: 14px;
        background: white;
        color: var(--text);
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .invite-search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
    }

    .invite-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 14px;
    }

    /* ================= LOADING SPINNER ================= */
    .loading-spinner {
        text-align: center;
        padding: 20px;
    }

    .loading-spinner i {
        font-size: 24px;
        color: var(--primary);
        animation: spin 1s linear infinite;
    }

    /* ================= INVITE RESULTS REDESIGN ================= */
    .invite-results {
        max-height: calc(100vh - 280px);
        overflow-y: auto;
        padding: 8px 0;
    }

    .invite-user-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .invite-user-item:hover {
        background: rgba(0,0,0,0.02);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background: var(--light-gray);
        flex-shrink: 0;
        border: 2px solid white;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        text-transform: uppercase;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 2px;
        line-height: 1.4;
    }

    .user-email {
        font-size: 12px;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* ================= INVITE BUTTON ================= */
    .invite-btn {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: var(--radius-md);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: var(--shadow-sm);
    }

    .invite-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .invite-btn:disabled {
        background: var(--light-gray);
        color: var(--text-light);
        cursor: not-allowed;
        box-shadow: none;
    }

    .invite-btn.invited {
        background: var(--success);
    }

    .invite-btn.pending {
        background: var(--warning);
    }

    .invite-btn.loading {
        position: relative;
        color: transparent !important;
    }

    .invite-btn.loading::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        top: 50%;
        left: 50%;
        margin-left: -7px;
        margin-top: -7px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    /* ================= EMPTY STATE ================= */
    .empty-invite-state {
        text-align: center;
        padding: 40px 16px;
        color: var(--text-light);
    }

    .empty-invite-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: var(--light-gray);
        opacity: 0.5;
    }

    .empty-invite-state p {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
    }

    /* ================= BOTTOM NAV ================= */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 0 10px;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 1000;
        border-top: 1px solid var(--light-gray);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        padding: 4px 8px;
        transition: all 0.2s ease;
        border-radius: var(--radius-md);
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 48px;
        color: #999;
    }

    .nav-item.active {
        color: var(--primary);
    }

    .nav-item.active .nav-icon {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
        transform: scale(1.05);
    }

    .nav-item.active::after {
        content: "";
        position: absolute;
        top: -4px;
        width: 24px;
        height: 3px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .nav-item:hover {
        color: var(--primary);
        background: rgba(10, 92, 54, 0.05);
    }

    .nav-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        background: var(--light);
        color: var(--text-light);
    }

    .nav-label {
        font-size: 10px;
        font-weight: 700;
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast-container {
        position: fixed;
        top: 80px;
        right: 16px;
        z-index: 3000;
        max-width: 280px;
        pointer-events: none;
    }

    .toast {
        background: white;
        border-radius: var(--radius-md);
        padding: 12px;
        margin-bottom: 8px;
        box-shadow: var(--shadow-md);
        border-left: 4px solid var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
        transform: translateX(300px);
        transition: transform 0.2s ease, opacity 0.2s ease;
        opacity: 0;
        pointer-events: auto;
    }

    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        background: #E8F5E9;
        color: var(--success);
    }

    .toast.error .toast-icon {
        background: #FFEBEE;
        color: var(--danger);
    }

    .toast.info .toast-icon {
        background: #E3F2FD;
        color: var(--info);
    }

    .toast-content {
        flex: 1;
        min-width: 0;
    }

    .toast-content h4 {
        margin: 0 0 4px 0;
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }

    .toast-content p {
        margin: 0;
        font-size: 11px;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 360px) {
        .header-content {
            padding: 12px;
            gap: 12px;
        }

        .back-button-simple {
            width: 28px;
            height: 28px;
            font-size: 16px;
        }

        .header-title {
            font-size: 16px;
        }

        .main-content {
            padding: 60px 12px 80px;
        }

        .invite-container {
            padding: 12px;
        }

        .invite-search-input {
            padding: 8px 10px 8px 36px;
            font-size: 12px;
        }

        .invite-search-icon {
            left: 10px;
            font-size: 12px;
        }

        .invite-results {
            max-height: 350px;
            padding: 6px;
        }

        .invite-user-item {
            padding: 10px;
            margin-bottom: 6px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
        }

        .user-name {
            font-size: 12px;
        }

        .user-email {
            font-size: 10px;
        }

        .invite-btn {
            padding: 6px 12px;
            font-size: 11px;
            gap: 4px;
        }

        .empty-invite-state {
            padding: 30px 12px;
        }

        .empty-invite-state i {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .empty-invite-state p {
            font-size: 12px;
        }

        .bottom-nav {
            padding: 6px 0 8px;
        }

        .nav-item {
            padding: 3px 6px;
            min-width: 40px;
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .nav-label {
            font-size: 9px;
        }

        .toast-container {
            top: 70px;
            right: 12px;
            left: 12px;
            max-width: calc(100% - 24px);
        }
    }

    @media (min-width: 480px) {
        .mobile-container {
            max-width: 480px;
            margin: 10px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: var(--radius-2xl);
            overflow: hidden;
            position: relative;
        }

        .simple-header {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        }

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .main-content {
            padding: 80px 20px 70px;
        }

        .invite-container {
            padding: 20px;
        }

        .invite-search-input {
            font-size: 14px;
            padding: 12px 16px 12px 40px;
        }

        .invite-search-icon {
            font-size: 16px;
            left: 16px;
        }

        .user-name {
            font-size: 14px;
        }

        .user-email {
            font-size: 12px;
        }

        .invite-btn {
            padding: 10px 20px;
            font-size: 13px;
        }
    }

    /* ================= UTILITIES ================= */
    .text-sm {
        font-size: 11px;
    }

    .font-semibold {
        font-weight: 600;
    }

    .text-center {
        text-align: center;
    }

    .w-full {
        width: 100%;
    }

    /* ================= LOADING ANIMATION ================= */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ================= SCROLLBAR STYLING ================= */
    .invite-results::-webkit-scrollbar {
        width: 6px;
    }

    .invite-results::-webkit-scrollbar-track {
        background: var(--light);
        border-radius: 3px;
    }

    .invite-results::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    .invite-results::-webkit-scrollbar-thumb:hover {
        background: var(--text-lighter);
    }

    /* Consolidating styles for Checkbox and Draft Footer */
    .invite-user-item {
        cursor: pointer;
        position: relative;
    }

    .invite-user-item.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #f8f9fa;
    }

    .custom-checkbox {
        width: 24px;
        height: 24px;
        border: 2px solid var(--text-light);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        color: white;
    }

    .invite-user-item.selected .custom-checkbox {
        background: var(--primary);
        border-color: var(--primary);
    }
    
    .invite-user-item.selected {
        background-color: rgba(10, 92, 54, 0.05); /* Light green tint */
        border-color: var(--primary);
    }

    .custom-checkbox i {
        font-size: 12px;
        transform: scale(0);
        transition: transform 0.2s ease;
    }

    .invite-user-item.selected .custom-checkbox i {
        transform: scale(1);
    }

    .status-badge.pending {
        font-size: 11px;
        color: var(--warning);
        font-weight: 600;
        background: rgba(243, 156, 18, 0.1);
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Draft Footer */
    .draft-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        padding: 16px;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
        z-index: 1050;
        text-align: center;
        border-top: 1px solid var(--border);
    }

    @media (min-width: 480px) {
        .draft-footer {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }
    }

    .view-draft-btn {
        width: 100%;
        background: white;
        color: var(--primary);
        border: 1px solid var(--primary);
        padding: 12px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .view-draft-btn:hover {
        background: var(--primary);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="mobile-container">
    <!-- Simple Header -->
    <header class="simple-header">
        <div class="header-content">
            <button class="back-button-simple" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="header-title">Undang Anggota</h1>
        </div>
    </header>

    <main class="main-content">
        <!-- Page Description -->
        <div class="page-title">
            <p>Pilih cara untuk mengundang anggota baru ke komunitas</p>
        </div>

        <div class="invite-container">
            <div class="invite-search-box">
                <i class="fas fa-search invite-search-icon"></i>
                <input 
                    type="text" 
                    id="inviteSearch" 
                    class="invite-search-input" 
                    placeholder="Cari nama atau email user..."
                    autocomplete="off"
                >
            </div>

            <div id="inviteResults" class="invite-results">
                <!-- Hasil akan ditampilkan di sini secara real-time -->
                <div class="empty-invite-state">
                    <i class="fas fa-search"></i>
                    <p>Ketik untuk mencari user...</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('buyer.communities.show', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-user-circle"></i></div>
            <span class="nav-label">Profil</span>
        </a>
        <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-calendar-alt"></i></div>
            <span class="nav-label">Aktivitas</span>
        </a>
        <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-users"></i></div>
            <span class="nav-label">Anggota</span>
        </a>
        <a href="#" class="nav-item">
            <div class="nav-icon"><i class="fas fa-trophy"></i></div>
            <span class="nav-label">Kompetisi</span>
        </a>
        <a href="{{ route('buyer.communities.galeri', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-images"></i></div>
            <span class="nav-label">Galeri</span>
        </a>
    </nav>

    <!-- Sticky Draft Footer -->
    <div id="draftFooter" class="draft-footer" style="display: none;">
        <button id="viewDraftBtn" class="view-draft-btn" onclick="goToDraft()">
            Lihat draft undangan (<span id="draftCount">0</span>)
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ================= STATE MANAGEMENT =================
    let selectedUsers = new Set();
    const draftFooter = document.getElementById('draftFooter');
    const draftCountSpan = document.getElementById('draftCount');
    const viewDraftBtn = document.getElementById('viewDraftBtn');

    // ================= DEBOUNCE FUNCTION =================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ================= SEARCH USERS =================
    const searchInput = document.getElementById('inviteSearch');
    const resultsContainer = document.getElementById('inviteResults');

    searchInput.addEventListener('input', debounce(function(e) {
        const query = e.target.value.trim();
        
        // Require minimum 3 characters
        if (query.length >= 3) {
            searchUsers(query);
        } else if (query.length > 0) {
            showEmptyState('Ketik minimal 3 huruf untuk mencari...');
        } else {
            showEmptyState('Ketik untuk mencari user...');
        }
    }, 300));

    async function searchUsers(query) {
        // Double check minimum length
        if (!query || query.trim().length < 3) {
            showEmptyState('Ketik minimal 3 huruf untuk mencari...');
            return;
        }

        showLoading();
        
        try {
            const url = "{{ route('buyer.communities.searchUsers', $community->id) }}";
            
            const response = await fetch(
                url + "?q=" + encodeURIComponent(query),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            );

            if (!response.ok) throw new Error('Network response was not ok');
            const users = await response.json();
            updateResults(users);
            
        } catch (error) {
            console.error('Error searching users:', error);
            showEmptyState('Terjadi kesalahan saat mencari user');
        }
    }

    function updateResults(users) {
        resultsContainer.innerHTML = '';
        
        if (!users || users.length === 0) {
            showEmptyState('User tidak ditemukan');
            return;
        }

        users.forEach(user => {
            const isSelected = selectedUsers.has(user.id);
            const userItem = document.createElement('div');
            userItem.className = `invite-user-item ${user.is_pending ? 'disabled' : ''} ${isSelected ? 'selected' : ''}`;
            if (!user.is_pending) {
                userItem.onclick = () => toggleSelection(user.id, userItem);
            }
            
            // Action badge or checkbox
            let actionHtml = '';
            if (user.is_pending) {
                actionHtml = '<span class="status-badge pending">Pending</span>';
            } else {
                actionHtml = `
                    <div class="custom-checkbox" id="checkbox-${user.id}">
                        <i class="fas fa-check"></i>
                    </div>
                `;
            }

            // Avatar & Initials logic
            let avatarHtml = '';
            if (user.avatar && !user.avatar.includes('default-avatar.png')) {
                avatarHtml = `
                    <img 
                        src="${user.avatar}" 
                        class="user-avatar" 
                        alt="${user.name}"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="user-avatar initials-placeholder" style="background-color: ${getAvatarColor(user.name)}; display: none;">
                        ${getInitials(user.name)}
                    </div>
                `;
            } else {
                avatarHtml = `
                    <div class="user-avatar initials-placeholder" style="background-color: ${getAvatarColor(user.name)}">
                        ${getInitials(user.name)}
                    </div>
                `;
            }

            userItem.innerHTML = `
                <div class="user-info">
                    ${avatarHtml}
                    <div class="user-details">
                        <div class="user-name">${user.name}</div>
                        <div class="user-email">${user.email || 'Alamat email tidak tersedia'}</div>
                    </div>
                </div>
                ${actionHtml}
            `;
            
            resultsContainer.appendChild(userItem);
        });
    }

    function showLoading() {
        resultsContainer.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p style="margin-top: 8px; color: var(--text-light); font-size: 12px;">Mencari user...</p>
            </div>
        `;
    }

    function showEmptyState(message) {
        resultsContainer.innerHTML = `
            <div class="empty-invite-state">
                <i class="fas fa-search"></i>
                <p>${message}</p>
            </div>
        `;
    }

    // ================= SELECTION LOGIC =================
    function toggleSelection(userId, element) {
        if (element.classList.contains('disabled')) return;

        if (selectedUsers.has(userId)) {
            selectedUsers.delete(userId);
            element.classList.remove('selected');
        } else {
            selectedUsers.add(userId);
            element.classList.add('selected');
        }
        
        updateFooter();
    }

    function updateFooter() {
        const count = selectedUsers.size;
        draftCountSpan.innerText = count;
        
        if (count > 0) {
            draftFooter.style.display = 'block';
            // Adjust main content padding to prevent covering
            document.querySelector('.main-content').style.paddingBottom = '80px';
        } else {
            draftFooter.style.display = 'none';
        }
    }

    // ================= GO TO DRAFT =================
    async function goToDraft() {
        if (selectedUsers.size === 0) return;

        const btn = document.getElementById('viewDraftBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('buyer.communities.invite.draft.add', $community->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ users: Array.from(selectedUsers) })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = "{{ route('buyer.communities.invite.draft', $community->id) }}";
            } else {
                throw new Error('Gagal menyimpan draft');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal memproses draft', 'error');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    // ================= TOAST NOTIFICATION =================
    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            </div>
            <div class="toast-content">
                <h4>${type === 'success' ? 'Sukses!' : type === 'error' ? 'Error!' : 'Info!'}</h4>
                <p>${message}</p>
            </div>
        `;

        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    // ================= UTILS =================
    function getInitials(name) {
        if (!name) return '??';
        const parts = name.split(' ');
        if (parts.length >= 2) {
            return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    }

    function getAvatarColor(name) {
        const colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        const index = Math.abs(hash) % colors.length;
        return colors[index];
    }
</script>
@endpush
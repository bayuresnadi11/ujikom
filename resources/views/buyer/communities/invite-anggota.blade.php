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
        padding: 70px 16px 90px; /* Increased bottom padding for button */
        min-height: 100vh;
    }

    /* ================= PAGE TITLE ================= */
    .page-title {
        padding: 16px 0 20px;
        background: white;
    }

    .page-title h1 {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
    }

    .page-title p {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
    }

    /* ================= TABS SECTION ================= */
    .tabs-section {
        background: white;
        padding: 0 0 16px;
        margin-bottom: 0;
    }

    .tabs-container {
        display: flex;
        background: var(--light);
        border-radius: var(--radius-md);
        padding: 4px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .tab-button {
        flex: 1;
        background: transparent;
        border: none;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: var(--radius-sm);
        text-align: center;
    }

    .tab-button.active {
        color: var(--primary);
        background: white;
        box-shadow: var(--shadow-sm);
    }

    /* ================= TAB CONTENT ================= */
    .tab-content {
        display: none;
        padding: 0;
        background: white;
    }

    .tab-content.active {
        display: block;
    }

    /* ================= REQUEST LIST ================= */
    .request-list {
        padding: 0;
    }

    .request-item {
        display: flex;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid var(--border);
        gap: 12px;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid var(--border);
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 2px;
    }

    .user-meta {
        font-size: 11px;
        color: var(--text-light);
    }

    .request-actions {
        display: flex;
        gap: 10px;
    }

    .btn-approve, .btn-reject {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-approve {
        background: #E8F5E9;
        color: var(--success);
    }

    .btn-approve:hover {
        background: var(--success);
        color: white;
    }

    .btn-reject {
        background: #FFEBEE;
        color: var(--danger);
    }

    .btn-reject:hover {
        background: var(--danger);
        color: white;
    }

    /* ================= EMPTY STATE FOR BOTH TABS ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 40px;
        color: var(--light-gray);
        margin-bottom: 16px;
        opacity: 0.6;
    }

    .empty-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .empty-message {
        font-size: 12px;
        color: var(--text-light);
        max-width: 250px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* ================= + ANGGOTA BUTTON ================= */
    .add-member-button-container {
        position: fixed;
        bottom: 95px; /* Consistent distance from bottom */
        right: 16px;
        z-index: 1200;
        pointer-events: none;
    }

    .add-member-button {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0 20px;
        height: 52px; /* Fixed height */
        border-radius: 26px; /* Pill shape */
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 6px 16px rgba(10, 92, 54, 0.3);
        text-decoration: none !important;
        gap: 8px;
        white-space: nowrap;
        pointer-events: auto;
    }

    .add-member-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(10, 92, 54, 0.4);
    }

    .add-member-button:active {
        transform: scale(0.95);
    }


    /* ================= BOTTOM NAV ================= */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 0 10px;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 1100;
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

    /* ================= LAINNYA TAB ================= */
    .lainnya-section {
        background: white;
        padding: 0;
        margin-top: 0;
    }

    .removed-list {
        padding: 0;
    }

    .removed-item {
        display: flex;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid var(--border);
        gap: 12px;
        position: relative;
    }

    .badge-removed {
        background: #6c757d;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 8px;
        vertical-align: middle;
        font-weight: 600;
    }

    .user-subtitle {
        font-size: 12px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .more-actions {
        position: relative;
    }

    .btn-more {
        background: none;
        border: none;
        color: var(--text-dark);
        font-size: 18px;
        cursor: pointer;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .btn-more:hover {
        background: var(--light);
    }

    /* Modal Bottom Sheet */
    .bottom-sheet {
        position: fixed;
        bottom: -100%;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        background: white;
        z-index: 2000;
        border-radius: 20px 20px 0 0;
        transition: bottom 0.3s ease;
        box-shadow: 0 -4px 16px rgba(0,0,0,0.1);
    }

    .bottom-sheet.show {
        bottom: 0;
    }

    .sheet-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1999;
        display: none;
    }

    .sheet-overlay.show {
        display: block;
    }

    .sheet-header {
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sheet-handle {
        width: 40px;
        height: 4px;
        background: var(--light-gray);
        border-radius: 2px;
    }

    .sheet-body {
        padding: 0 0 20px;
    }

    .sheet-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        gap: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: var(--text);
    }

    .sheet-item:hover {
        background: var(--light);
    }

    .sheet-item-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--text-light);
    }

    .sheet-item-content {
        flex: 1;
    }

    .sheet-item-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .sheet-item-desc {
        font-size: 12px;
        color: var(--text-light);
    }

    .sheet-item-arrow {
        color: var(--text-lighter);
        font-size: 14px;
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

        .page-title h1 {
            font-size: 18px;
        }

        .page-title p {
            font-size: 12px;
        }

        .tab-button {
            padding: 8px 12px;
            font-size: 12px;
        }

        .empty-state {
            padding: 50px 16px;
        }

        .empty-icon {
            font-size: 36px;
        }

        .empty-title {
            font-size: 14px;
        }

        .empty-message {
            font-size: 11px;
            max-width: 220px;
        }

        .add-member-button-container {
            bottom: 90px;
            right: 12px;
        }

        .add-member-button {
            height: 48px;
            padding: 0 16px;
            font-size: 13px;
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
    }

    /* ================= CUSTOM CONFIRM MODAL ================= */
    .confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
        padding: 20px;
    }

    .confirm-overlay.active {
        display: flex;
    }

    .confirm-modal {
        background: white;
        width: 100%;
        max-width: 320px;
        border-radius: var(--radius-2xl);
        padding: 24px;
        text-align: center;
        transform: scale(0.9);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .confirm-overlay.active .confirm-modal {
        transform: scale(1);
        opacity: 1;
    }

    .confirm-icon {
        width: 60px;
        height: 60px;
        background: #f0faf5;
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 16px;
    }

    .confirm-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
    }

    .confirm-message {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.5;
        margin-bottom: 24px;
    }

    .confirm-actions {
        display: flex;
        gap: 12px;
    }

    .btn-confirm {
        flex: 1;
        padding: 12px;
        border-radius: var(--radius-lg);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-confirm-cancel {
        background: var(--light);
        color: var(--text-light);
    }

    .btn-confirm-ok {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-confirm-ok:active {
        transform: scale(0.98);
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

        .add-member-button-container {
            max-width: 480px;
            right: calc(50% - 240px + 16px);
        }

        .bottom-nav {
            max-width: 480px;
            border-radius: 0 0 var(--radius-2xl) var(--radius-2xl);
        }

        .confirm-overlay {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-2xl);
        }

        .loading-overlay {
            border-radius: 0; /* Full screen doesn't need border radius */
        }
    }




    /* ================= LOADING OVERLAY ================= */
    .loading-overlay {
        position: fixed; /* Changed from absolute to fixed for full desktop */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;


        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        color: white;
        text-align: center;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    .loading-text {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .loading-subtext {
        font-size: 12px;
        opacity: 0.8;
        max-width: 250px;
        line-height: 1.5;
        padding: 0 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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

        <!-- Tabs -->
        <div class="tabs-section">
            <div class="tabs-container">
                <button class="tab-button active" onclick="switchTab('request', this)">
                    Request
                </button>
                <button class="tab-button" onclick="switchTab('lainnya', this)">
                    Lainnya
                </button>
            </div>
        </div>

        <!-- Tab Content: Request (menampilkan request bergabung) -->
        <div id="requestTab" class="tab-content active">
            <div class="request-section">
                @if($pendingRequests->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <h3 class="empty-title">Belum ada permohonan bergabung</h3>
                        <p class="empty-message">
                            Permohonan bergabung di komunitas ini bakal muncul di sini, ya.
                        </p>
                    </div>
                @else
                    <div class="request-list">
                        @foreach($pendingRequests as $request)
                            <div class="request-item" id="request-{{ $request->id }}">
                                <div class="user-avatar">
                                    @if($request->user->avatar)
                                        <img src="{{ asset('storage/' . $request->user->avatar) }}" alt="{{ $request->user->name }}">
                                    @else
                                        @php
                                            $initials = strtoupper(substr($request->user->name, 0, 2));
                                            $bgColors = ['#F56565', '#ED8936', '#ECC94B', '#48BB78', '#38B2AC', '#4299E1', '#667EEA', '#9F7AEA', '#ED64A6'];
                                            $bgColor = $bgColors[ord(substr($request->user->name, 0, 1)) % count($bgColors)];
                                        @endphp
                                        <div style="width: 100%; height: 100%; background-color: {{ $bgColor }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                </div>
                                <div class="user-info">
                                    <h4 class="user-name">{{ $request->user->name }}</h4>
                                    <p class="user-meta">Meminta bergabung {{ $request->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="request-actions">
                                    <button class="btn-reject" onclick="handleRequest({{ $request->id }}, 'reject')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <button class="btn-approve" onclick="handleRequest({{ $request->id }}, 'accept')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab Content: Lainnya (menampilkan member yang dikeluarkan) -->
        <div id="lainnyaTab" class="tab-content">
            <div class="lainnya-section">
                @if($removedMembers->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="empty-title">Tidak ada data lain</h3>
                        <p class="empty-message">
                            Tidak ada data member yang dikeluarkan untuk ditampilkan.
                        </p>
                    </div>
                @else
                    <div class="removed-list">
                        @foreach($removedMembers as $rm)
                            <div class="removed-item" id="removed-{{ $rm->id }}">
                                <div class="user-avatar">
                                    @if($rm->user->avatar)
                                        <img src="{{ asset('storage/' . $rm->user->avatar) }}" alt="{{ $rm->user->name }}">
                                    @else
                                        @php
                                            $initials = strtoupper(substr($rm->user->name, 0, 2));
                                            $bgColors = ['#F56565', '#ED8936', '#ECC94B', '#48BB78', '#38B2AC', '#4299E1', '#667EEA', '#9F7AEA', '#ED64A6'];
                                            $bgColor = $bgColors[ord(substr($rm->user->name, 0, 1)) % count($bgColors)];
                                        @endphp
                                        <div style="width: 100%; height: 100%; background-color: {{ $bgColor }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                </div>
                                <div class="user-info">
                                    <h4 class="user-name">
                                        {{ $rm->user->name }}
                                        <span class="badge-removed">Dikeluarkan</span>
                                    </h4>
                                </div>
                                <div class="more-actions">
                                    <button class="btn-more" onclick="showSheet({{ $rm->id }}, '{{ $rm->user->name }}', {{ $rm->has_pending_invite ? 'true' : 'false' }})">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Bottom Sheet Modal -->
    <div class="sheet-overlay" id="sheetOverlay" onclick="hideSheet()"></div>
    <div class="bottom-sheet" id="bottomSheet">
        <div class="sheet-header">
            <div class="sheet-handle"></div>
        </div>
        <div class="sheet-body">
            <div class="sheet-item" id="reinviteBtn">
                <div class="sheet-item-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="sheet-item-content">
                    <div class="sheet-item-title">Kirim Ulang Undangan</div>
                    <div class="sheet-item-desc">Undang ulang partisipan hanya dapat dilakukan satu kali</div>
                </div>
                <div class="sheet-item-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
            
            <!-- Removed Bagikan Link button as per request -->
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 class="confirm-title" id="confirmTitle">Konfirmasi</h3>
            <p class="confirm-message" id="confirmMessage">Apakah Anda yakin?</p>
            <div class="confirm-actions">
                <button class="btn-confirm btn-confirm-cancel" id="confirmCancel">Batal</button>
                <button class="btn-confirm btn-confirm-ok" id="confirmOk">Oke</button>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAVIGATION -->
    <nav class="bottom-nav">
        <a href="{{ route('buyer.communities.show', $community->id) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <span class="nav-label">Profil</span>
        </a>
        
        <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="nav-label">Aktivitas</span>
        </a>
        
        <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="nav-item active">
            <div class="nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span class="nav-label">Anggota</span>
        </a>
        
        <a href="#" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <span class="nav-label">Kompetisi</span>
        </a>
        
        <a href="{{ route('buyer.communities.galeri', $community->id) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-images"></i>
            </div>
            <span class="nav-label">Galeri</span>
    </nav>
    <!-- Add Member Floating Button -->

    <div class="add-member-button-container">
        <a href="{{ route('buyer.communities.invite', $community->id) }}" class="add-member-button">
            <i class="fas fa-plus"></i>
            <span>Anggota</span>
        </a>
    </div>
</div>


<!-- LOADING OVERLAY - Moved outside mobile-container for full desktop coverage -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text" id="loadingText">Memproses...</div>
    <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
</div>
@endsection


@push('scripts')
<script>
    // Tab switching function
    function switchTab(tab, btn) {
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });

        // Add active class to clicked tab button
        if (btn) {
            btn.classList.add('active');
        }

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Show selected tab content
        document.getElementById(tab + 'Tab').classList.add('active');
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) existingToast.remove();

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;

        // Add styles for toast
        const toastStyles = document.createElement('style');
        toastStyles.textContent = `
            .toast {
                position: fixed;
                top: 100px;
                left: 50%;
                transform: translateX(-50%);
                background: white;
                padding: 12px 16px;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-lg);
                display: flex;
                align-items: center;
                gap: 8px;
                z-index: 9999;
                opacity: 0;
                transition: opacity 0.3s ease;
                border-left: 4px solid var(--${type});
                max-width: 90%;
                animation: slideDown 0.3s ease;
            }
            
            .toast.show {
                opacity: 1;
            }
            
            .toast i {
                color: var(--${type});
                font-size: 16px;
            }
            
            .toast span {
                font-size: 13px;
                font-weight: 500;
                color: var(--text);
            }
            
            @keyframes slideDown {
                from {
                    transform: translateX(-50%) translateY(-20px);
                    opacity: 0;
                }
                to {
                    transform: translateX(-50%) translateY(0);
                    opacity: 1;
                }
            }
        `;

        document.head.appendChild(toastStyles);
        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
                toastStyles.remove();
            }, 300);
        }, 3000);
    }

    // Custom Confirm Helper
    function showConfirm(title, message) {
        return new Promise((resolve) => {
            const overlay = document.getElementById('confirmOverlay');
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const okBtn = document.getElementById('confirmOk');
            const cancelBtn = document.getElementById('confirmCancel');

            titleEl.textContent = title;
            messageEl.textContent = message;
            overlay.classList.add('active');

            function handleClose(result) {
                overlay.classList.remove('active');
                okBtn.onclick = null;
                cancelBtn.onclick = null;
                resolve(result);
            }

            okBtn.onclick = () => handleClose(true);
            cancelBtn.onclick = () => handleClose(false);
            overlay.onclick = (e) => {
                if(e.target === overlay) handleClose(false);
            };
        });
    }

    // Function to handle join request
    async function handleRequest(requestId, action) {
        const confirmed = await showConfirm(
            'Konfirmasi', 
            `Apakah Anda yakin ingin ${action === 'accept' ? 'menerima' : 'menolak'} permintaan ini?`
        );
        
        if (!confirmed) return;

        // Show global loading
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) loadingOverlay.style.display = 'flex';

        const requestItem = document.getElementById(`request-${requestId}`);
        const actionsDiv = requestItem.querySelector('.request-actions');
        const originalActions = actionsDiv.innerHTML;


        fetch("{{ route('buyer.communities.handle-invite-request', $community->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                request_id: requestId,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                showToast(data.message, 'success');
                // Remove item with animation
                requestItem.style.transition = 'all 0.5s ease';
                requestItem.style.opacity = '0';
                requestItem.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    requestItem.remove();
                    // Check if list is empty
                    if (document.querySelectorAll('.request-item').length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }, 500);
            } else {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                showToast(data.message || 'Terjadi kesalahan', 'error');
                actionsDiv.innerHTML = originalActions;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            showToast('Terjadi kesalahan jaringan', 'error');
            actionsDiv.innerHTML = originalActions;
        });

    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Find the request tab button (first one or specific one)
        const requestBtn = document.querySelector('.tab-button');
        if (requestBtn) {
            requestBtn.click();
        }
    });

    // Bottom Sheet Controls
    let activeMemberId = null;

    function showSheet(memberId, userName, hasInvite = false) {
        activeMemberId = memberId;
        document.getElementById('sheetOverlay').classList.add('show');
        document.getElementById('bottomSheet').classList.add('show');
        
        const reinviteBtn = document.getElementById('reinviteBtn');
        const iconDiv = reinviteBtn.querySelector('.sheet-item-icon');
        const titleDiv = reinviteBtn.querySelector('.sheet-item-title');

        if (hasInvite) {
            iconDiv.innerHTML = '<i class="fas fa-trash"></i>';
            iconDiv.style.background = '#ffebee';
            iconDiv.style.color = '#dc3545';
            titleDiv.textContent = 'Batalkan Undangan';
            titleDiv.style.color = '#dc3545';
            
            reinviteBtn.onclick = function() {
                cancelInvite(memberId);
            };
        } else {
            iconDiv.innerHTML = '<i class="fas fa-paper-plane"></i>';
            iconDiv.style.background = 'transparent';
            iconDiv.style.color = '#0A5C36'; // Primary color
            titleDiv.textContent = 'Kirim Ulang Undangan';
            titleDiv.style.color = 'var(--text)';
            
            reinviteBtn.onclick = function() {
                reinviteMember(memberId);
            };
        }
    }

    function hideSheet() {
        document.getElementById('sheetOverlay').classList.remove('show');
        document.getElementById('bottomSheet').classList.remove('show');
        activeMemberId = null;
    }

    async function reinviteMember(memberId) {
        const confirmed = await showConfirm('Konfirmasi', 'Apakah Anda yakin ingin mengundang kembali member ini?');
        if (!confirmed) return;

        hideSheet();
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) loadingOverlay.style.display = 'flex';

        const url = "{{ route('buyer.communities.reinvite-member', ['community' => $community->id, 'memberId' => ':memberId']) }}".replace(':memberId', memberId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            if (data.success) {
                showToast(data.message, 'success');
                location.reload(); 
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            showToast('Terjadi kesalahan jaringan', 'error');
        });

    }

    async function cancelInvite(memberId) {
        const confirmed = await showConfirm('Konfirmasi', 'Apakah Anda yakin ingin membatalkan undangan ini?');
        if (!confirmed) return;

        hideSheet();
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) loadingOverlay.style.display = 'flex';

        const url = "{{ route('buyer.communities.cancel-invite', ['community' => $community->id, 'memberId' => ':memberId']) }}".replace(':memberId', memberId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            if (data.success) {
                showToast(data.message, 'success');
                location.reload(); // Reload to update state
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            showToast('Terjadi kesalahan jaringan', 'error');
        });

    }
</script>
@endpush

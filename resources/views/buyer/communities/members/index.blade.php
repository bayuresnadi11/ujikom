@extends('layouts.main', ['title' => 'Member - ' . (is_array($community) ? $community['name'] : $community->name)])

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

    /* ================= VISUAL HEADER REDESIGN ================= */
    .visual-header {
        position: relative;
        width: 100%;
        height: 240px;
        background-color: #333;
        background-size: cover;
        background-position: center;
        overflow: hidden;
    }
    
    .visual-header::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.1) 100%);
    }

    .visual-top-bar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 40px 16px 20px; /* Top padding for status bar */
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
    }

    .visual-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .visual-btn:hover {
        background: rgba(0,0,0,0.6);
        transform: scale(1.05);
    }

    .visual-actions-right {
        display: flex;
        gap: 12px;
    }

    .camera-btn {
        position: absolute;
        bottom: 16px;
        right: 16px;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 999; /* Increased z-index */
        font-size: 18px;
    }

    /* PROFILE SECTION */
    .visual-profile-section {
        position: relative;
        margin-top: -60px;
        padding: 0 20px 20px;
        text-align: center;
        z-index: 20;
    }

    .visual-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 16px;
        border-radius: 50%;
        padding: 4px;
        background: white; /* Border effect */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .visual-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #f0f0f0;
    }

    .visual-logo-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        color: var(--primary);
        font-size: 16px;
    }

    .visual-name {
        font-size: 24px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
    }

    .visual-meta {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .visual-meta span {
        position: relative;
    }

    .visual-meta span:not(:last-child)::after {
        content: '•';
        margin-left: 8px;
        color: #ccc;
    }

    .visual-main-action {
        margin-bottom: 24px;
    }

    .btn-chat-large {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 12px;
        background: transparent;
        color: var(--text);
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-chat-large:hover {
        background: #f8f9fa;
        border-color: #bbb;
    }

    .community-actions-banner {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .action-button-banner {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: var(--primary);
        padding: 10px 16px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        text-decoration: none;
    }

    .action-button-banner:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .action-button-banner i {
        font-size: 16px;
    }
    
    /* Background Modal Bottom Sheet */
    .bg-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
        display: none;
        align-items: flex-end;
        justify-content: center; /* Center content horizontally */
        backdrop-filter: blur(2px);
    }
    
    .bg-modal-overlay.active {
        display: flex;
    }
    
    .bg-sheet {
        width: 100%;
        max-width: 480px; /* Constrain width matches mobile container */
        background: white;
        border-radius: 20px 20px 0 0;
        padding: 24px 20px 40px;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .bg-modal-overlay.active .bg-sheet {
        transform: translateY(0);
    }
    
    .bg-option {
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
        padding: 16px 0;
        border: none;
        background: none;
        text-align: left;
        color: var(--text);
        font-size: 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .bg-option:last-child {
        border-bottom: none;
    }
    
    .bg-option i {
        font-size: 20px;
        width: 24px;
        text-align: center;
        color: var(--text);
    }
    
    .bg-handle {
        width: 40px;
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        margin: 0 auto 20px;
    }

    /* ================= HEADER LENGKAP DENGAN NOTIFIKASI ================= */
    .mobile-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: var(--gradient-dark);
        z-index: 1100;
        box-shadow: var(--shadow-md);
        max-width: 100%;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .logo {
        font-size: 18px;
        font-weight: 800;
        color: white;
        text-decoration: none;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .logo-icon {
        background: rgba(255, 255, 255, 0.2);
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .logo span {
        color: #a3e9c0;
        font-weight: 700;
    }

    .header-actions {
        display: flex;
        gap: 8px;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: white;
        padding: 6px;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .header-icon:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: var(--danger);
        color: white;
        font-size: 9px;
        font-weight: 800;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--primary);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* ================= SEARCH BAR IN HEADER ================= */
    .search-bar {
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .search-container {
        display: flex;
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        position: relative;
    }

    .search-container:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
        transform: translateY(-1px);
    }

    .search-input-header {
        flex: 1;
        padding: 10px 12px;
        border: none;
        background: transparent;
        font-size: 13px;
        outline: none;
        color: var(--text);
        font-weight: 500;
    }

    .search-input-header::placeholder {
        color: var(--text-light);
        opacity: 0.8;
    }

    .search-btn-header {
        background: var(--gradient-accent);
        color: #fff;
        border: none;
        padding: 0 16px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn-header:hover {
        opacity: 0.9;
    }

    /* ================= NOTIFICATION DROPDOWN ================= */
    .notification-dropdown {
        position: absolute;
        top: 60px;
        right: 16px;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        width: 320px;
        max-width: calc(100vw - 32px);
        z-index: 2000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        border: 1px solid var(--border);
    }

    .notification-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .notification-header {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--light);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .notification-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mark-all-read {
        background: none;
        border: none;
        color: var(--accent);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
    }

    .mark-all-read:hover {
        background: rgba(46, 204, 113, 0.1);
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding: 80px 16px 85px; /* Adjusted for visual header */
        min-height: 100vh;
    }

    /* ================= COMMUNITY HEADER ================= */
    .community-header {
        margin-bottom: 20px;
    }

    .community-cover {
        position: relative;
        width: 100%;
        height: 200px;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 16px;
    }

    .community-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .community-cover-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.4));
    }

    .community-back-btn {
        position: absolute;
        top: 16px;
        left: 16px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--primary);
        font-size: 16px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .community-back-btn:hover {
        background: var(--primary);
        color: white;
        transform: translateX(-2px);
    }

    .community-info-container {
        padding: 0 8px;
    }

    .community-title-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .community-name {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
    }

    .community-description {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .community-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .community-stat {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--light);
        color: var(--text);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid var(--border);
    }

    .community-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--accent);
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    /* ================= BUTTON PESAN ================= */
    .message-community-section {
        background: white;
        border-radius: var(--radius-lg);
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        text-align: center;
    }

    .message-community-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .message-community-btn {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }

    .message-community-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .message-community-btn:active {
        transform: translateY(0);
    }

    .message-community-btn i {
        font-size: 16px;
    }

    /* ================= SEARCH SECTION ================= */
    .search-section {
        margin-bottom: 20px;
    }

    .search-container {
        position: relative;
        width: 100%;
    }

    .search-input {
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

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 16px;
    }

    /* ================= MEMBERS LIST ================= */
    .members-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: var(--accent);
        font-size: 16px;
    }

    .member-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        background: transparent;
        margin-bottom: 0;
        border: none;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }

    .member-item:hover {
        background: rgba(0,0,0,0.02);
    }

    .member-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        margin-right: 12px;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
        border: none;
    }

    .member-avatar-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
        border: none;
    }

    .member-info {
        flex: 1;
        min-width: 0;
    }

    .member-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .member-username {
        font-size: 13px;
        color: #888;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 0;
    }

    .member-username i {
        font-size: 10px;
    }

    .member-role {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: var(--light);
        color: var(--text-light);
        border-radius: 16px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid var(--border);
    }

    .member-role.admin {
        background: rgba(243, 156, 18, 0.1);
        color: var(--warning);
        border-color: rgba(243, 156, 18, 0.2);
    }

    .member-role.anggota {
        background: rgba(46, 204, 113, 0.1);
        color: var(--success);
        border-color: rgba(46, 204, 113, 0.2);
    }

    .more-options {
        background: var(--light);
        border: 1px solid var(--border);
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-light);
        font-size: 12px;
        transition: all 0.2s ease;
        flex-shrink: 0;
        margin-left: 8px;
        z-index: 10;
        position: relative;
    }

    .more-options:hover {
        background: #f8f9fa;
        transform: scale(1.1);
    }

    /* INVITATION CARD STYLE */
    .invitation-card {
        display: flex;
        align-items: center;
        padding: 16px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #eee;
        margin-bottom: 20px;
        text-decoration: none;
        color: #333;
    }

    .invitation-card-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #333;
        margin-right: 12px;
    }

    .invitation-card-text {
        font-size: 16px;
        font-weight: 700;
        flex: 1;
    }

    .invitation-card-arrow {
        color: #333;
        font-size: 14px;
    }

    /* EMPTY STATE INVITATION */
    .empty-invitation-container {
        padding: 0 4px;
        margin-bottom: 30px;
    }

    .empty-invitation-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .empty-invitation-desc {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .btn-green-invite {
        background: #0A5C36;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-green-invite:hover {
        opacity: 0.9;
        color: white;
    }

    .count-badge {
        background: #0A5C36;
        color: white;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
        margin-left: 8px;
        font-weight: 700;
    }

    /* ================= MEMBER STATS ================= */
    .member-stats {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .member-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 6px 10px;
        background: var(--light);
        border-radius: var(--radius-sm);
        min-width: 50px;
    }

    .stat-number {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 10px;
        color: var(--text-light);
        font-weight: 600;
    }

    /* ================= ADMIN/ANGGOTA SEPARATION ================= */
    .admins-section {
        margin-bottom: 24px;
    }

    .admins-section .section-title {
        color: var(--warning);
        border-bottom: 2px solid var(--warning);
    }

    .anggota-section .section-title {
        color: var(--success);
        border-bottom: 2px solid var(--success);
    }

    .admin-item {
        border-left: none;
    }

    .anggota-item {
        border-left: none;
    }

    /* ================= POPUP OVERLAY ================= */
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 16px;
        animation: fadeIn 0.3s ease;
    }

    .popup-overlay.active {
        display: flex;
    }

    /* ================= ACTION POPUP ================= */
    .action-popup {
        background: white;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 320px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
        animation: modalIn 0.3s ease-out;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .popup-header {
        padding: 16px;
        text-align: center;
        border-bottom: 1px solid var(--border);
    }

    .popup-member {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .popup-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 20px;
        box-shadow: var(--shadow-md);
        border: 3px solid white;
    }

    .popup-avatar-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: var(--shadow-md);
        border: 3px solid white;
    }

    .popup-member-info h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 3px;
    }

    .popup-member-info p {
        color: var(--text-light);
        font-size: 12px;
    }

    .popup-actions {
        padding: 8px;
    }

    .action-button {
        width: 100%;
        padding: 12px 16px;
        background: none;
        border: none;
        text-align: left;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: var(--radius-md);
        margin-bottom: 6px;
    }

    .action-button:hover {
        background: var(--light);
        transform: translateX(3px);
    }

    .action-button i {
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .action-button.make-admin i {
        color: var(--warning);
    }

    .action-button.make-anggota i {
        color: var(--info);
    }

    .action-button.remove-member i {
        color: var(--danger);
    }

    .cancel-button {
        width: 100%;
        padding: 12px 16px;
        background: var(--light);
        border: 1px solid var(--border);
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        cursor: pointer;
        border-radius: var(--radius-md);
        margin-top: 8px;
        transition: all 0.2s ease;
    }

    .cancel-button:hover {
        background: var(--border);
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
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

    /* ================= BOTTOM NAV - MODIFIED ================= */
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

    /* ================= LOADING ================= */
    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(10, 92, 54, 0.1);
        border-top: 2px solid var(--accent);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 40px 16px;
        color: var(--text-lighter);
    }

    .empty-state i {
        font-size: 36px;
        margin-bottom: 12px;
        color: var(--border);
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 16px;
        color: var(--text-light);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 12px;
        margin-bottom: 16px;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.4;
    }

    /* ================= BOTTOM SHEET MODAL ================= */
    .bottom-sheet-modal {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        z-index: 2001; /* Above overlay */
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        padding-bottom: 20px;
        max-width: 480px; /* Constrain on desktop */
        margin: 0 auto;
        left: 50%;
        transform: translateX(-50%) translateY(100%);
    }

    .bottom-sheet-modal.active {
        transform: translateX(-50%) translateY(0);
    }

    .sheet-handle-bar {
        width: 100%;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .sheet-handle {
        width: 36px;
        height: 4px;
        background: #E0E0E0;
        border-radius: 2px;
    }

    .sheet-content {
        padding: 0 16px 16px;
    }

    /* Leave Menu Item */
    .sheet-menu-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
        cursor: pointer;
        color: var(--text);
        text-decoration: none;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .sheet-menu-item:last-child {
        border-bottom: none;
    }

    .sheet-menu-icon {
        width: 24px;
        font-size: 18px;
        margin-right: 12px;
        display: flex;
        justify-content: center;
    }

    .sheet-menu-text {
        font-size: 14px;
        font-weight: 500;
        flex: 1;
    }

    .sheet-menu-arrow {
        font-size: 12px;
        color: var(--text-lighter);
    }

    .text-danger {
        color: #e74c3c !important;
    }
    
    /* ================= NEW LEAVE CONFIRMATION MODAL (Image 3) ================= */
    .leave-confirm-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.95);
        background: white;
        width: 90%;
        max-width: 340px;
        border-radius: 16px;
        z-index: 2002;
        padding: 24px;
        text-align: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .leave-confirm-modal.active {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
    }

    .leave-confirm-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text);
    }

    .leave-confirm-text {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .leave-confirm-actions {
        display: flex;
        gap: 12px;
    }

    .btn-confirm-cancel {
        flex: 1;
        padding: 10px;
        background: white;
        border: 1px solid var(--danger);
        color: var(--danger);
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-confirm-leave {
        flex: 1;
        padding: 10px;
        background: #A00000; /* Darker red per image */
        border: none;
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }

    /* ================= REMOVE CONFIRMATION MODAL ================= */
    .remove-confirmation-modal {
        max-width: 400px;
    }

    .remove-confirmation-modal .popup-header {
        background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
        color: var(--danger);
    }

    .remove-confirmation-content {
        padding: 16px;
    }

    .remove-warning {
        background: #FFF3E0;
        border: 1px solid #FFB74D;
        border-radius: var(--radius-md);
        padding: 12px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .remove-warning i {
        color: var(--warning);
        font-size: 20px;
        flex-shrink: 0;
    }

    .remove-warning p {
        font-size: 13px;
        color: #E65100;
        margin: 0;
        font-weight: 600;
    }

    .message-input-section {
        margin-bottom: 20px;
    }

    .message-input-section label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text);
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .message-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 14px;
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .message-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
    }

    .message-textarea::placeholder {
        color: var(--text-light);
        opacity: 0.7;
    }

    .char-counter {
        display: flex;
        justify-content: space-between;
        margin-top: 6px;
        font-size: 11px;
        color: var(--text-light);
    }

    .char-counter.warning {
        color: var(--warning);
    }

    .char-counter.error {
        color: var(--danger);
    }

    .remove-reason-select {
        margin-top: 16px;
    }

    .reason-options {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
    }

    .reason-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        background: var(--light);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid var(--border);
    }

    .reason-option:hover {
        background: rgba(10, 92, 54, 0.05);
        border-color: var(--primary);
    }

    .reason-option input[type="radio"] {
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .reason-option span {
        font-size: 12px;
        color: var(--text);
        flex: 1;
    }

    .remove-actions {
        display: flex;
        gap: 8px;
        margin-top: 20px;
    }

    .remove-actions .cancel-button {
        flex: 1;
        margin-top: 0;
        background: var(--light);
        color: var(--text);
    }

    .remove-actions .remove-button {
        flex: 1;
        background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .remove-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .remove-button:active {
        transform: translateY(0);
    }

    .remove-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 360px) {
        .header-top {
            padding: 10px 12px;
        }
        
        .logo {
            font-size: 16px;
        }
        
        .logo-icon {
            width: 28px;
            height: 28px;
            font-size: 14px;
        }
        
        .header-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }
        
        .notification-badge {
            width: 14px;
            height: 14px;
            font-size: 8px;
        }
        
        .search-bar {
            padding: 10px 12px;
        }
        
        .search-input-header {
            padding: 8px 10px;
            font-size: 12px;
        }
        
        .search-btn-header {
            padding: 0 12px;
        }
        
        .notification-dropdown {
            width: 280px;
            right: 12px;
        }
        
        .notification-item {
            padding: 10px 12px;
        }
        
        .notification-icon {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        
        .notification-title-item {
            font-size: 12px;
        }
        
        .notification-message {
            font-size: 10px;
        }

        .main-content {
            padding: 70px 12px 75px; /* Adjusted for custom nav */
        }

        .community-cover {
            height: 180px;
        }

        .community-name {
            font-size: 20px;
        }

        .community-description {
            font-size: 13px;
        }

        .message-community-section {
            padding: 12px;
        }

        .message-community-btn {
            padding: 10px 16px;
            font-size: 13px;
        }

        .search-input {
            padding: 10px 14px 10px 40px;
            font-size: 13px;
        }

        .search-icon {
            left: 14px;
            font-size: 14px;
        }

        .section-title {
            font-size: 15px;
        }

        .member-item {
            padding: 10px;
        }

        .member-avatar,
        .member-avatar-img {
            width: 45px;
            height: 45px;
            margin-right: 10px;
        }

        .member-name {
            font-size: 14px;
        }

        .member-username {
            font-size: 11px;
        }

        .member-role {
            font-size: 10px;
            padding: 3px 8px;
        }

        .more-options {
            width: 30px;
            height: 30px;
            font-size: 11px;
        }

        .member-stats {
            gap: 6px;
        }

        .member-stat {
            padding: 5px 8px;
            min-width: 45px;
        }

        .stat-number {
            font-size: 11px;
        }

        .stat-label {
            font-size: 9px;
        }

        .popup-avatar,
        .popup-avatar-img {
            width: 50px;
            height: 50px;
        }

        .popup-member-info h3 {
            font-size: 16px;
        }

        .popup-member-info p {
            font-size: 11px;
        }

        .action-button {
            padding: 10px 12px;
            font-size: 13px;
        }

        .action-button i {
            font-size: 14px;
        }

        .cancel-button {
            padding: 10px 12px;
            font-size: 13px;
        }

        .toast-container {
            left: 12px;
            right: 12px;
            max-width: calc(100% - 24px);
        }

        /* Custom nav adjustments for small screens */
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

        .loading {
            width: 14px;
            height: 14px;
        }

        .empty-state {
            padding: 30px 12px;
        }

        .empty-state i {
            font-size: 28px;
        }

        .empty-state h3 {
            font-size: 14px;
        }

        .empty-state p {
            font-size: 11px;
            max-width: 220px;
        }

        .remove-confirmation-modal {
            max-width: 300px;
        }

        .remove-actions {
            flex-direction: column;
        }

        .message-textarea {
            min-height: 80px;
            font-size: 13px;
        }
    }

    @media (min-width: 480px) {
        .mobile-container {
            max-width: 480px;
            margin: 10px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: var(--radius-2xl);
            overflow: hidden;
        }

        .mobile-header {
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
    }

    /* ================= LOADING OVERLAY ================= */
    .loading-overlay {
        position: fixed;
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
        animation: spin-overlay 1s linear infinite;
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

    @keyframes spin-overlay {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        z-index: 11000;
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
        .confirm-overlay {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-2xl);
        }
    }
</style>
@endpush

@section('content')
<div class="mobile-container">
    @php
        // Menangani kedua kemungkinan (array dan object)
        $logo = is_array($community) ? ($community['logo'] ?? $community['image'] ?? null) : ($community->logo ?? $community->image ?? null);
        $backgroundImage = is_array($community) ? ($community['background_image'] ?? null) : ($community->background_image ?? null);
        
        // Use gradient as fallback instead of missing image file
        $backgroundUrl = $backgroundImage 
            ? (str_starts_with($backgroundImage, 'http') 
                ? $backgroundImage 
                : asset('storage/' . $backgroundImage))
            : '';
        
        $hasBackgroundImage = !empty($backgroundImage);
        
        $logoUrl = $logo 
            ? (str_starts_with($logo, 'http') 
                ? $logo 
                : asset('storage/' . $logo))
            : asset('images/default-logo.png');
            
        $communityName = is_array($community) ? ($community['name'] ?? 'Komunitas') : ($community->name ?? 'Komunitas');
        $communityDesc = is_array($community) ? ($community['description'] ?? '') : ($community->description ?? '');
        $communityId = is_array($community) ? ($community['id'] ?? 0) : ($community->id ?? 0);
        $communityType = is_array($community) ? ($community['type'] ?? 'public') : ($community->type ?? 'public');
        $communityCategory = is_array($community) ? ($community['category'] ?? 'Olahraga') : ($community->category->category_name ?? 'Olahraga');
        $isManager = is_array($community) ? ($community['is_manager'] ?? false) : ($community->is_manager ?? false);
        $currentUserRole = is_array($community) ? ($community['current_user_role'] ?? 'member') : ($community->current_user_role ?? 'member');
        $communityCreatedBy = is_array($community) ? ($community['created_by'] ?? 0) : ($community->created_by ?? 0);
        $communityLocation = is_array($community) ? ($community['location'] ?? null) : ($community->location ?? null);
    @endphp

    <!-- ================= VISUAL HEADER ================= -->
    <div class="visual-header" 
         @if($hasBackgroundImage)
         style="background-image: url('{{ $backgroundUrl }}');"
         @else
         style="background: linear-gradient(135deg, #0A5C36 0%, #08472a 100%);"
         @endif
         id="visualHeader">
        <div class="visual-top-bar">
            <a href="{{ route('buyer.communities.show', $communityId) }}" class="visual-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div class="visual-actions-right">
                <button class="visual-btn">
                    <i class="fas fa-share-alt"></i>
                </button>
                @if($isManager)
                <a href="{{ route('buyer.communities.edit', $communityId) }}" class="visual-btn">
                    <i class="fas fa-cog"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="visual-profile-section">
        <div class="visual-avatar-wrapper">
            <img src="{{ $logoUrl }}" class="visual-avatar" alt="Logo">
        </div>
        
        <h1 class="visual-name">{{ $communityName }}</h1>
        <div class="visual-meta">
            <span>{{ $communityCategory }}</span>
            <span>{{ ucfirst($communityType) }}</span>
            <span>{{ $communityLocation ?? 'Belum ada lokasi' }}</span>
        </div>
        
        <div class="visual-main-action">
            <a href="{{ route('buyer.communities.chat', $communityId) }}" class="btn-chat-large">
                <i class="far fa-comment-dots"></i> Chat
            </a>
        </div>
        
        @if($isManager)
        <a href="{{ route('buyer.communities.invite-anggota', $communityId) }}" class="invitation-card">
            <div class="invitation-card-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="invitation-card-text">Undangan Anggota</div>
            <div class="invitation-card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
        @endif
    </div>

    <main class="main-content" style="padding-top: 0;">

        <!-- ================= SEARCH SECTION ================= -->
        <div class="search-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    class="search-input" 
                    id="searchInput" 
                    placeholder="Cari nama anggota..."
                >
            </div>
        </div>

        <!-- ================= MEMBERS LIST ================= -->
        <div id="membersList">
            <!-- ================= ADMINS SECTION ================= -->
            @php
                // Filter admin dan anggota dari data members
                $admins = collect($members)->filter(function($member) {
                    $role = is_array($member) ? ($member['role'] ?? 'anggota') : ($member->role ?? 'anggota');
                    return $role === 'admin';
                });
                
                $anggota = collect($members)->filter(function($member) {
                    $role = is_array($member) ? ($member['role'] ?? 'anggota') : ($member->role ?? 'anggota');
                    return $role === 'anggota';
                });
            @endphp
            
            @if($admins->count() > 0)
            <div class="admins-section">
                <h2 style="font-size: 18px; font-weight: 700; color: #333; margin-bottom: 20px; border: none; display: flex; align-items: center;">
                    Admin <span class="count-badge">{{ $admins->count() }}</span>
                </h2>
                
                @foreach($admins as $member)
                @php
                    // Menangani data member (array atau object)
                    $memberId = is_array($member) ? ($member['id'] ?? 0) : ($member->id ?? 0);
                    $userId = is_array($member) ? ($member['user_id'] ?? 0) : ($member->user_id ?? 0);
                    
                    // Mengambil data user dengan benar
                    if (is_array($member)) {
                        $user = $member['user'] ?? $member;
                        $memberName = $user['name'] ?? ($member['name'] ?? 'User');
                        $username = $user['username'] ?? ($user['email'] ?? ($member['username'] ?? ($member['email'] ?? '')));
                        $avatar = $user['avatar'] ?? $member['avatar'] ?? null;
                    } else {
                        $user = $member->user ?? $member;
                        $memberName = $user->name ?? ($member->name ?? 'User');
                        $username = $user->username ?? ($user->email ?? ($member->username ?? ($member->email ?? '')));
                        $avatar = $user->avatar ?? $member->avatar ?? null;
                    }
                    
                    $joinedAt = is_array($member) ? ($member['joined_at'] ?? date('Y-m-d')) : ($member->joined_at ?? date('Y-m-d'));
                    
                    // Format tanggal
                    $joinedDate = \Carbon\Carbon::parse($joinedAt)->format('d M Y');
                    
                    // Avatar handling
                    if ($avatar) {
                        $avatarUrl = str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar);
                    } else {
                        $avatarUrl = null;
                        $initials = strtoupper(substr($memberName, 0, 2));
                        $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
                        $colorIndex = crc32($memberName) % count($colors);
                        $avatarColor = $colors[$colorIndex];
                    }
                @endphp
                
                <div class="member-item admin-item" data-member-id="{{ $memberId }}" data-user-id="{{ $userId }}">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" class="member-avatar-img" alt="{{ $memberName }}">
                    @else
                        <div class="member-avatar" style="background-color: {{ $avatarColor }};">
                            {{ $initials }}
                        </div>
                    @endif
                    
                    <div class="member-info" onclick="viewMemberDetail({{ $userId }})" style="cursor: pointer;">
                        <div class="member-name">{{ $memberName }}</div>
                        <div class="member-username">
                            {{ str_replace('@', '', $username) }}
                        </div>
                    </div>

                    {{-- Tampilkan tombol options hanya untuk admin yang melihat admin lain --}}
                    @if($isManager && $userId !== Auth::id())
                        @if(in_array($currentUserRole, ['manager', 'admin']))
                        <div class="more-options" onclick="showActionPopup(event, {{ $memberId }})">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($anggota->count() == 0 && $isManager)
            <div class="empty-invitation-container">
                <h2 class="empty-invitation-title">Anggota</h2>
                <p class="empty-invitation-desc">
                    Yuk, mulai undang teman untuk bergabung di komunitasmu sekarang!
                </p>
                <a href="{{ route('buyer.communities.invite-anggota', $communityId) }}" class="btn-green-invite">
                    Undang Anggota <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endif

            <!-- ================= ANGGOTA SECTION ================= -->
            @if($anggota->count() > 0)
            <div class="anggota-section">
                <h2 style="font-size: 18px; font-weight: 700; color: #333; margin-bottom: 20px; border: none; display: flex; align-items: center;">
                    Anggota <span class="count-badge">{{ $anggota->count() }}</span>
                </h2>
                
                @foreach($anggota as $member)
                @php
                    // Menangani data member (array atau object)
                    $memberId = is_array($member) ? ($member['id'] ?? 0) : ($member->id ?? 0);
                    $userId = is_array($member) ? ($member['user_id'] ?? 0) : ($member->user_id ?? 0);
                    
                    // Mengambil data user dengan benar
                    if (is_array($member)) {
                        $user = $member['user'] ?? $member;
                        $memberName = $user['name'] ?? ($member['name'] ?? 'User');
                        $username = $user['username'] ?? ($user['email'] ?? ($member['username'] ?? ($member['email'] ?? '')));
                        $avatar = $user['avatar'] ?? $member['avatar'] ?? null;
                    } else {
                        $user = $member->user ?? $member;
                        $memberName = $user->name ?? ($member->name ?? 'User');
                        $username = $user->username ?? ($user->email ?? ($member->username ?? ($member->email ?? '')));
                        $avatar = $user->avatar ?? $member->avatar ?? null;
                    }
                    
                    $joinedAt = is_array($member) ? ($member['joined_at'] ?? date('Y-m-d')) : ($member->joined_at ?? date('Y-m-d'));
                    
                    // Format tanggal
                    $joinedDate = \Carbon\Carbon::parse($joinedAt)->format('d M Y');
                    
                    // Avatar handling
                    if ($avatar) {
                        $avatarUrl = str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar);
                    } else {
                        $avatarUrl = null;
                        $initials = strtoupper(substr($memberName, 0, 2));
                        $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
                        $colorIndex = crc32($memberName) % count($colors);
                        $avatarColor = $colors[$colorIndex];
                    }
                @endphp
                
                <div class="member-item anggota-item" data-member-id="{{ $memberId }}" data-user-id="{{ $userId }}">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" class="member-avatar-img" alt="{{ $memberName }}">
                    @else
                        <div class="member-avatar" style="background-color: {{ $avatarColor }};">
                            {{ $initials }}
                        </div>
                    @endif
                    
                    <div class="member-info" onclick="viewMemberDetail({{ $userId }})" style="cursor: pointer;">
                        <div class="member-name">{{ $memberName }}</div>
                        <div class="member-username">
                            {{ str_replace('@', '', $username) }}
                        </div>
                    </div>

                    {{-- Tampilkan tombol options untuk admin OR untuk diri sendiri (buat leave) --}}
                    {{-- Tampilkan tombol options untuk admin OR untuk diri sendiri (buat leave) --}}
                    @if(($isManager && $userId !== Auth::id()) || ($userId === Auth::id() && $communityCreatedBy !== Auth::id()))
                    <div class="more-options" onclick="showActionPopup(event, {{ $memberId }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- ================= NO RESULTS MESSAGE ================= -->
        <div id="noResults" style="display: none; text-align: center; padding: 40px 20px;">
            <div style="font-size: 48px; color: var(--light-gray); margin-bottom: 15px;">
                <i class="fas fa-search"></i>
            </div>
            <p style="color: var(--text-light); font-size: 14px;">
                Tidak ada member yang ditemukan
            </p>
        </div>
    </main>

    <!-- ================= ACTION POPUP ================= -->
    <div class="popup-overlay" id="popupOverlay" onclick="closePopup()">
        <div class="action-popup" onclick="event.stopPropagation()">
            <div class="popup-header">
                <div class="popup-member" id="popupMemberInfo">
                    <!-- Data akan diisi via JavaScript -->
                </div>
            </div>
            
            <div class="popup-actions" id="popupActions">
                <!-- Aksi akan diisi via JavaScript -->
            </div>
            
            <button class="cancel-button" onclick="closePopup()">
                Batal
            </button>
        </div>
    </div>

    <!-- ================= REMOVE CONFIRMATION MODAL ================= -->
    <div class="popup-overlay" id="removeConfirmationModal" onclick="closeRemoveConfirmationModal()">
        <div class="action-popup remove-confirmation-modal" onclick="event.stopPropagation()">
            <div class="popup-header">
                <h3 style="color: var(--danger); font-size: 18px; margin: 0;">
                    <i class="fas fa-exclamation-triangle"></i>
                    Keluarkan Anggota
                </h3>
            </div>
            
            <div class="remove-confirmation-content">
                <div class="remove-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Anda akan mengeluarkan anggota dari komunitas. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                
                <div class="message-input-section">
                    <label for="removeMessage">
                        <i class="fas fa-comment-alt"></i>
                        Pesan untuk anggota (wajib diisi)
                    </label>
                    <textarea 
                        id="removeMessage" 
                        class="message-textarea" 
                        placeholder="Berikan alasan mengapa Anda mengeluarkan anggota ini..."
                        maxlength="500"
                    ></textarea>
                    <div class="char-counter" id="charCounter">0/500 karakter</div>
                </div>
                
                <div class="remove-reason-select">
                    <label style="font-weight: 600; color: var(--text); font-size: 13px; margin-bottom: 8px; display: block;">
                        <i class="fas fa-list-alt"></i>
                        Pilih alasan utama (opsional)
                    </label>
                    <div class="reason-options">
                        <label class="reason-option">
                            <input type="radio" name="removeReason" value="pelanggaran_aturan">
                            <span>Pelanggaran aturan komunitas</span>
                        </label>
                        <label class="reason-option">
                            <input type="radio" name="removeReason" value="tidak_aktif">
                            <span>Tidak aktif dalam waktu lama</span>
                        </label>
                        <label class="reason-option">
                            <input type="radio" name="removeReason" value="perilaku_tidak_sopan">
                            <span>Perilaku tidak sopan</span>
                        </label>
                        <label class="reason-option">
                            <input type="radio" name="removeReason" value="lainnya">
                            <span>Lainnya</span>
                        </label>
                    </div>
                </div>
                
                <div class="remove-actions">
                    <button class="cancel-button" onclick="closeRemoveConfirmationModal()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button class="remove-button" onclick="confirmRemoveMember()" id="confirmRemoveBtn">
                        <i class="fas fa-user-slash"></i> Keluarkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MESSAGE MODAL ================= -->
    <div class="popup-overlay" id="messageModal" onclick="closeMessageModal()">
        <div class="action-popup" onclick="event.stopPropagation()" style="max-width: 400px;">
            <div class="popup-header">
                <h3 style="color: var(--primary); font-size: 18px; margin: 0;">
                    <i class="fas fa-comment-dots"></i>
                    Kirim Pesan ke Komunitas
                </h3>
            </div>
            
            <div style="padding: 16px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text); font-size: 13px;">
                        <i class="fas fa-edit"></i> Pesan Anda
                    </label>
                    <textarea 
                        id="messageText" 
                        placeholder="Tulis pesan Anda untuk komunitas {{ $communityName }}..."
                        style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 14px; min-height: 120px; resize: vertical; font-family: inherit;"
                    ></textarea>
                    <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                        <small style="color: var(--text-light); font-size: 11px;">
                            <i class="fas fa-info-circle"></i> Pesan akan dikirim ke semua member
                        </small>
                        <small style="color: var(--text-light); font-size: 11px;" id="charCount">
                            0/500
                        </small>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text); font-size: 13px;">
                        <i class="fas fa-bell"></i> Tipe Pesan
                    </label>
                    <div style="display: flex; gap: 8px; margin-top: 8px;">
                        <label style="display: flex; align-items: center; gap: 6px; padding: 8px 12px; background: var(--light); border-radius: var(--radius-md); flex: 1; cursor: pointer;">
                            <input type="radio" name="messageType" value="announcement" checked style="accent-color: var(--accent);">
                            <span style="font-size: 12px; font-weight: 600;">
                                <i class="fas fa-bullhorn" style="color: var(--warning);"></i>
                                Pengumuman
                            </span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; padding: 8px 12px; background: var(--light); border-radius: var(--radius-md); flex: 1; cursor: pointer;">
                            <input type="radio" name="messageType" value="reminder" style="accent-color: var(--accent);">
                            <span style="font-size: 12px; font-weight: 600;">
                                <i class="fas fa-clock" style="color: var(--info);"></i>
                                Pengingat
                            </span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button 
                        class="cancel-button" 
                        onclick="closeMessageModal()"
                        style="flex: 1; background: var(--light); color: var(--text);"
                    >
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button 
                        onclick="sendCommunityMessage()" 
                        style="flex: 1; background: var(--gradient-primary); color: white; border: none; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"
                    >
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= CUSTOM BOTTOM NAVIGATION ================= -->
    <nav class="bottom-nav">
        <!-- Profil - Mengarah ke halaman show.blade.php -->
        <a href="{{ route('buyer.communities.show', $communityId) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <span class="nav-label">Profil</span>
        </a>
        
        <!-- Aktivitas -->
        <a href="{{ route('buyer.communities.aktivitas', $communityId) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="nav-label">Aktivitas</span>
        </a>
        
        <!-- Anggota - Active karena di halaman ini -->
        <a href="{{ route('buyer.communities.members.index', $communityId) }}" class="nav-item active">
            <div class="nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span class="nav-label">Anggota</span>
        </a>
        
        <!-- Kompetisi (sementara kosong) -->
        <a href="#" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <span class="nav-label">Kompetisi</span>
        </a>
        
        <!-- Galeri -->
        <a href="{{ route('buyer.communities.galeri', $communityId) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-images"></i>
            </div>
            <span class="nav-label">Galeri</span>
        </a>
    </nav>
</div>



    <!-- ================= BOTTOM SHEET OVERLAY ================= -->
    <div class="popup-overlay" id="bottomSheetOverlay" onclick="closeSelfActionBottomSheet()"></div>

    <!-- ================= SELF ACTION BOTTOM SHEET (Image 2) ================= -->
    <div class="bottom-sheet-modal" id="selfActionBottomSheet">
        <div class="sheet-handle-bar" onclick="closeSelfActionBottomSheet()">
            <div class="sheet-handle"></div>
        </div>
        <div class="sheet-content">
            <div class="sheet-menu-item" onclick="openLeaveConfirmation()">
                <div class="sheet-menu-icon">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </div>
                <div class="sheet-menu-text text-danger">
                    Keluar dari Komunitas
                </div>
                <div class="sheet-menu-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= LEAVE CONFIRMATION MODAL (Image 3) ================= -->
    <div class="popup-overlay" id="leaveConfirmationOverlay" onclick="closeLeaveConfirmationModal()">
        <div class="leave-confirm-modal" id="leaveConfirmationModal" onclick="event.stopPropagation()">
            <div class="leave-confirm-title">
                Keluar dari komunitas
            </div>
            <div class="leave-confirm-text">
                Apakah Kamu yakin ingin keluar dari {{ is_array($community) ? ($community['name'] ?? 'Komunitas') : ($community->name ?? 'Komunitas') }}
            </div>
            <div class="leave-confirm-actions">
                <button class="btn-confirm-cancel" onclick="closeLeaveConfirmationModal()">
                    Batal
                </button>
                <button class="btn-confirm-leave" onclick="confirmLeaveCommunity()" id="confirmLeaveBtn">
                    ya, keluar
                </button>
            </div>
        </div>
    </div>
    <!-- ================= LOADING OVERLAY ================= -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
    </div>
                

    <!-- ================= TOAST CONTAINER ================= -->
    <div id="toastContainer"></div>

    <!-- ================= CUSTOM CONFIRM MODAL ================= -->
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 class="confirm-title" id="confirmTitle">Konfirmasi</h3>
            <p class="confirm-message" id="confirmMessage"></p>
            <div class="confirm-actions">
                <button class="btn-confirm btn-confirm-cancel" id="confirmCancel">Batal</button>
                <button class="btn-confirm btn-confirm-ok" id="confirmOk">Oke</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentMemberId = null;
    let currentUserId = null;
    let currentMemberRole = null;
    let currentMemberName = null;
    const communityId = {{ $communityId }};
    const currentUserIdAuth = {{ Auth::id() ?? 'null' }};
    const currentUserRole = '{{ $currentUserRole }}';
    const members = @json($members);
    const csrfToken = '{{ csrf_token() }}';

    // ================= LOADING FUNCTIONS =================
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

    // ================= CUSTOM CONFIRM HELPER =================
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

    // ================= SEARCH FUNCTIONALITY =================
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const memberItems = document.querySelectorAll('.member-item');
        let visibleCount = 0;

        memberItems.forEach(item => {
            const memberName = item.querySelector('.member-name').textContent.toLowerCase();
            const username = item.querySelector('.member-username').textContent.toLowerCase();
            
            if (memberName.includes(searchTerm) || username.includes(searchTerm)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        const noResults = document.getElementById('noResults');
        if (visibleCount === 0 && searchTerm.length > 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    });

    // ================= ACTION POPUP FUNCTIONS =================
    function showActionPopup(event, memberId) {
        event.stopPropagation();
        event.preventDefault();
        currentMemberId = memberId;
        
        const member = members.find(m => m.id === memberId);
        if (!member) return;
        
        currentUserId = member.user_id;
        currentMemberRole = member.role;
        
        // Ambil nama user dengan benar
        if (member.user && member.user.name) {
            currentMemberName = member.user.name;
        } else if (member.name) {
            currentMemberName = member.name;
        } else {
            currentMemberName = 'User';
        }

        // CHECK IF SELF ACTION - SHOW BOTTOM SHEET INSTEAD
        if (currentUserId === currentUserIdAuth) {
             document.getElementById('selfActionBottomSheet').classList.add('active');
             document.body.style.overflow = 'hidden';
             
             // Add backdrop if not exists/handled by generic overlay
             // We use a dedicated overlay to avoid showing the default action-popup (which has the "Batal" button)
             document.getElementById('bottomSheetOverlay').classList.add('active');
             
             return; // Stop here, don't build the standard popup
        }
        
        // Siapkan data untuk popup
        const user = member.user || {};
        const avatar = user.avatar || member.avatar || null;
        const username = user.username || user.email || member.username || member.email || '';
        
        // Avatar handling
        let avatarHtml = '';
        if (avatar && (avatar.startsWith('http') || avatar.startsWith('/storage/'))) {
            const avatarUrl = avatar.startsWith('http') ? avatar : `{{ asset('') }}${avatar.replace('/storage/', 'storage/')}`;
            avatarHtml = `<img src="${avatarUrl}" class="popup-avatar-img" alt="${currentMemberName}">`;
        } else {
            const initials = currentMemberName.substring(0, 2).toUpperCase();
            const colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
            const colorIndex = hashCode(currentMemberName) % colors.length;
            avatarHtml = `<div class="popup-avatar" style="background-color: ${colors[colorIndex]}">${initials}</div>`;
        }
        
        document.getElementById('popupMemberInfo').innerHTML = `
            ${avatarHtml}
            <div class="popup-member-info">
                <h3>${currentMemberName}</h3>
                <p>${username.replace('@', '')} • ${currentMemberRole === 'admin' ? 'Admin' : 'Anggota'}</p>
            </div>
        `;
        
        // Tentukan aksi yang tersedia berdasarkan role member
        let actionsHtml = '';
        
        // SELF ACTIONS
        if (currentUserId === currentUserIdAuth) {
             actionsHtml = `
                <button class="action-button remove-member" onclick="showLeaveConfirmationModal()" style="color: var(--danger);">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar dari Komunitas</span>
                </button>
             `;
        } 
        else if (currentMemberRole === 'anggota') {
            // Untuk anggota biasa
            actionsHtml = `
                ${currentUserRole === 'admin' ? `
                <button class="action-button make-admin" onclick="makeAdmin()">
                    <i class="fas fa-user-shield"></i>
                    <span>Jadikan Admin</span>
                </button>
                ` : ''}
                <button class="action-button remove-member" onclick="showRemoveConfirmationModal()">
                    <i class="fas fa-user-slash"></i>
                    <span>Keluarkan Anggota</span>
                </button>
            `;
        } else if (currentMemberRole === 'admin') {
            // Untuk admin, hanya jika bukan diri sendiri
            if (currentUserId !== currentUserIdAuth) {
                actionsHtml = `
                    ${currentUserRole === 'admin' ? `
                    <button class="action-button make-anggota" onclick="makeAnggota()">
                        <i class="fas fa-user"></i>
                        <span>Jadikan Anggota</span>
                    </button>
                    ` : ''}
                    <button class="action-button remove-member" onclick="showRemoveConfirmationModal()">
                        <i class="fas fa-user-slash"></i>
                        <span>Keluarkan Anggota</span>
                    </button>
                `;
            } else {
                // Should not reach here due to top if, but safe fallback
                actionsHtml = `
                    <div style="text-align: center; padding: 20px; color: var(--text-light);">
                        <i class="fas fa-user" style="font-size: 24px; margin-bottom: 10px;"></i>
                        <p>Ini adalah akun Anda</p>
                    </div>
                `;
            }
        }
        
        document.getElementById('popupActions').innerHTML = actionsHtml;
        document.getElementById('popupOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePopup(clearData = true) {
        document.getElementById('popupOverlay').classList.remove('active');
        document.body.style.overflow = '';
        
        if (clearData) {
            currentMemberId = null;
            currentUserId = null;
            currentMemberRole = null;
            currentMemberName = null;
        }
    }

    // ================= REMOVE CONFIRMATION MODAL FUNCTIONS =================
    function showRemoveConfirmationModal() {
        // Tutup action popup dulu tapi jangan hapus data ID nya
        closePopup(false);
        
        // Reset form
        document.getElementById('removeMessage').value = '';
        document.getElementById('charCounter').textContent = '0/500 karakter';
        document.getElementById('charCounter').className = 'char-counter';
        
        // Reset radio buttons
        document.querySelectorAll('input[name="removeReason"]').forEach(radio => {
            radio.checked = false;
        });
        
        // Tampilkan modal
        const modal = document.getElementById('removeConfirmationModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('removeMessage').focus();
        }, 300);
    }

    function closeRemoveConfirmationModal() {
        const modal = document.getElementById('removeConfirmationModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ================= CHARACTER COUNT FOR REMOVE MESSAGE =================
    document.getElementById('removeMessage')?.addEventListener('input', function(e) {
        const charCount = e.target.value.length;
        const charCounter = document.getElementById('charCounter');
        const confirmBtn = document.getElementById('confirmRemoveBtn');
        
        charCounter.textContent = `${charCount}/500 karakter`;
        
        // Update counter color based on length
        if (charCount === 0) {
            charCounter.className = 'char-counter';
            if (confirmBtn) confirmBtn.disabled = true;
        } else if (charCount > 0 && charCount <= 10) {
            charCounter.className = 'char-counter warning';
            if (confirmBtn) confirmBtn.disabled = false;
        } else if (charCount > 10 && charCount <= 500) {
            charCounter.className = 'char-counter';
            if (confirmBtn) confirmBtn.disabled = false;
        } else if (charCount > 500) {
            charCounter.className = 'char-counter error';
            // Trim to 500 characters
            e.target.value = e.target.value.substring(0, 500);
            charCounter.textContent = '500/500 karakter (maksimum)';
            if (confirmBtn) confirmBtn.disabled = false;
        }
    });

    // ================= CONFIRM REMOVE MEMBER =================
    async function confirmRemoveMember() {
        console.log('confirmRemoveMember called', {
            currentMemberId,
            currentUserId,
            communityId: {{ $communityId }}
        });

        if (!currentMemberId || !currentUserId) {
            console.error('Member IDs missing', { currentMemberId, currentUserId });
            showToast('Member tidak ditemukan atau data tidak valid', 'error');
            return;
        }
        
        const message = document.getElementById('removeMessage').value.trim();
        const reason = document.querySelector('input[name="removeReason"]:checked')?.value || null;
        
        // Validasi pesan wajib diisi
        if (!message) {
            showToast('Silakan tulis pesan untuk anggota', 'error');
            document.getElementById('removeMessage').focus();
            return;
        }
        
        if (message.length < 5) {
            showToast('Pesan terlalu pendek (minimal 5 karakter)', 'error');
            return;
        }
        
        const confirmBtn = document.getElementById('confirmRemoveBtn');
        const originalText = confirmBtn.innerHTML;
        
        try {
            showLoading('Mengeluarkan Anggota...');
            confirmBtn.innerHTML = '<span class="loading"></span> Mengeluarkan...';
            confirmBtn.disabled = true;
            
            // Gunakan route yang benar berdasarkan web.php
            const url = `/buyer/communities/${communityId}/members/${currentMemberId}/remove`;
            
            console.log('Sending request to:', url);
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    reason: reason
                })
            });
            
            const result = await response.json();
            console.log('Response from server:', result);
            
            if (result.success) {
                showToast('Anggota berhasil dikeluarkan!', 'success');
                closeRemoveConfirmationModal();
                
                // Refresh page after 1.5 seconds
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                hideLoading();
                const errorMsg = result?.message || `Error ${response.status}: ${response.statusText}`;
                showToast(errorMsg, 'error');
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
            
        } catch (error) {
            hideLoading();
            showToast('Terjadi kesalahan koneksi: ' + error.message, 'error');
            console.error('Fetch error:', error);
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        }
    }

    // Helper function untuk hash string
    function hashCode(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = ((hash << 5) - hash) + str.charCodeAt(i);
            hash |= 0; // Convert to 32bit integer
        }
        return Math.abs(hash);
    }

    // ================= MESSAGE MODAL FUNCTIONS =================
    function openMessageModal() {
        const modal = document.getElementById('messageModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Reset form
        document.getElementById('messageText').value = '';
        document.getElementById('charCount').textContent = '0/500';
        document.getElementById('charCount').style.color = 'var(--text-light)';
        
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('messageText').focus();
        }, 300);
    }

    function closeMessageModal() {
        const modal = document.getElementById('messageModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ================= CHARACTER COUNT FOR MESSAGE =================
    document.getElementById('messageText')?.addEventListener('input', function(e) {
        const charCount = e.target.value.length;
        const charCountElement = document.getElementById('charCount');
        charCountElement.textContent = `${charCount}/500`;
        
        if (charCount > 500) {
            charCountElement.style.color = 'var(--danger)';
            e.target.value = e.target.value.substring(0, 500);
        } else if (charCount > 450) {
            charCountElement.style.color = 'var(--warning)';
        } else {
            charCountElement.style.color = 'var(--text-light)';
        }
    });

    // ================= SEND COMMUNITY MESSAGE =================
    async function sendCommunityMessage() {
        const messageText = document.getElementById('messageText').value.trim();
        const messageType = document.querySelector('input[name="messageType"]:checked').value;
        
        // Validasi
        if (!messageText) {
            showToast('Silakan tulis pesan terlebih dahulu', 'error');
            document.getElementById('messageText').focus();
            return;
        }
        
        if (messageText.length > 500) {
            showToast('Pesan terlalu panjang (maksimal 500 karakter)', 'error');
            return;
        }
        
        const button = event.target;
        const originalText = button.innerHTML;
        
        try {
            showLoading('Mengirim Pesan...');
            button.innerHTML = '<span class="loading"></span>';
            button.disabled = true;
            
            const url = `/buyer/communities/${communityId}/send-message`;
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: messageText,
                    type: messageType
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Pesan berhasil dikirim ke komunitas!', 'success');
                closeMessageModal();
                hideLoading();
            } else {
                hideLoading();
                const errorMsg = result?.message || `Error ${response.status}: ${response.statusText}`;
                showToast(errorMsg, 'error');
            }
            
        } catch (error) {
            hideLoading();
            showToast('Terjadi kesalahan koneksi: ' + error.message, 'error');
            console.error('Fetch error:', error);
        } finally {
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }
    }

    // ================= VIEW MEMBER DETAIL =================
    function viewMemberDetail(memberId) {
        // Anda bisa menambahkan route untuk melihat detail member jika diperlukan
        // Untuk sekarang, kita arahkan ke route yang sudah ada
        // window.location.href = `/buyer/communities/${communityId}/members/${memberId}`;
        showToast('Fitur detail member belum tersedia', 'info');
    }

    // ================= MAKE ADMIN FUNCTION =================
    async function makeAdmin() {
        if (!currentMemberId || !currentUserId) {
            showToast('Member tidak ditemukan', 'error');
            return;
        }
        
        const confirmed = await showConfirm(
            'Konfirmasi Admin', 
            `Apakah Anda yakin ingin menjadikan ${currentMemberName} sebagai admin?`
        );
        
        if (!confirmed) {
            return;
        }
        
        const button = event.target.closest('button');
        if (!button) return;
        
        const originalText = button.innerHTML;
        
        try {
            showLoading('Mempromosikan Admin...');
            button.innerHTML = '<span class="loading"></span>';
            button.disabled = true;
            
            // Gunakan route yang benar berdasarkan web.php
            const url = `/buyer/communities/${communityId}/members/${currentMemberId}/make-admin`;
            
            console.log('Sending make-admin request to:', url);
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            console.log('Response from make-admin:', result);
            
            if (result.success) {
                showToast('Anggota berhasil dijadikan admin!', 'success');
                closePopup();
                
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                hideLoading();
                const errorMsg = result?.message || `Error ${response.status}: ${response.statusText}`;
                showToast(errorMsg, 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
            
        } catch (error) {
            hideLoading();
            showToast('Terjadi kesalahan koneksi: ' + error.message, 'error');
            console.error('Fetch error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // ================= MAKE ANGGOTA FUNCTION =================
    async function makeAnggota() {
        if (!currentMemberId || !currentUserId) {
            showToast('Member tidak ditemukan', 'error');
            return;
        }
        
        const confirmed = await showConfirm(
            'Konfirmasi Perubahan', 
            `Apakah Anda yakin ingin mengubah ${currentMemberName} menjadi anggota?`
        );
        
        if (!confirmed) {
            return;
        }
        
        const button = event.target.closest('button');
        if (!button) return;
        
        const originalText = button.innerHTML;
        
        try {
            showLoading('Mendemosikan ke Anggota...');
            button.innerHTML = '<span class="loading"></span>';
            button.disabled = true;
            
            // Gunakan route yang benar berdasarkan web.php
            const url = `/buyer/communities/${communityId}/members/${currentMemberId}/remove-admin`;
            
            console.log('Sending remove-admin request to:', url);
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            console.log('Response from remove-admin:', result);
            
            if (result.success) {
                showToast('Admin berhasil diubah menjadi anggota!', 'success');
                closePopup();
                
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                hideLoading();
                const errorMsg = result?.message || `Error ${response.status}: ${response.statusText}`;
                showToast(errorMsg, 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
            
        } catch (error) {
            hideLoading();
            showToast('Terjadi kesalahan koneksi: ' + error.message, 'error');
            console.error('Fetch error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // ================= TOAST NOTIFICATION =================
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        if (type === 'info') icon = 'info-circle';
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="toast-content">
                <h4>${type === 'success' ? 'Sukses!' : type === 'error' ? 'Error!' : 'Info'}</h4>
                <p>${message}</p>
            </div>
        `;

        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ================= KEYBOARD SHORTCUTS =================
    document.addEventListener('keydown', (e) => {
        // Escape to close popup
        if (e.key === 'Escape') {
            closePopup();
            closeRemoveConfirmationModal();
            closeMessageModal();
        }
    });

    // ================= INITIALIZE MESSAGE CHARACTER COUNTER =================
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize remove message counter
        const removeMessage = document.getElementById('removeMessage');
        if (removeMessage) {
            const charCounter = document.getElementById('charCounter');
            if (charCounter) {
                const charCount = removeMessage.value.length;
                charCounter.textContent = `${charCount}/500 karakter`;
                if (charCount === 0) {
                    charCounter.className = 'char-counter';
                } else if (charCount <= 10) {
                    charCounter.className = 'char-counter warning';
                }
            }
        }
        
        // Debug info
        console.log('Community ID:', communityId);
        console.log('Current User ID:', currentUserIdAuth);
        console.log('Current User Role:', currentUserRole);
        console.log('Total Members:', members.length);
        console.log('Routes configured:');
        console.log('- Make Admin: POST /buyer/communities/' + communityId + '/members/{member}/make-admin');
        console.log('- Remove Admin: POST /buyer/communities/' + communityId + '/members/{member}/remove-admin');
        console.log('- Remove Member: POST /buyer/communities/' + communityId + '/members/{member}/remove');
    });
    async function removeMember(reason, message) {
        // ... existing removeMember logic ...
        // Keeping duplicate removeMember call signature out of this snippet to avoid errors.
        // I will just add the leaveCommunity functions at the end of script.
    }
    
    async function confirmLeaveCommunity() {
        const btn = document.getElementById('confirmLeaveBtn');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<div class="loading"></div>';
        btn.disabled = true;

        try {
            showLoading('Sedang Keluar...');
            const response = await fetch("{{ route('buyer.communities.leave', $communityId) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                // Close modal first
                closeLeaveConfirmationModal();
                showToast(data.message || 'Berhasil keluar', 'success');
                setTimeout(() => {
                    window.location.href = "{{ route('buyer.communities.index') }}";
                }, 1000);
            } else {
                hideLoading();
                showToast(data.message || 'Gagal keluar', 'error');
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        } catch (error) {
            hideLoading();
            console.error('Error:', error);
            showToast('Terjadi kesalahan sistem', 'error');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    }
    
    // NEW UI FUNCTIONS
    function closeSelfActionBottomSheet() {
        document.getElementById('selfActionBottomSheet').classList.remove('active');
        document.getElementById('bottomSheetOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function openLeaveConfirmation() {
        closeSelfActionBottomSheet();
        setTimeout(() => {
            document.getElementById('leaveConfirmationModal').classList.add('active');
            document.getElementById('leaveConfirmationOverlay').classList.add('active');
        }, 300); // Wait for sheet to slide down
    }

    function closeLeaveConfirmationModal() {
        document.getElementById('leaveConfirmationModal').classList.remove('active');
        document.getElementById('leaveConfirmationOverlay').classList.remove('active');
    }
</script>
@endpush
@extends('layouts.main', ['title' => $community->name])

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
        z-index: 999;
        font-size: 18px;
    }

    /* Background Modal Bottom Sheet */
    .bg-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
        display: none;
        align-items: flex-end;
        justify-content: center;
        backdrop-filter: blur(2px);
    }
    
    .bg-modal-overlay.active {
        display: flex;
    }
    
    .bg-sheet {
        width: 100%;
        max-width: 480px; 
        background: white;
        border-radius: 20px 20px 0 0;
        padding: 24px 20px 40px;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .bg-modal-overlay.active .bg-sheet {
        transform: translateY(0);
    }
    
    .bg-handle {
        width: 40px;
        height: 4px;
        background: #eee;
        border-radius: 2px;
        margin: 0 auto 20px;
    }
    
    .bg-option {
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
        padding: 16px;
        border: none;
        background: #f8f9fa;
        border-radius: 12px;
        color: #333;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
        text-align: left;
    }
    
    .bg-option:hover {
        background: #f1f3f5;
    }
    
    .bg-option i {
        font-size: 20px;
        color: var(--primary);
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
    
    .btn-chat-large:hover {
        background: #f8f9fa;
        border-color: #bbb;
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
        flex: 1;
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

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding: 80px 16px 85px; /* Increased bottom padding for custom nav */
        min-height: 100vh;
    }

    /* ================= COMMUNITY HEADER BANNER ================= */
    .community-banner {
        position: relative;
        width: 100%;
        height: 180px;
        overflow: hidden;
        border-radius: var(--radius-lg);
        margin-bottom: 20px;
        box-shadow: var(--shadow-md);
    }

    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 16px;
    }

    /* ================= BACK BUTTON ON BANNER ================= */
    .banner-back-button {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: white;
        padding: 8px;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        z-index: 10;
        backdrop-filter: blur(4px);
    }

    .banner-back-button:hover {
        background: rgba(0, 0, 0, 0.7);
        transform: scale(1.05);
    }

    .banner-content {
        color: white;
        position: relative;
        z-index: 2;
    }

    .community-name {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .community-description {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 12px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        max-width: 80%;
    }

    /* Re-join Button Style */
    .rejoin-btn {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: white;
        padding: 8px 16px;
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
    }
    
    .rejoin-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .rejoin-btn i {
        font-size: 14px;
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

    /* ================= COMMUNITY INFO CARD ================= */
    .community-info-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        margin-bottom: 20px;
    }

    .community-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .community-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(10, 92, 54, 0.1);
        color: var(--primary);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .community-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(39, 174, 96, 0.1);
        color: var(--accent);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .community-stats {
        display: flex;
        justify-content: space-around;
        border-top: 1px solid var(--border);
        padding-top: 16px;
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-number {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 16px;
        box-shadow: var(--shadow-sm);
        text-align: center;
        border: 1px solid var(--border);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        font-size: 20px;
        color: var(--primary);
        margin-bottom: 8px;
    }

    /* ================= SECTION CARDS ================= */
    .section-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin: 24px 0 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--accent);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-title a {
        font-size: 12px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .section-title i {
        color: var(--accent);
        font-size: 14px;
    }

    .section-container {
        margin-bottom: 24px;
    }

    /* ================= MEMBERS SECTION ================= */
    .members-section {
        background: white;
        border-radius: var(--radius-lg);
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .members-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .members-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .members-title i {
        color: var(--accent);
        font-size: 16px;
    }

    .view-all-link {
        font-size: 12px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .view-all-link:hover {
        text-decoration: underline;
    }

    .members-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .member-card {
        text-align: center;
        position: relative;
    }

    .member-avatar-wrapper {
        position: relative;
        width: 50px;
        height: 50px;
        margin: 0 auto 8px;
    }

    .member-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: var(--shadow-sm);
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        overflow: hidden;
    }

    .member-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .member-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
        padding: 0 4px;
    }

    .member-role {
        font-size: 10px;
        color: var(--text-light);
        font-weight: 500;
    }

    /* ================= ADMIN BADGE STYLES ================= */
    .admin-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: var(--warning);
        color: white;
        font-size: 9px;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        border: 1.5px solid white;
    }

    .admin-badge.admin-crown {
        background: linear-gradient(135deg, #f39c12 0%, #d68910 100%);
    }

    .admin-badge.admin-star {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .members-show-all {
        text-align: center;
        margin-top: 12px;
    }

    .members-show-all-btn {
        background: var(--light);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 8px 16px;
        border-radius: var(--radius-md);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .members-show-all-btn:hover {
        border-color: var(--accent);
        background: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* ================= MAIN BARENG SECTION ================= */
    .main-bareng-section {
        background: white;
        border-radius: var(--radius-lg);
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .main-bareng-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .main-bareng-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .main-bareng-title i {
        color: var(--accent);
        font-size: 16px;
    }

    .main-bareng-tabs {
        display: flex;
        background: #F1F3F5;
        padding: 5px;
        border-radius: 30px;
        gap: 0;
        margin-bottom: 24px;
        border: none;
    }

    .main-bareng-tab {
        flex: 1;
        background: transparent;
        border: none;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 700;
        color: #8A9C93;
        cursor: pointer;
        border-radius: 25px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .main-bareng-tab.active {
        background: white;
        color: #0A5C36;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .main-bareng-tab.active::after {
        display: none;
    }

    .main-bareng-content {
        text-align: center;
        padding: 20px 0;
    }

    .activity-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .activity-illustration {
        width: 260px;
        height: auto;
        margin-bottom: 20px;
        mix-blend-mode: multiply;
        opacity: 0.9;
    }

    .activity-title {
        font-size: 20px;
        font-weight: 800;
        color: #1A3A27;
        margin-bottom: 8px;
    }

    .activity-desc {
        font-size: 14px;
        color: #6C757D;
        max-width: 280px;
        margin: 0 auto 28px;
        line-height: 1.6;
    }

    .btn-create-activity {
        background: #0A5C36;
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.2);
        text-decoration: none;
    }

    .btn-create-activity:hover {
        background: #08472a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(10, 92, 54, 0.3);
    }

    .btn-create-activity i {
        font-size: 16px;
    }

    .main-bareng-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ================= CREATE ACTIVITY SECTION ================= */
    .create-activity-section {
        background: var(--light);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px 16px;
        margin-bottom: 20px;
        text-align: center;
    }

    .create-activity-icon {
        font-size: 28px;
        color: var(--accent);
        margin-bottom: 12px;
    }

    .create-activity-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .create-activity-desc {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 16px;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    .create-activity-btn {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .create-activity-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ================ ACTIVITY CARD STYLES ================ */
    .card-container-activities {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-top: 16px;
        text-align: left;
    }

    .activity-card {
        background: white;
        border: 1px solid #E6F7EF;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(10, 92, 54, 0.05);
    }

    .activity-card-image {
        position: relative;
        height: 180px;
        width: 100%;
    }

    .activity-venue-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .activity-card-booking-code {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(10, 92, 54, 0.9);
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        z-index: 2;
    }

    .activity-card-header {
        padding: 16px 16px 8px;
    }

    .activity-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #1A3A27;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .activity-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
    }

    .activity-badge-public { background: #E8F5E9; color: #0A5C36; }
    .activity-badge-private { background: #FFF3E0; color: #E65100; }
    .activity-badge-paid { background: #E3F2FD; color: #1565C0; }
    .activity-badge-free { background: #F3E5F5; color: #7B1FA2; }
    .activity-badge-today { background: #FFF3E0; color: #E65100; }
    .activity-badge-upcoming { background: #E8F5E9; color: #0A5C36; }
    .activity-badge-active { background: #E8F5E9; color: #0A5C36; }
    .activity-badge-pending { background: #FFF3E0; color: #E65100; }
    .activity-badge-canceled { background: #FFEBEE; color: #C62828; }
    .activity-badge-community { background: #E3F2FD; color: #1565C0; }
    .activity-badge-gender { background: #F8F9FA; color: #0A5C36; }
    .activity-badge-cost { background: #FFF3E0; color: #E65100; }
    .activity-badge-host-yes { background: #E3F2FD; color: #1565C0; border: 1px solid #BBDEFB; }
    .activity-badge-host-no { background: #F8F9FA; color: #6C757D; border: 1px solid #E9ECEF; }

    .activity-card-body {
        padding: 0 16px 16px;
    }

    .activity-card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #F1F3F5;
    }

    .activity-card-row:last-child {
        border-bottom: none;
    }

    .activity-card-label {
        font-size: 13px;
        color: #6C757D;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-card-value {
        font-size: 13px;
        font-weight: 700;
        color: #1A3A27;
    }

    .activity-card-footer {
        padding: 12px 16px;
        border-top: 1px solid #F1F3F5;
    }

    .activity-btn-view {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        background: white;
        color: #0A5C36;
        border: 1px solid #E6F7EF;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .activity-btn-view:hover {
        background: #f8faf9;
        border-color: #0A5C36;
    }

    /* ================= ACTIVITY BOTTOM SHEET ================= */
    .activity-options-modal {
        align-items: flex-end !important;
        justify-content: center !important;
        padding: 0 !important;
    }

    .activity-options-modal .modal-content {
        max-width: 480px;
        margin: 0 auto;
        border-radius: 28px 28px 0 0 !important;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        padding-bottom: env(safe-area-inset-bottom, 24px);
        animation: none !important; /* Disable default modal animation */
        width: 100%;
        box-shadow: 0 -8px 32px rgba(0,0,0,0.1);
    }

    .activity-options-modal.active .modal-content {
        transform: translateY(0);
    }

    .activity-options-modal .modal-header {
        border-bottom: none;
        padding: 24px 20px 12px;
        background: white;
    }

    .activity-options-modal .modal-title {
        text-align: left;
        justify-content: flex-start;
        font-size: 20px;
        font-weight: 800;
        color: #1A3A27;
    }

    .activity-type-card {
        display: flex;
        align-items: center;
        padding: 16px;
        border: 2px solid #F1F3F5;
        border-radius: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .activity-type-card.active {
        border-color: #0A5C36;
        background: rgba(10, 92, 54, 0.02);
    }

    .activity-type-icon-wrapper {
        width: 40px;
        height: 40px;
        background: #F8F9FA;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        color: #0A5C36;
        font-size: 18px;
        transition: all 0.2s ease;
    }

    .activity-type-card.active .activity-type-icon-wrapper {
        background: #0A5C36;
        color: white;
    }

    .activity-type-info {
        flex: 1;
    }

    .activity-type-name {
        font-size: 15px;
        font-weight: 700;
        color: #1A3A27;
        margin-bottom: 2px;
    }

    .activity-type-desc {
        font-size: 12px;
        color: #6C757D;
        line-height: 1.4;
    }

    .activity-radio-indicator {
        width: 22px;
        height: 22px;
        border: 2px solid #DEE2E6;
        border-radius: 50%;
        margin-left: 12px;
        position: relative;
        transition: all 0.2s ease;
    }

    .activity-type-card.active .activity-radio-indicator {
        border-color: #0A5C36;
        background: #0A5C36;
    }

    .activity-type-card.active .activity-radio-indicator::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
    }

    .btn-next-activity {
        width: 100%;
        background: #DEE2E6;
        color: #ADB5BD;
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        margin-top: 12px;
        cursor: not-allowed;
        transition: all 0.3s ease;
    }

    .btn-next-activity.active {
        background: #0A5C36;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.2);
    }

    /* ================= MODAL STYLES ================= */
    .modal-overlay {
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

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
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

    .modal-header {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--light);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-lighter);
        cursor: pointer;
        padding: 6px;
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .modal-close:hover {
        background: var(--border);
        color: var(--primary);
    }

    .modal-body {
        padding: 16px;
    }

    /* ================= FORM STYLES ================= */
    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--text);
        font-size: 12px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 13px;
        background: white;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
    }

    .form-textarea {
        min-height: 80px;
        resize: vertical;
        font-family: inherit;
    }

    .radio-group {
        display: flex;
        gap: 12px;
        margin-top: 8px;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        padding: 8px;
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
        border: 1px solid var(--border);
        flex: 1;
        justify-content: center;
    }

    .radio-label:hover {
        border-color: var(--accent);
    }

    .radio-label input[type="radio"] {
        accent-color: var(--accent);
    }

    .form-actions {
        display: flex;
        gap: 8px;
        margin-top: 24px;
    }

    .btn-primary {
        flex: 1;
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary {
        flex: 1;
        background: var(--light);
        color: var(--text);
        border: 1px solid var(--border);
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-secondary:hover {
        background: var(--border);
        border-color: var(--accent);
        transform: translateY(-2px);
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

        .community-banner {
            height: 150px;
        }

        .community-name {
            font-size: 20px;
        }

        .community-description {
            font-size: 12px;
        }

        .banner-back-button {
            top: 8px;
            left: 8px;
            width: 32px;
            height: 32px;
            padding: 6px;
            font-size: 14px;
        }

        .action-button-banner {
            font-size: 12px;
            padding: 8px 12px;
        }

        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .stat-card {
            padding: 12px;
        }

        .stat-icon {
            font-size: 16px;
        }

        .stat-number {
            font-size: 16px;
        }

        .stat-label {
            font-size: 9px;
        }

        .section-title {
            font-size: 15px;
            margin: 20px 0 12px;
        }

        .section-title a {
            font-size: 11px;
        }

        .members-section,
        .main-bareng-section,
        .create-activity-section {
            padding: 12px;
        }

        .members-header,
        .main-bareng-header {
            margin-bottom: 12px;
        }

        .members-title,
        .main-bareng-title,
        .create-activity-title {
            font-size: 14px;
        }

        .members-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .member-name {
            font-size: 11px;
        }

        .member-role {
            font-size: 9px;
        }

        .admin-badge {
            font-size: 7px;
            padding: 1px 3px;
        }

        .main-bareng-tabs {
            flex-wrap: wrap;
            gap: 6px;
        }

        .main-bareng-tab {
            font-size: 11px;
            padding: 5px 10px;
        }

        .main-bareng-icon {
            font-size: 24px;
        }

        .main-bareng-message,
        .create-activity-desc {
            font-size: 11px;
            max-width: 220px;
        }

        .main-bareng-button,
        .create-activity-btn {
            padding: 8px 16px;
            font-size: 11px;
        }

        .activity-option-item {
            padding: 10px;
        }

        .activity-option-icon {
            font-size: 16px;
            margin-right: 10px;
        }

        .activity-option-title {
            font-size: 13px;
        }

        .activity-option-desc {
            font-size: 11px;
        }

        .form-control {
            padding: 8px 10px;
            font-size: 12px;
        }

        .form-label {
            font-size: 11px;
        }

        .radio-label {
            padding: 6px;
            font-size: 12px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 8px 16px;
            font-size: 12px;
        }

        .main-bareng-button,
        .create-activity-btn {
            padding: 8px 16px;
            font-size: 11px;
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

    /* ================= UTILITIES ================= */
    .text-muted {
        color: var(--text-lighter);
        font-size: 11px;
    }

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

    /* Background Modal Bottom Sheet */
    .bg-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
        display: none;
        align-items: flex-end;
        justify-content: center; /* Center horizontally */
        backdrop-filter: blur(2px);
    }
    
    .bg-modal-overlay.active {
        display: flex;
    }
    
    .bg-sheet {
        width: 100%;
        max-width: 480px; /* Match mobile container */
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
</style>
@endpush

@section('content')
<div class="mobile-container">
    @php
        $currentUserMember = $members->where('user_id', auth()->id())->first();
        $isManager = $currentUserMember && $currentUserMember->role === 'admin';
        $isMember = $currentUserMember !== null;
        $isRemoved = \App\Models\CommunityMember::where('community_id', $community->id)
            ->where('user_id', auth()->id())
            ->where('status', 'removed')
            ->exists();
    @endphp

    @php
        $backgroundUrl = $community->background_image ? asset('storage/'.$community->background_image) : '';
    @endphp

    <div class="visual-header" 
         @if($community->background_image)
         style="background-image: url('{{ $backgroundUrl }}');"
         @else
         style="background: linear-gradient(135deg, #0A5C36 0%, #08472a 100%);"
         @endif
         id="visualHeader">
        <div class="visual-top-bar">
            <a href="{{ route('buyer.communities.index') }}" class="visual-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div class="visual-actions-right">
                <button class="visual-btn">
                     <i class="fas fa-share-alt"></i>
                </button>
                @if($isManager)
                <a href="{{ route('buyer.communities.edit', $community->id) }}" class="visual-btn">
                     <i class="fas fa-cog"></i>
                </a>
                @endif
            </div>
        </div>
        
        @if($isManager)
        <button class="camera-btn" onclick="openBackgroundModal()">
            <i class="fas fa-camera"></i>
        </button>
        @endif
    </div>

    <div class="visual-profile-section">
        <div class="visual-avatar-wrapper">
             <img src="{{ $community->logo ? asset('storage/' . $community->logo) : asset('images/default-logo.png') }}" class="visual-avatar" alt="Logo">
             {{-- Overlay removed as requested --}}
        </div>
        
        <h1 class="visual-name">{{ $community->name }}</h1>
        <div class="visual-meta">
            <span>{{ $community->category->category_name ?? 'Olahraga' }}</span>
            <span>{{ ucfirst($community->type) }}</span>
            <span>{{ $community->location ?? 'Belum ada lokasi' }}</span>
        </div>
        
        <div class="visual-main-action">
             @if($isMember || $isManager)
             <a href="{{ route('buyer.communities.chat', $community->id) }}" class="btn-chat-large">
                 <i class="far fa-comment-dots"></i> Chat
             </a>
             @elseif($isRemoved)
                 <button onclick="requestRejoin()" class="btn-chat-large" style="background:#fff3cd; border-color:#ffeeba; color:#856404;">
                     <i class="fas fa-undo"></i> Minta Bergabung Kembali
                 </button>
             @else
                 {{-- Logic join default jika ada --}}
             @endif
        </div>
    </div>

    <main class="main-content" style="padding-top: 0;">

        <!-- ================= MEMBERS SECTION ================= -->
        <section class="members-section">
            <div class="members-header">
                <h2 class="members-title">
                    <i class="fas fa-users"></i>
                    Anggota 
                </h2>
                <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="view-all-link">
                    Lihat Semua <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <div class="members-grid">
                @foreach ($members->take(6) as $member)
                    @php
                        $user = $member->user;
                        $memberName = $user->name ?? 'User';
                        $avatar = $user->avatar;
                        
                        if ($avatar) {
                            $avatarUrl = asset('storage/' . $avatar);
                        } else {
                            $avatarUrl = null;
                            $initials = strtoupper(substr($memberName, 0, 2));
                            $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
                            $colorIndex = crc32($memberName) % count($colors);
                            $avatarColor = $colors[$colorIndex];
                        }
                    @endphp
                    <div class="member-card">
                        <div class="member-avatar-wrapper">
                            <!-- Admin badge untuk role admin -->
                            @if(strtolower($member->role) === 'admin')
                                <div class="admin-badge admin-crown" title="Admin">
                                    <i class="fas fa-crown" style="font-size: 8px;"></i>
                                </div>
                            @endif
                            
                            <div class="member-avatar" @if(!$avatarUrl) style="background-color: {{ $avatarColor }};" @endif>
                                @if($avatarUrl)
                                    <img 
                                        src="{{ $avatarUrl }}" 
                                        class="member-avatar-img" 
                                        alt="{{ $memberName }}"
                                        onerror="this.style.display='none'; this.parentElement.style.backgroundColor='{{ $avatarColor ?? '#0A5C36' }}'; this.parentElement.innerText='{{ strtoupper(substr($memberName, 0, 2)) }}';"
                                    >
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                        </div>
                        <div class="member-name">{{ $memberName }}</div>
                        <div class="member-role">
                            @if(strtolower($member->role) === 'admin')
                                Admin
                            @elseif(strtolower($member->role) === 'anggota')
                                Anggota
                            @else
                                {{ ucfirst($member->role) }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- ================= MAIN BARENG SECTION ================= -->
        <section class="main-bareng-section">

            <div class="main-bareng-tabs">
                <button class="main-bareng-tab active" onclick="setTab('main-bareng')">
                    Main Bareng
                </button>
            </div>

            <div class="main-bareng-content">
                <div id="main-bareng-tab-content">
                    @if($activities->count() > 0)
                        <div class="card-container-activities">
                            @foreach($activities as $playTogether)
                                @php
                                    $booking = $playTogether->booking;
                                    $venue = $booking->venue ?? null;
                                    $category = $venue->category ?? null;
                                    $creator = $playTogether->creator;
                                    
                                    $approvedParticipants = $playTogether->participants()
                                        ->where('approval_status', 'approved')
                                        ->count();
                                        
                                    $eventDate = \Carbon\Carbon::parse($playTogether->date);
                                    $isToday = $eventDate->isToday();
                                    $isTomorrow = $eventDate->isTomorrow();
                                @endphp

                                @if($venue)
                                    <div class="activity-card">
                                        <div class="activity-card-image">
                                            <img src="{{ $venue->photo ? asset('storage/' . $venue->photo) : 'https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}" 
                                                 alt="{{ $venue->venue_name }}" class="activity-venue-image" />
                                            <div class="activity-card-booking-code">{{ $booking->ticket_code ?? 'BK-' . str_pad($playTogether->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        </div>

                                        <div class="activity-card-header">
                                            <div class="activity-card-title">
                                                <i class="fas fa-futbol"></i>
                                                {{ $venue->venue_name }}
                                            </div>
                                            <div class="activity-card-badges">
                                                <span class="activity-badge activity-badge-{{ $playTogether->privacy }}">
                                                    <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i> 
                                                    {{ strtoupper($playTogether->privacy) }}
                                                </span>
                                                <span class="activity-badge activity-badge-{{ $playTogether->type }}">
                                                    <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i> 
                                                    {{ strtoupper($playTogether->type) }}
                                                </span>
                                                <span class="activity-badge activity-badge-{{ $playTogether->status }}">
                                                    {{ strtoupper($playTogether->status) }}
                                                </span>
                                                @if($isToday)
                                                    <span class="activity-badge activity-badge-today">
                                                        <i class="fas fa-calendar-day"></i> HARI INI
                                                    </span>
                                                @elseif($isTomorrow)
                                                    <span class="activity-badge activity-badge-upcoming">
                                                        <i class="fas fa-calendar-alt"></i> BESOK
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="activity-card-body">
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-calendar-alt"></i> Tanggal
                                                </div>
                                                <div class="activity-card-value">
                                                    {{ $eventDate->translatedFormat('d M Y') }}
                                                    @if($isToday)
                                                        <span class="activity-badge activity-badge-today" style="margin-left: 5px;">HARI INI</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-tag"></i> Kategori
                                                </div>
                                                <div class="activity-card-value">
                                                    {{ $category->category_name ?? 'Olahraga' }}
                                                </div>
                                            </div>
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-users"></i> Peserta
                                                </div>
                                                <div class="activity-card-value">
                                                    {{ $approvedParticipants }} / {{ $playTogether->max_participants }} orang
                                                </div>
                                            </div>
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-money-bill-wave"></i> Biaya
                                                </div>
                                                <div class="activity-card-value">
                                                    @if($playTogether->type === 'paid')
                                                        <span class="activity-badge activity-badge-cost">
                                                            Rp {{ number_format($playTogether->price_per_person, 0, ',', '.') }}/orang
                                                        </span>
                                                    @else
                                                        <span class="activity-badge activity-badge-free">GRATIS</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-user"></i> Host
                                                </div>
                                                <div class="activity-card-value">
                                                    {{ $creator->name ?? 'Tidak diketahui' }}
                                                </div>
                                            </div>
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-venus-mars"></i> Jenis Kelamin
                                                </div>
                                                <div class="activity-card-value">
                                                    <span class="activity-badge activity-badge-gender">
                                                        @if(in_array(strtolower($playTogether->gender), ['male', 'laki-laki']))
                                                            Laki-laki
                                                        @elseif(in_array(strtolower($playTogether->gender), ['female', 'perempuan']))
                                                            Perempuan
                                                        @else
                                                            Campur
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="activity-card-row">
                                                <div class="activity-card-label">
                                                    <i class="fas fa-user-check"></i> Persetujuan Host
                                                </div>
                                                <div class="activity-card-value">
                                                    @if($playTogether->host_approval)
                                                        <span class="activity-badge activity-badge-host-yes">
                                                            <i class="fas fa-user-check"></i> DIPERLUKAN
                                                        </span>
                                                    @else
                                                        <span class="activity-badge activity-badge-host-no">
                                                            <i class="fas fa-bolt"></i> AUTO JOIN
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="activity-card-footer">
                                            <a href="{{ route('buyer.communities.main_bareng.show_community', [$community->id, $playTogether->id]) }}" class="activity-btn-view">
                                                <i class="fas fa-eye"></i> Detail Aktivitas
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                    @else
                        <div class="activity-empty-state">
                            <img src="{{ asset('images/activity-empty.png') }}" alt="Empty Illustration" class="activity-illustration">
                            <h3 class="activity-title">Mulai buat aktivitas!</h3>
                            <p class="activity-desc">
                                Semua dimulai dari aktivitas. Yuk, mulai buat aktivitas pertamamu sekarang.
                            </p>
                            <button class="btn-create-activity" onclick="openActivityOptionsModal()">
                                Buat Aktivitas <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <div id="sparring-tab-content" style="display: none;">
                    <div class="activity-empty-state">
                        <img src="{{ asset('images/activity-empty.png') }}" alt="Empty Illustration" class="activity-illustration">
                        <h3 class="activity-title">Belum ada Sparring</h3>
                        <p class="activity-desc">
                            Ayo cari lawan tanding yang seimbang untuk komunitasmu!
                        </p>
                        <button class="btn-create-activity" onclick="showToast('Fitur Sparring akan segera tersedia!', 'info')">
                            Cari Lawan <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ================= ACTIVITY OPTIONS MODAL ================= -->
    <div class="modal-overlay activity-options-modal" id="activityOptionsModal" onclick="closeActivityOptionsModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">Aktivitas</h2>
                <button class="modal-close" onclick="closeActivityOptionsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 12px 20px 24px;">
                <div class="activity-type-card" onclick="selectType(this, 'main-bareng')">
                    <div class="activity-type-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="activity-type-info">
                        <div class="activity-type-name">Main Bareng</div>
                        <div class="activity-type-desc">Buat aktivitas dan undang partisipan untuk bergabung.</div>
                    </div>
                    <div class="activity-radio-indicator"></div>
                </div>
                <button id="btnNextActivity" class="btn-next-activity" onclick="handleNextActivity()" disabled>
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    <!-- ================= CUSTOM BOTTOM NAVIGATION ================= -->
    <nav class="bottom-nav">
        @php
            // Mendapatkan URL saat ini
            $currentUrl = url()->current();
            $isProfilePage = str_contains($currentUrl, route('buyer.communities.show', $community->id)) && !str_contains($currentUrl, 'aktivitas') && !str_contains($currentUrl, 'members') && !str_contains($currentUrl, 'galeri');
            $isAktivitasPage = str_contains($currentUrl, route('buyer.communities.aktivitas', $community->id));
            $isMembersPage = str_contains($currentUrl, route('buyer.communities.members.index', $community->id));
            $isGaleriPage = str_contains($currentUrl, route('buyer.communities.galeri', $community->id));
        @endphp
        
        <a href="{{ route('buyer.communities.show', $community->id) }}" 
           class="nav-item {{ $isProfilePage ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <span class="nav-label">Profil</span>
        </a>
        
        <!-- Aktivitas -->
        <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" 
           class="nav-item {{ $isAktivitasPage ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="nav-label">Aktivitas</span>
        </a>
        
        <!-- Anggota -->
        <a href="{{ route('buyer.communities.members.index', $community->id) }}" 
           class="nav-item {{ $isMembersPage ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span class="nav-label">Anggota</span>
        </a>
        
        <!-- Kompetisi -->
        <a href="#" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <span class="nav-label">Kompetisi</span>
        </a>
        
        <!-- Galeri -->
        <a href="{{ route('buyer.communities.galeri', $community->id) }}" 
           class="nav-item {{ $isGaleriPage ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-images"></i>
            </div>
            <span class="nav-label">Galeri</span>
        </a>
    </nav>

    {{-- BACKGROUND OPTIONS MODAL --}}
    <div class="bg-modal-overlay" id="bgOptionsModal" onclick="closeBackgroundModal()">
        <div class="bg-sheet" onclick="event.stopPropagation()">
            <div class="bg-handle"></div>
            
            <button class="bg-option" onclick="triggerUpload()">
                <i class="fas fa-image"></i>
                <div style="flex:1;">
                    <div style="font-weight:600;">Ubah Background</div>
                    <div style="font-size:12px; color:#888;">Ambil foto atau pilih dari galeri</div>
                </div>
            </button>
            
            <form id="bgForm" style="display:none;">
                @csrf
                <input type="file" id="bgInput" name="image" accept="image/*" onchange="uploadBackground(this)">
            </form>
        </div>
    </div>
</div>


    @endsection

@push('scripts')
<script>
    // ================= BACKGROUND MODAL FUNCTIONS =================
    function openBackgroundModal() {
        document.getElementById('bgOptionsModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeBackgroundModal() {
        document.getElementById('bgOptionsModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function triggerUpload() {
        document.getElementById('bgInput').click();
    }
    
    function uploadBackground(input) {
        if (input.files && input.files[0]) {
            const formData = new FormData();
            formData.append('image', input.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            closeBackgroundModal();
            showToast('Sedang mengupload background...', 'info');
            
            fetch("{{ route('buyer.communities.update-background', $community->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Background berhasil diperbarui!', 'success');
                    // Reload is safer to ensure all views get the new image
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Gagal mengupload background');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal mengupload background: ' + error.message, 'error');
            });
            
            input.value = '';
        }
    }

    // ================= TOAST NOTIFICATION =================
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;

        if (!document.getElementById('toastContainer')) {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = "position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 400px; display: flex; flex-direction: column; gap: 10px;";
            document.body.appendChild(container);
        }

        document.getElementById('toastContainer').appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ================= MODAL CONTROLS =================
    let currentTab = 'main-bareng';

    function openActivityOptionsModal() {
        document.getElementById('activityOptionsModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeActivityOptionsModal() {
        document.getElementById('activityOptionsModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // ================= SET TAB =================
    function setTab(tab) {
        currentTab = tab;
        
        // Update tab button states
        const tabs = document.querySelectorAll('.main-bareng-tab');
        tabs.forEach(btn => btn.classList.remove('active'));
        
        const mainBarengContent = document.getElementById('main-bareng-tab-content');
        const sparringContent = document.getElementById('sparring-tab-content');

        if (tab === 'main-bareng') {
            tabs[0].classList.add('active');
            if(mainBarengContent) mainBarengContent.style.display = 'block';
            if(sparringContent) sparringContent.style.display = 'none';
        } else {
            tabs[1].classList.add('active');
            if(mainBarengContent) mainBarengContent.style.display = 'none';
            if(sparringContent) sparringContent.style.display = 'block';
        }
    }

    // ================= SELECT ACTIVITY OPTION =================
    let selectedActivityType = null;

    function selectType(element, type) {
        // Clear all active states
        document.querySelectorAll('.activity-type-card').forEach(card => {
            card.classList.remove('active');
        });

        // Set current as active
        element.classList.add('active');
        selectedActivityType = type;

        // Enable button
        const nextBtn = document.getElementById('btnNextActivity');
        nextBtn.classList.add('active');
        nextBtn.disabled = false;
    }

    function handleNextActivity() {
        if (!selectedActivityType) return;

        if (selectedActivityType === 'main-bareng') {
            window.location.href = "{{ route('buyer.main_bareng.index') }}";
        } else {
            showToast('Fitur Sparring akan segera tersedia!', 'info');
            closeActivityOptionsModal();
        }
    }

    // ================= BOTTOM NAVIGATION ACTIVE STATE =================
    document.addEventListener('DOMContentLoaded', function() {
        // Active state sudah dihandle oleh PHP di Blade
        // Jika ingin menambahkan logika tambahan, bisa ditambahkan di sini
        
        // Optional: Tambahkan efek hover dan klik
        const navItems = document.querySelectorAll('.custom-nav-item');
        
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Jika bukan link yang valid (#), tampilkan pesan
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                    const label = this.querySelector('.custom-nav-label').textContent;
                    showToast(`Halaman ${label} akan segera tersedia!`, 'info');
                }
            });
        });
    });

    // ================= KEYBOARD SHORTCUTS =================
    document.addEventListener('keydown', (e) => {
        // Escape to close modals
        if (e.key === 'Escape') {
            closeActivityOptionsModal();
        }
        
        // Back button (B) for going back
        if (e.key === 'b' && (e.ctrlKey || e.metaKey)) {
            e.preventDefault();
            window.location.href = "{{ route('buyer.communities.index') }}";
        }
    });

    // ================= REQUEST REJOIN =================
    async function requestRejoin() {
        if(!confirm('Apakah Anda yakin ingin mengajukan permohonan untuk bergabung kembali?')) return;

        const btn = document.querySelector('.rejoin-btn');
        if(btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
        }

        try {
            const response = await fetch("{{ route('buyer.communities.requestRejoin', $community->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('Permintaan berhasil dikirim! Menunggu persetujuan admin.');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengirim permintaan.');
                if(btn) {
                    btn.innerHTML = '<i class="fas fa-undo"></i> Minta Bergabung Kembali';
                    btn.disabled = false;
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
            if(btn) {
                btn.innerHTML = '<i class="fas fa-undo"></i> Minta Bergabung Kembali';
                btn.disabled = false;
            }
        }
    }

    // ================= MODAL CLICK OUTSIDE =================
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                const modalId = this.id;
                if (modalId === 'activityOptionsModal') {
                    closeActivityOptionsModal();
                }
            }
        });
    });
</script>
@endpush
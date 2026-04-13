@extends('layouts.main', ['title' => 'Join Komunitas'])

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
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        box-shadow: 0 0 20px rgba(10, 92, 54, 0.08);
        max-width: 500px;
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

    @media (min-width: 480px) {
        .mobile-header {
            width: 480px;
            left: 50%;
            transform: translateX(-50%);
        }
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

    /* ================= HEADER ACTIONS ================= */
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

    .search-container-header {
        display: flex;
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        position: relative;
    }

    .search-container-header:focus-within {
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

    .notification-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s ease;
        gap: 12px;
    }

    .notification-item:hover {
        background: var(--light);
    }

    .notification-item.unread {
        background: rgba(10, 92, 54, 0.05);
    }

    .notification-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        color: white;
    }

    .notification-icon.info {
        background: var(--info);
    }

    .notification-icon.success {
        background: var(--success);
    }

    .notification-icon.warning {
        background: var(--warning);
    }

    .notification-icon.danger {
        background: var(--danger);
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title-item {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .notification-message {
        font-size: 11px;
        color: var(--text-light);
        line-height: 1.4;
        margin-bottom: 6px;
    }

    .notification-time {
        font-size: 10px;
        color: var(--text-lighter);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notification-unread-dot {
        width: 8px;
        height: 8px;
        background: var(--accent);
        border-radius: 50%;
        flex-shrink: 0;
    }

    .notification-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--border);
        text-align: center;
        background: var(--light);
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    }

    .view-all-notifications {
        color: var(--accent);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        padding: 6px 12px;
        border-radius: var(--radius-sm);
    }

    .view-all-notifications:hover {
        background: rgba(46, 204, 113, 0.1);
    }

    /* ================= NO NOTIFICATIONS STATE ================= */
    .no-notifications {
        text-align: center;
        padding: 40px 16px;
        color: var(--text-lighter);
    }

    .no-notifications i {
        font-size: 32px;
        margin-bottom: 12px;
        color: var(--border);
        opacity: 0.5;
    }

    .no-notifications p {
        font-size: 13px;
        color: var(--text-light);
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding: 80px 16px 85px; /* Added top padding and bottom space for nav */
    }

    /* ================= PAGE HEADER ================= */
    .page-header {
        background: white;
        padding: 12px 0;
        margin-bottom: 0;
    }

    .page-header-content {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 16px;
    }

    .back-link {
        color: var(--primary);
        font-size: 18px;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .page-title {
        font-size: 18px;
        font-weight: 800;
        color: #0A3A27;
        margin: 0;
    }

    .page-description-section {
        padding: 12px 16px 20px;
        background: white;
    }

    .page-subtitle {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
        margin: 0;
        font-weight: 400;
    }

    /* ================= SEARCH BAR ================= */
    .search-container {
        margin-bottom: 20px;
        position: relative;
    }

    .search-box {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 13px;
        background: white;
        color: var(--text);
        transition: all 0.2s ease;
        font-weight: 500;
        box-shadow: var(--shadow-sm);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
    }

    .search-input::placeholder {
        color: var(--text-light);
        opacity: 0.7;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 14px;
    }

    .search-hint {
        font-size: 11px;
        color: var(--text-light);
        margin-top: 6px;
        padding-left: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ================= COMMUNITY CARDS ================= */
    .community-cards-container {
        padding-bottom: 20px;
    }

    .community-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 24px;
        border: 1px solid #C0E6D4;
        box-shadow: 0 4px 20px rgba(10, 92, 54, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .community-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(10, 92, 54, 0.08);
        border-color: var(--primary);
    }

    .card-banner {
        height: 100px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .card-banner::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 100%);
    }

    .card-profile-section {
        padding: 0 16px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-top: -45px;
        margin-bottom: 12px;
        z-index: 10;
        gap: 8px;
    }

    .logo-wrapper {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        background: white;
        padding: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        flex-shrink: 0;
    }

    .community-logo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #f8f9fa;
    }

    .community-basic-info {
        width: 100%;
        min-width: 0;
    }

    .community-name {
        font-size: 18px;
        font-weight: 800;
        color: #0A3A27;
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .community-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        justify-content: center;
    }

    .tag {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tag-category {
        background: #EBF7EE;
        color: #2E7D32;
    }

    .tag-type {
        background: #E8F2FE;
        color: #1976D2;
    }

    .community-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 6px;
    }

    .community-category {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: rgba(10, 92, 54, 0.1);
        color: var(--primary);
        border-radius: 16px;
        font-size: 10px;
        font-weight: 700;
    }

    .community-type {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: rgba(39, 174, 96, 0.1);
        color: var(--accent);
        border-radius: 16px;
        font-size: 10px;
        font-weight: 700;
    }

    .community-stats {
        display: flex;
        gap: 10px;
        font-size: 11px;
        color: var(--text-light);
        font-weight: 500;
    }

    .community-stats span {
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .community-stats i {
        color: var(--accent);
        font-size: 10px;
    }

    /* ================= CARD BODY ================= */
    .card-body-content {
        padding: 0 16px 20px;
    }
    .community-description {
        font-size: 13px;
        color: #777;
        line-height: 1.6;
        margin-bottom: 16px;
        word-break: break-word;
    }

    .community-grid-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        padding: 20px;
        background: #F7F8FA;
        border-radius: 16px;
        margin-bottom: 16px;
    }

    .grid-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .item-label {
        font-size: 10px;
        font-weight: 700;
        color: #A0A5B1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .item-value {
        font-size: 14px;
        font-weight: 800;
        color: #1A1C1E;
    }

    .card-bottom {
        display: flex;
        flex-direction: column;
        padding-top: 4px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        width: 100%;
    }

    .btn-action {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex: 1;
        text-align: center;
    }

    .btn-detail {
        background: #FFFFFF;
        color: #0A3A27;
        border: 1px solid #E0E0E0;
    }

    .btn-detail:hover {
        background: #F5F5F5;
        border-color: #CCCCCC;
    }

    .btn-join {
        background: #0A5C36;
        color: white;
        width: 100%;
        border: 1px solid #0A5C36;
    }

    .btn-join:hover {
        background: #08472a;
        transform: translateY(-2px);
    }

    .btn-join:disabled {
        background: #E0E0E0;
        border-color: #E0E0E0;
        color: #9E9E9E;
        cursor: not-allowed;
        transform: none;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 40px 16px;
        background: var(--light);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        margin-bottom: 20px;
    }

    .empty-state-icon {
        font-size: 36px;
        color: var(--border);
        opacity: 0.5;
        margin-bottom: 12px;
    }

    .empty-state-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-light);
    }

    .empty-state-desc {
        font-size: 12px;
        color: var(--text-lighter);
        margin-bottom: 16px;
        line-height: 1.4;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
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

        .page-title {
            font-size: 18px;
        }

        .page-subtitle {
            font-size: 12px;
        }

        .page-header-content {
            gap: 8px;
            padding: 0 12px;
        }

        .back-button {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .search-input {
            padding: 8px 14px 8px 36px;
            font-size: 12px;
        }

        .search-icon {
            left: 10px;
            font-size: 12px;
        }

        .search-hint {
            font-size: 10px;
        }

        .card-header {
            padding: 10px 12px;
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .community-meta {
            justify-content: center;
            gap: 6px;
        }

        .community-category,
        .community-type {
            font-size: 9px;
            padding: 2px 6px;
        }

        .community-stats {
            font-size: 10px;
            gap: 8px;
            justify-content: center;
        }

        .card-body {
            padding: 12px;
        }

        .community-description {
            font-size: 11px;
            margin-bottom: 12px;
        }

        .community-details {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .detail-label {
            font-size: 9px;
        }

        .detail-value {
            font-size: 11px;
        }

        .card-footer {
            padding: 10px 12px;
            flex-direction: column;
            gap: 10px;
        }

        .action-buttons {
            width: 100%;
        }

        .btn-action {
            flex: 1;
            min-width: auto;
            padding: 6px 10px;
            font-size: 10px;
        }

        .status-badge {
            font-size: 10px;
            padding: 5px 10px;
        }

        .empty-state {
            padding: 30px 12px;
        }

        .empty-state-icon {
            font-size: 28px;
        }

        .empty-state-title {
            font-size: 14px;
        }

        .empty-state-desc {
            font-size: 11px;
            max-width: 220px;
        }

        .toast-container {
            left: 12px;
            right: 12px;
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
        }

        .mobile-header {
            width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        }

        .community-details {
            grid-template-columns: repeat(3, 1fr);
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

    /* ================= SCROLLBAR STYLING ================= */
    .notification-list::-webkit-scrollbar {
        width: 6px;
    }

    .notification-list::-webkit-scrollbar-track {
        background: var(--light);
        border-radius: 3px;
    }

    .notification-list::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    .notification-list::-webkit-scrollbar-thumb:hover {
        background: var(--text-lighter);
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
        border-top: 5px solid #2ecc71;
        border-radius: 50%;
        animation: spin-overlay 1s linear infinite;
        margin-bottom: 20px;
    }

    .loading-text {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .loading-subtext {
        font-size: 14px;
        opacity: 0.8;
        padding: 0 40px;
    }

    @keyframes spin-overlay {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="mobile-container">
    @include('layouts.header')

    <main class="main-content">
        <!-- ================= PAGE HEADER ================= -->
        <header class="page-header">
            <div class="page-header-content">
                @auth
                    @if(auth()->user()->role === 'buyer')
                        <a href="{{ route('buyer.communities.index') }}" class="back-link">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    @else
                        <a href="{{ route('welcome') }}" class="back-link">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    @endif
                @else
                    <a href="{{ route('welcome') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @endauth
                
                <h1 class="page-title">
                    @auth
                        Gabung Komunitas
                    @else
                        Komunitas Olahraga
                    @endauth
                </h1>
            </div>
        </header>

        <section class="page-description-section">
            <p class="page-subtitle">
                @auth
                    Temukan dan bergabung dengan komunitas olahraga favorit Anda
                @else
                    Jelajahi komunitas olahraga yang tersedia. Login untuk bergabung!
                @endauth
            </p>
        </section>

        <!-- ================= SEARCH BAR ================= -->
        <div class="search-container" style="padding: 0 16px;">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input
                    type="text"
                    class="search-input live-search"
                    placeholder="Cari nama atau kategori..."
                    @auth
                        data-url="{{ route('buyer.communities.search') }}"
                    @endauth
                    id="searchInput"
                >
            </div>
            <div class="search-hint" style="padding: 8px 0; text-align: left; color: #777; font-size: 11px;">
                <i class="fas fa-info-circle"></i>
                Ketik minimal 2 karakter untuk mencari
            </div>
        </div>

        <div id="joinCommunityList" class="community-cards-container" style="padding: 12px 16px;">
            @foreach ($communities as $community)

                @if($community->is_banned)
                    @continue
                @endif

                @php
                    if (!auth()->check() && $community->type !== 'public') {
                        continue;
                    }
                @endphp

                <div class="community-card">
                    {{-- Banner Background --}}
                    <div class="card-banner" 
                         @if($community->background_image)
                         style="background-image: url('{{ asset('storage/' . $community->background_image) }}');"
                         @else
                         style="background: linear-gradient(135deg, #0A5C36 0%, #08472a 100%);"
                         @endif>
                    </div>

                    {{-- Profile Header Overlay --}}
                    <div class="card-profile-section">
                        <div class="logo-wrapper">
                            <img 
                                src="{{ $community->logo ? asset('storage/' . $community->logo) : asset('images/default-community.png') }}"
                                class="community-logo"
                                alt="{{ $community->name }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/default-community.png') }}';"
                            >
                        </div>
                        <div class="community-basic-info">
                            <h2 class="community-name">{{ $community->name }}</h2>
                            <div class="community-tags">
                                <span class="tag tag-category">
                                    <i class="fas fa-tag"></i>
                                    {{ $community->category->category_name ?? 'Olahraga' }}
                                </span>
                                <span class="tag tag-type">
                                    <i class="fas fa-{{ $community->type === 'public' ? 'globe' : 'lock' }}"></i>
                                    {{ ucfirst($community->type) }}
                                </span>
                                @if($community->type === 'public' && $community->requires_approval)
                                    <span class="tag" style="background: rgba(243, 156, 18, 0.1); color: #f39c12;">
                                        <i class="fas fa-user-check"></i>
                                        Perlu Pengajuan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body-content">
                        @if($community->description)
                            <div class="community-description">
                                {{ $community->description }}
                            </div>
                        @endif

                        <div class="community-grid-info">
                            <div class="grid-item">
                                <span class="item-label">Kategori</span>
                                <span class="item-value">{{ $community->category->category_name ?? 'Olahraga' }}</span>
                            </div>
                            <div class="grid-item">
                                <span class="item-label">Tipe</span>
                                <span class="item-value">{{ ucfirst($community->type) }}</span>
                            </div>
                            <div class="grid-item">
                                <span class="item-label">Anggota</span>
                                <span class="item-value">{{ $community->members_count ?? 0 }} orang</span>
                            </div>
                            <div class="grid-item">
                                <span class="item-label">Dibuat</span>
                                <span class="item-value">
                                    {{ $community->created_at ? $community->created_at->format('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-bottom">
                            @auth
                                {{-- Untuk USER YANG LOGIN --}}
                                @if($community->role || $community->membership_status !== 'none')
                                    <div class="status-container" style="width: 100%; margin-bottom: 12px; text-align: center;">
                                        @if($community->role === 'manager' || $community->role === 'admin')
                                            <span class="status-badge badge-joined" style="width: 100%; justify-content: center;">
                                                <i class="fas fa-crown"></i> Pengelola
                                            </span>
                                        @elseif($community->membership_status === 'approved')
                                            <span class="status-badge badge-joined" style="width: 100%; justify-content: center;">
                                                <i class="fas fa-check"></i> Bergabung
                                            </span>
                                        @elseif($community->membership_status === 'pending')
                                            <span class="status-badge badge-pending" style="width: 100%; justify-content: center;">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @elseif($community->membership_status === 'rejected')
                                            <span class="status-badge badge-rejected" style="width: 100%; justify-content: center;">
                                                <i class="fas fa-times"></i> Ditolak
                                            </span>
                                        @elseif($community->membership_status === 'removed')
                                            <span class="status-badge badge-rejected" style="width: 100%; justify-content: center;">
                                                <i class="fas fa-user-slash"></i> Dikeluarkan
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            @endauth

                            <div class="action-buttons">
                                @auth
                                    <a href="{{ route('buyer.communities.show', $community->id) }}" class="btn-action btn-detail">
                                        Detail
                                    </a>
                                @else
                                    <a href="#" class="btn-action btn-detail" onclick="showDetailModal({{ $community->id }})">
                                        Detail
                                    </a>
                                @endauth

                                @auth
                                    {{-- Untuk USER YANG LOGIN (BUYER) --}}
                                    @if($community->membership_status === 'removed')
                                        <form method="POST" action="{{ route('buyer.communities.requestRejoin', $community->id) }}" class="join-form" style="flex: 1;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-join" style="background:#fff3cd; border-color:#ffeeba; color:#856404;">
                                                <i class="fas fa-undo"></i> Minta Bergabung Kembali
                                            </button>
                                        </form>
                                    @elseif(!$community->role && $community->membership_status !== 'approved' && $community->membership_status !== 'pending')
                                        @if($community->type !== 'private')
                                            <form method="POST" action="{{ route('buyer.communities.join.store', $community->id) }}" class="join-form" style="flex: 1;">
                                                @csrf
                                                <button type="submit" class="btn-action btn-join">
                                                    @if($community->membership_status === 'rejected')
                                                        Coba Lagi
                                                    @elseif($community->requires_approval)
                                                        Ajukan
                                                    @else
                                                        Gabung
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    {{-- Untuk GUEST --}}
                                    <a href="{{ route('login') }}" class="btn-action btn-join" style="flex: 1;">
                                        <i class="fas fa-sign-in-alt"></i> Login untuk Bergabung
                                    </a>
                                @endauth
                            </div>
                            
                            @guest
                                <div style="text-align: center; margin-top: 12px; font-size: 11px; color: #666;">
                                    <i class="fas fa-info-circle"></i> Login diperlukan untuk bergabung dengan komunitas
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            @endforeach

            @php
                $visibleCommunities = auth()->check() ? $communities : $communities->where('type', 'public');
            @endphp
            
            @if($visibleCommunities->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-users empty-state-icon"></i>
                    <h3 class="empty-state-title">Tidak ada komunitas ditemukan</h3>
                    <p class="empty-state-desc">
                        @auth
                            Saat ini belum ada komunitas yang tersedia untuk bergabung
                        @else
                            Saat ini belum ada komunitas yang tersedia
                        @endauth
                    </p>
                </div>
            @endif
        </div>
    </main>

    @include('layouts.bottom-nav')

    <!-- ================= LOADING OVERLAY ================= -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, kami sedang memproses permintaan Anda.</div>
    </div>
</div>

<!-- Modal untuk detail komunitas (untuk guest) -->
@guest
<div id="detailModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; width: 90%; max-width: 400px; border-radius: 12px; padding: 20px; position: relative;">
        <button class="modal-close" onclick="closeDetailModal()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer; color: #666;">&times;</button>
        <div id="modalContent">
            <!-- Konten akan diisi via JavaScript -->
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('login') }}" class="btn-action btn-join" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Login untuk Bergabung
            </a>
        </div>
    </div>
</div>
@endguest
@endsection

@push('scripts')
<script>
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

    document.addEventListener('DOMContentLoaded', function() {
        @auth
        // ================= FORM SUBMISSION AJAX (HANYA UNTUK USER LOGIN) =================
        document.addEventListener('submit', async function(e) {
            if (!e.target.classList.contains('join-form')) return;
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const card = form.closest('.community-card');
            
            // Disable button & show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            
            // Show full screen loading
            const isRejoin = submitBtn.innerText.toLowerCase().includes('bergabung kembali');
            showLoading(isRejoin ? 'Sedang Meminta Bergabung Kembali...' : 'Sedang Mengajukan...');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                
                // Hide full screen loading
                hideLoading();

                if (response.ok) {
                    // Update UI based on status
                    let statusContainer = card.querySelector('.status-container');
                    let statusBadge = card.querySelector('.status-badge');
                    
                    if (!statusContainer) {
                        statusContainer = document.createElement('div');
                        statusContainer.className = 'status-container';
                        statusContainer.style.width = '100%';
                        statusContainer.style.marginBottom = '12px';
                        statusContainer.style.textAlign = 'center';
                        
                        // Insert before action-buttons
                        const actionButtons = card.querySelector('.action-buttons');
                        if (actionButtons) {
                            actionButtons.parentNode.insertBefore(statusContainer, actionButtons);
                        } else {
                            card.querySelector('.card-bottom').appendChild(statusContainer);
                        }
                    }

                    if (!statusBadge) {
                        statusBadge = document.createElement('span');
                        statusBadge.className = 'status-badge';
                        statusBadge.style.width = '100%';
                        statusBadge.style.justifyContent = 'center';
                        statusContainer.appendChild(statusBadge);
                    }

                    if (data.status === 'approved') {
                        statusBadge.className = 'status-badge badge-joined';
                        statusBadge.innerHTML = '<i class="fas fa-check"></i> Sudah Bergabung';
                        
                        // Remove the form
                        form.remove();
                    } else if (data.status === 'pending') {
                        statusBadge.className = 'status-badge badge-pending';
                        statusBadge.innerHTML = '<i class="fas fa-clock"></i> Menunggu Persetujuan';
                        
                        // Remove the form
                        form.remove();
                    }

                    showToast(data.message || 'Permintaan berhasil dikirim', 'success');
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                // Hide full screen loading
                hideLoading();
                console.error('AJAX error:', error);
                showToast('Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
        @endauth

        // ================= LIVE SEARCH =================
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                
                if (searchTerm.length < 2) {
                    return;
                }
                
                searchTimeout = setTimeout(async () => {
                    try {
                        @auth
                            if (!this.dataset.url) return;
                            const url = this.dataset.url + '?q=' + encodeURIComponent(searchTerm);
                        @else
                            // Untuk guest, tidak ada search AJAX
                            return;
                        @endauth
                        
                        const response = await fetch(url);
                        const data = await response.text();
                        
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        const newList = doc.getElementById('joinCommunityList');
                        
                        if (newList) {
                            document.getElementById('joinCommunityList').innerHTML = newList.innerHTML;
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                    }
                }, 500);
            });
        }
    });

    @guest
    // ================= FUNGSI MODAL UNTUK GUEST =================
    function showDetailModal(communityId) {
        // Tampilkan modal loading
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        
        modalContent.innerHTML = `
            <div style="text-align: center; padding: 20px;">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Memuat detail komunitas...</p>
            </div>
        `;
        
        modal.style.display = 'flex';
        
        // Cari komunitas dari data yang sudah ada
        const communities = {!! $communities->toJson() !!};
        const community = communities.find(c => c.id == communityId);
        
        if (community) {
            modalContent.innerHTML = `
                <h3 style="margin-bottom: 10px; color: #0A5C36;">${community.name}</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">${community.description || 'Tidak ada deskripsi'}</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                    <div>
                        <small style="color: #888;">Kategori</small>
                        <p style="margin: 5px 0; font-weight: bold;">${community.category?.category_name || 'Olahraga'}</p>
                    </div>
                    <div>
                        <small style="color: #888;">Tipe</small>
                        <p style="margin: 5px 0; font-weight: bold;">${community.type === 'public' ? 'Publik' : 'Privat'}</p>
                    </div>
                    <div>
                        <small style="color: #888;">Anggota</small>
                        <p style="margin: 5px 0; font-weight: bold;">${community.members_count || 0} orang</p>
                    </div>
                    <div>
                        <small style="color: #888;">Dibuat</small>
                        <p style="margin: 5px 0; font-weight: bold;">${new Date(community.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
            `;
        } else {
            modalContent.innerHTML = '<p>Komunitas tidak ditemukan</p>';
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    @endguest

    // ================= TOAST FUNCTION =================
    function showToast(message, type = 'success') {
        @auth
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                ${message}
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 400);
            }, 3000);
        @endauth
    }
</script>
@endpush

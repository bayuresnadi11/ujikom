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
        
        /* Color classes for notifications */
        --color-primary: #0A5C36;
        --color-success: #2ecc71;
        --color-warning: #f39c12;
        --color-danger: #e74c3c;
        --color-info: #3498db;
        --color-purple: #9b59b6;
        --color-pink: #fd79a8;
        --color-teal: #00b894;
        
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
        background: #f8faf9;
        position: relative;
        overflow-x: hidden;
        max-width: 100%;
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding: 85px 16px 85px;
        min-height: 100vh;
    }

    /* ================= PAGE HEADER ================= */
    .page-header {
        margin-bottom: 20px;
        padding-top: 16px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 6px;
        line-height: 1.2;
        text-align: left;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.4;
        font-weight: 500;
        text-align: left;
    }

    /* ================= ACTIONS BAR ================= */
    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding: 16px;
        background: white;
        border-radius: var(--radius-lg);
        border: 2px solid var(--border);
        box-shadow: var(--shadow-sm);
        flex-wrap: wrap;
        gap: 12px;
    }

    .notification-stats {
        display: flex;
        gap: 24px;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-width: 60px;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 900;
        color: var(--primary);
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-action {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-action:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* ================= NOTIFICATION TABS ================= */
    .notification-tabs {
        display: flex;
        gap: 8px;
        background: #eaf7f1;
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .notification-tabs::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        flex: 1;
        min-width: 100px;
        border: none;
        background: transparent;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #2f7d5a;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        position: relative;
    }

    .tab-btn.active {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .tab-btn:not(.active):hover {
        background: rgba(46, 204, 113, 0.15);
    }

    .badge-count {
        background: var(--danger);
        color: white;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 8px;
        margin-left: 4px;
    }

    /* ================= NOTIFICATION TABS PILL STYLE ================= */
    .notification-tabs-pill {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        justify-content: flex-start;
        padding: 0;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .pill-btn {
        border: 1px solid #EAEAEA;
        background: white;
        color: #555;
        padding: 8px 20px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        position: relative;
        box-shadow: none;
    }

    .pill-btn:hover {
        background: #F9F9F9;
        border-color: #DDD;
        transform: none;
        box-shadow: none;
    }

    .pill-btn.active {
        background: #E8F5F1;
        color: #0A5C36;
        border-color: #0A5C36;
        box-shadow: none;
        font-weight: 700;
    }

    .pill-badge {
        background: #FF4B4B;
        color: white;
        font-size: 11px;
        font-weight: 800;
        min-width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin-left: 8px;
        box-shadow: 0 2px 4px rgba(255, 75, 75, 0.2);
    }

    /* ================= COMMUNITY CONTEXT HEADER ================= */
    .community-context-header {
        display: none; /* Hidden by default, toggled via JS */
        flex-direction: row;
        align-items: flex-start;
        padding: 20px 16px;
        background: white;
        margin-bottom: 20px;
        animation: fadeInDown 0.4s ease;
        gap: 24px;
        border-bottom: 1px solid var(--border);
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .community-context-header::-webkit-scrollbar {
        display: none;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .community-context-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 8px;
        padding: 4px;
        transition: all 0.3s ease;
        cursor: pointer;
        min-width: 80px;
        flex-shrink: 0;
    }

    .community-context-item.active {
        transform: translateY(-2px);
    }

    .community-context-logo-container {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        padding: 3px;
        background: white;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.1);
        border: 2px solid #E1F0E7;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .community-context-item.active .community-context-logo-container {
        border-color: var(--primary);
        box-shadow: 0 6px 16px rgba(10, 92, 54, 0.15);
        transform: scale(1.05);
    }

    .community-context-logo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .community-context-info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .community-context-name {
        font-size: 13px;
        font-weight: 800;
        color: #8A9C93;
        margin-bottom: 1px;
        line-height: 1.3;
        transition: all 0.2s ease;
    }

    .community-context-item.active .community-context-name {
        color: var(--primary);
    }

    .community-context-category {
        font-size: 10px;
        color: #B2BEC3;
        font-weight: 600;
        text-transform: capitalize;
        line-height: 1.2;
    }

    /* ================= FILTER TABS ================= */
    .filter-tabs {
        display: flex;
        gap: 0;
        padding: 0;
        border-radius: 0;
        margin-bottom: 20px;
        background: transparent;
        border: none;
        border-bottom: 2px solid #F0F0F0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .filter-tabs::-webkit-scrollbar, .notification-tabs-pill::-webkit-scrollbar {
        display: none;
    }

    .filter-btn {
        flex: 1;
        min-width: 100px;
        border: none;
        background: transparent;
        padding: 14px 16px;
        font-size: 15px;
        font-weight: 600;
        color: #888;
        border-radius: 0;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        white-space: nowrap;
        position: relative;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
    }

    .filter-btn.active {
        background: transparent;
        color: var(--primary);
        box-shadow: none;
        font-weight: 700;
        transform: none;
        border-bottom: 3px solid var(--primary);
    }

    .filter-btn:not(.active):hover {
        background: rgba(46, 204, 113, 0.1);
        color: var(--primary);
    }

    /* ================= NOTIFICATIONS LIST ================= */
    .notifications-list {
        padding-bottom: 20px;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        background: white;
        border-radius: 14px;
        border: 1px solid #eee;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        top: -1px;
        left: -1px;
        bottom: -1px;
        width: 3px;
        background: var(--success);
        border-radius: 14px 0 0 14px;
    }

    .notification-item:hover {
        border-color: #0A5C36;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.08);
    }

    .notification-item.unread {
        background: #F8FDFB;
        border-color: #E6F7EF;
    }

    .notification-item.read {
        opacity: 0.95;
    }

    .notification-item.read:hover {
        opacity: 1;
    }

    /* Notification Icon */
    .notification-icon, .notification-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
        object-fit: cover;
    }

    .notification-icon.booking {
        background: var(--color-info);
    }

    .notification-icon.promo {
        background: var(--color-danger);
    }

    .notification-icon.reminder {
        background: var(--color-warning);
    }

    .notification-icon.community,
    .notification-icon.community_removed,
    .notification-icon.community_role_changed,
    .notification-icon.community_message,
    .notification-icon.community_invitation {
        background: var(--color-purple);
    }

    .notification-icon.system {
        background: var(--color-teal);
    }

    .notification-icon.update {
        background: var(--color-success);
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
        gap: 8px;
        padding-right: 20px;
    }

    .notification-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        line-height: 1.4;
        flex: 1;
    }

    .notification-time {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .notification-message {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
        margin: 0;
        margin-bottom: 10px;
    }

    .notification-message i {
        margin-right: 6px;
    }

    /* Notification Actions */
    .notification-actions {
        display: flex;
        gap: 8px;
        flex-direction: column;
    }

    .btn-notification-action {
        background: #f8f9fa;
        color: var(--primary);
        border: 1px solid #eee;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        width: 100%;
    }

    .btn-notification-action:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Membership Actions (Accept/Reject) */
    .membership-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        width: 100%;
    }

    .btn-approve, .btn-reject {
        flex: 1;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        text-decoration: none;
    }

    .btn-approve {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.2);
    }

    .btn-approve:active {
        transform: scale(0.96);
        box-shadow: 0 2px 6px rgba(46, 204, 113, 0.2);
    }

    .btn-reject {
        background: #F8F9FA;
        color: #636E72;
        border: 1px solid #E9ECEF;
    }

    .btn-reject:active {
        transform: scale(0.96);
        background: #F1F2F6;
    }

    .btn-reject:hover {
        background: #FFEBEB;
        color: #E74C3C;
        border-color: #FFD2D2;
    }

    /* Action Status (After action taken) */
    .action-status {
        margin-top: 10px;
        width: 100%;
    }

    .action-status .badge {
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        border: 1px solid transparent;
    }

    .bg-success { background: #F0FAF5 !important; color: #27AE60 !important; border-color: #D5F5E3 !important; }
    .bg-danger { background: #FFF5F5 !important; color: #E74C3C !important; border-color: #FFD2D2 !important; }

    .btn-delete-notification {
        position: absolute;
        top: 8px;
        right: 8px;
        background: transparent;
        color: #B2BEC3;
        border: none;
        padding: 6px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        opacity: 0.5;
        z-index: 10;
    }

    .btn-delete-notification:hover {
        color: var(--danger);
        opacity: 1;
        background: #FFF5F5;
        border-radius: 8px;
    }

    .unread-indicator {
        position: absolute;
        top: 10px;
        right: 36px;
        width: 8px;
        height: 8px;
        background: var(--danger);
        border-radius: 50%;
        border: 1.5px solid white;
        box-shadow: 0 0 0 1.5px var(--light);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* ================= LOAD MORE SECTION ================= */
    .load-more-section {
        text-align: center;
        padding: 20px 0;
    }

    .btn-load-more {
        background: var(--light);
        color: var(--text);
        border: 2px solid var(--border);
        padding: 12px 24px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-load-more:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-load-more:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-radius: 20px;
        border: 2px dashed rgba(10, 92, 54, 0.2);
        margin: 20px 0;
    }

    .empty-state-icon {
        font-size: 64px;
        color: var(--light-gray);
        margin-bottom: 20px;
    }

    .empty-state-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 12px 0;
    }

    .empty-state-desc {
        font-size: 14px;
        color: var(--text-light);
        margin: 0;
        line-height: 1.5;
    }

    /* ================= TOAST CONTAINER ================= */
    .toast-container {
        position: fixed;
        bottom: 80px;
        left: 16px;
        right: 16px;
        z-index: 3000;
        pointer-events: none;
    }

    .toast {
        background: var(--gradient-primary);
        color: white;
        padding: 16px;
        margin-bottom: 8px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateY(100px);
        transition: transform 0.3s ease, opacity 0.3s ease;
        opacity: 0;
        pointer-events: auto;
        animation: slideInUp 0.3s ease forwards;
    }

    @keyframes slideInUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .toast i {
        font-size: 20px;
    }

    .toast.success {
        background: var(--gradient-accent);
    }

    .toast.error {
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
    }

    .toast.info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .toast.warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    /* ================= BOTTOM NAVIGATION ================= */
    /* Diubah sesuai dengan style home */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 0;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 2000;
        border-top: 1px solid var(--light-gray);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 10px;
        color: var(--text-light);
        text-decoration: none;
        padding: 4px 12px;
        transition: all 0.3s ease;
        border-radius: 10px;
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 56px;
    }

    .nav-item.active {
        color: var(--primary);
        transform: translateY(-6px);
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
        font-size: 18px;
        margin-bottom: 4px;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: var(--light);
        color: var(--text-light);
    }

    .nav-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nav-badge {
        position: absolute;
        top: -2px;
        right: 4px;
        background: var(--danger);
        color: white;
        font-size: 9px;
        font-weight: 800;
        min-width: 16px;
        height: 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid white;
        animation: pulse 1.5s infinite;
    }

    /* ================= FIXED HEADER ================= */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: white;
        z-index: 1000;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .header-left {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .back-button {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0A5C36;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.2s;
        margin-right: 15px;
    }

    .back-button:hover {
        transform: scale(1.1);
    }

    .header-title {
        font-size: 20px;
        font-weight: 800;
        color: #0A5C36;
        letter-spacing: -0.5px;
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

    /* ================= RESPONSIVE ================= */
    @media (max-width: 480px) {
        .mobile-container {
            padding-bottom: 65px;
        }
        
        .main-content {
            padding: 70px 12px 80px;
        }
        
        .page-title {
            font-size: 22px;
        }
        
        .actions-bar {
            flex-direction: column;
            align-items: stretch;
            padding: 12px;
        }
        
        .notification-stats {
            justify-content: space-around;
        }
        
        .tab-btn, .filter-btn {
            min-width: 80px;
            padding: 10px 12px;
            font-size: 12px;
        }
        
        .notification-item {
            padding: 12px;
            gap: 12px;
        }
        
        .notification-icon {
            width: 44px;
            height: 44px;
            font-size: 18px;
        }
        
        .notification-title {
            font-size: 15px;
        }
        
        .notification-message {
            font-size: 13px;
        }
        
        .notification-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .membership-actions {
            flex-direction: column;
        }
        
        .btn-approve, .btn-reject {
            width: 100%;
        }
        
        /* Bottom Nav Responsive - Diubah */
        .bottom-nav {
            padding: 8px 0;
        }
        
        .nav-item {
            padding: 4px 12px;
            max-width: 70px;
            min-width: 56px;
        }
        
        .nav-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
            margin-bottom: 4px;
        }
        
        .nav-label {
            font-size: 10px;
        }
        
        .nav-badge {
            font-size: 8px;
            min-width: 14px;
            height: 14px;
        }
        
        .nav-item.active {
            transform: translateY(-6px);
        }
        
        .nav-item.active::after {
            top: -4px;
            width: 24px;
            height: 3px;
        }
        
        /* Fixed Header Responsive */
        .fixed-header {
            padding: 10px 12px;
        }
        
        .back-button {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }
        
        .header-title {
            font-size: 16px;
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
    }

    @media (max-width: 360px) {
        .main-content {
            padding: 65px 10px 75px;
        }
        
        .page-title {
            font-size: 20px;
        }
        
        .tab-btn, .filter-btn {
            min-width: 70px;
            padding: 8px 10px;
            font-size: 11px;
        }
        
        .notification-item {
            padding: 10px;
            gap: 10px;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .notification-title {
            font-size: 14px;
        }
        
        .notification-message {
            font-size: 12px;
        }
        
        /* Bottom Nav untuk layar sangat kecil */
        .bottom-nav {
            padding: 6px 0;
        }
        
        .nav-item {
            padding: 3px 6px;
            max-width: 60px;
            min-width: 50px;
        }
        
        .nav-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
            margin-bottom: 2px;
        }
        
        .nav-label {
            font-size: 9px;
        }
        
        .nav-badge {
            font-size: 7px;
            min-width: 12px;
            height: 12px;
        }
        
        .nav-item.active {
            transform: translateY(-6px);
        }
        
        .nav-item.active::after {
            top: -4px;
            width: 20px;
            height: 3px;
        }
    }

    @media (min-width: 481px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: var(--radius-2xl);
            overflow: hidden;
            padding-bottom: 70px;
        }
        
        .fixed-header {
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
            padding: 8px 0;
        }
        
        .nav-item {
            min-width: 56px;
        }
    }

    /* ================= UTILITIES ================= */
    .text-success { color: var(--success); }
    .text-danger { color: var(--danger); }
    .text-warning { color: var(--warning); }
    .text-info { color: var(--info); }
    
    .mt-1 { margin-top: 4px; }
    .mt-2 { margin-top: 8px; }
    .mt-3 { margin-top: 12px; }
    .mt-4 { margin-top: 16px; }
    .mt-5 { margin-top: 20px; }
    
    .mb-1 { margin-bottom: 4px; }
    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 16px; }
    .mb-5 { margin-bottom: 20px; }
    
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    
    .w-full { width: 100%; }
    .h-full { height: 100%; }
    /* ================= MENU DOTS BUTTON ================= */
    .menu-dots-btn {
        background: none;
        border: none;
        color: #0A5C36;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .menu-dots-btn:hover {
        background: rgba(10, 92, 54, 0.05);
        border-radius: 50%;
    }

    /* ================= BOTTOM SHEET ================= */
    .bottom-sheet-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 3000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .bottom-sheet-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .bottom-sheet {
        position: fixed;
        bottom: -100%;
        left: 0;
        width: 100%;
        background: white;
        z-index: 3001;
        border-radius: 20px 20px 0 0;
        padding: 12px 0 30px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    }

    .bottom-sheet.active {
        bottom: 0;
    }

    @media (min-width: 481px) {
        .bottom-sheet {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            bottom: -100%;
        }
        .bottom-sheet.active {
            bottom: 0;
        }
    }

    .bottom-sheet-handle {
        width: 40px;
        height: 4px;
        background: #E0E0E0;
        border-radius: 2px;
        margin: 0 auto 16px;
    }

    .bottom-sheet-content {
        padding: 0 16px;
    }

    .bottom-sheet-item {
        display: flex;
        align-items: center;
        padding: 16px;
        gap: 16px;
        cursor: pointer;
        border-radius: 12px;
        transition: background 0.2s;
    }

    .bottom-sheet-item:hover {
        background: #F5F5F5;
    }

    .bottom-sheet-icon {
        width: 36px;
        height: 36px;
        background: #F0FAF5;
        color: #0A5C36;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .bottom-sheet-text {
        font-size: 16px;
        font-weight: 600;
        color: var(--text);
    }

    .bottom-sheet-icon.delete {
        background: #FFF5F5;
        color: var(--danger);
    }

    /* ================= CUSTOM MODAL ================= */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 4000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        padding: 20px;
    }

    .custom-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .custom-modal {
        background: white;
        width: 100%;
        max-width: 320px;
        border-radius: 24px;
        padding: 30px 24px;
        text-align: center;
        transform: scale(0.9);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .custom-modal-overlay.active .custom-modal {
        transform: scale(1);
    }

    .modal-icon {
        width: 64px;
        height: 64px;
        background: #FFF5F5;
        color: var(--danger);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 20px;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 12px;
    }

    .modal-message {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .modal-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .modal-btn {
        width: 100%;
        padding: 14px;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
    }

    .btn-confirm:active {
        transform: scale(0.98);
        opacity: 0.9;
    }

    .btn-cancel {
        background: #F5F5F5;
        color: #666;
    }

    .btn-cancel:active {
        background: #EAEAEA;
    }

    .empty-state-filtered {
        padding: 40px 20px;
        text-align: center;
        background: transparent;
        border: none;
        box-shadow: none;
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
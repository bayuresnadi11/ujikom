<style>
    /* ================= VARIABLES ================= */
    /* ================= VARIABLES ================= */
    :root {
        --primary: #8B1538;
        --primary-dark: #6B0F2A;
        --primary-light: #A01B42;
        --secondary: #A01B42;
        --accent: #C7254E;
        --success: #8B1538;
        --warning: #F39C12;
        --danger: #E74C3C;
        --info: #3498DB;
        --light: #FFF5F7;
        --light-gray: #FFE4E8;
        --border: #FFE4E8;
        --text: #2C1810;
        --text-light: #5a3a2a;
        --text-lighter: #8B1538;
        --shadow-sm: 0 1px 3px rgba(139, 21, 56, 0.1);
        --shadow-md: 0 2px 8px rgba(139, 21, 56, 0.15);
        --shadow-lg: 0 4px 12px rgba(139, 21, 56, 0.2);
        --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);
        
        /* Color classes for notifications */
        --color-primary: #8B1538;
        --color-success: #8B1538;
        --color-warning: #F39C12;
        --color-danger: #E74C3C;
        --color-info: #3498DB;
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
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
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
        padding-bottom: 70px; /* Space for bottom nav */
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding: 80px 16px 90px;
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
        background: #FFF5F7;
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
        color: #A01B42;
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
        background: rgba(139, 21, 56, 0.15);
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

    /* ================= FILTER TABS ================= */
    .filter-tabs {
        display: flex;
        gap: 8px;
        padding: 4px;
        border-radius: 12px;
        margin-bottom: 16px;
        background: #FFF5F7;
        border: 1px solid var(--light-gray);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .filter-tabs::-webkit-scrollbar {
        display: none;
    }

    .filter-btn {
        flex: 1;
        min-width: 100px;
        border: none;
        background: transparent;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-light);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
        position: relative;
    }

    .filter-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 8px rgba(139, 21, 56, 0.2);
        font-weight: 700;
        transform: translateY(-1px);
    }

    .filter-btn:not(.active):hover {
        background: rgba(139, 21, 56, 0.1);
        color: var(--primary);
    }

    /* ================= NOTIFICATIONS LIST ================= */
    .notifications-list {
        padding-bottom: 20px;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 2px solid transparent;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        border-left: 4px solid var(--primary);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .notification-item.unread {
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-left-color: var(--success);
    }

    .notification-item.read {
        opacity: 0.8;
    }

    .notification-item.read:hover {
        opacity: 1;
    }

    /* Notification Icon */
    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
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
        margin-bottom: 8px;
        gap: 12px;
    }

    .notification-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin: 0;
        line-height: 1.3;
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
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.5;
        margin: 0;
        margin-bottom: 12px;
    }

    .notification-message i {
        margin-right: 6px;
    }

    /* Notification Actions */
    .notification-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .btn-notification-action {
        background: var(--light);
        color: var(--primary);
        border: 2px solid var(--light-gray);
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-notification-action:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-1px);
    }

    .btn-delete-notification {
        background: transparent;
        color: var(--text-light);
        border: none;
        padding: 6px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        opacity: 0.6;
    }

    .btn-delete-notification:hover {
        color: var(--danger);
        opacity: 1;
        transform: scale(1.1);
    }

    /* Membership Actions */
    .membership-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-approve, .btn-reject {
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: none;
        flex: 1;
        justify-content: center;
    }

    .btn-approve {
        background: var(--gradient-accent);
        color: white;
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.3);
    }

    .btn-reject {
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
        color: white;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .unread-indicator {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 10px;
        height: 10px;
        background: var(--danger);
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--light);
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
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-radius: 20px;
        border: 2px dashed rgba(139, 21, 56, 0.2);
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
        box-shadow: 0 -2px 12px rgba(139, 21, 56, 0.1);
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
        background: rgba(139, 21, 56, 0.05);
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
        background: var(--gradient-dark);
        z-index: 1000;
        box-shadow: var(--shadow-md);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .back-button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: white;
        font-size: 18px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-2px);
    }

    .header-title {
        font-size: 18px;
        font-weight: 700;
        color: white;
        letter-spacing: -0.3px;
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
</style>
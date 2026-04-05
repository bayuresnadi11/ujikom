<style>
    /* ================= VARIABLES ================= */
    :root {
        --primary: #0A5C36;
        --primary-dark: #08482B;
        --secondary: #27AE60;
        --accent: #2ECC71;
        --light: #F8F9FA;
        --light-gray: #E9ECEF;
        --text: #212529;
        --text-light: #6C757D;
        --danger: #E74C3C;
        --gold: #FFD700;
        --warning: #F59E0B;
        
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
        --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-lg: 0 4px 12px rgba(0,0,0,0.12);
        --shadow-xl: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* ================= GLOBAL ================= */
    * {
        box-sizing: border-box;
    }
    
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.4;
        color: var(--text);
        background: #f5f5f5;
    }

    /* ================= MOBILE CONTAINER ================= */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        max-width: 100%;
    }

    /* ================= HEADER ================= */
    .mobile-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: var(--gradient-dark);
        z-index: 1100;
        box-shadow: var(--shadow-md);
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
        border-radius: 8px;
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
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
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
    }
    
    .header-icon:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding-top: 80px;
        padding-bottom: 70px;
        min-height: 100vh;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ================= MENU HEADER ================= */
    .menu-header {
        margin-bottom: 20px;
        padding: 16px 16px 0;
        text-align: center;
    }

    .menu-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
        line-height: 1.3;
        text-align: center;
    }

    .menu-subtitle {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.4;
        font-weight: 500;
        text-align: center;
        max-width: 280px;
        margin: 0 auto;
    }

    /* ================= LOGIN REMINDER ================= */
    .login-reminder {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        border-radius: 12px;
        padding: 16px;
        margin: 20px 16px;
        border: 1px solid #ffd54f;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .login-reminder-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #ffb300 0%, #ff8f00 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .login-reminder-text h3 {
        font-size: 13px;
        font-weight: 800;
        color: #e65100;
        margin-bottom: 4px;
    }

    .login-reminder-text p {
        font-size: 11px;
        color: #8d6e63;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .login-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-login, .btn-register {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 11px;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: var(--shadow-sm);
    }

    .btn-login {
        background: var(--gradient-primary);
        color: white;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-register {
        background: white;
        color: var(--primary);
        border: 1px solid var(--accent);
    }

    .btn-register:hover {
        background: var(--light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ================= RECENT ACTIVITY ================= */
    .recent-activity {
        margin: 20px 16px;
    }

    .activity-list {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .activity-item {
        background: white;
        border-radius: 10px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow-sm);
        border-left: 3px solid var(--secondary);
        position: relative;
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .activity-info {
        flex: 1;
        min-width: 0;
    }

    .activity-info h4 {
        font-size: 13px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-info p {
        font-size: 11px;
        color: var(--text-light);
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-time {
        font-size: 10px;
        color: #9CA3AF;
        font-weight: 600;
    }

    .activity-action {
        background: var(--light);
        color: var(--primary);
        border: 1px solid var(--light-gray);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .activity-action:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .activity-action-mobile {
    margin-left: auto;
    color: #9CA3AF;
    flex-shrink: 0;
}


    /* ================= QUICK STATS ================= */
    .quick-stats {
        margin: 20px 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 12px;
    }

    .stat-item {
        background: white;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        background: var(--light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: var(--primary);
        margin: 0 auto 8px;
        border: 1px solid var(--light-gray);
    }

    .stat-info h4 {
        font-size: 18px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 3px;
    }

    .stat-info p {
        font-size: 11px;
        color: var(--text-light);
    }

    /* ================= MENU CATEGORIES ================= */
    .menu-categories {
        margin-bottom: 24px;
        padding: 0 16px;
        width: 100%;
    }

    .category-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        margin: 20px 0 12px;
        position: relative;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .category-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .category-title i {
        color: var(--accent);
        font-size: 13px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
        width: 100%;
    }

    /* Menu Card dengan background warna terang */
    .menu-card {
        background: var(--light);
        border-radius: 12px;
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        border: 1px solid var(--light-gray);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        min-height: 140px;
        justify-content: space-between;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .menu-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent);
        box-shadow: var(--shadow-md);
    }

    .menu-card.highlight {
        background: var(--gradient-light);
        border: 1px solid var(--accent);
    }

    .menu-card.featured {
        grid-column: span 2;
        background: var(--gradient-primary);
        color: white;
        border: none;
        min-height: 130px;
        padding: 14px 12px;
    }

    .menu-card.featured .menu-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        box-shadow: 0 2px 4px rgba(255, 255, 255, 0.2);
    }

    .menu-card.featured .menu-name {
        color: white;
        font-size: 14px;
    }

    .menu-card.featured .menu-desc {
        color: rgba(255, 255, 255, 0.9);
        font-size: 11px;
    }

    .menu-card.locked {
        opacity: 0.7;
        position: relative;
    }

    .menu-card.locked::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 1;
        border-radius: 12px;
    }

    .lock-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--warning);
        font-size: 12px;
        z-index: 2;
    }

    .menu-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(46, 204, 113, 0.2);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .menu-card:hover .menu-icon {
        transform: scale(1.05);
    }

    .menu-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
        transition: color 0.3s ease;
        line-height: 1.2;
        width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .menu-desc {
        font-size: 11px;
        color: var(--text-light);
        line-height: 1.2;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .menu-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--accent);
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 8px;
        z-index: 2;
    }

    /* ================= DUMMY CONTENT PREVIEW ================= */
    .dummy-preview {
        background: var(--gradient-light);
        border-radius: 10px;
        padding: 16px;
        margin: 20px 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
        position: relative;
        overflow: hidden;
    }
    
    .dummy-preview::before {
        content: "";
        position: absolute;
        top: -20px;
        right: -20px;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .preview-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 12px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .dummy-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    .dummy-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: white;
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    
    .dummy-item:hover {
        border-color: var(--accent);
        transform: translateX(2px);
    }

    .dummy-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: var(--light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        border: 1px solid var(--light-gray);
    }

    .dummy-info {
        flex: 1;
        min-width: 0;
    }

    .dummy-info h4 {
        font-size: 13px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 3px;
    }

    .dummy-info p {
        font-size: 11px;
        color: var(--text-light);
        line-height: 1.2;
    }

    .dummy-badge {
        background: var(--accent);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 8px;
        white-space: nowrap;
        flex-shrink: 0;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .dummy-badge:hover {
        background: var(--secondary);
        transform: translateY(-1px);
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
        padding: 8px 0;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 1000;
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

    /* ================= UTILITIES ================= */
    .btn-view-all {
        background: transparent;
        border: none;
        color: var(--primary);
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }

    .btn-view-all:hover {
        color: var(--primary-dark);
        gap: 6px;
    }

    /* ================= RESPONSIVE ADJUSTMENTS ================= */
    @media (max-width: 360px) {
        .main-content {
            padding-top: 70px;
            padding-bottom: 60px;
        }
        
        .menu-categories {
            padding: 0 12px;
        }
        
        .menu-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        
        .menu-card.featured {
            grid-column: span 2;
        }
        
        .menu-card {
            padding: 14px 10px;
            min-height: 130px;
        }
        
        .menu-icon {
            width: 40px;
            height: 40px;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .menu-name {
            font-size: 12px;
        }
        
        .menu-desc {
            font-size: 10px;
        }
        
        .category-title {
            font-size: 14px;
            margin: 16px 0 10px;
        }
        
        .menu-title {
            font-size: 16px;
        }
        
        .menu-subtitle {
            font-size: 12px;
        }
        
        .recent-activity,
        .dummy-preview,
        .quick-stats,
        .login-reminder {
            margin: 20px 12px;
        }
        
        .dummy-item {
            padding: 10px;
            gap: 10px;
        }
        
        .dummy-icon {
            width: 34px;
            height: 34px;
            font-size: 12px;
        }
        
        .dummy-info h4 {
            font-size: 12px;
        }
        
        .dummy-info p {
            font-size: 10px;
        }
        
        .activity-item {
            padding: 10px;
            gap: 10px;
        }
        
        .activity-icon {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        
        .activity-info h4 {
            font-size: 12px;
        }
        
        .activity-info p {
            font-size: 10px;
        }
        
        .activity-action {
            padding: 5px 10px;
            font-size: 10px;
        }
        
        .login-reminder-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .btn-login, .btn-register {
            padding: 6px 12px;
            font-size: 10px;
        }
    }

    @media (min-width: 480px) {
        .mobile-container {
            max-width: 480px;
            margin: 10px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: 20px;
            overflow: hidden;
        }
        
        .mobile-header {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0;
        }
        
        .main-content {
            padding-top: 90px;
            padding-bottom: 80px;
        }
        
        .menu-grid {
            gap: 14px;
        }
        
        .menu-card {
            padding: 18px 14px;
            min-height: 150px;
        }
        
        .menu-icon {
            width: 48px;
            height: 48px;
            font-size: 18px;
        }
        
        .menu-name {
            font-size: 14px;
        }
        
        .menu-desc {
            font-size: 12px;
        }
        
        .menu-title {
            font-size: 20px;
        }
        
        .menu-subtitle {
            font-size: 14px;
        }
        
        .category-title {
            font-size: 16px;
        }
        
        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 20px 20px 0 0;
        }
        
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Animation for page transition */
    .page-transition {
        opacity: 0;
        transform: translateY(4px);
        animation: pageIn 0.2s ease-out forwards;
    }

    @keyframes pageIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
        .menu-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        .menu-card.featured {
            grid-column: span 2;
        }
        
        .login-reminder {
            flex-direction: column;
            text-align: center;
        }
        
        .login-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-login, .btn-register {
            width: 100%;
            justify-content: center;
        }
    }
</style>
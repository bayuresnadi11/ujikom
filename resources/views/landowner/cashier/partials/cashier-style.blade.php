<style>
    /* VARIABEL CSS YANG SAMA DENGAN MENU */
    /* VARIABEL CSS YANG SAMA DENGAN MENU */
    :root {
        --primary: #8B1538;  /* Burgundy */
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
        --text: #2C1810;
        --text-light: #5a3a2a;
        --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);
        --shadow-sm: 0 2px 8px rgba(139, 21, 56, 0.1);
        --shadow-md: 0 4px 16px rgba(139, 21, 56, 0.15);
        --shadow-lg: 0 8px 24px rgba(139, 21, 56, 0.2);
        --shadow-xl: 0 12px 32px rgba(139, 21, 56, 0.25);
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: linear-gradient(135deg, #FFFFFF 0%, #E6F7ED 100%);
        color: var(--text);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
    }
    
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        position: relative;
        box-shadow: 0 0 35px rgba(139, 21, 56, 0.12);
        overflow-x: hidden;
        padding-bottom: 80px;
    }
    
    /* HEADER - STYLE YANG SAMA DENGAN MENU */
    .mobile-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: var(--gradient-dark);
        z-index: 1100;
        box-shadow: var(--shadow-md);
        backdrop-filter: blur(10px);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .logo {
        font-size: 16px;
        font-weight: 800;
        color: white;
        text-decoration: none;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon {
        background: rgba(255, 255, 255, 0.2);
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .logo span {
        color: #FFE4E8;
        font-weight: 700;
        background: linear-gradient(135deg, #FFF5F7, #FFE4E8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: white;
        padding: 8px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .notification-badge {
        position: absolute;
        top: 4px;
        right: 4px;
        background: var(--danger);
        color: white;
        font-size: 10px;
        font-weight: 800;
        width: 18px;
        height: 18px;
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

    .header-icon:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px) scale(1.05);
    }

    .search-bar {
        padding: 16px 20px;
        background: rgba(255, 255, 255, 0.1);
    }

    .search-container {
        display: flex;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .search-container:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.2);
        transform: translateY(-2px);
    }

    .search-input {
        flex: 1;
        padding: 16px;
        border: none;
        background: transparent;
        font-size: 16px;
        outline: none;
        color: var(--text);
        font-weight: 500;
    }

    .search-input::placeholder {
        color: var(--text-light);
        opacity: 0.8;
    }

    .search-btn {
        background: var(--gradient-accent);
        color: #fff;
        border: none;
        padding: 0 20px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn:hover {
        opacity: 0.9;
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
    }

    /* MAIN CONTENT */
    .main-content {
        padding-top: 50px;
        padding-bottom: 90px;
        min-height: calc(100vh - 170px);
    }

    .page-header {
        padding: 30px 20px;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-title {
        font-size: 32px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .page-subtitle {
        font-size: 16px;
        color: var(--text-light);
        line-height: 1.6;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    /* STATS GRID - STYLE YANG SAMA DENGAN MENU */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        padding: 0 20px;
        margin-top: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        border-color: var(--accent);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        font-size: 28px;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-light);
        font-weight: 600;
    }

    /* ACTION BAR - STYLE BUTTON MENU */
    .action-bar {
        padding: 20px 20px 0;
    }

    .btn-add {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-add:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary {
        background: var(--gradient-accent);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* =========================================== */
    /* ENHANCED CASHIER CARDS - MODERN & PROFESSIONAL */
    /* =========================================== */
    
    /* CASHIER LIST GRID */
    .cashier-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .cashier-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .cashier-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--secondary);
    }

    .cashier-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .cashier-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(139, 21, 56, 0.2);
    }

    .cashier-details {
        display: flex;
        flex-direction: column;
    }

    .cashier-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 4px 0;
    }

    .cashier-phone {
        font-size: 13px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 4px;
    }
    
    .cashier-phone i {
        color: var(--accent);
        font-size: 12px;
    }

    .cashier-meta {
        font-size: 11px;
        color: #95a5a6;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cashier-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-edit {
        background: rgba(241, 196, 15, 0.1);
        color: #f1c40f;
    }

    .btn-edit:hover {
        background: #f1c40f;
        color: white;
    }

    .btn-delete {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .btn-delete:hover {
        background: #e74c3c;
        color: white;
    }

    /* Keep Empty State logic below */
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-radius: 20px;
        border: 2px dashed rgba(139, 21, 56, 0.2);
        margin: 20px;
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(139, 21, 56, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .empty-state-icon {
        font-size: 80px;
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    .empty-state-title {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 10px;
        color: var(--text);
        position: relative;
        z-index: 1;
    }
    
    .empty-state-desc {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 24px;
        line-height: 1.5;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 1;
    }

    /* FORM STYLES */
    .content-card {
        background: white;
        border-radius: 18px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
        margin: 20px;
    }

    .btn-save {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* ALERT MESSAGES */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.1) 0%, rgba(160, 27, 66, 0.1) 100%);
        color: var(--success);
        border: 2px solid rgba(139, 21, 56, 0.2);
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%);
        color: var(--danger);
        border: 2px solid rgba(231, 76, 60, 0.2);
    }

    /* MODAL STYLES */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 2200;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }
    
    .modal-container {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
        border: 2px solid var(--light-gray);
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 2px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
    
    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-close {
        background: var(--light-gray);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-light);
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        transform: rotate(90deg);
        background: var(--danger);
        color: white;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px 24px;
        border-top: 2px solid var(--light-gray);
        display: flex;
        gap: 12px;
        position: sticky;
        bottom: 0;
        background: white;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text);
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.1);
    }

    .form-text {
        font-size: 13px;
        color: var(--text-light);
        margin-top: 6px;
        display: block;
    }

    .text-muted {
        color: var(--text-light) !important;
    }
    
    .btn-modal {
        flex: 1;
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-modal-primary {
        background: var(--gradient-accent);
        color: white;
    }

    .btn-modal-primary:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        transform: translateY(-2px);
    }
    
    .btn-modal-secondary {
        background: var(--light);
        color: var(--text);
        border: 2px solid var(--light-gray);
    }

    .btn-modal-secondary:hover {
        background: var(--light-gray);
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    /* TOAST STYLE YANG SAMA DENGAN MENU */
    .toast {
        position: fixed;
        bottom: 110px;
        left: 50%;
        transform: translateX(-50%) translateY(120px);
        background: var(--gradient-primary);
        color: white;
        padding: 18px 28px;
        border-radius: 14px;
        box-shadow: var(--shadow-xl);
        z-index: 2200;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 14px;
        max-width: 90%;
        font-size: 15px;
    }

    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .toast.success {
        background: var(--gradient-accent);
    }

    .toast.error {
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
    }

    .toast.info {
        background: linear-gradient(135deg, #3498DB 0%, #2980B9 100%);
    }

    /* BOTTOM NAV - STYLE YANG SAMA DENGAN MENU */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 12px 0;
        box-shadow: 0 -5px 25px rgba(139, 21, 56, 0.15);
        z-index: 1000;
        border-top: 2px solid var(--light-gray);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 11px;
        color: var(--text-light);
        text-decoration: none;
        padding: 6px 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 14px;
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 60px;
    }

    .nav-item.active {
        color: var(--primary);
        transform: translateY(-10px);
    }

    .nav-item.active .nav-icon {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-lg);
        transform: scale(1.05);
    }

    .nav-item.active::after {
        content: "";
        position: absolute;
        top: -6px;
        width: 30px;
        height: 4px;
        background: var(--gradient-accent);
        border-radius: 3px;
    }

    .nav-item:hover {
        color: var(--primary);
        background: rgba(10, 92, 54, 0.05);
    }

    .nav-icon {
        font-size: 22px;
        margin-bottom: 6px;
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: var(--light);
        color: var(--text-light);
    }

    /* RESPONSIVE */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 60px rgba(10, 92, 54, 0.25);
            border-radius: 20px;
            overflow: hidden;
        }

        .mobile-header {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0;
        }

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 20px 20px 0 0;
        }

        .section-cards-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .section-cards-container {
            grid-template-columns: 1fr;
        }
        
        .empty-state {
            grid-column: span 1;
        }
        
        .section-badges {
            flex-direction: column;
        }
    }

    @media (max-width: 359px) {
        .page-title {
            font-size: 24px;
        }
        
        .section-title {
            font-size: 16px;
        }
        
        .bottom-nav {
            padding: 10px 0;
        }
        
        .nav-item {
            padding: 4px 8px;
            min-width: 50px;
        }
        
        .nav-icon {
            width: 36px;
            height: 36px;
            font-size: 18px;
        }
        
        .section-header {
            padding: 15px 15px 10px;
        }
        
        .section-content {
            padding: 0 15px 10px;
        }
        
        .section-footer {
            padding: 12px 15px;
        }
    }
</style>
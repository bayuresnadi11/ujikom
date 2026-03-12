<style>
    /* VARIABEL CSS YANG SAMA DENGAN MENU */
    :root {
        /* WARNA AYO BURGUNDY #8B1538 */
        --primary: #8B1538;
        --primary-dark: #6B0F2A;
        --primary-light: #A01B42;
        --secondary: #A01B42;
        --accent: #C7254E;
        
        --success: #27AE60;
        --warning: #F39C12;
        --danger: #C0392B;
        --info: #3498DB;
        
        --light: #FFF5F7;
        --light-gray: #f8f9fa;
        --text: #333333;
        --text-light: #718096;
        
        --gradient-primary: linear-gradient(135deg, #8B1538 0%, #6B0F2A 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --gradient-dark: linear-gradient(135deg, #2C0510 0%, #1A0208 100%);
        
        --shadow-sm: 0 2px 8px rgba(139, 21, 56, 0.08);
        --shadow-md: 0 4px 16px rgba(139, 21, 56, 0.12);
        --shadow-lg: 0 8px 24px rgba(139, 21, 56, 0.16);
        --shadow-xl: 0 12px 32px rgba(139, 21, 56, 0.2);
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: #ffffff;
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
        font-size: 16px; /* Reduced from 24px to match 'Kembali' text size usually found in headers, or keep 24 if that's the title. Wait, user said 'tombol kembali'. */
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
        color: white; /* Changed from gradient to white for 'Kembali' text readability if it's acting as a button label */
        font-weight: 700;
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

    /* MAIN CONTENT - SESUAI DENGAN MENU */
    .main-content {
        padding-top: 50px; /* Adjusted to remove white gap */
        padding-bottom: 90px;
        min-height: calc(100vh - 80px);
    }

    .page-header {
        padding: 40px;
        background: var(--gradient-light);
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

    /* VENUE SELECTOR CARD - STYLE MENU CARD */
    .venue-selector-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        margin: 20px;
        border-top: 6px solid var(--accent);
    }

    .venue-selector-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--accent);
    }

    .venue-selector-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .venue-selector-icon {
        font-size: 24px;
        color: var(--primary);
        background: var(--light);
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }

    .venue-selector-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin: 0;
    }

    .venue-select {
        width: 100%;
        padding: 16px;
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        font-size: 14px;
        color: var(--text);
        background: white;
        outline: none;
        transition: all 0.3s ease;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 16px;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238B1538' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 45px;
    }

    .venue-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.1);
    }

    .venue-info {
        margin-top: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-radius: 12px;
        border-left: 4px solid var(--accent);
        display: flex;
        flex-direction: column;
        gap: 12px;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .venue-info-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .venue-info-icon {
        color: var(--accent);
        font-size: 18px;
        min-width: 24px;
    }

    .venue-info-text {
        color: var(--text);
        font-size: 14px;
        font-weight: 600;
        flex: 1;
    }

    .venue-info-text strong {
        color: var(--primary);
        background: rgba(139, 21, 56, 0.1);
        padding: 2px 8px;
        border-radius: 6px;
        margin-left: 4px;
    }

    .venue-info-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
    }

    .clear-venue-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: white;
        color: var(--danger);
        border: 2px solid #fee2e2;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: var(--shadow-sm);
    }

    .clear-venue-btn:hover {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .empty-venue-state {
        text-align: center;
        padding: 30px 20px;
        background: var(--light);
        border-radius: 12px;
        border: 2px dashed var(--light-gray);
    }

    .empty-venue-state i {
        font-size: 48px;
        color: var(--light-gray);
        margin-bottom: 16px;
        opacity: 0.7;
    }

    .empty-venue-state p {
        color: var(--text-light);
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.5;
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ACTION BAR - STYLE BUTTON MENU */
    .action-bar {
        padding: 0 20px 20px;
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
    /* ENHANCED SECTION CARDS - MODERN & PROFESSIONAL */
    /* =========================================== */
    
    .section-cards-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 0 20px 30px;
    }

    .section-card {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        border: 1px solid rgba(139, 21, 56, 0.08);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        height: 100%;
        min-height: 240px;
        display: flex;
        flex-direction: column;
    }

    .section-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: rgba(199, 37, 78, 0.3);
    }

    .section-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, 
            rgba(199, 37, 78, 0.8) 0%,
            rgba(160, 27, 66, 0.9) 50%,
            rgba(139, 21, 56, 1) 100%);
        z-index: 1;
    }

    /* Card Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px 20px 15px;
        position: relative;
    }

    .section-title-wrapper {
        flex: 1;
        overflow: hidden;
    }

    .section-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .section-venue {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text-light);
        font-size: 13px;
        font-weight: 500;
    }

    .section-venue i {
        color: var(--accent);
        font-size: 12px;
    }

    .section-actions {
        display: flex;
        gap: 6px;
        margin-left: 10px;
    }

    .card-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
        position: relative;
        z-index: 2;
    }

    .card-action-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: var(--shadow-md);
    }

    .btn-edit-card {
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.1) 0%, rgba(241, 196, 15, 0.1) 100%);
        color: var(--warning);
        border: 1px solid rgba(243, 156, 18, 0.2);
    }

    .btn-edit-card:hover {
        background: linear-gradient(135deg, var(--warning) 0%, #f1c40f 100%);
        color: white;
        border-color: var(--warning);
    }

    .btn-delete-card {
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%);
        color: var(--danger);
        border: 1px solid rgba(231, 76, 60, 0.2);
    }

    .btn-delete-card:hover {
        background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%);
        color: white;
        border-color: var(--danger);
    }

    /* Card Content */
    .section-content {
        padding: 0 20px 10px;
        flex: 1;
    }

    .section-description {
        color: var(--text-light);
        font-size: 13px;
        line-height: 1.5;
        padding: 12px;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-radius: 10px;
        border-left: 3px solid var(--accent);
        max-height: 80px;
        overflow-y: auto;
        margin-bottom: 15px;
        position: relative;
    }

    .section-description::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 60%, #FFE4E8 100%);
        pointer-events: none;
    }

    .section-description::-webkit-scrollbar {
        width: 3px;
    }

    .section-description::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .section-description::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--accent) 0%, var(--secondary) 100%);
        border-radius: 10px;
    }

    /* SIMPLE SECTION BADGES */
    .section-badges {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        background: rgba(139, 21, 56, 0.08);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        border: 1px solid rgba(139, 21, 56, 0.2);
        transition: all 0.3s ease;
    }

    .mini-badge:hover {
        background: rgba(139, 21, 56, 0.12);
        transform: translateY(-1px);
    }

    .mini-badge i {
        font-size: 11px;
        color: var(--accent);
    }

    /* Card Footer */
    .section-footer {
        padding: 15px 20px;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    .section-date {
        font-size: 11px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }

    .section-date i {
        font-size: 10px;
        color: var(--accent);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: var(--shadow-sm);
    }

    .badge-active {
        background: linear-gradient(135deg, var(--success) 0%, var(--accent) 100%);
        color: white;
    }

    .badge-inactive {
        background: linear-gradient(135deg, var(--light-gray) 0%, #dee2e6 100%);
        color: var(--text-light);
    }

    .badge-pending {
        background: linear-gradient(135deg, var(--warning) 0%, #f1c40f 100%);
        color: white;
    }

    /* Empty State */
    .empty-state {
        grid-column: span 2;
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        border-radius: 20px;
        border: 2px dashed rgba(139, 21, 56, 0.2);
        margin: 0 20px;
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
        background: radial-gradient(circle, rgba(46, 204, 113, 0.1) 0%, transparent 70%);
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

    /* ALERT MESSAGES */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin: 0 20px 20px;
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
        background: linear-gradient(135deg, rgba(46, 204, 113, 0.1) 0%, rgba(39, 174, 96, 0.1) 100%);
        color: var(--success);
        border: 2px solid rgba(46, 204, 113, 0.2);
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
        box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
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
        box-shadow: 0 -5px 25px rgba(10, 92, 54, 0.15);
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
        
        .venue-selector-title {
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
    /* ANIMATIONS */
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* FORM SECTIONS */
    .form-section-card {
        background: white;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    
    .form-section-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .data-group-title {
        font-size: 16px; 
        font-weight: 800; 
        color: var(--primary); 
        margin-bottom: 20px; 
        display: flex; 
        align-items: center; 
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px dashed var(--light-gray);
    }

    /* INPUT GROUPS WITH ICONS */
    .input-group-icon {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary);
        font-size: 18px;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .form-control.with-icon {
        padding-left: 48px;
    }
    
    .form-control:focus + .input-icon,
    .form-control:focus ~ .input-icon {
        color: var(--accent);
        transform: translateY(-50%) scale(1.1);
    }
    
    /* FORM ACTIONS */
    .form-actions {
        margin-top: 30px;
        position: sticky;
        bottom: 20px;
        z-index: 100;
    }
    /* BACK LINK */
    .back-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 16px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .back-link:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }
    
    /* SECTION INFO */
    .section-info-header {
        background: var(--gradient-light);
        padding: 24px;
        margin-bottom: 20px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid var(--glass-border);
    }
    
    .section-info-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }
    
    .section-info-text h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 4px 0;
    }
    
    .section-info-text p {
        font-size: 14px;
        color: var(--text-light);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    /* EDIT SCHEDULE PAGE STYLES */
    .form-container {
        padding: 24px 20px;
        max-width: 600px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    .screen-title {
        color: white;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .btn-save {
        background: var(--gradient-primary);
        color: white;
        border: none;
        width: 100%;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(139, 21, 56, 0.25);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(139, 21, 56, 0.35);
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    }

    .btn-save:active {
        transform: translateY(-1px);
    }

    .schedule-details-box {
        background: var(--light);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid rgba(139, 21, 56, 0.1);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .schedule-details-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        border-bottom: 1px dashed rgba(139, 21, 56, 0.1);
        padding-bottom: 8px;
    }

    .schedule-details-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .schedule-details-label {
        color: var(--text-light);
        font-weight: 600;
    }

    .schedule-details-value {
        color: var(--text);
        font-weight: 700;
        text-align: right;
    }
</style>
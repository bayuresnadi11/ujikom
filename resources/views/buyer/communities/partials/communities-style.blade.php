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
        --warning: #f39c12;
        
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
        --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        
        --shadow-sm: 0 1px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 3px 12px rgba(0,0,0,0.08);
        --shadow-lg: 0 6px 24px rgba(0,0,0,0.12);
        --shadow-xl: 0 9px 36px rgba(0,0,0,0.15);
    }

    /* ================= GLOBAL ================= */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.4;
        color: var(--text);
        background: #ffffff;
        margin: 0;
        padding: 0;
        -webkit-tap-highlight-color: transparent;
        -webkit-font-smoothing: antialiased;
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

    /* ================= PAGE HEADER ================= */
    .page-header {
        padding: 20px 16px 0;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 900;
        color: #0A5C36;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.4;
        margin: 0;
        font-weight: 500;
    }

    /* ================= ACTION CARDS ================= */
    .action-card-section {
        margin: 24px 16px 40px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .menu-action-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: white;
        padding: 16px;
        border-radius: 20px;
        text-decoration: none;
        color: var(--text);
        box-shadow: 0 4px 15px rgba(10, 92, 54, 0.08);
        border: 1px solid #E1F0E7;
        transition: all 0.3s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .menu-action-card:active {
        transform: scale(0.97);
        background: #F8FFF9;
        border-color: #27AE60;
    }

    .action-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .icon-join {
        background: #E9F6EF;
        color: #0A5C36;
    }

    .icon-create {
        background: #FFF9E6;
        color: #E67E22;
    }

    .menu-action-card:active .action-card-icon {
        transform: scale(1.1);
    }

    .action-card-content {
        flex: 1;
    }

    .action-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #0A5C36;
        margin-bottom: 2px;
        display: block;
    }

    .action-card-desc {
        font-size: 12px;
        color: #8A9C93;
        font-weight: 500;
    }

    .action-card-arrow {
        color: #CBD5E0;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .menu-action-card:active .action-card-arrow {
        transform: translateX(4px);
        color: #27AE60;
    }

    /* ================= REPORT TABS ================= */
    .report-tabs {
        display: flex;
        gap: 6px;
        background: #F0F7F3;
        padding: 6px;
        border-radius: 14px;
        margin: 24px 16px 20px;
        box-shadow: 0 2px 8px rgba(10, 92, 54, 0.08);
        border: 1px solid #E1F0E7;
    }

    .tab-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 12px 0;
        font-size: 14px;
        font-weight: 700;
        color: #2D7A54;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        -webkit-tap-highlight-color: transparent;
    }

    .tab-btn i {
        font-size: 16px;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #0A5C36, #27AE60);
        color: white;
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
        transform: translateY(-1px);
    }

    .tab-btn:not(.active):hover {
        background: rgba(46, 204, 113, 0.15);
    }

    /* ================= TAB CONTENT ================= */
    .tab-content {
        display: none;
        padding: 0 16px;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-content.active {
        display: block;
    }

    /* ================= COMMUNITY CARDS ================= */
    /* ================= COMMUNITY GRID ================= */
    .community-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 16px;
        padding: 0 4px;
        margin-bottom: 100px;
    }

    .community-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .community-item:active {
        transform: scale(0.95);
    }

    .community-logo-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        padding: 4px;
        background: white;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.12);
        border: 2px solid #E1F0E7;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .community-item:hover .community-logo-container {
        border-color: #27AE60;
        box-shadow: 0 6px 16px rgba(10, 92, 54, 0.18);
    }

    .community-logo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .community-info {
        width: 100%;
    }

    .community-name {
        font-size: 15px;
        font-weight: 800;
        color: #0A5C36;
        margin-bottom: 2px;
        line-height: 1.4;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .community-category {
        font-size: 11px;
        color: #8A9C93;
        font-weight: 600;
        text-transform: capitalize;
    }

    .community-badge-admin {
        position: absolute;
        top: -5px;
        right: -5px;
        background: linear-gradient(135deg, #FFD700, #F39C12);
        color: white;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 12px;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        z-index: 5;
    }

    .community-badge-pending {
        position: absolute;
        bottom: -5px;
        right: -5px;
        background: #E74C3C;
        color: white;
        font-size: 10px;
        font-weight: 800;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        z-index: 5;
    }

    .join-card .community-logo-container {
        background: #F0F7F3;
        border: 2px dashed #27AE60;
        box-shadow: none;
    }

    .join-card .community-logo-container i {
        color: #27AE60;
    }

    .community-logo-wrapper {
        position: relative;
    }

    .card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(10, 92, 54, 0.1);
        border: 1px solid #E1F0E7;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 25px rgba(10, 92, 54, 0.15);
        border-color: #27AE60;
    }

    .card-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #F8FFF9, #E6F7ED);
        border-bottom: 1px solid #E1F0E7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0A5C36;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        font-size: 18px;
        color: #27AE60;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-active {
        background: #D5F4E6;
        color: #0A5C36;
    }

    .badge-warning {
        background: #FFEECC;
        color: #E67E22;
    }

    .badge-pending {
        background: #FFE5E5;
        color: #E74C3C;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .badge-pending:hover {
        background: #FFD1D1;
        transform: scale(1.05);
    }

    .card-badges {
        display: flex;
        gap: 6px;
    }

    .card-body {
        padding: 20px;
    }

    .card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #F0F7F3;
    }

    .card-row:last-child {
        border-bottom: none;
    }

    .card-label {
        font-size: 13px;
        color: #6C757D;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-label i {
        width: 20px;
        text-align: center;
        color: #27AE60;
    }

    .card-value {
        font-size: 14px;
        font-weight: 600;
        color: #0A5C36;
    }

    .card-footer {
        padding: 16px 20px;
        background: #F8FFF9;
        border-top: 1px solid #E1F0E7;
        display: flex;
        justify-content: center;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 12px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-view {
        background: linear-gradient(135deg, #0A5C36, #27AE60);
        color: white;
        box-shadow: 0 3px 10px rgba(10, 92, 54, 0.2);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(10, 92, 54, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        width: 100%;
    }

    .action-buttons .btn-action {
        flex: 1;
        justify-content: center;
    }



    /* ================= MODALS ================= */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        display: none;
        justify-content: center;
        align-items: flex-end;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 24px 24px 0 0;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-content::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #27AE60, #2ECC71);
        border-radius: 16px 16px 0 0;
    }

    .modal-header {
        padding: 20px 16px 12px;
        text-align: center;
        border-bottom: 1px solid #F0F7F3;
        position: relative;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: #0A5C36;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .modal-close {
        position: absolute;
        right: 16px;
        top: 20px;
        background: #F0F7F3;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6C757D;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .modal-close:hover {
        background: #E1F0E7;
        color: #0A5C36;
    }

    .modal-body {
        padding: 16px;
    }

    .modal-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .modal-option {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        text-decoration: none;
        color: inherit;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        background: #F8FFF9;
        -webkit-tap-highlight-color: transparent;
    }

    .modal-option:hover {
        background: white;
        border-color: #E1F0E7;
        transform: translateX(4px);
    }

    .option-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #E6F7ED, #D5F4E6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #0A5C36;
        flex-shrink: 0;
    }

    .option-content {
        flex: 1;
    }

    .option-content h3 {
        font-size: 15px;
        font-weight: 700;
        color: #0A5C36;
        margin: 0 0 4px 0;
    }

    .option-content p {
        font-size: 12px;
        color: #6C757D;
        margin: 0;
        line-height: 1.4;
    }

    .option-arrow {
        color: #27AE60;
        font-size: 14px;
        opacity: 0.7;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 40px 16px;
    }

    .empty-state-icon {
        font-size: 48px;
        color: #E1F0E7;
        margin-bottom: 16px;
    }

    .empty-state-title {
        font-size: 16px;
        font-weight: 700;
        color: #0A5C36;
        margin-bottom: 8px;
    }

    .empty-state-desc {
        font-size: 13px;
        color: #6C757D;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #0A5C36, #27AE60);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.2);
        -webkit-tap-highlight-color: transparent;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(10, 92, 54, 0.3);
    }

    .btn-add i {
        font-size: 16px;
    }

    /* ================= RESPONSIVE ADJUSTMENTS ================= */
    @media (max-width: 480px) {
        .page-title {
            font-size: 24px;
        }

        .page-subtitle {
            font-size: 13px;
        }

        /* FAB position lebih dekat dengan bottom nav */
        .fab-container {
            bottom: 75px;
            right: 12px;
        }

        .fab-main {
            width: 52px;
            height: 52px;
            font-size: 20px;
        }

        .fab-menu {
            bottom: 60px;
            gap: 8px;
        }

        .fab-item {
            padding: 8px 14px;
            font-size: 12px;
            min-width: 140px;
        }

        .fab-icon {
            width: 28px;
            height: 28px;
            font-size: 13px;
        }

        .report-tabs {
            margin: 20px 12px 16px;
            gap: 4px;
            padding: 4px;
        }

        .tab-btn {
            font-size: 13px;
            padding: 10px 0;
            gap: 6px;
        }

        .tab-content {
            padding: 0 12px;
        }

        .card {
            border-radius: 14px;
        }

        .card-header, .card-body, .card-footer {
            padding: 14px 16px;
        }

        .card-title {
            font-size: 15px;
        }

        .card-label, .card-value {
            font-size: 13px;
        }

        .btn-action {
            padding: 8px 16px;
            font-size: 13px;
        }

        /* Bottom nav lebih kompak */
        .bottom-nav {
            padding: 6px 0 10px;
        }

        .nav-item {
            padding: 4px 6px;
            min-width: 52px;
        }

        .nav-icon {
            width: 38px;
            height: 38px;
            font-size: 17px;
        }

        .nav-item.active {
            transform: translateY(-6px);
        }
    }

    @media (max-width: 375px) {
        .fab-container {
            bottom: 70px;
            right: 10px;
        }

        .fab-main {
            width: 50px;
            height: 50px;
            font-size: 19px;
        }

        .fab-item {
            padding: 7px 12px;
            font-size: 11px;
            min-width: 130px;
        }

        .fab-icon {
            width: 26px;
            height: 26px;
            font-size: 12px;
        }

        .page-title {
            font-size: 22px;
        }

        /* Bottom nav lebih kecil */
        .nav-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .nav-item {
            font-size: 9px;
        }
    }

    @media (max-width: 320px) {
        .fab-container {
            bottom: 65px;
            right: 8px;
        }

        .fab-main {
            width: 48px;
            height: 48px;
            font-size: 18px;
        }

        .fab-item {
            padding: 6px 10px;
            font-size: 11px;
            min-width: 120px;
        }

        .fab-text {
            font-size: 11px;
        }

        .fab-icon {
            width: 24px;
            height: 24px;
            font-size: 11px;
        }

        .page-title {
            font-size: 20px;
        }

        .page-subtitle {
            font-size: 12px;
        }

    }

    /* Untuk layar sangat kecil, sederhanakan menu */
    @media (max-width: 280px) {
        .fab-container {
            bottom: 60px;
            right: 6px;
        }

        .fab-main {
            width: 46px;
            height: 46px;
            font-size: 17px;
        }

        /* Mode kompak: hanya icon */
        .fab-item.compact-mode {
            padding: 8px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            justify-content: center;
            min-width: auto;
        }
        
        .fab-item.compact-mode .fab-text {
            display: none;
        }
        
        .fab-item.compact-mode .fab-icon {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            margin: 0;
        }

    }

    /* Touch-friendly improvements */
    @media (hover: none) and (pointer: coarse) {
        .fab-main:active {
            transform: scale(0.95);
            background: linear-gradient(135deg, #27AE60, #2ECC71);
        }
        
        .fab-item:active {
            transform: scale(0.95);
            background: #E9F6EF;
        }
        
        .btn-add:active {
            transform: scale(0.98);
        }
        
        .nav-item:active {
            transform: scale(0.95);
        }
        
        .nav-item.active:active {
            transform: translateY(-6px) scale(0.95);
        }
        
        .tab-btn:active {
            transform: scale(0.98);
        }
        
        .tab-btn.active:active {
            transform: translateY(-1px) scale(0.98);
        }
    }

    /* Untuk layar desktop/dengan mouse */
    @media (min-width: 480px) {
        .mobile-container {
            max-width: 480px;
            margin: 10px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: 20px;
            overflow: hidden;
            min-height: calc(100vh - 20px);
        }
        
        .bottom-nav {
            max-width: 480px;
            border-radius: 20px 20px 0 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .modal-content {
            max-width: 480px;
        }
        
        .fab-container {
            right: 20px;
        }
    }

    /* Smooth scrolling for mobile */
    @media (max-width: 768px) {
        html {
            scroll-behavior: smooth;
        }
        
        .card-container {
            padding-bottom: 20px;
        }
    }

    /* ================= INVITE POPUP ================= */
    .invite-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: 3000;
        display: none;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(6px);
        padding: 20px;
    }

    .invite-popup-box {
        background: white;
        width: 100%;
        max-width: 380px;
        border-radius: 28px;
        padding: 30px 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        position: relative;
        animation: invitePopupShow 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes invitePopupShow {
        from { transform: translateY(20px) scale(0.9); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .invite-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 20px;
    }

    .invite-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }

    .invite-logo {
        width: 90px;
        height: 90px;
        border-radius: 24px;
        object-fit: cover;
        box-shadow: 0 8px 20px rgba(10, 92, 54, 0.15);
        border: 4px solid #f0f7f3;
    }

    .invite-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .invite-name {
        font-size: 20px;
        font-weight: 800;
        color: #0A5C36;
        display: block;
    }

    .invite-category {
        font-size: 13px;
        color: #6C757D;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .invite-actions {
        display: flex;
        gap: 12px;
        width: 100%;
        margin-top: 10px;
    }

    .invite-actions form {
        flex: 1;
    }

    .btn-accept, .btn-reject {
        width: 100%;
        padding: 14px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-accept {
        background: linear-gradient(135deg, #0A5C36, #27AE60);
        color: white;
        box-shadow: 0 6px 15px rgba(10, 92, 54, 0.3);
    }

    .btn-reject {
        background: #f8f9fa;
        color: #dc3545;
        border: 1px solid #feeaea;
    }

    .btn-accept:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(10, 92, 54, 0.4);
    }

    .btn-reject:hover {
        background: #fff5f5;
        color: #c82333;
    }

    /* Prevent text selection on interactive elements */
    .fab-main, .fab-item, .tab-btn, .btn-action, .btn-add, .nav-item {
        user-select: none;
    }
</style>
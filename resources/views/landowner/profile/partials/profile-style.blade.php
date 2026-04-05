<style>
    /* ================= VARIABLES ================= */
    :root {
        --primary: #8B1538;  /* Burgundy */
        --primary-dark: #6B0F2A; /* Dark Burgundy */
        --secondary: #A01B42; /* Medium Burgundy */
        --accent: #C7254E;    /* Light Burgundy */
        --light: #F8F9FA;
        --light-gray: #E9ECEF;
        --text: #212529;
        --text-light: #6C757D;
        --danger: #E74C3C;
        --gold: #FFD700;

        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%); /* Light Pink Gradient */
        --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);

        --shadow-sm: 0 1px 4px rgba(139, 21, 56, 0.05); /* Burgundy shadow */
        --shadow-md: 0 3px 12px rgba(139, 21, 56, 0.08);
        --shadow-lg: 0 6px 24px rgba(139, 21, 56, 0.12);
        --shadow-xl: 0 9px 36px rgba(139, 21, 56, 0.15);
    }
    
    /* ================= GLOBAL RESET ================= */
    * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #FFFFFF 0%, #FFFFFF 100%);
        color: var(--text);
        font-size: 14px;
        line-height: 1.5;
        overscroll-behavior-y: none;
    }

    /* ================= MOBILE CONTAINER ================= */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        padding-bottom: 80px; /* Space for bottom nav */
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding-top: 45px; /* Adjust based on header height */
        min-height: calc(100vh - 80px);
    }

    .logo span {
        color: #FFE4E1; /* Misty Rose */
        font-weight: 700;
        text-shadow: 0 0 10px rgba(255, 228, 225, 0.4);
    }

    .profile-header {
        padding: 30px 20px;
        background: #FFE4E8; /* Light Pink Background requested by user */
        text-align: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
        border-bottom: none;
        color: var(--primary); /* Burgundy Text */
    }

    .profile-header::before {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(139, 21, 56, 0.1) 0%, transparent 70%); /* Burgundy Radial */
        border-radius: 50%;
    }

    .profile-avatar-container {
        position: relative;
        width: 96px;
        height: 96px;
        margin: 0 auto 16px;
        z-index: 1;
    }

    .avatar-link {
        display: block;
        width: 100%;
        height: 100%;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #545454;
        background-size: cover;
        background-position: center;
        border: 4px solid white;
        box-shadow: var(--shadow-md);
    }

    .edit-avatar-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .edit-avatar-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

    .profile-name {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary);
        margin: 0 0 8px;
    }

    .profile-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(139, 21, 56, 0.1);
        padding: 6px 16px;
        border-radius: 20px;
        color: var(--primary);
        font-weight: 600;
        font-size: 12px;
        backdrop-filter: blur(5px);
    }

    .deposit-summary {
        padding: 0 20px;
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .deposit-card {
        background: #FFE4E8; /* Light Pink Background */
        color: var(--primary); /* Burgundy text */
        border-radius: 16px;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 24px rgba(139, 21, 56, 0.15); /* Softer shadow */
        position: relative;
        overflow: hidden;
    }

    .deposit-card::after {
        content: "";
        position: absolute;
        right: -20px;
        top: -20px;
        width: 100px;
        height: 100px;
        background: rgba(139, 21, 56, 0.05); /* Soft burgundy circle */
        border-radius: 50%;
    }

    .deposit-info .deposit-label {
        font-size: 13px;
        margin-bottom: 6px;
        opacity: 0.9;
        color: var(--text-light); /* Darker label for contrast */
    }

    .deposit-info .deposit-amount {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .deposit-action {
        background-color: rgba(139, 21, 56, 0.1); /* Light burgundy bg */
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(139, 21, 56, 0.1);
    }

    .deposit-action:hover {
        background-color: rgba(139, 21, 56, 0.2);
        transform: translateY(-2px);
    }

    /* ================= PROFILE DETAILS ================= */
    .profile-details {
        background: white;
        padding: 24px;
        border-radius: 16px;
        margin: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
    }

    .details-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .details-title i {
        color: var(--primary);
    }

    .details-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .detail-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-item:first-child {
        padding-top: 0;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        background: var(--light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 16px;
        margin-right: 14px;
        flex-shrink: 0;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 14px;
        color: var(--text);
        font-weight: 600;
    }

    /* Menu Pengaturan, Bantuan & Logout */
    .settings-menu {
        padding: 24px;
        background: white;
        margin: 20px;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
    }

    .menu-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-list-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--light-gray);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .menu-list-item:hover {
        transform: translateX(4px);
    }

    .menu-icon {
        width: 44px;
        height: 44px;
        background: var(--light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 18px;
        margin-right: 14px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .menu-list-item:hover .menu-icon {
        background: var(--gradient-primary);
        color: white;
        transform: scale(1.05);
    }

    .menu-content {
        flex: 1;
    }

    .menu-item-title {
        font-size: 15px;
        color: var(--text);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .menu-item-desc {
        font-size: 12px;
        color: var(--text-light);
    }

    .menu-arrow {
        color: var(--text-light);
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .menu-list-item:hover .menu-arrow {
        color: var(--primary);
        transform: translateX(4px);
    }

    /* Logout button khusus styling */
    .logout-menu-item {
        display: flex;
        align-items: center;
        padding: 18px 0;
        border-top: 1px solid var(--light-gray);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .logout-menu-item:hover {
        transform: translateX(4px);
    }

    .logout-menu-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        margin-right: 14px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .logout-menu-item:hover .logout-menu-icon {
        transform: scale(1.05);
        box-shadow: var(--shadow-md);
    }

    .logout-menu-title {
        font-size: 15px;
        color: #E74C3C;
        font-weight: 700;
        margin-bottom: 4px;
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
        box-shadow: 0 -2px 12px rgba(139, 21, 56, 0.1);
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

    /* ================= MODALS ================= */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(139, 21, 56, 0.95);
        z-index: 2100;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(10px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 24px;
        width: 100%;
        max-width: 360px;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: var(--shadow-xl);
        border: 1px solid var(--light-gray);
        animation: modalSlideIn 0.3s ease;
    }
    
    /* ... (Rest of Modal Styles from Step 344) ... */
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-content::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--gradient-accent);
        border-radius: 16px 16px 0 0;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }

    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--text-light);
        cursor: pointer;
        line-height: 1;
        padding: 0;
        transition: all 0.2s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .close-modal:hover {
        color: var(--primary);
        background: var(--light-gray);
        transform: rotate(90deg);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text);
        font-size: 14px;
    }

    .form-input,
    .form-input select,
    .form-input textarea {
        width: 100%;
        padding: 14px;
        border: 1px solid var(--light-gray);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: var(--light);
        transition: all 0.3s ease;
        font-weight: 500;
        color: var(--text);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.1);
    }

    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 20 20'%3E%3Cpath fill='%238B1538' d='M10 14L4 8h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 40px;
    }

    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }

    .form-submit {
        width: 100%;
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        margin-top: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .form-submit:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Avatar Upload in Modal */
    .avatar-upload-section {
        text-align: center;
        margin-bottom: 24px;
        padding: 16px;
        background: var(--light);
        border-radius: 12px;
        border: 2px dashed var(--light-gray);
    }

    .avatar-preview-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 16px;
    }

    .avatar-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--light-gray);
        background: var(--gradient-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 40px;
        cursor: pointer;
    }

    .avatar-upload-label {
        display: inline-block;
        padding: 12px 20px;
        background: var(--gradient-primary);
        color: white;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 700;
        font-size: 14px;
        box-shadow: var(--shadow-sm);
    }

    .avatar-upload-label:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary) 100%);
    }

    .avatar-input {
        display: none;
    }

    .avatar-upload-hint {
        margin-top: 10px;
        font-size: 12px;
        color: var(--text-light);
    }
    
    /* Toast Notification */
    .toast {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: var(--gradient-primary);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        box-shadow: var(--shadow-lg);
        z-index: 2200;
        opacity: 0;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 90%;
        font-size: 13px;
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

    /* Popup Alert Styles */
    .alert-popup {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .alert-popup.active {
        opacity: 1;
        visibility: visible;
    }

    .alert-popup-content {
        background: white;
        border-radius: 12px;
        width: 85%;
        max-width: 320px;
        padding: 20px;
        text-align: center;
        transform: translateY(20px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        box-shadow: var(--shadow-lg);
        animation: popupAppear 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .alert-popup.active .alert-popup-content {
        transform: translateY(0);
        opacity: 1;
    }

    .alert-popup-icon {
        font-size: 40px;
        margin-bottom: 12px;
        display: block;
    }

    .alert-popup-icon.success {
        color: #8B1538;
    }

    .alert-popup-icon.error {
        color: #EF4444;
    }

    .alert-popup-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #1F2937;
    }

    .alert-popup-message {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .alert-popup-button {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.2s ease;
    }

    .alert-popup-button:hover {
        background: var(--primary-dark);
    }

    .alert-popup-button.success {
        background: #8B1538;
    }

    .alert-popup-button.success:hover {
        background: #6B0F2A;
    }

    .alert-popup-button.error {
        background: #EF4444;
    }

    .alert-popup-button.error:hover {
        background: #DC2626;
    }

    @keyframes popupAppear {
        0% {
            transform: scale(0.9) translateY(20px);
            opacity: 0;
        }

        100% {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    /* Modal Switch Role Styles */
    .confirm-switch-modal {
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
        padding: 16px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .confirm-switch-modal.active {
        display: flex;
        opacity: 1;
    }

    .confirm-switch-modal .modal-container {
        width: 100%;
        max-width: 320px;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    .confirm-switch-modal.active .modal-container {
        transform: scale(1);
    }
    
    /* ... (Existing modal content styles can remain if valid, but better to be safe) ... */
    /* Assuming standard modal styles are sufficient or included in the blocks above for consistency */
    
    /* ================= DESKTOP PREVIEW ================= */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 60px rgba(139, 21, 56, 0.25);
            border-radius: 24px;
            overflow: hidden;
        }

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 24px 24px 0 0;
        }
    }
</style>

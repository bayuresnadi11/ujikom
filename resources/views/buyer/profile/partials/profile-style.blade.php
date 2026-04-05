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

        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
        --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);

        --shadow-sm: 0 1px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 3px 12px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 6px 24px rgba(0, 0, 0, 0.12);
        --shadow-xl: 0 9px 36px rgba(0, 0, 0, 0.15);
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
    }

    /* ================= MOBILE CONTAINER ================= */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
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

    /* ================= PROFILE CONTENT ================= */
    .main-content {
        padding-top: 20px;
        padding-bottom: 80px;
        background: var(--gradient-light);
    }

    .profile-header {
        padding: 30px 16px;
        background: var(--gradient-primary);
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .profile-header::before {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-avatar-container {
        position: relative;
        width: 90px;
        height: 90px;
        margin: 0 auto 15px;
        z-index: 1;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: var(--shadow-lg);
        background: var(--gradient-accent);
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--gradient-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .change-avatar-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--gradient-accent);
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        z-index: 2;
    }

    .change-avatar-btn:hover {
        transform: scale(1.1);
        background: var(--primary-dark);
    }

    .profile-name {
        font-size: 22px;
        font-weight: 900;
        color: white;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .profile-email {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 12px;
        position: relative;
        z-index: 1;
    }

    .profile-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 20px;
        color: white;
        font-weight: 600;
        font-size: 12px;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(5px);
    }

    .status-icon {
        color: var(--accent);
        font-size: 14px;
    }

    .profile-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 20px;
        position: relative;
        z-index: 1;
        flex-wrap: wrap;
    }

    .btn-edit-profile {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
        flex: 1;
        min-width: 120px;
        justify-content: center;
    }

    .btn-edit-profile:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-settings {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-md);
        flex: 1;
        min-width: 120px;
        justify-content: center;
    }

    .btn-settings:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
    }

    /* Tombol Switch Role */
    .btn-switch-role {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a3a27;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-md);
        width: 100%;
        max-width: 280px;
        margin: 16px auto 0;
        justify-content: center;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-switch-role::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: 0.5s;
        z-index: -1;
    }

    .btn-switch-role:hover::before {
        left: 100%;
    }

    .btn-switch-role:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: linear-gradient(135deg, #FFA500 0%, #FFD700 100%);
    }

    .btn-switch-role i {
        color: #1a3a27;
        font-size: 16px;
        animation: rotateRoleIcon 3s infinite linear;
    }

    @keyframes rotateRoleIcon {
        0% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(90deg);
        }

        50% {
            transform: rotate(180deg);
        }

        75% {
            transform: rotate(270deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Stats Section - Dihapus */
    .stats-section {
        display: none;
    }

    /* Profile Details */
    .profile-details {
        padding: 20px;
        background: white;
        margin: 16px;
        border-radius: 16px;
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

    .btn-edit-details {
        background: none;
        border: none;
        color: var(--primary);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .btn-edit-details:hover {
        color: var(--secondary);
        background: var(--light);
        gap: 6px;
    }

    .details-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-icon {
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
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 4px;
        font-weight: 600;
    }

    .detail-value {
        font-size: 15px;
        color: var(--text);
        font-weight: 700;
    }

    .detail-action {
        color: var(--text-light);
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 6px;
        border-radius: 6px;
    }

    .detail-action:hover {
        color: var(--primary);
        background: var(--light);
    }

    /* Booking History */
    .booking-history {
        padding: 20px;
        background: white;
        margin: 16px;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
    }

    .history-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: space-between;
    }

    .view-all-bookings {
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .view-all-bookings:hover {
        gap: 6px;
        color: var(--secondary);
        background: var(--light);
    }

    .booking-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .booking-item {
        background: var(--light);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .booking-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .booking-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
    }

    .booking-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 800;
    }

    .status-confirmed {
        background: rgba(39, 174, 96, 0.15);
        color: var(--success);
    }

    .status-pending {
        background: rgba(243, 156, 18, 0.15);
        color: var(--warning);
    }

    .status-cancelled {
        background: rgba(231, 76, 60, 0.15);
        color: var(--danger);
    }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    .booking-detail {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--text-light);
    }

    .booking-detail i {
        color: var(--primary);
        width: 14px;
        font-size: 14px;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
    }

    .btn-booking-action {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-details {
        background: white;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-details:hover {
        background: var(--light);
    }

    .btn-rating {
        background: var(--gradient-primary);
        color: white;
    }

    .btn-rating:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Menu Pengaturan, Bantuan & Logout */
    .settings-menu {
        padding: 20px;
        background: white;
        margin: 16px;
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
        padding: 18px 0;
        border-bottom: 1px solid var(--light-gray);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .menu-list-item:last-child {
        border-bottom: none;
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

    .logout-menu-desc {
        font-size: 12px;
        color: #C0392B;
    }

    /* ================= BOTTOM NAV ================= */

    /* ================= MODALS ================= */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(8, 72, 43, 0.95);
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
        box-shadow: 0 0 0 3px rgba(10, 92, 54, 0.1);
    }

    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 20 20'%3E%3Cpath fill='%230A5C36' d='M10 14L4 8h12z'/%3E%3C/svg%3E");
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

    .toast.info {
        background: linear-gradient(135deg, #3498DB 0%, #2980B9 100%);
    }

    .toast i {
        font-size: 16px;
    }

    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
        margin-left: 8px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
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
        color: #10B981;
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
        background: #10B981;
    }

    .alert-popup-button.success:hover {
        background: #059669;
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

    .confirm-switch-modal .modal-header {
        padding: 20px 20px 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .confirm-switch-modal .modal-title {
        font-size: 16px;
        font-weight: 800;
        color: #333;
        margin: 0;
        line-height: 1.4;
    }

    .confirm-switch-modal .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: none;
        border: none;
        font-size: 18px;
        color: #666;
        cursor: pointer;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .confirm-switch-modal .modal-close:hover {
        background: #f5f5f5;
        color: #333;
    }

    .confirm-switch-modal .modal-body {
        padding: 20px;
    }

    .confirm-switch-modal .modal-icon-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .confirm-switch-modal .modal-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .confirm-switch-modal .modal-icon {
        font-size: 20px;
        color: var(--primary);
    }

    .confirm-switch-modal .modal-content-text h4 {
        margin: 0 0 4px 0;
        font-size: 15px;
        font-weight: 800;
        color: #333;
    }

    .confirm-switch-modal .modal-content-text p {
        margin: 0;
        font-size: 13px;
        color: #666;
        line-height: 1.4;
    }

    .confirm-switch-modal .modal-info-box {
        background: linear-gradient(135deg, #f8f9ff 0%, #f5f7ff 100%);
        border-left: 3px solid var(--primary);
        padding: 14px;
        border-radius: 8px;
        margin: 16px 0;
    }

    .confirm-switch-modal .modal-info-header {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 10px;
    }

    .confirm-switch-modal .modal-info-icon {
        color: var(--primary);
        font-size: 14px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .confirm-switch-modal .modal-info-title {
        font-size: 13px;
        font-weight: 800;
        color: #333;
        margin: 0 0 6px 0;
    }

    .confirm-switch-modal .modal-info-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .confirm-switch-modal .modal-info-item {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 6px;
        font-size: 12px;
        color: #555;
        line-height: 1.4;
    }

    .confirm-switch-modal .modal-info-item:last-child {
        margin-bottom: 0;
    }

    .confirm-switch-modal .modal-info-item i {
        color: var(--primary);
        font-size: 10px;
        margin-top: 3px;
        flex-shrink: 0;
    }

    .confirm-switch-modal .modal-footer {
        padding: 16px 20px 20px;
        display: flex;
        gap: 10px;
        background: #fafafa;
        border-top: 1px solid #f0f0f0;
    }

    .confirm-switch-modal .modal-btn {
        flex: 1;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        outline: none;
        text-align: center;
        font-family: inherit;
    }

    .confirm-switch-modal .modal-btn-secondary {
        background: white;
        color: #666;
        border: 1px solid #e0e0e0;
    }

    .confirm-switch-modal .modal-btn-secondary:hover {
        background: #f5f5f5;
        border-color: #d0d0d0;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    }

    .confirm-switch-modal .modal-btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #0aca27 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(13, 110, 253, 0.2);
    }

    .confirm-switch-modal .modal-btn-primary:hover {
        background: linear-gradient(135deg, #0aca27 0%, #089819 100%);
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(13, 253, 65, 0.3);
    }

    .confirm-switch-modal .modal-btn:active {
        transform: translateY(0);
    }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: 20px;
            overflow: hidden;
        }

        .profile-header {
            padding: 40px 20px;
        }

        .profile-avatar-container {
            width: 110px;
            height: 110px;
        }

        .avatar-placeholder {
            font-size: 42px;
        }

        .profile-name {
            font-size: 24px;
        }

        .profile-email {
            font-size: 15px;
        }

        .profile-details,
        .booking-history,
        .settings-menu {
            margin: 20px;
            padding: 24px;
            border-radius: 18px;
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
    }

    @media (max-width: 350px) {
        .profile-avatar-container {
            width: 80px;
            height: 80px;
        }

        .avatar-placeholder {
            font-size: 32px;
        }

        .profile-name {
            font-size: 20px;
        }

        .profile-email {
            font-size: 13px;
        }

        .profile-actions {
            flex-direction: column;
            gap: 8px;
        }

        .btn-edit-profile,
        .btn-settings {
            min-width: 100%;
        }

        .btn-switch-role {
            max-width: 100%;
        }

        .profile-details,
        .booking-history,
        .settings-menu {
            margin: 12px;
            padding: 16px;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .logout-menu-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .booking-details {
            grid-template-columns: 1fr;
        }

        .booking-actions {
            flex-direction: column;
        }

        .logo {
            font-size: 16px;
        }

        .logo-icon {
            width: 28px;
            height: 28px;
            font-size: 14px;
        }
    }
    }

    /* Animation */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes modalFadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    /* Smooth transitions */
    .confirm-switch-modal,
    .confirm-switch-modal .modal-container {
        will-change: transform, opacity;
    }

    /* ================= DESKTOP PREVIEW ================= */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 60px rgba(10, 92, 54, 0.25);
            border-radius: 24px;
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
            border-radius: 24px 24px 0 0;
        }
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        width: 90%;
        max-width: 500px;
        border-radius: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        resize: vertical;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .modal-actions button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-actions button[type="button"] {
        background-color: #ddd;
    }

    .modal-actions button[type="submit"] {
        background-color: #007bff;
        color: white;
    }

    .profile-avatar-container {
        position: relative;
        display: inline-block;
    }

    .profile-avatar-container {
        position: relative;
        width: 96px;
        height: 96px;
        margin: 0 auto;
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
    }

    /* tombol edit kecil */
    .edit-avatar-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 28px;
        height: 28px;
        background: #545454;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .edit-avatar-btn:hover {
        background: #b0b0b0;
    }

    .profile-header {
        padding-bottom: 1rem;
        margin-bottom: 0;
    }

    .deposit-summary {
        padding: 0 1rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .deposit-card {
        background: linear-gradient(135deg, #0A5C36, #23a45b);
        color: white;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .deposit-info .deposit-label {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        opacity: 0.9;
    }

    .deposit-info .deposit-amount {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .deposit-action {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: background-color: 0.3s ease;
    }

    .deposit-action:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }
</style>
 
 
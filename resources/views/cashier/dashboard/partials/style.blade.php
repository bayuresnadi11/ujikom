<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        overflow: hidden;
        height: 100vh;
        width: 100vw;
        background-color: #f8f9fa;
    }

    /* Fixed Header */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: linear-gradient(135deg, #41a67e 0%, #2f8d6a 100%);
        color: #fff;
        padding: 10px 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fixed-header .title {
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .fixed-header .subtitle {
        font-size: 0.65rem;
        opacity: 0.9;
        font-weight: 400;
    }

    /* BOTTOM NAVBAR */
    .bottom-navbar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: #fff;
        border-top: 3px solid #e9ecef;
        padding: 15px 20px;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        height: 100px;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .bottom-navbar .nav-buttons {
        display: flex;
        gap: 10px;
        width: 100%;
        justify-content: space-between;
        align-items: stretch;
        flex-wrap: nowrap;
    }

    .bottom-navbar .btn-nav {
        flex: 1;
        min-height: 70px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        text-align: center;
        white-space: nowrap;
        padding: 12px 8px;
        font-size: 0.9rem;
    }

    .bottom-navbar .btn-nav i {
        font-size: 1.3rem;
        margin-bottom: 2px;
    }

    .bottom-navbar .btn-nav span {
        font-size: 0.8rem;
        line-height: 1.2;
    }

    .bottom-navbar .btn-nav:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .display-dropdown {
        flex: 1;
        display: flex;
    }

    .display-dropdown .btn-nav {
        flex: 1;
        width: 100%;
    }

    .display-dropdown .dropdown-menu {
        min-width: 280px;
        font-size: 1rem;
        border-radius: 12px;
        margin-top: 8px;
        margin-bottom: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .display-dropdown .dropdown-item {
        padding: 18px 22px;
        min-height: 60px;
        display: flex;
        align-items: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 6px;
        font-size: 1.1rem;
    }

    .display-dropdown .dropdown-item:last-child {
        margin-bottom: 0;
    }

    .display-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }

    .display-dropdown .dropdown-item i {
        font-size: 1.4rem;
        width: 32px;
    }

    .btn-outline-primary:hover, 
    .display-dropdown .btn-outline-primary.show {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 10px;
    }

    .btn-info:hover {
        background-color: #06b0d2ff;
        border-color: #06b0d2ff;
        color: white;
    }

    .btn-outline-success {
        color: #198754;
        border-color: #198754;
    }
    
    .btn-outline-success:hover {
        background-color: #198754;
        color: white;
    }
    
    .btn-success {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }

    .btn-outline-warning:hover {
        background-color: #ffb300;
        border-color: #ffb300;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    }

    /* Main Container - FIXED TIDAK BISA SCROLL */
    .dashboard-container {
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        bottom: 100px;
        overflow: hidden !important;
        padding: 12px;
        background: #f8f9fa;
    }

    .dashboard-container .container-fluid {
        height: 100%;
        overflow: hidden !important;
    }

    .dashboard-container .row {
        height: 100%;
        margin: 0;
        overflow: hidden !important;
    }

    /* ========== CARD TAMBAH PENYEWA - FIXED ========== */
    .col-lg-4 {
        height: 100%;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
    }

    .col-lg-4 .card-fixed-height {
        height: 100% !important;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        background: white;
    }

    .col-lg-4 .card-fixed-height .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 700;
        padding: 12px 16px;
        font-size: 1rem;
        color: #198754;
        flex-shrink: 0;
    }

    .col-lg-4 .card-fixed-height .card-body {
        flex: 1;
        padding: 10px 12px !important;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
        gap: 6px !important;
    }

    /* Form yang kompak */
    .col-lg-4 .form-label {
        margin-bottom: 2px !important;
        font-size: 0.75rem !important;
        font-weight: 600;
        color: #333;
    }

    .col-lg-4 .form-control {
        padding: 6px 10px !important;
        font-size: 0.8rem !important;
        border-radius: 6px !important;
        border: 2px solid #e0e0e0;
        transition: all 0.3s;
    }

    .col-lg-4 .form-control-lg:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }

    /* Tombol yang lebih kecil */
    .col-lg-4 .btn-lg {
        padding: 10px 20px !important;
        font-size: 0.95rem !important;
        border-radius: 8px !important;
        margin-top: 4px !important;
        font-weight: 600;
        background: linear-gradient(135deg, #198754, #146c43);
        border: none;
        transition: all 0.3s;
    }

    .col-lg-4 .btn-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }

    /* ========== CARD DAFTAR PENYEWA - FIXED ========== */
    .col-lg-8 {
        height: 100%;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
    }

    .col-lg-8 .card-fixed-height {
        height: 100% !important;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        background: white;
    }

    .col-lg-8 .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 700;
        padding: 12px 16px !important;
        font-size: 1rem;
        color: #198754;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: nowrap;
        gap: 15px;
    }

    .col-lg-8 .card-header > div:first-child {
        white-space: nowrap;
    }

    .col-lg-8 .card-body-scrollable {
        flex: 1;
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
        padding: 0 !important;
    }

    /* HANYA TABEL YANG BISA SCROLL */
    .scrollable-table-container {
        flex: 1;
        overflow-y: auto !important;
        overflow-x: hidden;
        position: relative;
    }

    .scrollable-table-container table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .scrollable-table-container thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 10;
        border-bottom: 2px solid #dee2e6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 8px 10px;
        font-weight: 700;
        color: #333;
        font-size: 0.65rem;
    }

    .scrollable-table-container tbody td {
        padding: 8px 10px;
        vertical-align: middle;
        font-size: 0.75rem;
    }

    /* Custom scrollbar untuk tabel */
    .scrollable-table-container::-webkit-scrollbar {
        width: 8px;
    }

    .scrollable-table-container::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 4px;
    }

    .scrollable-table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .scrollable-table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* ========== SEARCH FORM ========== */
    .search-form-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 250px;
        max-width: 500px;
    }

    .search-container {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-box {
        padding-left: 48px !important;
        padding-right: 48px !important;
        height: 46px !important;
        border-radius: 12px !important;
        border: 2px solid #e0e0e0 !important;
        font-size: 0.95rem !important;
        transition: all 0.3s ease !important;
        background-color: #fafafa !important;
        font-weight: 500 !important;
    }

    .search-box:focus {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15) !important;
        background-color: #fff !important;
        outline: none !important;
    }

    .search-box::placeholder {
        color: #aaa !important;
        font-weight: 400 !important;
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #198754;
        font-size: 1.15rem;
        z-index: 5;
        pointer-events: none;
    }

    .search-clear {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: #ff4757;
        border: none;
        color: white;
        cursor: pointer;
        padding: 6px 8px;
        border-radius: 6px;
        z-index: 5;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .search-clear:hover {
        opacity: 1;
        background: #ff3838;
        transform: translateY(-50%) scale(1.05);
    }

    .btn-reset {
        height: 46px;
        border-radius: 12px !important;
        padding: 0 20px !important;
        font-weight: 600 !important;
        white-space: nowrap;
        border: 2px solid #6c757d !important;
        transition: all 0.3s ease !important;
    }

    .btn-reset:hover {
        background-color: #6c757d !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
    }

    /* ========== PAGINATION ========== */
    .pagination-container {
        padding: 24px 20px;
        border-top: 2px solid #f0f0f0;
        background: #fafafa;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .custom-pagination {
        margin-bottom: 0 !important;
    }

    .custom-pagination .page-link {
        border: 2px solid #e0e0e0 !important;
        margin: 0 4px;
        border-radius: 10px !important;
        color: #555 !important;
        font-weight: 600 !important;
        min-width: 44px !important;
        height: 44px !important;
        text-align: center;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        font-size: 1rem !important;
        padding: 0 12px !important;
    }

    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #198754, #146c43) !important;
        border-color: #198754 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3) !important;
    }

    .custom-pagination .page-link:hover:not(.active) {
        background: #f5f5f5 !important;
        border-color: #198754 !important;
        color: #198754 !important;
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.1) !important;
    }

    .custom-pagination .page-link i {
        font-size: 0.9rem;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        height: 100%;
        padding: 60px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #e0e0e0;
        margin-bottom: 24px;
    }

    .empty-state-title {
        color: #999;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.3rem;
    }

    .empty-state-subtitle {
        color: #bbb;
        font-size: 0.95rem;
        max-width: 350px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ========== ROW COUNT BADGE ========== */
    .row-count-badge {
        background: linear-gradient(135deg, #198754, #146c43);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 700;
        box-shadow: 0 3px 10px rgba(25, 135, 84, 0.3);
        letter-spacing: 0.3px;
    }

    /* ========== CUSTOM ALERT & TOAST ========== */
    .custom-alert-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        animation: fadeIn 0.2s ease forwards;
        padding: 20px;
    }

    .custom-alert {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
        transform: translateY(30px) scale(0.95);
        animation: alertPopUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .custom-alert-header {
        padding: 28px 28px 20px;
        text-align: center;
        background: linear-gradient(135deg, #fff5f5 0%, #ffeaea 100%);
        position: relative;
    }

    .custom-alert-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #ff4757);
        border-radius: 2px;
    }

    .custom-alert-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        background: white;
        box-shadow: 0 8px 24px rgba(255, 107, 107, 0.2);
        color: #ff4757;
    }

    .custom-alert-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .custom-alert-message {
        color: #636e72;
        font-size: 0.95rem;
        line-height: 1.5;
        max-width: 320px;
        margin: 0 auto;
    }

    .custom-alert-body {
        padding: 24px 28px;
    }

    .custom-alert-actions {
        display: flex;
        gap: 12px;
        margin-top: 8px;
    }

    .custom-alert-btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.95rem;
        position: relative;
        overflow: hidden;
    }

    .custom-alert-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .custom-alert-btn:active::after {
        animation: ripple 1s ease-out;
    }

    .custom-alert-btn.cancel {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #e9ecef;
    }

    .custom-alert-btn.cancel:hover {
        background: #e9ecef;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.1);
    }

    .custom-alert-btn.confirm {
        background: linear-gradient(135deg, #ff6b6b, #ff4757);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }

    .custom-alert-btn.confirm:hover {
        background: linear-gradient(135deg, #ff5252, #ff3838);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .custom-alert-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* TOAST NOTIFICATION */
    .custom-toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 380px;
    }

    .custom-toast {
        background: white;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 16px;
        transform: translateX(120%) scale(0.9);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        border-left: 4px solid;
        animation: toastSlideIn 0.4s forwards;
        position: relative;
        overflow: hidden;
    }

    .custom-toast::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.1;
    }

    .custom-toast.success {
        border-left-color: #00b894;
        color: #00b894;
    }

    .custom-toast.error {
        border-left-color: #ff7675;
        color: #ff7675;
    }

    .custom-toast.info {
        border-left-color: #74b9ff;
        color: #74b9ff;
    }

    .custom-toast.warning {
        border-left-color: #fdcb6e;
        color: #fdcb6e;
    }

    .custom-toast.show {
        transform: translateX(0) scale(1);
    }

    .custom-toast.hiding {
        transform: translateX(120%) scale(0.9);
        opacity: 0;
    }

    .custom-toast-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: currentColor;
        color: white;
        flex-shrink: 0;
    }

    .custom-toast-content {
        flex: 1;
    }

    .custom-toast-title {
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 4px;
        font-size: 1rem;
    }

    .custom-toast-message {
        color: #636e72;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .custom-toast-close {
        background: none;
        border: none;
        color: #b2bec3;
        cursor: pointer;
        padding: 6px;
        margin-left: 8px;
        font-size: 14px;
        transition: all 0.2s;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-toast-close:hover {
        background: rgba(178, 190, 195, 0.1);
        color: #636e72;
    }

    /* ANIMATIONS */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes alertPopUp {
        to {
            transform: translateY(0) scale(1);
        }
    }

    @keyframes toastSlideIn {
        to {
            transform: translateX(0) scale(1);
        }
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        20% {
            transform: scale(25, 25);
            opacity: 0.3;
        }
        100% {
            opacity: 0;
            transform: scale(40, 40);
        }
    }

    /* ========== RESPONSIVE ========== */
    /* Tablet */
    @media (max-width: 1024px) {
        .bottom-navbar {
            height: 110px;
            padding: 15px 20px;
        }

        .bottom-navbar .nav-buttons {
            gap: 8px;
        }

        .bottom-navbar .btn-nav {
            min-height: 75px;
            padding: 14px 8px;
        }

        .bottom-navbar .btn-nav i {
            font-size: 1.4rem;
        }

        .bottom-navbar .btn-nav span {
            font-size: 0.85rem;
        }

        .display-dropdown .dropdown-menu {
            min-width: 350px;
            font-size: 1.2rem;
        }

        .display-dropdown .dropdown-item {
            min-height: 65px;
            padding: 20px 24px;
            font-size: 1.2rem;
        }

        .display-dropdown .dropdown-item i {
            font-size: 1.5rem;
            width: 36px;
        }

        .dashboard-container {
            padding: 16px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .fixed-header .title {
            font-size: 1.1rem;
        }
        
        .fixed-header .subtitle {
            font-size: 0.8rem;
        }

        .bottom-navbar {
            height: 90px;
            padding: 10px 12px;
        }

        .bottom-navbar .nav-buttons {
            gap: 6px;
        }

        .bottom-navbar .btn-nav {
            min-height: 65px;
            padding: 10px 6px;
        }

        .bottom-navbar .btn-nav i {
            font-size: 1.2rem;
        }

        .bottom-navbar .btn-nav span {
            font-size: 0.75rem;
        }

        .display-dropdown .dropdown-menu {
            min-width: 95vw;
            left: 50% !important;
            transform: translateX(-50%) !important;
            font-size: 1.1rem;
        }

        /* Di mobile, izinkan scroll */
        .dashboard-container {
            overflow-y: auto !important;
            bottom: 90px;
        }

        .dashboard-container .row {
            flex-direction: column;
            gap: 1rem;
        }

        .col-lg-4,
        .col-lg-8 {
            height: auto !important;
        }

        .col-lg-4 .card-fixed-height {
            height: auto !important;
        }

        .col-lg-8 .card-fixed-height {
            height: 500px !important;
        }

        /* Search form mobile */
        .search-form-wrapper {
            flex-direction: column;
            max-width: 100%;
            width: 100%;
            margin-top: 10px;
        }

        .search-container {
            width: 100%;
        }

        .btn-reset {
            width: 100%;
            margin-top: 5px;
        }

        /* Pagination mobile */
        .custom-pagination .page-link {
            min-width: 38px !important;
            height: 38px !important;
            font-size: 0.9rem !important;
        }

        /* Alert & Toast mobile */
        .custom-alert {
            max-width: 90%;
        }
        
        .custom-alert-actions {
            flex-direction: column;
        }
        
        .custom-alert-btn {
            width: 100%;
        }
        
        .custom-toast-container {
            left: 20px;
            right: 20px;
            max-width: none;
        }
        
        .custom-toast {
            width: 100%;
        }
    }

    /* Small Mobile */
    @media (max-width: 576px) {
        .pagination-container {
            flex-direction: column;
            gap: 12px;
            text-align: center;
        }

        .pagination-info {
            font-size: 0.85rem;
        }

        .fixed-header {
            padding: 12px 16px;
            height: 70px;
        }

        .dashboard-container {
            top: 70px;
            padding: 12px;
        }

        .col-lg-4 .card-fixed-height .card-body {
            padding: 16px !important;
            gap: 12px !important;
        }

        .col-lg-4 .form-control-lg {
            padding: 10px 14px !important;
        }

        .col-lg-4 .btn-lg {
            padding: 12px 20px !important;
        }
    }
</style>
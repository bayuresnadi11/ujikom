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
        }

        /* Fixed Header - ULTRA COMPACT */
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
        }

        .fixed-header .title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0;
        }

        .fixed-header .subtitle {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        /* BOTTOM NAVBAR - UPDATE DENGAN STYLE YANG BARU */
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

    /* TOMBOL NAVIGASI - SEMUA SAMA LEBAR */
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

    /* Dropdown Display - SAMA DENGAN TOMBOL LAIN */
    .display-dropdown {
        flex: 1;
        display: flex;
    }

    .display-dropdown .btn-nav {
        flex: 1;
        width: 100%;
    }

    /* DROPDOWN MENU LEBIH BESAR UNTUK TABLET */
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

    /* WARNA TOMBOL */
    .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
        border-width: 2px;
    }

    .btn-outline-primary:hover, 
    .display-dropdown .btn-outline-primary.show {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
        border-width: 2px;
    }

    .btn-outline-info:hover {
        color: #fff;
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-outline-success {
        color: #198754;
        border-color: #198754;
        border-width: 2px;
    }

    .btn-outline-success:hover {
        background-color: #198754;
        color: white;
    }

    .btn-outline-warning {
        color: #fd7e14;
        border-color: #fd7e14;
        border-width: 2px;
    }

    .btn-outline-warning:hover {
        background-color: #fd7e14;
        border-color: #fd7e14;
        color: #fff;
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.4);
    }

    /* RESPONSIVE TABLET (iPad) */
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

        /* DROPDOWN LEBIH BESAR DI TABLET */
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
    }

    /* RESPONSIVE MOBILE */
    @media (max-width: 768px) {
        .bottom-navbar {
            height: 90px;
            padding: 10px 12px;
        }

        .bottom-navbar .nav-buttons {
            gap: 6px;
            justify-content: space-between;
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

        /* DROPDOWN FULL WIDTH DI MOBILE */
        .display-dropdown .dropdown-menu {
            min-width: 95vw;
            left: 50% !important;
            transform: translateX(-50%) !important;
            font-size: 1.1rem;
        }

        .display-dropdown .dropdown-item {
            min-height: 55px;
            padding: 16px 20px;
            font-size: 1.1rem;
        }
    }

        /* Main Container - COMPACT */
        .scan-container {
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            bottom: 100px;
            display: flex;
            background: #f8f9fa;
            overflow: hidden;
        }

        /* Left Panel - Scanner (55%) - COMPACT */
        .scanner-panel {
            flex: 0 0 55%;
            padding: 15px 20px;
            background: #fff;
            border-right: 2px solid #e9ecef;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            align-items: center;
        }

        /* Right Panel - Results (45%) - COMPACT */
        .results-panel {
            flex: 0 0 45%;
            padding: 15px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .results-panel-inner {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scanner Controls - STACKED & COMPACT */
        .scanner-controls {
            width: 100%;
            max-width: 580px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
            flex-shrink: 0;
        }

        .mode-toggle {
            display: flex;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .mode-toggle .btn {
            flex: 1;
            border-radius: 0;
            border: none;
            padding: 10px 12px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
        }

        .mode-toggle .btn i {
            font-size: 1rem;
        }

        .mode-toggle .btn.active {
            background: #198754;
            color: white;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.2);
        }

        #camera-select {
            width: 100%;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #4a5568;
            background-color: #f7fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #camera-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
            background-color: #fff;
        }

        #camera-select:hover {
            background-color: #fff;
            border-color: #cbd5e0;
        }

        /* Scanner Box - SQUARE & COMPACT */
        .scanner-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            width: 100%;
            max-width: 580px;
        }

        #qr-reader {
            width: 100%;
            max-width: 580px;
            aspect-ratio: 1 / 1; /* FORCE SQUARE */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(25,135,84,.15);
            background: #000;
        }

        #qr-reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }

        .scanner-status {
            margin-top: 8px;
            text-align: center;
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            padding: 8px 16px;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(25,135,84,.3);
            flex-shrink: 0;
        }

        /* Results Panel Styling - COMPACT */
        .results-header {
            font-size: 1rem;
            font-weight: 700;
            color: #198754;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #198754;
            flex-shrink: 0;
        }

        #result {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .success-result, .error-result {
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .success-result {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 5px solid #28a745;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }

        .error-result {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 5px solid #dc3545;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        .result-icon {
            font-size: 2rem;
            margin-bottom: 8px;
            line-height: 1;
        }

        .result-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .result-detail {
            margin: 8px 0;
            font-size: 0.8rem;
        }

        .result-detail small {
            display: block;
            color: #6c757d;
            font-size: 0.7rem;
            margin-bottom: 2px;
        }

        .result-detail strong {
            display: block;
            color: #212529;
            font-size: 0.85rem;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Modern Notification */
        .notification-container {
            position: fixed;
            top: 70px;
            right: 24px;
            z-index: 9999;
            width: 380px;
        }

        .notification {
            background: white;
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 14px;
            animation: slideInRight 0.3s ease;
            border-left: 5px solid;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .notification.success {
            border-left-color: #28a745;
        }

        .notification.error {
            border-left-color: #dc3545;
        }

        .notification.warning {
            border-left-color: #ffc107;
        }

        .notification.info {
            border-left-color: #17a2b8;
        }

        .notification-icon {
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 3px;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .notification-close {
            background: none;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-close:hover {
            opacity: 1;
        }

        /* Logout Modal */
        .logout-icon {
            width: 70px;
            height: 70px;
            background: #fff8e1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .logout-icon i {
            font-size: 28px;
            color: #ff9800;
        }

        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
        }

        .btn-warning:hover {
            background-color: #f57c00;
            border-color: #f57c00;
            color: white;
        }

        .btn-outline-secondary {
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
        }
        .btn-outline-warning:hover {
            background-color: #ffb300;
            border-color: #ffb300;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }


        /* Responsive iPad & Desktop */
        @media (max-width: 1024px) {
            .scanner-panel {
                flex: 0 0 60%;
            }
            .results-panel {
                flex: 0 0 40%;
            }
        }

        @media (max-width: 768px) {
            .fixed-header .title {
                font-size: 0.95rem;
            }
            .fixed-header .subtitle {
                font-size: 0.65rem;
            }
            .scanner-panel {
                flex: 0 0 100%;
                border-right: none;
            }
            .results-panel {
                display: none;
            }
            .notification-container {
                width: calc(100% - 40px);
                left: 20px;
                right: 20px;
            }
        }

    /* Fade Out Animation */
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(20px); }
    }

    .fade-out {
        animation: fadeOut 0.5s ease forwards;
    }

    /* Scrollbar Styling for Results Panel */
    .results-panel-inner {
        scroll-behavior: smooth;
    }

    .results-panel-inner::-webkit-scrollbar {
        width: 6px;
    }

    .results-panel-inner::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .results-panel-inner::-webkit-scrollbar-thumb {
        background: #198754;
        border-radius: 10px;
    }

    .results-panel-inner::-webkit-scrollbar-thumb:hover {
        background: #157347;
    }
    
    #scan-log {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 20px;
    }
</style>
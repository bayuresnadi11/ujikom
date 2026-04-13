@extends('layouts.main', ['title' => 'Detail Main Bareng - SewaLap'])

@push('styles')
    <style>        
        #toastContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast {
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 250px;
        }
        .toast.success { background: #2ecc71; }
        .toast.error { background: #e74c3c; }
        .toast.info { background: #3498db; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
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
            border-top: 5px solid var(--secondary);
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
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

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
            
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 3px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 6px 24px rgba(0,0,0,0.12);
            --shadow-xl: 0 9px 36px rgba(0,0,0,0.15);
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

        /* ================= HEADER ================= */
        .mobile-container {
            width: 100%;
            min-height: 100vh;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            overflow-x: hidden;
        }
        
        .mobile-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--gradient-dark);
            z-index: 1100;
            box-shadow: var(--shadow-md);
        }

        /* ================= SIMPLE HEADER ================= */
        .simple-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: white;
            z-index: 1100;
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid var(--light-gray);
        }

        .header-content {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            gap: 16px;
        }

        .back-button-simple {
            background: none;
            border: none;
            font-size: 18px;
            color: var(--primary);
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .back-button-simple:hover {
            background: var(--light);
        }

        .header-title-simple {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
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

        /* ================= MAIN ================= */
        .main-content {
            padding: 70px 0 80px;
            min-height: 100vh;
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

        /* ================ DETAIL PAGE STYLING ================ */
        .detail-header {
            padding: 16px;
            background: var(--gradient-light);
            border-bottom: 1px solid var(--light-gray);
        }

        .detail-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 16px;
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
            width: fit-content;
            transition: all 0.3s ease;
        }

        .detail-back-btn:hover {
            background: var(--light);
            transform: translateX(-2px);
        }

        /* Updated Detail Header */
        .detail-header {
            padding: 16px;
            background: var(--gradient-light);
            border-bottom: 1px solid var(--light-gray);
        }

        .detail-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .detail-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-public {
            background: #E8F5E9;
            color: #0A5C36;
        }

        .badge-private {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-paid {
            background: #E3F2FD;
            color: #1565C0;
        }

        .badge-free {
            background: #F3E5F5;
            color: #7B1FA2;
        }

        .badge-active {
            background: #E8F5E9;
            color: #0A5C36;
        }

        .badge-pending {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-gender {
            background: var(--light);
            color: var(--primary);
        }

        .badge-host-yes {
            background: #E3F2FD;
            color: #1565C0;
        }

        .badge-host-no {
            background: #FFEBEE;
            color: #C62828;
        }

        .badge-approved {
            background: #E8F5E9;
            color: #0A5C36;
            font-size: 10px;
        }

        .badge-pending-sm {
            background: #FFF3E0;
            color: #E65100;
            font-size: 10px;
        }

        .badge-partial-sm {
            background: #FFF3E0;
            color: #E65100;
            font-size: 10px;
        }

        /* Payment Status Badges */
        .badge-lunas {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #C8E6C9;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            margin-left: 4px;
        }
        .badge-belum-lunas {
            background: #FFEBEE;
            color: #D32F2F;
            border: 1px solid #FFCDD2;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            margin-left: 4px;
        }

        .badge-host {
            background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%);
            color: #856404;
            font-size: 10px;
        }

        /* ================ DETAIL CONTENT ================ */
        .detail-content {
            padding: 16px;
        }

        .detail-section {
            margin-bottom: 24px;
            padding: 16px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--light-gray);
        }

        .detail-section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--light);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media (min-width: 400px) {
            .detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-light);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text);
            font-size: 13px;
            text-align: right;
        }

        .detail-description {
            padding: 12px;
            background: var(--light);
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text);
            margin-top: 12px;
        }

        /* ================ PARTICIPANTS SECTION ================ */
        .participants-container {
            margin-top: 12px;
        }

        .participant-count {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 12px;
        }

        .participant-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            padding: 4px;
        }

        .participant-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid var(--light-gray);
            transition: background 0.3s ease;
        }

        .participant-item:last-child {
            border-bottom: none;
        }

        .participant-item:hover {
            background: var(--light);
        }

        .participant-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .participant-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--light-gray);
        }

        .participant-name {
            font-weight: 600;
            font-size: 13px;
            color: var(--text);
        }

        /* ================ ACTION BUTTONS ================ */
        .action-buttons {
            margin: 24px 16px 20px;
            display: flex;
            justify-content: center;
        }

        .btn-container {
            display: flex;
            gap: 12px;
            width: fit-content;
        }

        .btn-action {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            min-width: 200px;
        }

        .btn-join {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-apply {
            background: var(--gradient-accent);
            color: white;
        }

        .btn-host-join {
            background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%);
            color: #856404;
        }

        .btn-disabled {
            background: var(--light-gray);
            color: var(--text-light);
            cursor: not-allowed;
        }

        .btn-action:hover:not(.btn-disabled) {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* ================ FULL QUOTA ALERT ================ */
        .quota-alert {
            margin: 16px;
            padding: 16px;
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            border-left: 4px solid #F57C00;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-sm);
        }

        .quota-alert-icon {
            width: 40px;
            height: 40px;
            background: #F57C00;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .quota-alert-content {
            flex: 1;
        }

        .quota-alert-title {
            font-size: 14px;
            font-weight: 700;
            color: #E65100;
            margin-bottom: 4px;
        }

        .quota-alert-message {
            font-size: 12px;
            color: #6C757D;
            line-height: 1.4;
        }

        /* ================ CUSTOM CONFIRM MODAL ================ */
        .confirm-overlay {
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
            backdrop-filter: blur(4px);
            padding: 20px;
        }

        .confirm-overlay.active {
            display: flex;
        }

        .confirm-modal {
            background: white;
            width: 100%;
            max-width: 320px;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .confirm-overlay.active .confirm-modal {
            transform: scale(1);
            opacity: 1;
        }

        .confirm-icon {
            width: 60px;
            height: 60px;
            background: #f0faf5;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px;
        }

        .confirm-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 8px;
        }

        .confirm-message {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .confirm-actions {
            display: flex;
            gap: 12px;
        }

        .btn-confirm {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-confirm-cancel {
            background: var(--light);
            color: var(--text-light);
        }

        .btn-confirm-ok {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-confirm-ok:active {
            transform: scale(0.98);
        }


        /* ================ TOAST NOTIFICATION ================ */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            padding: 12px 16px;
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            margin-bottom: 10px;
            animation: toastSlide 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            max-width: 100%;
        }

        @keyframes toastSlide {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .toast.success {
            border-left: 4px solid #0A5C36;
        }

        .toast.error {
            border-left: 4px solid #C62828;
        }

        /* ================ PAYMENT MODAL ================ */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 16px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 480px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-light);
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .modal-close:hover {
            background: var(--light);
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid var(--light-gray);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .payment-amount {
            text-align: center;
            margin-bottom: 20px;
        }

        .payment-amount h3 {
            font-size: 16px;
            color: var(--primary);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .payment-amount p {
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .payment-amount .amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        #snap-container {
            margin-top: 20px;
        }

        .btn-cancel,
        .btn-submit {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel {
            background: var(--light);
            color: var(--text);
        }

        .btn-submit {
            background: var(--gradient-primary);
            color: white;
        }

        /* ================ LOADING STATE ================ */
        .loading {
            text-align: center;
            padding: 30px 16px;
            color: var(--primary);
        }

        .loading i {
            font-size: 24px;
            margin-bottom: 12px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* ================= RESPONSIVE ================= */
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

            .simple-header {
                max-width: 480px;
                left: 50%;
                transform: translateX(-50%);
            }
            
            .bottom-nav {
                max-width: 480px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 20px 20px 0 0;
            }
            
            .action-buttons {
                /* Removed buggy positioning */
                width: 100%;
            }
        }

        @media (max-width: 350px) {
            .logo {
                font-size: 16px;
            }
            
            .logo-icon {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }
            
            .btn-container {
                flex-direction: column;
            }
        }

        /* ================ SWEETALERT2 CUSTOM STYLE ================ */
        .swal2-popup {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            border-radius: 12px !important;
        }

        .swal2-confirm {
            background: var(--gradient-primary) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            background: var(--light) !important;
            border: 1px solid var(--light-gray) !important;
            border-radius: 8px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            color: var(--text) !important;
        }
    </style>
@endpush

@section('content')
<div class="mobile-container">
    <header class="simple-header">
        <div class="header-content">
            <a href="{{ auth()->check() && auth()->user()->role === 'buyer' ? route('buyer.main_bareng.index') : route('guest.main_bareng.index') }}" class="back-button-simple">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="header-title-simple">Detail Aktivitas</h1>
        </div>
    </header>

    <main class="main-content">
        <!-- Detail Header -->
        <div class="detail-header" style="background: none; border: none; padding-top: 20px;">
            <h1 class="detail-title">{{ $playTogether->booking->venue->venue_name ?? 'Main Bareng' }}</h1>
            
            <div class="detail-badges">
                <span class="badge badge-{{ $playTogether->privacy === 'public' ? 'public' : 'private' }}">
                    <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i> 
                    {{ strtoupper($playTogether->privacy) }}
                </span>
                <span class="badge badge-{{ $playTogether->type === 'paid' ? 'paid' : 'free' }}">
                    <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i> 
                    {{ strtoupper($playTogether->type) }}
                </span>
                <span class="badge badge-{{ $playTogether->status }}">
                    {{ strtoupper($playTogether->status) }}
                </span>
                <span class="badge badge-{{ $playTogether->host_approval ? 'host-yes' : 'host-no' }}">
                    @if($playTogether->host_approval)
                        <i class="fas fa-user-check"></i> Perlu Persetujuan
                    @else
                        <i class="fas fa-bolt"></i> Auto Join
                    @endif
                </span>
            </div>
        </div>

        <!-- Detail Content -->
        <div class="detail-content">
            <!-- Booking Details Section -->
            <div class="detail-section">
                <h3 class="detail-section-title">
                    <i class="fas fa-calendar-alt"></i> Detail Booking
                </h3>
                
                @if($playTogether->booking)
                    @php
                        $booking = $playTogether->booking;
                        $venue = $booking->venue;
                        $schedule = $booking->schedule;
                    @endphp
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-map-marker-alt"></i> Lokasi
                            </div>
                            <div class="detail-value">{{ $venue->location }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-calendar-day"></i> Tanggal Booking
                            </div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y') }}</div>
                        </div>
                        
                        @if($schedule)
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-clock"></i> Waktu
                                </div>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-hourglass-half"></i> Durasi
                                </div>
                                <div class="detail-value">{{ $schedule->rental_duration }} jam</div>
                            </div>
                        @endif
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-dollar-sign"></i> Status Pembayaran
                            </div>
                            <div class="detail-value">
                                @switch($booking->booking_payment)
                                    @case('full')
                                        <span class="badge badge-approved">LUNAS</span>
                                        @break

                                    @case('partial')
                                        <span class="badge badge-partial-sm">PARTIAL</span>
                                        @break

                                    @default
                                        <span class="badge badge-pending-sm">PENDING</span>
                                @endswitch
                            </div>
                        </div>
                        
                        @if($booking->isPaid())
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-check-circle"></i> Dibayar pada
                                </div>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($booking->paid_at)->translatedFormat('d M Y H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <p style="color: var(--text-light); text-align: center; padding: 20px;">
                        Detail booking tidak tersedia
                    </p>
                @endif
            </div>

            <!-- Main Bareng Details Section -->
            <div class="detail-section">
                <h3 class="detail-section-title">
                    <i class="fas fa-info-circle"></i> Informasi Main Bareng
                </h3>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Tanggal Main
                        </div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-users"></i> Kuota Peserta
                        </div>
                        <div class="detail-value">{{ $approvedParticipantsCount }} / {{ $playTogether->max_participants }} orang</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-venus-mars"></i> Jenis Kelamin
                        </div>
                        <div class="detail-value">
                            <span class="badge badge-gender">
                                @if($playTogether->gender === 'male')
                                    <i class="fas fa-mars"></i> Laki-laki
                                @elseif($playTogether->gender === 'female')
                                    <i class="fas fa-venus"></i> Perempuan
                                @else
                                    <i class="fas fa-venus-mars"></i> Campur
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-user"></i> Host
                        </div>
                        <div class="detail-value">
                            {{ $playTogether->creator->name }}
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-user-check"></i> Persetujuan Host
                        </div>
                        <div class="detail-value">
                            @if($playTogether->host_approval)
                                <span class="badge badge-host-yes">Diperlukan</span>
                            @else
                                <span class="badge badge-host-no">Auto Join</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($playTogether->description)
                    <div class="detail-description">
                        <strong>Deskripsi:</strong><br>
                        {{ $playTogether->description }}
                    </div>
                @endif
            </div>

            <!-- Participants Section -->
            <div class="detail-section">
                <h3 class="detail-section-title">
                    <i class="fas fa-users"></i> Daftar Peserta
                </h3>
                
                <div class="participant-count">
                    Total peserta: {{ $approvedParticipantsCount }} dari {{ $playTogether->max_participants }} kuota
                </div>
                
                <div class="participants-container">
                    <!-- Approved Participants -->
                    <h4 style="font-size: 14px; color: var(--primary); margin-bottom: 8px;">
                        <i class="fas fa-check-circle"></i> Peserta Diterima ({{ $approvedParticipants->count() }})
                    </h4>
                    
                    @if($approvedParticipants->count() > 0)
                        <div class="participant-list">
                            @foreach($approvedParticipants as $participant)
                                <div class="participant-item">
                                    <div class="participant-info">
                                        @if($participant->user->avatar)
                                            <img src="{{ asset('storage/' . $participant->user->avatar) }}" alt="{{ $participant->user->name }}" class="participant-avatar">
                                        @else
                                            <div class="participant-avatar" style="display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user" style="color: var(--text-light);"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="participant-name">{{ $participant->user->name }}</div>
                                            <small style="color: var(--text-light); font-size: 11px;">
                                                Bergabung {{ \Carbon\Carbon::parse($participant->joined_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div>
                                        @if($participant->user_id == $playTogether->created_by)
                                            <span class="badge badge-host">Host</span>
                                        @else
                                            <span class="badge badge-approved">Diterima</span>
                                        @endif

                                        @if($playTogether->type === 'paid')
                                            @if($participant->payment_status === 'paid')
                                                <span class="badge-lunas">LUNAS</span>
                                            @else
                                                <span class="badge-belum-lunas">BELUM LUNAS</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--text-light); text-align: center; padding: 20px;">
                            Belum ada peserta yang diterima
                        </p>
                    @endif
                </div>
            </div>
        </div>


        <!-- Full Quota Alert -->
        @if($isFull && !$hasJoined && !$isCreator)
            <div class="quota-alert">
                <div class="quota-alert-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quota-alert-content">
                    <div class="quota-alert-title">Kuota Penuh</div>
                    <div class="quota-alert-message">
                        Maaf, kuota peserta untuk Main Bareng ini sudah penuh ({{ $approvedParticipantsCount }}/{{ $playTogether->max_participants }}). Silakan cari Main Bareng lainnya.
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <div class="btn-container">
                @auth
                    @if($isCreator)
                        @if($showHostJoinButton)
                            <button id="hostJoinButton" class="btn-action btn-host">
                                <i class="fas fa-crown"></i> Gabung sbg Host
                            </button>
                        @else
                            <button class="btn-action btn-disabled">
                                <i class="fas fa-crown"></i> Anda adalah Host
                            </button>
                        @endif
                    @elseif($hasJoined)
                        @if($participant->payment_status === 'pending')
                            <button id="payButton" class="btn-action btn-join">
                                <i class="fas fa-money-bill-wave"></i> Bayar Sekarang
                            </button>
                        @else
                            <button class="btn-action btn-disabled">
                                <i class="fas fa-check-circle"></i> Sudah Bergabung
                            </button>
                        @endif
                    @elseif($hasApplied)
                        <button class="btn-action btn-disabled">
                            <i class="fas fa-clock"></i> Menunggu Persetujuan
                        </button>
                    @else
                        @if($isFull)
                            <button class="btn-action btn-disabled">
                                <i class="fas fa-users-slash"></i> Kuota Penuh
                            </button>
                        @else
                            @if($showApplyButton)
                                <button id="applyButton" class="btn-action btn-apply">
                                    <i class="fas fa-user-check"></i> Ajukan Bergabung
                                </button>
                            @elseif($showJoinButton)
                                <button id="joinButton" class="btn-action btn-join">
                                    <i class="fas fa-sign-in-alt"></i> Gabung Sekarang
                                </button>
                            @endif
                        @endif
                    @endif
                @else
                    <!-- Guest View -->
                    @if($isFull)
                        <button class="btn-action btn-disabled">
                            <i class="fas fa-users-slash"></i> Kuota Penuh
                        </button>
                    @elseif($showApplyButton || $showJoinButton)
                        <a href="{{ route('login') }}" class="btn-action btn-join">
                            <i class="fas fa-sign-in-alt"></i> Login untuk Bergabung
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
    </div>

    <div id="toastContainer"></div>

    <!-- Payment Modal -->
    <div class="modal-overlay" id="paymentModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Pembayaran</h3>
                <button class="modal-close" onclick="closePaymentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="payment-amount">
                    <h3>Total Pembayaran</h3>
                    <div class="amount" id="paymentAmount">Rp 0</div>
                    <p>Selesaikan pembayaran untuk bergabung</p>
                </div>
                
                <div id="snap-container"></div>
                
                <div id="paymentStatus" style="display: none; text-align: center; padding: 20px;">
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p style="margin-top: 10px; color: var(--text-light);">Menunggu pembayaran...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Join Confirmation Modal -->
    <div class="confirm-overlay" id="joinConfirmModal">
        <div class="confirm-modal">
            <div class="confirm-icon">
                <i class="fas fa-question"></i>
            </div>
            <h3 class="confirm-title">Konfirmasi</h3>
            <p class="confirm-message" id="joinModalText">Apakah Anda yakin ingin bergabung?</p>
            <div class="confirm-actions">
                <button class="btn-confirm btn-confirm-cancel" onclick="closeJoinModal()">Batal</button>
                <button class="btn-confirm btn-confirm-ok" onclick="confirmJoin()">Oke</button>
            </div>
        </div>
    </div>

    <!-- Custom Success Modal -->
    <div class="confirm-overlay" id="successModal">
        <div class="confirm-modal">
            <div class="confirm-icon" style="background: #E8F5E9; color: #2ECC71;">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="confirm-title" id="successModalTitle">Berhasil</h3>
            <p class="confirm-message" id="successModalText">Permintaan bergabung berhasil dikirim.</p>
            <div class="confirm-actions" style="justify-content: center;">
                <button class="btn-confirm btn-confirm-ok" onclick="closeSuccessModal()" style="flex: none; min-width: 120px;">OK</button>
            </div>
        </div>
    </div>

    <!-- Bottom Nav untuk Guest -->
    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global variables
        let currentPaymentAmount = 0;
        let currentOrderId = null;
        let isHostJoin = false;
        let paymentCheckInterval = null;

        let selectedAction = null;
        let selectedPlayTogetherId = {{ $playTogether->id }};

        // Modal Functions
        function showJoinModal(message, action) {
            selectedAction = action;
            document.getElementById('joinModalText').innerText = message;
            document.getElementById('joinConfirmModal').classList.add('active');
        }

        function closeJoinModal() {
            document.getElementById('joinConfirmModal').classList.remove('active');
            selectedAction = null;
        }

        function confirmJoin() {
            if (selectedAction) {
                selectedAction();
            }
            closeJoinModal();
        }

        // Success Modal Functions
        function showSuccessModal(title, message) {
            document.getElementById('successModalTitle').innerText = title;
            document.getElementById('successModalText').innerText = message;
            document.getElementById('successModal').classList.add('active');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('active');
            window.location.reload();
        }

        // Host Join Button Click Handler
        @if($showHostJoinButton)
            document.getElementById('hostJoinButton').addEventListener('click', function() {
                showJoinModal('Apakah Anda yakin ingin bergabung sebagai Host?', function() {
                    isHostJoin = true;
                    joinMainBareng(selectedPlayTogetherId, false, true);
                });
            });
        @endif

        // Join Button Click Handler
        if(document.getElementById('joinButton')) {
            document.getElementById('joinButton').addEventListener('click', function() {
                showJoinModal('Apakah Anda yakin ingin bergabung?', function() {
                    isHostJoin = false;
                    joinMainBareng(selectedPlayTogetherId, false, false);
                });
            });
        }

        // Apply Button Click Handler
        if(document.getElementById('applyButton')) {
            document.getElementById('applyButton').addEventListener('click', function() {
                showJoinModal('Ajukan permintaan untuk bergabung dengan Main Bareng ini?', function() {
                    isHostJoin = false;
                    joinMainBareng(selectedPlayTogetherId, true, false);
                });
            });
        }

        // Pay Button Click Handler
        if(document.getElementById('payButton')) {
            document.getElementById('payButton').addEventListener('click', function() {
                payNow({{ $playTogether->id }});
            });
        }

        // Function to join main bareng
        function joinMainBareng(playTogetherId, needsApproval = false, isHost = false) {
            showLoading('Memproses permintaan...');

            fetch(`/buyer/main-bareng/${playTogetherId}/join`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    needs_approval: needsApproval,
                    is_host: isHost
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();

                if(data.success){
                    if(data.needs_payment){
                        // Kalau tipe paid / butuh bayar -> pakai flow Midtrans dengan token dari response
                        if (data.snap_token) {
                            currentOrderId = data.order_id;
                            document.getElementById('paymentAmount').textContent = 'Rp ' + parseInt(data.amount).toLocaleString('id-ID');
                            document.getElementById('paymentModal').classList.add('active');
                            
                            // Trigger Snap
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    createParticipantAfterPayment(result);
                                },
                                onPending: function(result) {
                                    document.getElementById('snap-container').style.display = 'none';
                                    document.getElementById('paymentStatus').style.display = 'block';
                                    startPaymentStatusCheck(currentOrderId);
                                },
                                onError: function(result) {
                                    showToast('Pembayaran gagal. Silakan coba lagi.', 'error');
                                    closePaymentModal();
                                },
                                onClose: function() {
                                    showToast('Pembayaran dibatalkan.', 'info');
                                    closePaymentModal();
                                }
                            });
                        } else {
                            showToast('Gagal mendapatkan token pembayaran', 'error');
                        }
                    } else {
                        // Free tanpa split ATAU pending approval
                        const title = data.needs_approval ? 'Permintaan Terkirim!' : 'Berhasil!';
                        showSuccessModal(title, data.message);
                    }
                } else {
                    showToast(data.message || 'Gagal bergabung', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat bergabung', 'error');
            });
        }

        // Function to pay for an approved request
        function payNow(playTogetherId) {
            showLoading('Mendapatkan data pembayaran...');

            fetch(`/buyer/main-bareng-saya/${playTogetherId}/get-snap-token`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();

                if (data.success && data.snap_token) {
                    currentOrderId = data.order_id; // Usually handled in Snap.pay callback or not needed as much here
                    document.getElementById('paymentModal').classList.add('active');
                    
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            showLoading('Sinkronisasi status pembayaran...');
                            // Call updatePaymentStatus on backend
                            fetch(`/buyer/main-bareng-saya/${playTogetherId}/update-payment-status`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    status: result.transaction_status,
                                    order_id: result.order_id,
                                    gross_amount: result.gross_amount
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Pembayaran Anda telah berhasil.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => window.location.reload());
                                } else {
                                    showToast(data.message || 'Gagal sinkronisasi pembayaran.', 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error('Update Error:', err);
                                showToast('Pembayaran berhasil, tapi gagal sinkronisasi.', 'warning');
                                setTimeout(() => window.location.reload(), 2000);
                            });
                        },
                        onPending: function(result) {
                            showToast('Menunggu pembayaran...', 'info');
                            window.location.reload();
                        },
                        onError: function(result) {
                            showToast('Pembayaran gagal.', 'error');
                        },
                        onClose: function() {
                            showToast('Pembayaran ditunda.', 'info');
                        }
                    });
                } else {
                    showToast(data.message || 'Gagal memulai pembayaran.', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showToast('Terjadi kesalahan sistem.', 'error');
            });
        }
        
        // Removed separate processPayment function as it is now integrated into join response handling


        // Create participant after successful payment
        function createParticipantAfterPayment(paymentResult) {
            showLoading('Menyelesaikan proses bergabung...');
            
            fetch('/buyer/main-bareng/create-participant', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: currentOrderId,
                    transaction_status: paymentResult.transaction_status,
                    transaction_id: paymentResult.transaction_id
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    if (data.needs_approval) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pembayaran berhasil. Permintaan bergabung telah dikirim dan menunggu persetujuan host.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-popup'
                            }
                        }).then(() => {
                            closePaymentModal();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-popup'
                            }
                        }).then(() => {
                            closePaymentModal();
                            window.location.reload();
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    });
                    closePaymentModal();
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error creating participant:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyelesaikan proses.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal2-popup'
                    }
                });
                closePaymentModal();
            });
        }

        // Start checking payment status
        function startPaymentStatusCheck(orderId) {
            // Check immediately
            checkPaymentStatus(orderId);
            
            // Then check every 5 seconds
            paymentCheckInterval = setInterval(() => {
                checkPaymentStatus(orderId);
            }, 5000);
        }

        // Check payment status
        function checkPaymentStatus(orderId) {
            fetch(`/buyer/main-bareng/check-payment-status/${orderId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.status === 'completed') {
                        // Participant sudah dibuat
                        clearInterval(paymentCheckInterval);
                        
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-popup'
                            }
                        }).then(() => {
                            closePaymentModal();
                            window.location.reload();
                        });
                    } else if (data.status === 'paid') {
                        // Pembayaran berhasil tapi participant belum dibuat, buat sekarang
                        createParticipantAfterManualCheck();
                    }
                    // Jika masih pending, teruskan pengecekan
                } else {
                    if (data.status === 'expired' || data.status === 'cancel' || data.status === 'deny') {
                        // Pembayaran gagal/expired
                        clearInterval(paymentCheckInterval);
                        
                        Swal.fire({
                            title: 'Pembayaran Gagal',
                            text: data.message || 'Pembayaran tidak berhasil.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-popup'
                            }
                        }).then(() => {
                            closePaymentModal();
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
        }

        // Create participant after manual status check
        function createParticipantAfterManualCheck() {
            showLoading('Menyelesaikan proses bergabung...');
            
            fetch('/buyer/main-bareng/create-participant', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: currentOrderId,
                    transaction_status: 'settlement',
                    transaction_id: null
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    clearInterval(paymentCheckInterval);
                    
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    }).then(() => {
                        closePaymentModal();
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    });
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error creating participant:', error);
            });
        }

        function closePaymentModal() {
            if (paymentCheckInterval) {
                clearInterval(paymentCheckInterval);
            }
            
            document.getElementById('paymentModal').classList.remove('active');
            document.getElementById('snap-container').style.display = 'block';
            document.getElementById('paymentStatus').style.display = 'none';
            
            // Clear snap container
            const snapContainer = document.getElementById('snap-container');
            snapContainer.innerHTML = '';
            
            currentPaymentAmount = 0;
            currentOrderId = null;
            isHostJoin = false;
        }

        // Toast notification
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <div>${message}</div>
            `;

            container.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                toast.style.animation = 'toastSlide 0.3s reverse';
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, duration);
        }

        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            if (overlay && text) {
                text.textContent = message;
                overlay.style.display = 'flex';
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Close modals on outside click
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                    }
                });
            });

            // Escape key to close modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                        modal.classList.remove('active');
                    });
                }
            });
        });
    </script>
@endpush
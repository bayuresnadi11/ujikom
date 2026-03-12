<style>
    :root {
        --primary: #8B1538;  /* Burgundy */
        --primary-dark: #6B0F2A;
        --primary-light: #A01B42;
        --secondary: #A01B42;
        --accent: #C7254E;
        --light: #FFF5F7;
        --light-gray: #FFE4E8;
        --text: #2C1810;
        --text-light: #5a3a2a;
        --success: #8B1538; /* Keeping consistent theme */
        --warning: #F39C12;
        --danger: #E74C3C;
        --gold: #FFD700;
        --info: #3498DB;

        --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);

        --shadow-sm: 0 2px 12px rgba(139, 21, 56, 0.08);
        --shadow-md: 0 4px 20px rgba(139, 21, 56, 0.12);
        --shadow-lg: 0 8px 30px rgba(139, 21, 56, 0.15);
        --shadow-xl: 0 12px 40px rgba(139, 21, 56, 0.18);
    }

    /* Mobile Container */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        border-radius: 0;
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        box-shadow: 0 0 35px rgba(139, 21, 56, 0.12);
        overflow: hidden;
    }

    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 60px rgba(139, 21, 56, 0.25);
            border-radius: 20px;
            overflow: hidden;
            min-height: 800px;
        }
    }

    /* Header Mobile */
    .mobile-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: var(--gradient-primary);
        padding: 16px;
        z-index: 100;
        box-shadow: var(--shadow-md);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .back-button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s;
        backdrop-filter: blur(10px);
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .header-title {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        text-align: center;
        flex: 1;
        padding: 0 10px;
    }

    .header-actions {
        width: 40px;
    }

    /* Main Content */
    .main-content {
        padding: 90px 16px 30px 16px;
        background: var(--light);
        min-height: calc(100vh - 72px);
    }

    /* Card Styles */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid var(--light-gray);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .card-header {
        padding: 18px 20px;
        background: var(--gradient-primary);
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
    }

    .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent);
    }

    .card-header i {
        font-size: 20px;
        color: white;
    }

    .card-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: white;
    }

    .card-body {
        padding: 20px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--primary);
        font-size: 12px;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        background-color: white;
        color: var(--text);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.1);
        background-color: white;
    }

    .form-control::placeholder {
        color: var(--text-light);
        opacity: 0.7;
    }

    /* Avatar Section */
    .avatar-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 0;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        border: 4px solid white;
        box-shadow: var(--shadow-lg);
        transition: all 0.3s;
    }

    .avatar-preview:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-xl);
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-preview i {
        font-size: 48px;
        color: white;
    }

    .avatar-actions {
        text-align: center;
    }

    /* Hide file input dengan cara yang benar */
.avatar-input-hidden {
    position: absolute;
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    z-index: -1;
}

/* Atau alternatif */
.avatar-input-hidden {
    display: none;
}

/* Style untuk button upload */
.avatar-upload-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 24px;
    background: var(--gradient-accent);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.avatar-upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    background: var(--gradient-primary);
}

.avatar-upload-btn:active {
    transform: translateY(0);
}

.avatar-upload-btn i {
    font-size: 16px;
}

.avatar-upload-btn span {
    pointer-events: none;
}

    .avatar-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: var(--gradient-accent);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .avatar-upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: var(--gradient-primary);
    }

    .avatar-hint {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 8px;
        text-align: center;
    }

    /* Button Styles */
    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 16px 32px;
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        box-shadow: var(--shadow-md);
        margin: 30px 0 20px 0;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: var(--gradient-dark);
    }

    .btn-submit:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Loading Animation */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }
    
    .phone-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 16px;
    padding: 30px 20px;
}

.overlay-content {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 350px;
    width: 90%;
}

/* Modern Spinner */
.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(37, 99, 235, 0.1);
    border-top-color: #3b82f6;
    border-right-color: #8b5cf6;
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Modern Buttons */
.phone-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 16px;
    padding: 30px 20px;
}

/* Spinner yang lebih kecil */
.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(37, 99, 235, 0.1);
    border-top-color: #3b82f6;
    border-right-color: #8b5cf6;
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Tombol yang lebih kecil */
.overlay-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
}

.btn-modern {
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.2);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 3px 10px rgba(239, 68, 68, 0.2);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

/* Custom Alert Modal */
.custom-alert {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.alert-box {
    background: white;
    border-radius: 20px;
    padding: 30px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 25px;
}

.alert-btn {
    padding: 12px 30px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 100px;
}

.alert-btn.confirm {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.alert-btn.cancel {
    background: #f1f5f9;
    color: #64748b;
}

.alert-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

    @keyframes spin {
        to { transform: rotate(360deg); }
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

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Alert Messages */
    .alert {
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.3s ease;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    @keyframes slideDown {
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
        background: linear-gradient(135deg, #d4ffe8 0%, #a8e6cf 100%);
        color: var(--primary-dark);
        border-color: var(--success);
    }

    .alert-success i {
        color: var(--success);
    }

    .alert-error {
        background: linear-gradient(135deg, #ffe6e6 0%, #ffb8b8 100%);
        color: var(--danger);
        border-color: var(--danger);
    }

    .alert-error i {
        color: var(--danger);
    }

    /* Input Validation */
    .is-invalid {
        border-color: var(--danger) !important;
        background-color: #fff8f8;
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        animation: fadeIn 0.3s ease;
    }

    .invalid-feedback::before {
        content: '⚠';
        font-size: 10px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Password Strength Indicator */
    .password-strength {
        height: 6px;
        background: var(--light-gray);
        border-radius: 3px;
        margin-top: 10px;
        overflow: hidden;
        position: relative;
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    .strength-weak {
        background: linear-gradient(90deg, #E74C3C, #F39C12);
        width: 33%;
    }

    .strength-medium {
        background: linear-gradient(90deg, #F39C12, #F1C40F);
        width: 66%;
    }

    .strength-strong {
        background: linear-gradient(90deg, #27AE60, #2ECC71);
        width: 100%;
    }

    /* View Only Field */
    .view-only-field {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        background: var(--gradient-light);
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        color: var(--text-light);
        font-weight: 500;
    }

    .view-only-field i {
        color: var(--primary);
        font-size: 16px;
    }

    /* Password Toggle */
    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        padding: 4px;
        font-size: 14px;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 50px;
    }
    /* ================= DESKTOP PREVIEW ================= */
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
        }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-dark);
    }
</style>
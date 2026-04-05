<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        min-height: 100vh;
        padding: 20px;
        padding-bottom: 120px; /* Extra space for bottom-nav */
    }

    .pengajuan-container {
        width: 100%;
        max-width: 500px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        margin: 0 auto; /* Center horizontally */
        margin-top: 20px;
        margin-bottom: 40px; /* Extra bottom space */
    }

    .pengajuan-header {
        background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
        padding: 30px 24px;
        text-align: center;
        color: white;
        position: relative;
    }

    .pengajuan-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 36px;
        backdrop-filter: blur(10px);
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .pengajuan-header h1 {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .pengajuan-header p {
        font-size: 14px;
        opacity: 0.9;
        line-height: 1.5;
    }

    .pengajuan-body {
        padding: 32px 24px;
    }

    .info-box {
        background: #f0fdf4;
        border-left: 4px solid #10b981;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .info-box-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .info-box-icon {
        color: #10b981;
        font-size: 18px;
    }

    .info-box-title {
        font-weight: 700;
        color: #065f46;
        font-size: 15px;
    }

    .info-box-text {
        color: #047857;
        font-size: 13px;
        line-height: 1.6;
        margin-left: 28px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .form-label .required {
        color: #ef4444;
    }

    .form-textarea {
        width: 100%;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 140px;
        transition: all 0.3s ease;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #0A5C36;
        box-shadow: 0 0 0 4px rgba(10, 92, 54, 0.1);
    }

    .form-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
        line-height: 1.4;
    }

    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .btn {
        flex: 1;
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(10, 92, 54, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(10, 92, 54, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Alert Popup */
    .alert-popup {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .alert-popup.active {
        opacity: 1;
        visibility: visible;
    }

    .alert-popup-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 350px;
        padding: 32px 24px 24px;
        text-align: center;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .alert-popup.active .alert-popup-content {
        transform: scale(1);
    }

    .alert-popup-icon {
        font-size: 56px;
        margin-bottom: 16px;
    }

    .alert-popup-icon.success {
        color: #10b981;
    }

    .alert-popup-icon.error {
        color: #ef4444;
    }

    .alert-popup-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .alert-popup-message {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .alert-popup-button {
        width: 100%;
        padding: 14px 24px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .alert-popup-button.success {
        background: #10b981;
        color: white;
    }

    .alert-popup-button.success:hover {
        background: #059669;
    }

    .alert-popup-button.error {
        background: #ef4444;
        color: white;
    }

    .alert-popup-button.error:hover {
        background: #dc2626;
    }

    @media (max-width: 480px) {
        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
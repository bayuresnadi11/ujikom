<style>
    :root {
        --primary: #0A5C36;
        --primary-dark: #08482b;
        --primary-light: #2E8B57;
        --secondary: #27AE60;
        --accent: #2ECC71;
        --light: #f7fdf9;
        --light-gray: #e8f5ef;
        --text: #1a3a27;
        --text-light: #5a7a6a;
        --success: #27AE60;
        --warning: #F39C12;
        --danger: #E74C3C;
        --gold: #FFD700;
        --info: #3498DB;
        --gradient-primary: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
        --gradient-accent: linear-gradient(135deg, #2ECC71 0%, #27AE60 100%);
        --gradient-light: linear-gradient(135deg, #f7fdf9 0%, #e8f5ef 100%);
        --gradient-dark: linear-gradient(135deg, #08482b 0%, #0A5C36 100%);
        --shadow-sm: 0 2px 12px rgba(10, 92, 54, 0.08);
        --shadow-md: 0 4px 20px rgba(10, 92, 54, 0.12);
        --shadow-lg: 0 8px 30px rgba(10, 92, 54, 0.15);
        --shadow-xl: 0 12px 40px rgba(10, 92, 54, 0.18);
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: var(--text);
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    /* Header Styles */
    .admin-header {
        background: white;
        border-radius: 16px;
        padding: 24px 32px;
        margin-bottom: 32px;
        transition-delay: 0.1s;
        box-shadow: var(--shadow-md);
        border-left: 6px solid var(--primary);
    }

    .admin-title {
        color: var(--primary);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 8px;
    }

    .admin-subtitle {
        color: var(--text-light);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    /* Fade Down (Header Only) */
    .fade-down {
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
        transition-delay: 0.3s;
    }
    
    .fade-down.show {
        opacity: 1;
        transform: translateY(0);
    }
    /* Page Fade In */
.fade-page {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
    transition-delay: 0.2s;
}

.fade-page.show {
    opacity: 1;
    transform: translateY(0);
}




    /* Settings Panel */
    .settings-panel {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition-delay: 0.25s;
        box-shadow: var(--shadow-lg);
        margin-bottom: 32px;
    }

    .settings-header {
        background: var(--gradient-primary);
        color: white;
        padding: 24px 32px;
        position: relative;
    }

    .settings-header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .settings-header:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
    }

    /* Tab Navigation */
    .settings-tabs {
        background: var(--light);
        border-bottom: 1px solid var(--light-gray);
        padding: 0 32px;
        display: flex;
        gap: 8px;
    }

    .settings-tab {
        padding: 18px 24px;
        background: transparent;
        border: none;
        color: var(--text-light);
        font-weight: 500;
        font-size: 1rem;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .settings-tab:hover {
        color: var(--primary);
    }

    .settings-tab.active {
        color: var(--primary);
        background: white;
        border-top: 3px solid var(--primary);
    }

    .settings-tab i {
        margin-right: 10px;
        font-size: 1.1rem;
    }

    /* Content Area */
    .settings-content {
        padding: 40px 32px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Section Cards */
    .section-card {
        background: var(--light);
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 32px;
        border: 1px solid var(--light-gray);
        transition: all 0.3s ease;
    }

    .section-card:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow-sm);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(10, 92, 54, 0.1);
    }

    .section-icon {
        width: 56px;
        height: 56px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        box-shadow: var(--shadow-sm);
        color: var(--primary);
        font-size: 1.5rem;
    }

    .section-title {
        color: var(--primary);
        font-weight: 600;
        font-size: 1.3rem;
        margin: 0 0 4px 0;
    }

    .section-desc {
        color: var(--text-light);
        margin: 0;
        font-size: 0.95rem;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        color: var(--text);
        font-weight: 500;
        font-size: 1rem;
    }

    .form-label .required {
        color: var(--danger);
        margin-left: 4px;
    }

    .input-group {
        position: relative;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        font-size: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        background: white;
        color: var(--text);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(10, 92, 54, 0.1);
    }

    .form-control-lg {
        padding: 16px 20px;
        font-size: 1.05rem;
    }

    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        z-index: 2;
    }

    .input-with-icon {
        padding-left: 50px;
    }

    .input-append {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        font-size: 1rem;
    }

    .form-text {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-top: 8px;
    }

    /* Logo Upload */
    .logo-upload-area {
        border: 2px dashed var(--light-gray);
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .logo-upload-area:hover {
        border-color: var(--primary);
        background: var(--light);
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--primary);
        opacity: 0.7;
        margin-bottom: 20px;
    }

    .logo-preview {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--light-gray);
    }

    .preview-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-right: 20px;
        border-radius: 8px;
        background: var(--light);
        padding: 10px;
    }

    /* Switch Toggle */
    .switch-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid var(--light-gray);
    }

    .switch-label {
        display: flex;
        flex-direction: column;
    }

    .switch-title {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
    }

    .switch-desc {
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--primary);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-left: 20px;
    }

    .status-badge.success {
        background: rgba(39, 174, 96, 0.1);
        color: var(--success);
    }

    .status-badge.warning {
        background: rgba(243, 156, 18, 0.1);
        color: var(--warning);
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
        animation: pulse 2s infinite;
    }

    .status-success .status-indicator {
        background: var(--success);
    }

    .status-warning .status-indicator {
        background: var(--warning);
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

     /* Logo Container */
    .logo-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Current Logo Section */
    .current-logo-section {
        margin-bottom: 16px;
    }

    .logo-label {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .logo-preview-box {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--surface);
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .current-logo {
        height: 120px;
        width: 120px;
        object-fit: contain;
        border-radius: 8px;
        background: white;
        padding: 8px;
        border: 1px solid var(--border-light);
    }

    .logo-info {
        flex: 1;
    }

    .logo-name {
        font-size: 14px;
        color: var(--text);
        word-break: break-all;
    }

    /* Upload Section */
    .upload-section {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .upload-area {
        position: relative;
        background: var(--surface);
        border: 2px dashed var(--primary-light);
        border-radius: 16px;
        padding: 40px 24px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .upload-area:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.02);
    }

    .upload-area.dragover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.05);
    }

    .upload-content {
        pointer-events: none;
    }

    .upload-icon {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 16px;
    }

    .upload-text h5 {
        color: var(--primary);
        margin: 0 0 8px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .upload-instruction {
        color: var(--text-light);
        margin: 0 0 4px 0;
        font-size: 14px;
    }

    .browse-text {
        color: var(--primary);
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
    }

    .upload-requirements {
        color: var(--text-lighter);
        font-size: 12px;
        margin: 0;
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* New Logo Preview */
    .new-logo-preview {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .new-logo-preview.show {
        display: block;
    }

    .preview-container {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--surface);
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .preview-img {
        height: 120px;
        width: 120px;
        object-fit: contain;
        border-radius: 8px;
        background: white;
        padding: 8px;
        border: 1px solid var(--border-light);
    }

    .preview-info {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-name {
        font-size: 14px;
        color: var(--text);
        word-break: break-all;
    }

    .remove-btn {
        background: var(--danger-light);
        color: var(--danger);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .remove-btn:hover {
        background: var(--danger);
        color: white;
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

    /* Responsive */
    @media (max-width: 768px) {
        .logo-preview-box,
        .preview-container {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }

        .current-logo,
        .preview-img {
            height: 100px;
            width: 100px;
        }
    }

    /* Action Buttons */
    .action-bar {
        background: white;
        padding: 24px 32px;
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: flex-end;
        align-items: center;
        position: sticky;
        bottom: 0;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
    }

    .btn {
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: white;
        min-width: 160px;
    }

    .btn-primary:hover {
        background: var(--gradient-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: white;
        color: var(--text);
        border: 2px solid var(--light-gray);
    }

    .btn-secondary:hover {
        background: var(--light);
        border-color: var(--primary-light);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-outline:hover {
        background: var(--primary);
        color: white;
    }

    /* Info Box */
    .info-box {
        background: var(--light);
        border-radius: 12px;
        padding: 24px;
        margin-top: 40px;
        border-left: 4px solid var(--primary);
    }

    .info-icon {
        font-size: 1.5rem;
        color: var(--primary);
        margin-right: 16px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-container {
            padding: 20px;
        }
        
        .settings-tabs {
            flex-direction: column;
            padding: 0;
        }
        
        .settings-tab {
            width: 100%;
            text-align: left;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .section-icon {
            margin-bottom: 15px;
        }
        
        .action-bar {
            flex-direction: column;
            gap: 16px;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
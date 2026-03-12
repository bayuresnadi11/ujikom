<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* VARIABEL CSS SAMA DENGAN MENU */
    :root {
        /* Warna Utama - AYO BURGUNDY #8B1538 */
        --primary: #8B1538;
        --primary-dark: #6B0F2A;
        --secondary: #A01B42;
        --accent: #C7254E;
        
        --danger: #e74c3c;
        --warning: #f39c12;
        --light: #FFF5F7;
        --light-gray: #e9ecef;
        --text: #333333;
        --text-light: #6c757d;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(139, 21, 56, 0.1);
        
        --shadow-sm: 0 2px 8px rgba(139, 21, 56, 0.08);
        --shadow-md: 0 4px 16px rgba(139, 21, 56, 0.12);
        --shadow-lg: 0 8px 32px rgba(139, 21, 56, 0.16);
        
        --gradient-primary: linear-gradient(135deg, #8B1538 0%, #6B0F2A 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --gradient-dark: linear-gradient(135deg, #2C0510 0%, #1A0208 100%);
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: #ffffff;
        color: var(--text);
        line-height: 1.6;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        position: relative;
        box-shadow: 0 0 35px rgba(139, 21, 56, 0.12);
        overflow: hidden;
    }

    /* HEADER SAMA DENGAN MENU */
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
        font-size: 16px;
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
        color: white;
        font-weight: 700;
        /* Removed gradient */
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

    /* MAIN CONTENT */
    .main-content {
        padding-top: 50px;
        padding-bottom: 90px;
        min-height: calc(100vh - 170px);
    }

    .page-header {
        padding: 30px 20px;
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

    /* STATS OVERVIEW */
    .stats-overview {
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
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stat-card:hover {
        border-color: var(--accent);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        background: var(--gradient-accent);
    }

    .stat-content {
        text-align: left;
        flex: 1;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 900;
        color: var(--text);
        line-height: 1;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-light);
        margin-top: 4px;
        font-weight: 600;
    }

    /* ACTION BAR */
    .action-bar {
        padding: 20px;
        margin-top: 16px;
    }

    .btn-add {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* CATEGORY FILTER */
    .category-filter {
        padding: 16px 20px;
        margin-bottom: 8px;
    }

    .filter-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-chips {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .filter-chips::-webkit-scrollbar {
        display: none;
    }

    .filter-chip {
        padding: 8px 16px;
        background: white;
        border: 1px solid var(--light-gray);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .filter-chip.active {
        background: var(--gradient-accent);
        color: white;
        border-color: transparent;
    }

    /* VENUE LIST */
    .venue-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 0 20px 24px;
    }

    /* VENUE CARD - SEDERHANA DAN RAPI */
    .venue-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        cursor: pointer;
        border-top: 6px solid var(--accent);
    }

    .venue-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--accent);
    }

    .venue-image-container {
        position: relative;
        width: 100%;
        height: 180px;
        overflow: hidden;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
    }

    .venue-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* VENUE BADGES */
    .venue-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: white;
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .venue-rating {
        position: absolute;
        top: 12px;
        right: 12px;
        background: white;
        padding: 6px 10px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: var(--shadow-sm);
    }

    .venue-rating i {
        color: #FFD700;
        font-size: 14px;
    }

    /* VENUE INFO */
    .venue-info {
        padding: 20px;
    }

    .venue-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .venue-name {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin: 0;
        flex: 1;
        line-height: 1.3;
    }

    .venue-status {
        background: var(--gradient-accent);
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 8px;
        white-space: nowrap;
        text-transform: uppercase;
    }

    .venue-location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-light);
        font-size: 14px;
        margin-bottom: 12px;
    }

    .venue-location i {
        color: var(--accent);
        font-size: 16px;
    }

    .venue-description {
        font-size: 14px;
        color: var(--text);
        line-height: 1.5;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* VENUE META INFO */
    .venue-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        padding: 16px 0;
        border-top: 1px solid var(--light-gray);
        margin-bottom: 16px;
    }

    .venue-meta-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 8px;
        background: rgba(139, 21, 56, 0.05);
        border-radius: 10px;
    }

    .meta-icon {
        font-size: 18px;
        color: var(--primary);
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 16px;
        font-weight: 800;
        color: var(--primary);
    }

    .meta-label {
        font-size: 11px;
        color: var(--text-light);
        margin-top: 4px;
        font-weight: 600;
    }

    /* VENUE ACTIONS */
    .venue-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .btn-venue-action {
        padding: 10px 8px;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 60px;
    }

    .btn-venue-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-venue-action i {
        font-size: 18px;
    }

    .btn-warning {
        background: #fef5e7;
        color: #d68910;
        border: 1px solid #f9e79f;
    }

    .btn-warning:hover {
        background: #fcf3cf;
    }

    .btn-danger {
        background: #fdedec;
        color: #e74c3c;
        border: 1px solid #fadbd8;
    }

    .btn-danger:hover {
        background: #fadbd8;
    }

    .btn-info {
        background: #ebf5fb;
        color: #3498db;
        border: 1px solid #d4e6f1;
    }

    .btn-info:hover {
        background: #d4e6f1;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--glass-bg);
        border-radius: 16px;
        border: 2px dashed var(--light-gray);
        margin: 20px;
    }
    
    .empty-state-icon {
        font-size: 60px;
        color: var(--accent);
        margin-bottom: 16px;
    }
    
    .empty-state-title {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text);
    }
    
    .empty-state-desc {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 24px;
        line-height: 1.5;
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
    }

    /* NO PHOTO PLACEHOLDER */
    .no-photo-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #bdc3c7;
    }

    .no-photo-placeholder i {
        font-size: 48px;
        margin-bottom: 12px;
    }

    .no-photo-placeholder span {
        font-size: 14px;
        font-weight: 600;
    }

    /* MODAL STYLES - SEDERHANA */
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
    }
    
    .modal-container {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
    }
    
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        border-radius: 20px 20px 0 0;
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
        font-size: 16px;
    }

    .modal-close:hover {
        background: var(--danger);
        color: white;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 20px;
        border-top: 1px solid var(--light-gray);
        display: flex;
        gap: 12px;
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

    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
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
        box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.15);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 48px;
    }
    
    /* DROP ZONE - DRAG & DROP UPLOAD */
    .drop-zone {
        border: 2px dashed var(--accent);
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        background: linear-gradient(135deg, rgba(199, 37, 78, 0.05) 0%, rgba(160, 27, 66, 0.08) 100%);
        transition: all 0.3s ease;
        position: relative;
    }

    .drop-zone:hover {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(199, 37, 78, 0.1) 0%, rgba(160, 27, 66, 0.15) 100%);
        transform: translateY(-2px);
    }

    .drop-zone.dragover {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(199, 37, 78, 0.15) 0%, rgba(160, 27, 66, 0.2) 100%);
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(199, 37, 78, 0.25);
    }

    .drop-zone input[type="file"] {
        display: none;
    }

    .drop-zone-icon {
        font-size: 48px;
        color: var(--accent);
        margin-bottom: 12px;
        display: block;
    }

    .drop-zone-text {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
    }

    .drop-zone-subtext {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    .drop-zone-info {
        font-size: 12px;
        color: var(--text-light);
        opacity: 0.8;
    }

    /* PHOTO COUNTER */
    .photo-counter {
        margin-top: 12px;
        padding: 10px 16px;
        background: linear-gradient(135deg, rgba(199, 37, 78, 0.1) 0%, rgba(160, 27, 66, 0.15) 100%);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .photo-counter i {
        color: var(--accent);
    }

    /* PREVIEW GRID - ADAPTIVE LAYOUT */
    .preview-grid {
        margin-top: 16px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .preview-grid.one-photo {
        grid-template-columns: 1fr;
        max-width: 200px;
    }

    .preview-grid.two-photos {
        grid-template-columns: repeat(2, 1fr);
    }

    /* PREVIEW ITEM */
    .preview-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        background: var(--light);
        aspect-ratio: 4/3;
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .preview-remove {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(231, 76, 60, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .preview-remove:hover {
        background: #c0392b;
        transform: scale(1.1);
    }

    .preview-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        color: white;
        font-size: 11px;
        font-weight: 600;
        padding: 20px 8px 8px;
        text-align: center;
    }

    /* PHOTO DELETED STATE */
    .photo-deleted {
        opacity: 0.3;
        filter: grayscale(100%);
    }

    .photo-deleted .preview-remove,
    .photo-deleted .btn-delete-photo {
        background: rgba(52, 152, 219, 0.9) !important;
    }

    /* LEGACY FILE UPLOAD STYLES (kept for backward compatibility) */
    .file-upload {
        border: 2px dashed var(--light-gray);
        border-radius: 12px;
        padding: 24px 16px;
        text-align: center;
        cursor: pointer;
        background: var(--light);
        transition: all 0.3s ease;
    }

    .file-upload:hover {
        border-color: var(--accent);
        background: rgba(46, 204, 113, 0.05);
    }

    .file-upload-icon {
        font-size: 48px;
        color: var(--accent);
        margin-bottom: 12px;
    }
    
    .file-upload input[type="file"] {
        display: none;
    }

    .file-upload-text {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
    }

    .file-upload-subtext {
        font-size: 13px;
        color: var(--text-light);
    }

    .preview-container {
        margin-top: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
    }
    
    /* TOMBOL MODAL */
    .btn-modal {
        flex: 1;
        padding: 14px 20px;
        border-radius: 12px;
        font-weight: 700;
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
        box-shadow: var(--shadow-sm);
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

    .btn-modal-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-modal-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
    }

    /* TOAST NOTIFICATION - SAMA DENGAN MENU */
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

    /* BOTTOM NAV - SAMA DENGAN MENU */
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
            overflow-y: auto;
            overflow-x: hidden;
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

    @media (max-width: 480px) {
        .venue-list {
            padding: 0 16px 20px;
        }
        
        .venue-card {
            border-radius: 16px;
        }
        
        .venue-image-container {
            height: 160px;
        }
        
        .venue-name {
            font-size: 16px;
        }
        
        .venue-meta {
            gap: 8px;
            padding: 12px 0;
        }
        
        .meta-value {
            font-size: 14px;
        }
        
        .venue-actions {
            gap: 6px;
        }
        
        .btn-venue-action {
            padding: 8px 6px;
            font-size: 11px;
            min-height: 55px;
        }
        
        .btn-venue-action i {
            font-size: 16px;
        }
        
        .modal-container {
            max-height: 85vh;
        }
    }

    @media (max-width: 359px) {
        .venue-actions {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .btn-venue-action {
            flex-direction: row;
            padding: 12px;
            min-height: auto;
            justify-content: flex-start;
        }
        
        .bottom-nav {
            padding: 10px 0;
        }
        
        .nav-item {
            padding: 4px 12px;
            min-width: 56px;
        }
        
        .nav-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
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

    /* ENHANCED DROP ZONE */
    .drop-zone {
        background: #f8f9fa; 
        border: 2px dashed #cbd5e0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .drop-zone:hover, .drop-zone.dragover {
        background: #f0faf5;
        border-color: var(--accent);
        transform: scale(1.01);
    }
    
    .drop-zone-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        z-index: 5;
        position: relative;
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        box-shadow: var(--shadow-sm);
        color: var(--primary);
        font-size: 24px;
        transition: all 0.3s ease;
    }
    
    .drop-zone:hover .icon-circle {
        transform: scale(1.1) rotate(10deg);
        color: var(--accent);
        box-shadow: var(--shadow-md);
    }
    
    .drop-zone-text {
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    
    .drop-zone-subtext {
        font-size: 13px;
        color: var(--text-light);
    }
    
    .photo-counter-badge {
        background: var(--gradient-accent);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
    }
</style>
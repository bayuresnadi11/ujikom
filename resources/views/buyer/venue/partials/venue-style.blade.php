<style>
    /* VARIABEL CSS YANG SAMA DENGAN BOOKING */
    :root {
        --primary: #0a5c36;
        --primary-dark: #08472a;
        --primary-light: #0e6b40;
        --secondary: #2ecc71;
        --accent: #27ae60;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --info: #3498db;
        --light: #f8f9fa;
        --light-gray: #e9ecef;
        --text: #1a3a27;
        --text-light: #6c757d;
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        --gradient-dark: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        --shadow-sm: 0 2px 8px rgba(10, 92, 54, 0.1);
        --shadow-md: 0 4px 16px rgba(10, 92, 54, 0.15);
        --shadow-lg: 0 8px 24px rgba(10, 92, 54, 0.2);
        --shadow-xl: 0 12px 32px rgba(10, 92, 54, 0.25);
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: #f4f7f5; /* Ubah dari gradien hijau ke abu-abu sangat muda agar bersih */
        color: var(--text);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
    }
    
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        position: relative;
        box-shadow: 0 0 35px rgba(10, 92, 54, 0.12);
        overflow-x: hidden;
        padding-bottom: 80px;
        max-width: 480px;
        margin: 0 auto;
    }
    
    /* HEADER - SESUAIKAN UKURAN UNTUK MOBILE */
    .mobile-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: var(--gradient-dark);
        z-index: 1100;
        box-shadow: var(--shadow-md);
        backdrop-filter: blur(10px);
        max-width: 500px;
        margin: 0 auto;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .header-title {
        font-size: 18px;
        font-weight: 800;
        color: white;
        margin: 0;
        flex: 1;
        text-align: center;
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

    .search-bar {
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
    }

    .search-container {
        display: flex;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        position: relative;
    }

    .search-container:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
        transform: translateY(-1px);
    }

    .search-input {
        flex: 1;
        padding: 12px;
        border: none;
        background: transparent;
        font-size: 14px;
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
        padding: 0 16px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn:hover {
        opacity: 0.9;
    }

    /* MAIN CONTENT */
    .main-content {
        padding-top: 60px;
        padding-bottom: 90px;
        min-height: 100vh;
    }

    .page-header {
        padding: 40px 16px;
        background: #ffffff; /* Ubah jadi putih polos agar lebih bersih */
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-title {
        font-size: 24px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .page-subtitle {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.4;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    /* CATEGORY FILTER */
    .category-filter {
        display: flex;
        gap: 8px;
        padding: 16px;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .category-filter::-webkit-scrollbar {
        display: none;
    }

    .filter-btn {
        background: white;
        border: 1px solid var(--light-gray);
        padding: 8px 16px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text);
        white-space: nowrap;
    }

    .filter-btn:hover {
        border-color: var(--accent);
        background: rgba(46, 204, 113, 0.05);
        transform: translateY(-2px);
    }

    .filter-btn.active {
        background: var(--gradient-accent);
        color: white;
        border-color: var(--accent);
        box-shadow: var(--shadow-sm);
    }

    .filter-btn i {
        font-size: 11px;
    }

    /* VENUE CARDS - GRID 2 KOLOM */
    .section-cards-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 16px;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        padding: 0;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid rgba(10, 92, 54, 0.08);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        height: 100%;
        min-height: 280px;
        display: flex;
        flex-direction: column;
    }

    .section-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: rgba(46, 204, 113, 0.3);
    }

    .venue-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, 
            rgba(46, 204, 113, 0.8) 0%,
            rgba(39, 174, 96, 0.9) 50%,
            rgba(10, 92, 54, 1) 100%);
        z-index: 1;
    }

    /* Venue Image */
    .venue-image {
        width: 100%;
        height: 120px;
        position: relative;
        overflow: hidden;
        border-radius: 12px 12px 0 0;
    }

    .venue-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .section-card:hover .venue-image img {
        transform: scale(1.05);
    }

    .venue-category-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(255, 255, 255, 0.95);
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 800;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: var(--shadow-sm);
        z-index: 2;
    }

    .venue-category-badge i {
        font-size: 9px;
        color: var(--accent);
    }

    .venue-rating {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(243, 156, 18, 0.95);
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: var(--shadow-sm);
        z-index: 2;
    }

    .venue-rating i {
        font-size: 9px;
    }

    /* Card Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px;
        position: relative;
    }

    .section-title-wrapper {
        flex: 1;
        overflow: hidden;
    }

    .section-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .section-venue {
        display: flex;
        align-items: center;
        gap: 4px;
        color: var(--text-light);
        font-size: 11px;
        font-weight: 500;
    }

    .section-venue i {
        color: var(--accent);
        font-size: 10px;
    }

    /* Card Content */
    .section-content {
        padding: 0 12px 8px;
        flex: 1;
    }

    .section-description {
        color: var(--text-light);
        font-size: 11px;
        line-height: 1.4;
        padding: 8px;
        background: linear-gradient(135deg, #f8fcf9 0%, #f0f9f4 100%);
        border-radius: 8px;
        border-left: 2px solid var(--accent);
        max-height: 60px;
        overflow-y: auto;
        margin-bottom: 8px;
        position: relative;
    }

    .section-description::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 60%, #f0f9f4 100%);
        pointer-events: none;
    }

    .section-description::-webkit-scrollbar {
        width: 2px;
    }

    .section-description::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .section-description::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--accent) 0%, var(--secondary) 100%);
        border-radius: 10px;
    }

    .section-badges {
        display: flex;
        gap: 6px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: rgba(46, 204, 113, 0.08);
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        color: var(--primary);
        border: 1px solid rgba(46, 204, 113, 0.2);
        transition: all 0.2s ease;
    }
    .mini-badge-ban {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: rgba(220, 53, 69, 0.08);
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        color: var(--danger);
        border: 1px solid rgba(220, 53, 69, 0.2);
        transition: all 0.2s ease;
    }

    .mini-badge:hover {
        background: rgba(46, 204, 113, 0.12);
        transform: translateY(-1px);
    }

    .mini-badge i {
        font-size: 9px;
        color: var(--accent);
    }

    /* Card Footer */
    .section-footer {
        padding: 10px 12px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .section-date {
        font-size: 10px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }

    .section-date i {
        font-size: 9px;
        color: var(--accent);
    }

    .btn-detail {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 11px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }

    .btn-detail:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* VENUE DETAIL PAGE STYLES - DISESUAIKAN UNTUK MOBILE */
    .venue-detail-image {
        width: 100%;
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .venue-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .venue-detail-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 50%, rgba(0,0,0,0.3) 100%);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 16px;
    }

    .venue-rating-large {
        background: rgba(243, 156, 18, 0.95);
        padding: 8px 12px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: var(--shadow-sm);
    }

    .venue-rating-large-show {
        background: rgba(243, 156, 18, 0.95);
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 8px 12px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: var(--shadow-sm);
    }

    .venue-info-section {
        padding: 16px;
        background: white;
    }

    .venue-detail-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
    }

    .venue-detail-location,
    .venue-detail-owner {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text-light);
        font-size: 12px;
        margin-bottom: 6px;
    }

    .venue-detail-location i,
    .venue-detail-owner i {
        color: var(--accent);
        font-size: 13px;
    }

    .venue-description-box {
        background: linear-gradient(135deg, #f8fcf9 0%, #f0f9f4 100%);
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
        border-left: 3px solid var(--accent);
    }

    .venue-description-box h3 {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .venue-description-box h3 i {
        color: var(--accent);
        font-size: 14px;
    }

    .venue-description-box p {
        color: var(--text-light);
        line-height: 1.6;
        font-size: 13px;
    }

    .venue-sections-section {
        padding: 16px;
        background: white;
    }

    .section-heading {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-heading i {
        color: var(--accent);
        font-size: 16px;
    }

    .sections-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .section-item {
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-radius: 12px;
        padding: 12px;
        border: 1px solid rgba(46, 204, 113, 0.2);
        transition: all 0.2s ease;
    }

    .section-item:hover {
        border-color: var(--accent);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .section-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .section-item-header h4 {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        margin: 0;
    }

    .section-price {
        background: var(--gradient-accent);
        color: white;
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 800;
    }

    .section-item-desc {
        color: var(--text-light);
        font-size: 11px;
        margin-bottom: 8px;
    }

    .section-item-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-schedule-count {
        display: flex;
        align-items: center;
        gap: 4px;
        color: var(--accent);
        font-size: 10px;
        font-weight: 700;
    }

    .section-schedule-count i {
        font-size: 9px;
    }

    .venue-action-buttons {
        display: flex;
        gap: 8px;
        padding: 16px;
        position: sticky;
        bottom: 70px;
        background: white;
        border-top: 1px solid var(--light-gray);
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
    }

    .btn-back {
        flex: 1;
        background: white;
        color: var(--text);
        border: 1px solid var(--light-gray);
        padding: 12px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: var(--light-gray);
        border-color: var(--accent);
        transform: translateY(-1px);
    }

    .btn-booking {
        flex: 2;
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-booking:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Empty State */
    .empty-state {
        grid-column: span 2;
        text-align: center;
        padding: 40px 16px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-radius: 12px;
        border: 1px dashed rgba(10, 92, 54, 0.2);
        margin: 0 16px;
        position: relative;
        overflow: hidden;
    }
    
    .empty-state::before {
        content: "";
        position: absolute;
        top: -40%;
        right: -40%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .empty-state-icon {
        font-size: 60px;
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }
    
    .empty-state-title {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text);
        position: relative;
        z-index: 1;
    }
    
    .empty-state-desc {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 16px;
        line-height: 1.4;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 1;
    }

    .empty-state-small {
        text-align: center;
        padding: 24px 16px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-radius: 10px;
        border: 1px dashed rgba(10, 92, 54, 0.2);
    }

    .empty-state-small i {
        font-size: 32px;
        color: var(--accent);
        margin-bottom: 8px;
        opacity: 0.5;
    }

    .empty-state-small p {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 500;
    }

    /* ALERT MESSAGES */
    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(46, 204, 113, 0.1) 0%, rgba(39, 174, 96, 0.1) 100%);
        color: var(--success);
        border: 1px solid rgba(46, 204, 113, 0.2);
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%);
        color: var(--danger);
        border: 1px solid rgba(231, 76, 60, 0.2);
    }

    /* TOAST */
    .toast {
        position: fixed;
        bottom: 90px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: var(--gradient-primary);
        color: white;
        padding: 14px 20px;
        border-radius: 10px;
        box-shadow: var(--shadow-md);
        z-index: 2200;
        opacity: 0;
        transition: all 0.3s ease;
        font-weight: 700;
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


    /* RESPONSIVE - TETAPKAN UNTUK MOBILE SAJA */
    @media (max-width: 480px) {
        .section-cards-container {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .empty-state {
            grid-column: span 2;
        }
    }

    @media (max-width: 359px) {
        .section-cards-container {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .empty-state {
            grid-column: span 1;
        }
        
        .page-title {
            font-size: 20px;
        }
        
        .section-title {
            font-size: 13px;
        }
        
        .venue-image {
            height: 100px;
        }
        
        .bottom-nav {
            padding: 6px 0;
        }
        
        .nav-item {
            padding: 3px 8px;
            min-width: 50px;
        }
        
        .nav-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }
        
        .section-header {
            padding: 10px 12px;
        }
        
        .section-content {
            padding: 0 12px 6px;
        }
        
        .section-footer {
            padding: 8px 12px;
        }
        
        .venue-detail-image {
            height: 160px;
        }
        
        .venue-detail-title {
            font-size: 18px;
        }
        
        .venue-action-buttons {
            bottom: 60px;
            padding: 12px;
        }
        
        .btn-back,
        .btn-booking {
            padding: 10px;
            font-size: 12px;
        }
    }

    /* HILANGKAN DESKTOP PREVIEW */
    @media (min-width: 600px) {
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

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 20px 20px 0 0;
        }

        .section-cards-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
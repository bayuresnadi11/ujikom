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
        --warning: #F59E0B;
        
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
        --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-lg: 0 4px 12px rgba(0,0,0,0.12);
        --shadow-xl: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* ================= MOBILE CONTAINER ================= */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        max-width: 100%;
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

    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding-top: 80px;
        padding-bottom: 70px;
        min-height: 100vh;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ================= EXPLORE HEADER ================= */
    .explore-header {
        margin-bottom: 20px;
        padding: 16px 16px 0;
        text-align: center;
    }

    .explore-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
        line-height: 1.3;
        text-align: center;
    }

    .explore-subtitle {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.4;
        font-weight: 500;
        text-align: center;
        max-width: 280px;
        margin: 0 auto;
    }

    /* ================= LOGIN REMINDER ================= */
    .login-reminder {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        border-radius: 12px;
        padding: 16px;
        margin: 20px 16px;
        border: 1px solid #ffd54f;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .login-reminder-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #ffb300 0%, #ff8f00 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .login-reminder-text h3 {
        font-size: 13px;
        font-weight: 800;
        color: #e65100;
        margin-bottom: 4px;
    }

    .login-reminder-text p {
        font-size: 11px;
        color: #8d6e63;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .login-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-login, .btn-register {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 11px;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: var(--shadow-sm);
    }

    .btn-login {
        background: var(--gradient-primary);
        color: white;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-register {
        background: white;
        color: var(--primary);
        border: 1px solid var(--accent);
    }

    .btn-register:hover {
        background: var(--light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ================= SEARCH SECTION REVISI ================= */
    .search-section {
        margin-bottom: 20px;
        padding: 0 16px;
    }

    .search-bar {
        background: white;
        border-radius: 12px;
        padding: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
    }

    .search-container {
        margin-bottom: 12px;
    }

    .search-input-wrapper {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 2px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
        position: relative;
    }

    .search-input-wrapper:focus-within {
        border-color: var(--accent);
        background: white;
    }

    .search-icon {
        color: #94a3b8;
        margin-left: 10px;
        font-size: 14px;
    }

    .search-input {
        flex: 1;
        padding: 12px 10px;
        border: none;
        background: transparent;
        font-size: 14px;
        color: var(--text);
        outline: none;
    }

    .search-input::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    .search-btn {
        background: var(--accent);
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        margin-left: 6px;
        flex-shrink: 0;
    }

    .search-btn:hover {
        background: var(--secondary);
        transform: scale(1.05);
    }

    .quick-filters {
        position: relative;
    }

    .quick-filters::before,
    .quick-filters::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 16px;
        pointer-events: none;
        z-index: 2;
    }

    .quick-filters::before {
        left: 0;
        background: linear-gradient(to right, white, transparent);
    }

    .quick-filters::after {
        right: 0;
        background: linear-gradient(to left, white, transparent);
    }

    .filters-scroll {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding: 4px 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .filters-scroll::-webkit-scrollbar {
        display: none;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        font-size: 12px;
        color: #475569;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        flex-shrink: 0;
        font-weight: 500;
    }

    .filter-chip i {
        font-size: 12px;
    }

    .filter-chip.active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
        box-shadow: 0 2px 6px rgba(46, 204, 113, 0.2);
    }

    .filter-chip:hover:not(.active) {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    .filter-chip:active {
        transform: scale(0.98);
    }

    /* ================= SPORTS CATEGORIES ================= */
    .sports-categories {
        margin-bottom: 24px;
        padding: 0 16px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .section-header-text {
        flex: 1;
    }

    .section-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 3px;
    }

    .section-icon {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, var(--secondary), var(--accent));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }

    .section-subtitle {
        font-size: 12px;
        color: var(--text-light);
        margin: 0;
        font-weight: 400;
    }

    .view-all-btn {
        background: transparent;
        border: 1px solid var(--light-gray);
        color: var(--text-light);
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 16px;
        transition: all 0.2s ease;
        white-space: nowrap;
        height: fit-content;
    }

    .view-all-btn:hover {
        background: var(--light);
        border-color: var(--accent);
        gap: 6px;
    }

    .view-all-btn i {
        font-size: 10px;
        transition: transform 0.2s ease;
    }

    .view-all-btn:hover i {
        transform: translateX(2px);
    }

    .sports-container {
        position: relative;
        margin: 0 -16px;
        padding: 0 16px;
    }

    .sports-container::before,
    .sports-container::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 16px;
        pointer-events: none;
        z-index: 2;
    }

    .sports-container::before {
        left: 0;
        background: linear-gradient(to right, white, transparent);
    }

    .sports-container::after {
        right: 0;
        background: linear-gradient(to left, white, transparent);
    }

    .sports-scroll {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 8px 0 12px 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sports-scroll::-webkit-scrollbar {
        display: none;
    }

    .sport-card {
        flex: 0 0 auto;
        width: 80px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .sport-card-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 6px;
        border-radius: 12px;
        background: var(--light);
        border: 1px solid var(--light-gray);
        transition: all 0.2s ease;
        height: 100%;
    }

    .sport-card:hover .sport-card-content {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        border-color: var(--accent);
        background: white;
    }

    .sport-card.active .sport-card-content {
        background: var(--gradient-accent);
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.2);
    }

    .sport-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .sport-card:not(.active) .sport-icon {
        color: var(--text);
        font-size: 16px;
    }

    .sport-card.active .sport-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        box-shadow: 0 2px 6px rgba(255, 255, 255, 0.1);
    }

    .sport-card:hover:not(.active) .sport-icon {
        transform: scale(1.05);
        background: white;
    }

    .sport-name {
        font-size: 11px;
        font-weight: 600;
        color: var(--text);
        text-align: center;
        line-height: 1.2;
        transition: color 0.2s ease;
    }

    .sport-card.active .sport-name {
        color: white;
        font-weight: 700;
    }

    .sport-card:hover:not(.active) .sport-name {
        color: var(--primary);
    }

    .sport-card.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background: var(--accent);
        border-radius: 50%;
    }

    /* ================= FEATURED VENUES ================= */
    .featured-venues {
        padding: 0 16px 24px;
        margin-bottom: 8px;
    }

    .map-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
        transition: all 0.2s ease;
    }

    .map-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .map-wrapper {
        position: relative;
        height: 160px;
        overflow: hidden;
    }

    .map-frame iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
        filter: saturate(0.9) brightness(0.95);
        transition: filter 0.3s ease;
    }

    .map-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, 
            rgba(0, 0, 0, 0.1) 0%,
            rgba(0, 0, 0, 0.05) 50%,
            rgba(0, 0, 0, 0) 100%
        );
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .map-card:hover .map-overlay {
        opacity: 1;
    }

    .explore-map-btn {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.8);
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 40px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        transform: translateY(8px);
        opacity: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .map-card:hover .explore-map-btn {
        transform: translateY(0);
        opacity: 1;
    }

    .explore-map-btn:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .explore-map-btn i {
        color: var(--secondary);
        font-size: 12px;
    }

    .map-footer {
        padding: 12px;
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .map-info {
        display: flex;
        gap: 12px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: var(--text-light);
    }

    .info-item i {
        color: var(--secondary);
        font-size: 10px;
        width: 14px;
        text-align: center;
    }

    .detail-link {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--primary);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 5px 10px;
        border-radius: 6px;
    }

    .detail-link:hover {
        background: var(--light);
        gap: 8px;
        color: var(--primary-dark);
    }

    .detail-link i {
        font-size: 10px;
        transition: transform 0.2s ease;
    }

    .detail-link:hover i {
        transform: translateX(3px);
    }

    /* ================= SEARCH RESULTS ================= */
    .search-results-section {
        background: #f8f9fa;
        padding: 0;
        margin: 0;
    }

    .search-results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 16px 12px;
        background: white;
        border-radius: 12px 12px 0 0;
        margin: 0 16px;
    }

    .search-results-header .section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        margin: 0;
    }

    .search-results-header .section-icon {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }

    .clear-search-btn {
        background: white;
        color: var(--danger);
        border: 1px solid var(--danger);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .clear-search-btn:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-1px);
    }

    .search-count {
        color: var(--text-light);
        font-size: 11px;
        padding: 8px 16px;
        margin: 0 16px;
        background: white;
        border-radius: 0 0 12px 12px;
        margin-bottom: 12px;
    }

    .search-results-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        padding: 0 16px 70px;
    }

    .venue-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .venue-card:active {
        transform: scale(0.98);
    }

    .venue-card-image {
        position: relative;
        width: 100%;
        height: 140px;
        overflow: hidden;
        background: var(--light);
    }

    .venue-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .venue-card-content {
        padding: 12px;
    }

    .venue-name {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 8px 0;
        line-height: 1.2;
    }

    .venue-rating {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
    }

    .venue-rating .stars {
        display: flex;
        gap: 2px;
        font-size: 11px;
    }

    .venue-rating .stars i.fas {
        color: #fbbf24;
    }

    .venue-rating .stars i.far {
        color: #d1d5db;
    }

    .venue-rating .rating-text {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-light);
    }

    .venue-location {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        color: var(--text-light);
        font-size: 11px;
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .venue-location i {
        color: var(--accent);
        margin-top: 2px;
        flex-shrink: 0;
    }

    .venue-detail-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        background: var(--gradient-accent);
        color: white;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .venue-detail-btn:active {
        transform: scale(0.98);
    }

    .loading-state {
        text-align: center;
        padding: 40px 16px;
        background: white;
        border-radius: 12px;
        margin: 0 16px;
    }

    .loading-state p {
        color: var(--text-light);
        font-size: 12px;
        margin: 0;
    }

    .spinner {
        width: 32px;
        height: 32px;
        margin: 0 auto 12px;
        border: 2px solid var(--light);
        border-top: 2px solid var(--accent);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .popular-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, #f59e0b 0%, var(--danger) 100%);
        color: white;
        padding: 5px 10px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 3px;
        box-shadow: var(--shadow-sm);
    }

    #searchResultsContent .empty-wrapper {
        background: white;
        border-radius: 12px;
        margin: 0 16px;
        padding: 32px 16px;
    }

    #searchResultsContent .placeholder-box {
        text-align: center;
    }

    #searchResultsContent .placeholder-box i {
        font-size: 36px;
        color: var(--light-gray);
        margin-bottom: 12px;
    }

    #searchResultsContent .placeholder-box h3 {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 6px 0;
    }

    #searchResultsContent .placeholder-box p {
        font-size: 12px;
        color: var(--text-light);
        margin: 0;
    }

    /* ================= RECOMMENDATIONS SLIDER ================= */
    .recommendations-slider {
        padding: 0 16px 24px;
        margin-bottom: 8px;
    }

    .venues-slider {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 12px;
        padding-bottom: 8px;
    }

    .venues-slider::-webkit-scrollbar {
        display: none;
    }

    .slide-card {
        min-width: 85%;
        scroll-snap-align: start;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .slider-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 10px;
    }

    .slider-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--light-gray);
        transition: all .3s ease;
    }

    .slider-dot.active {
        background: var(--accent);
        transform: scale(1.2);
    }

    .stars i {
        color: #fbbf24;
        margin-right: 1px;
        font-size: 12px;
    }

    .rating-text {
        font-size: 11px;
        color: var(--text-light);
    }

    .detail-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: var(--primary);
        color: #fff;
        border-radius: 20px;
        font-size: 11px;
        text-decoration: none;
    }

    .detail-btn:hover {
        opacity: .9;
    }

    .card-image-container {
        position: relative;
        height: 140px;
        overflow: hidden;
    }

    .slide-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .slide-card:hover .slide-image {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
    }

    .category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.75);
        color: #fff;
        padding: 3px 8px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
        z-index: 3;
    }

    .slide-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, var(--danger), #f97316);
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 3px;
        z-index: 2;
        letter-spacing: 0.3px;
    }

    .slide-badge i {
        font-size: 8px;
    }

    .favorite-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        cursor: pointer;
        z-index: 2;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .favorite-btn:hover {
        background: white;
        color: var(--danger);
        transform: scale(1.1);
    }

    .favorite-btn.active {
        color: var(--danger);
    }

    .slide-content {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .slide-header {
        margin-bottom: 12px;
    }

    .slide-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .slide-rating {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stars {
        display: flex;
        gap: 1px;
    }

    .stars i {
        color: #fbbf24;
        font-size: 12px;
    }

    .rating-text {
        font-size: 11px;
        color: var(--text-light);
        font-weight: 500;
    }

    .slide-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
        padding: 12px 0;
        border-top: 1px solid var(--light);
        border-bottom: 1px solid var(--light);
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: var(--text-light);
    }

    .info-item i {
        color: #94a3b8;
        font-size: 10px;
        width: 14px;
    }

    .slide-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }

    .price-container {
        display: flex;
        flex-direction: column;
    }

    .price-main {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
    }

    .price-label {
        font-size: 10px;
        color: var(--text-light);
        margin-top: 2px;
    }

    .booking-btn {
        background: linear-gradient(135deg, var(--accent), var(--secondary));
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        min-width: 100px;
        justify-content: center;
    }

    .booking-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.2);
    }

    .booking-btn:active {
        transform: translateY(0);
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

    /* ================= RESPONSIVE ADJUSTMENTS ================= */
    @media (max-width: 360px) {
        .main-content {
            padding-top: 70px;
            padding-bottom: 60px;
        }
        
        .explore-header,
        .search-section,
        .sports-categories,
        .featured-venues,
        .recommendations-slider {
            padding: 0 12px;
        }
        
        .explore-title {
            font-size: 20px;
        }
        
        .explore-subtitle {
            font-size: 12px;
        }
        
        .search-bar {
            padding: 10px;
        }
        
        .search-input {
            padding: 10px 8px;
            font-size: 13px;
        }
        
        .search-btn {
            width: 32px;
            height: 32px;
        }
        
        .filter-chip {
            padding: 6px 10px;
            font-size: 11px;
        }
        
        .section-title {
            font-size: 14px;
        }
        
        .section-icon {
            width: 24px;
            height: 24px;
            font-size: 12px;
        }
        
        .section-subtitle {
            font-size: 11px;
        }
        
        .view-all-btn {
            padding: 5px 10px;
            font-size: 10px;
        }
        
        .sport-card {
            width: 76px;
        }
        
        .sport-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
        
        .sport-name {
            font-size: 10px;
        }
        
        .map-wrapper {
            height: 140px;
        }
        
        .map-footer {
            padding: 10px;
        }
        
        .info-item {
            font-size: 10px;
        }
        
        .detail-link {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        .venue-card-image {
            height: 120px;
        }
        
        .venue-name {
            font-size: 13px;
        }
        
        .venue-rating .stars {
            font-size: 10px;
        }
        
        .venue-rating .rating-text {
            font-size: 10px;
        }
        
        .venue-location {
            font-size: 10px;
        }
        
        .venue-detail-btn {
            padding: 8px 14px;
            font-size: 11px;
        }
        
        .card-image-container {
            height: 120px;
        }
        
        .slide-title {
            font-size: 13px;
        }
        
        .price-main {
            font-size: 16px;
        }
        
        .booking-btn {
            padding: 6px 12px;
            font-size: 11px;
            min-width: 90px;
        }
        
        .login-reminder {
            margin: 20px 12px;
        }
        
        .login-reminder-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .login-reminder-text h3 {
            font-size: 12px;
        }
        
        .login-reminder-text p {
            font-size: 10px;
        }
        
        .btn-login, .btn-register {
            padding: 6px 12px;
            font-size: 10px;
        }
    }

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
        
        .main-content {
            padding-top: 90px;
            padding-bottom: 80px;
        }
        
        .explore-title {
            font-size: 24px;
        }
        
        .explore-subtitle {
            font-size: 14px;
        }
        
        .section-title {
            font-size: 16px;
        }
        
        .section-subtitle {
            font-size: 13px;
        }
        
        .search-input {
            font-size: 15px;
        }
        
        .filter-chip {
            font-size: 13px;
            padding: 9px 14px;
        }
        
        .sport-card {
            width: 86px;
        }
        
        .sport-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .sport-name {
            font-size: 12px;
        }
        
        .map-wrapper {
            height: 180px;
        }
        
        .info-item {
            font-size: 12px;
        }
        
        .detail-link {
            font-size: 13px;
        }
        
        .venue-name {
            font-size: 15px;
        }
        
        .venue-card-image {
            height: 160px;
        }
        
        .venue-rating .stars {
            font-size: 12px;
        }
        
        .venue-rating .rating-text {
            font-size: 12px;
        }
        
        .venue-location {
            font-size: 12px;
        }
        
        .venue-detail-btn {
            font-size: 13px;
        }
        
        .card-image-container {
            height: 160px;
        }
        
        .slide-title {
            font-size: 15px;
        }
        
        .price-main {
            font-size: 20px;
        }
        
        .booking-btn {
            padding: 9px 16px;
            font-size: 13px;
        }
        
        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 20px 20px 0 0;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .search-bar {
        animation: fadeIn 0.3s ease-out;
    }

    .sports-categories {
        animation: fadeInUp 0.4s ease-out;
    }

    .map-card {
        animation: fadeInUp 0.5s ease-out;
    }

    .slide-card {
        animation: slideInUp 0.5s ease-out;
    }

    .slide-card:nth-child(2) {
        animation-delay: 0.1s;
    }
</style>
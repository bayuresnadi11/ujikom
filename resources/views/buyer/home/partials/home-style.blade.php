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

    .search-bar {
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
    }

    .search-container {
        display: flex;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .search-container:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
    }

    .search-input {
        flex: 1;
        padding: 14px;
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
        font-size: 13px;
    }

    .search-btn {
        background: var(--gradient-accent);
        color: #fff;
        border: none;
        padding: 0 16px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn:hover {
        opacity: 0.9;
    }

    /* ================= MAIN ================= */
    .main-content {
        padding-top: 45px;
        padding-bottom: 80px;
    }

    .hero-section {
        padding:40px 16px;
        background: var(--gradient-light);
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--light-gray);
    }

    .hero-section::before {
        content: "";
        position: absolute;
        top: -80px;
        right: -80px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 12px;
        line-height: 1.3;
        position: relative;
        z-index: 1;
    }

    .hero-subtitle {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 20px;
        line-height: 1.4;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .cta-buttons {
        display: flex;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .btn-primary {
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        border: none;
        background: var(--gradient-primary);
        color: #fff;
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary:active {
        transform: translateY(-1px);
    }

    .section-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text);
        margin: 24px 16px 16px;
        position: relative;
        padding-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .view-all {
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.3s ease;
        background: var(--light);
        padding: 6px 12px;
        border-radius: 8px;
    }

    .view-all:hover {
        color: var(--primary-dark);
        gap: 6px;
        background: var(--light-gray);
    }

    /* ================= FEATURES ================= */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 0 12px;
        max-width: 100%;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        border: 1px solid var(--light-gray);
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
        min-height: 140px;
    }

    .feature-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .feature-icon {
        font-size: 18px;
        width: 40px;
        height: 40px;
        background: var(--gradient-accent);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin: 0 auto 8px;
        box-shadow: 0 3px 6px rgba(46, 204, 113, 0.2);
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.05);
    }

    .feature-title {
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--text);
        font-size: 14px;
        line-height: 1.2;
        text-align: center;
    }

    .feature-desc {
        font-size: 12px;
        color: var(--text-light);
        line-height: 1.3;
        margin: 0;
        text-align: center;
    }

    /* Categories Scroll */
    .categories-scroll {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding: 0 16px 16px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .categories-scroll::-webkit-scrollbar {
        display: none;
    }

    .category-card {
        flex: 0 0 130px;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid var(--light-gray);
    }

    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .category-img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-img {
        filter: brightness(1.05);
    }

    .category-name {
        padding: 8px 12px 4px;
        font-weight: 800;
        color: var(--text);
        text-align: center;
        font-size: 14px;
        background: var(--light);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .category-desc {
        padding: 0 12px 12px;
        font-size: 11px;
        color: var(--text-light);
        text-align: center;
        line-height: 1.3;
        background: var(--light);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 42px;
    }

    .full-width-card {
        flex: 1 1 100%;
        max-width: 100%;
        min-width: 100%;
    }

    /* ================= TESTIMONIALS ================= */
    .testimonials-slider {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding: 0 16px 16px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .testimonials-slider::-webkit-scrollbar {
        display: none;
    }

    .testimonial-card {
        flex: 0 0 240px;
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: var(--shadow-sm);
        border-left: 3px solid var(--secondary);
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .testimonial-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .testimonial-card::before {
        content: "\f10d";
        position: absolute;
        top: 10px;
        right: 10px;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 18px;
        color: var(--accent);
        opacity: 0.1;
    }

    .testimonial-text {
        font-style: italic;
        color: var(--text);
        margin-bottom: 12px;
        font-size: 13px;
        line-height: 1.4;
        flex-grow: 1;
    }

    .testimonial-rating {
        color: var(--gold);
        margin-bottom: 10px;
        font-size: 11px;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: auto;
    }

    .author-avatar {
        width: 32px;
        height: 32px;
        background: var(--gradient-accent);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 13px;
        flex-shrink: 0;
    }

    .author-info h4 {
        font-weight: 800;
        color: var(--primary);
        font-size: 13px;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .author-info p {
        font-size: 10px;
        color: var(--text-light);
        line-height: 1.2;
    }

    /* Quick Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 0 16px;
        margin-top: 20px;
    }

    .stat-card {
        background: var(--gradient-primary);
        color: white;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }

    .stat-card:nth-child(2) {
        background: var(--gradient-accent);
    }

    .stat-card:nth-child(3) {
        background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%);
    }

    .stat-card:nth-child(4) {
        background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        font-size: 20px;
        margin-bottom: 8px;
        opacity: 0.9;
    }

    .stat-number {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.9;
        font-weight: 600;
    }

    /* Communities Section */
    .communities-slider {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding: 0 16px 16px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .communities-slider::-webkit-scrollbar {
        display: none;
    }

    .community-card {
        flex: 0 0 280px;
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid var(--light-gray);
        cursor: pointer;
    }

    .community-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .community-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .community-logo {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .community-info {
        flex: 1;
        min-width: 0;
    }

    .community-name {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .community-category {
        font-size: 12px;
        color: var(--primary);
        font-weight: 600;
        background: rgba(10, 92, 54, 0.1);
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .community-desc {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.4;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .community-stats {
        display: flex;
        gap: 16px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-light);
        font-weight: 500;
    }

    .stat-item i {
        color: var(--primary);
        font-size: 14px;
    }

    /* Upcoming Events */
    .events-slider {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding: 0 16px 16px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .events-slider::-webkit-scrollbar {
        display: none;
    }

    .event-card {
        flex: 0 0 240px;
        background: white;
        border-radius: 10px;
        padding: 14px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
        border-left: 3px solid var(--accent);
    }

    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .event-date {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .date-icon {
        background: var(--light);
        color: var(--primary);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        flex-shrink: 0;
    }

    .date-day {
        font-size: 14px;
        line-height: 1;
    }

    .date-month {
        font-size: 10px;
        opacity: 0.8;
    }

    .event-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
    }

    .event-desc {
        color: var(--text-light);
        font-size: 11px;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .event-participants {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
    }

    .participants {
        display: flex;
        align-items: center;
    }

    .participant-avatar {
        width: 22px;
        height: 22px;
        background: var(--light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        color: var(--text);
        font-weight: 600;
        margin-left: -4px;
        border: 1px solid white;
    }

    .participant-avatar:first-child {
        margin-left: 0;
    }

    .participant-count {
        margin-left: 6px;
        color: var(--text-light);
        font-size: 10px;
    }

    .btn-join {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 11px;
        flex-shrink: 0;
    }

    .btn-join:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Venue Owner Section */
    .venue-section {
        background: var(--gradient-light);
        margin: 24px 16px;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid var(--light-gray);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .venue-section::before {
        content: "";
        position: absolute;
        top: -30px;
        right: -30px;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .venue-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .venue-desc {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 16px;
        line-height: 1.4;
        font-weight: 500;
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-venue {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        position: relative;
        z-index: 1;
    }

    .btn-venue:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
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

    .auth-switch {
        text-align: center;
        margin-top: 16px;
        color: var(--text-light);
        font-size: 13px;
        font-weight: 500;
    }

    .auth-link {
        color: var(--primary);
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .auth-link:hover {
        color: var(--secondary);
        text-decoration: underline;
    }

    .social-login {
        margin-top: 20px;
        text-align: center;
    }

    .social-title {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 12px;
        position: relative;
    }

    .social-title::before,
    .social-title::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: var(--light-gray);
    }

    .social-title::before {
        left: 0;
    }

    .social-title::after {
        right: 0;
    }

    .social-buttons {
        display: flex;
        gap: 10px;
    }

    .social-btn {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid var(--light-gray);
        background: white;
        color: var(--text);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 13px;
    }

    .social-btn:hover {
        border-color: var(--primary);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Loading Animation */
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

    .category-filter {
        display: flex;
        gap: 8px;
        padding: 0 16px 16px;
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
        border-radius: 20px;
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

    /* ================= RESPONSIVE ================= */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: 20px;
            overflow: hidden;
        }

        .features-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            padding: 0 20px;
        }

        .feature-card {
            padding: 16px;
        }

        .testimonial-card,
        .event-card {
            flex: 0 0 280px;
            padding: 20px;
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

        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .hero-title {
            font-size: 22px;
        }

        .hero-subtitle {
            font-size: 15px;
        }

        .section-title {
            font-size: 18px;
        }
    }

    @media (max-width: 350px) {
        .hero-title {
            font-size: 18px;
        }

        .hero-subtitle {
            font-size: 13px;
        }

        .section-title {
            font-size: 16px;
            margin: 20px 12px 14px;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 8px;
            padding: 0 10px;
        }

        .feature-card {
            padding: 10px;
            min-height: 120px;
        }

        .feature-title {
            font-size: 13px;
        }

        .feature-desc {
            font-size: 11px;
        }

        .testimonial-card {
            flex: 0 0 200px;
            padding: 12px;
        }

        .testimonial-text {
            font-size: 12px;
        }

        .author-avatar {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .author-info h4 {
            font-size: 12px;
        }

        .author-info p {
            font-size: 9px;
        }

        .event-card {
            flex: 0 0 200px;
            padding: 12px;
        }

        .venue-title {
            font-size: 15px;
        }

        .venue-desc {
            font-size: 12px;
        }

        .category-card {
            flex: 0 0 110px;
        }

        .logo {
            font-size: 16px;
        }

        .logo-icon {
            width: 28px;
            height: 28px;
            font-size: 14px;
        }

        .filter-btn {
            border-radius: 16px;
        }

        .filter-btn i {
            font-size: 11px;
        }

    }
</style>
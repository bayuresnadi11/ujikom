<style>
    /* CSS VARIABLES - AYO BURGUNDY untuk Landowner */
    :root {
        /* Warna Utama - AYO BURGUNDY #8B1538 */
        --primary: #8B1538;      /* AYO Burgundy - Main color */
        --primary-dark: #6B0F2A; /* Darker burgundy */
        --secondary: #A01B42;    /* Medium burgundy */
        --accent: #C7254E;       /* Light burgundy accent */
        --danger: #EF4444;       /* Red - untuk danger */
        --success: #8B1538;      /* Burgundy untuk success */
        --warning: #F59E0B;      /* Amber - untuk warning */
        
        /* Warna Netral */
        --text: #1E293B;         /* Slate 800 */
        --text-light: #64748B;   /* Slate 500 */
        --light: #F8FAFC;        /* Slate 50 background */
        --light-gray: #E2E8F0;   /* Slate 200 border */
        --gradient-dark: #6B0F2A; /* Dark burgundy untuk gradients */
        
        /* Gradients - AYO BURGUNDY */
        --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);
        
        /* Shadows */
        --shadow-sm: 0 2px 10px rgba(139, 21, 56, 0.08);
        --shadow-md: 0 5px 20px rgba(139, 21, 56, 0.12);
        --shadow-lg: 0 10px 30px rgba(139, 21, 56, 0.15);
        --shadow-xl: 0 15px 40px rgba(139, 21, 56, 0.18);
    }

    html {
        min-height: 100%;
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: white;
        background-attachment: fixed;
        background-size: cover;
        background-repeat: no-repeat;
        color: #2C1810;
        line-height: 1.6;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        min-height: 100vh;
    }
    
    /* ================= HEADER ================= */
    /* Mobile Container */
    .mobile-container {
        width: 100%;
        min-height: 100vh;
        border-radius: 0;
        margin: 0 auto;
        background: #ffffff;
        position: relative;
        box-shadow: 0 0 35px rgba(139, 0, 0, 0.08);
        overflow: hidden;
    }
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
        font-size: 24px;
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
        color: #FFD7D7;
        font-weight: 700;
        background: linear-gradient(135deg, #FFD7D7, #FFB6B6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
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
        background: var(--accent);
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
        box-shadow: 0 0 0 3px rgba(205, 92, 92, 0.2);
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
        background: linear-gradient(135deg, var(--accent) 0%, var(--secondary) 100%);
    }

    /* ================= MAIN ================= */
    .main-content {
        padding-top: 50px;
        padding-bottom: 90px;
    }

    /* Welcome Section */
    .welcome-section {
        padding: 40px 16px;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--light-gray);
    }

    .welcome-title {
        font-size: 32px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 12px;
        line-height: 1.3;
        position: relative;
        z-index: 1;
    }

    .welcome-subtitle {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 20px;
        line-height: 1.4;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .welcome-role {
        display: inline-flex;
        background: var(--gradient-primary);
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
        position: relative;
        z-index: 1;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
        border: none;
    }

    /* Stats Section */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 0 16px;
        margin-top: 16px;
        margin-bottom: 20px;
    }
    
    .stat-card {
        background: white;
        color: var(--text);
        border-radius: 12px;
        padding: 16px 12px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1.5px solid var(--light-gray);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }
    
    .stat-icon {
        font-size: 24px;
        margin-bottom: 6px;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-number {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 2px;
        color: var(--primary);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 11px;
        color: var(--text-light);
        font-weight: 600;
        line-height: 1.3;
    }


    /* Section Title */
    .section-title {
        font-size: 22px;
        font-weight: 900;
        color: var(--text);
        margin: 30px 20px 20px;
        position: relative;
        padding-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--gradient-accent);
        border-radius: 3px;
    }
    
    .view-all {
        font-size: 14px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        background: var(--light);
        padding: 8px 14px;
        border-radius: 10px;
    }
    
    .view-all:hover {
        color: var(--secondary);
        gap: 10px;
        background: var(--light-gray);
        transform: translateY(-2px);
    }

    /* ================= DAFTAR VENUE ================= */
    .venues-container {
        padding: 0 20px;
    }
    
    .venues-slider {
        display: flex;
        overflow-x: auto;
        gap: 16px;
        padding: 10px 0 24px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    
    .venues-slider::-webkit-scrollbar {
        display: none;
    }
    
    .venue-card {
        flex: 0 0 280px;
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 3px solid transparent;
        position: relative;
    }
    
    .venue-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-xl);
        border-color: var(--accent);
    }
    
    .venue-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        filter: brightness(0.95);
        transition: all 0.3s ease;
    }
    
    .venue-card:hover .venue-img {
        filter: brightness(1);
        transform: scale(1.05);
    }
    
    /* Venue Photo Carousel */
    .venue-img-carousel {
        width: 100%;
        height: 160px;
        position: relative;
        overflow: hidden;
        background: var(--gradient-primary);
    }
    
    .venue-carousel-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.5s ease;
        filter: brightness(0.95);
    }
    
    /* Placeholder for no photo or add new venue */
    .venue-placeholder {
        width: 100%;
        height: 100%;
        background: var(--gradient-primary); /* Use theme gradient instead of hardcoded red */
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px; /* Adjusted font size */
        transition: all 0.3s ease;
    }

    .venue-card:hover .venue-placeholder {
        filter: brightness(1.1);
        transform: scale(1.05); /* Match image zoom effect */
    }
    
    .venue-carousel-img.active {
        opacity: 1;
    }
    
    .venue-card:hover .venue-carousel-img {
        filter: brightness(1);
    }
    
    .venue-no-photo {
        position: relative;
        opacity: 1;
    }
    
    .carousel-indicators {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
        z-index: 10;
    }
    
    .carousel-indicators .indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .carousel-indicators .indicator.active {
        background: white;
        width: 20px;
        border-radius: 4px;
    }
    
    .carousel-counter {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .venue-info {
        padding: 20px;
        background: var(--light);
    }
    
    .venue-name {
        font-weight: 900;
        color: var(--text);
        font-size: 18px;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .venue-type {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .venue-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
    }
    
    .venue-facilities {
        font-size: 14px;
        color: var(--primary);
        font-weight: 700;
    }
    
    .venue-status {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 800;
    }
    
    .status-active {
        background: rgba(139, 0, 0, 0.1);
        color: var(--success);
    }
    
    .status-inactive {
        background: rgba(210, 105, 30, 0.15);
        color: var(--warning);
    }
    
    .status-maintenance {
        background: rgba(205, 92, 92, 0.15);
        color: var(--accent);
    }
    
    /* Empty State Styles */
    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 40px 24px;
        text-align: center;
        margin: 0 20px;
        box-shadow: var(--shadow-sm);
        border: 2px dashed var(--light-gray);
    }
    
    .empty-state i {
        color: var(--text-light);
        margin-bottom: 20px;
        opacity: 0.7;
    }
    
    .empty-state h3 {
        font-size: 22px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 12px;
    }
    
    .empty-state p {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 28px;
        font-size: 15px;
    }
    
    .empty-state-small {
        background: var(--light);
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        margin: 20px 0;
    }
    
    .empty-state-small i {
        color: var(--text-light);
        margin-bottom: 15px;
        opacity: 0.6;
    }
    
    .empty-state-small p {
        color: var(--text-light);
        font-size: 15px;
        margin: 0;
    }
    
    .btn-primary {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 16px 28px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* ================= BOOKING TERBARU ================= */
    .bookings-section {
        background: white;
        margin: 30px 20px;
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--shadow-md);
        border: 2px solid var(--light-gray);
    }
    
    .booking-item {
        display: flex;
        align-items: center;
        padding: 18px 0;
        border-bottom: 2px solid var(--light-gray);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .booking-item:last-child {
        border-bottom: none;
    }
    
    .booking-item:hover {
        background: var(--light);
        border-radius: 14px;
        padding-left: 16px;
        padding-right: 16px;
        margin: 0 -16px;
    }
    
    .booking-sport {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
        margin-right: 16px;
        flex-shrink: 0;
    }
    
    .sport-futsal {
        background: var(--gradient-primary);
    }
    
    .sport-badminton {
        background: var(--gradient-accent);
    }
    
    .sport-basket {
        background: linear-gradient(135deg, #8B0000 0%, #D2691E 100%);
    }
    
    .sport-tennis {
        background: linear-gradient(135deg, #CD5C5C 0%, #8B0000 100%);
    }
    
    .sport-voli {
        background: linear-gradient(135deg, #B22222 0%, #D2691E 100%);
    }
    
    .booking-info {
        flex: 1;
        min-width: 0;
    }
    
    .booking-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .booking-details {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 13px;
        color: var(--text-light);
    }
    
    .booking-details span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .booking-status {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 18px;
        font-size: 12px;
        font-weight: 800;
        margin-top: 8px;
    }
    
    .status-confirmed {
        background: rgba(139, 0, 0, 0.1);
        color: var(--success);
    }
    
    .status-pending {
        background: rgba(210, 105, 30, 0.15);
        color: var(--warning);
    }
    
    .status-completed {
        background: rgba(205, 92, 92, 0.15);
        color: var(--accent);
    }
    
    .status-upcoming {
        background: rgba(139, 0, 0, 0.1);
        color: var(--primary);
    }
    
    .status-cancelled {
        background: rgba(205, 92, 92, 0.15);
        color: var(--accent);
    }
    
    /* Tips Section */
    .tips-section {
        background: var(--light);
        margin: 30px 20px;
        border-radius: 20px;
        padding: 28px;
        text-align: center;
        border: 2px solid var(--light-gray);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    
    .tips-section::before {
        content: "";
        position: absolute;
        top: -30px;
        right: -30px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(165, 42, 42, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .tips-section::after {
        content: "";
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 140px;
        height: 140px;
        background: radial-gradient(circle, rgba(139, 0, 0, 0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .tips-title {
        font-size: 24px;
        font-weight: 900;
        color: var(--primary);
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }
    
    .tips-desc {
        font-size: 16px;
        color: var(--text-light);
        margin-bottom: 24px;
        line-height: 1.6;
        font-weight: 500;
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .btn-tips {
        background: var(--gradient-accent);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-lg);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 1;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .btn-tips:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-xl);
        background: linear-gradient(135deg, var(--accent) 0%, var(--secondary) 100%);
        border-color: rgba(255, 255, 255, 0.3);
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
        box-shadow: 0 -5px 25px rgba(139, 0, 0, 0.1);
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
        background: rgba(139, 0, 0, 0.05);
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
    
    /* Toast Notification */
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
        background: linear-gradient(135deg, #B22222 0%, #8B0000 100%);
    }
    
    .toast.info {
        background: linear-gradient(135deg, #CD5C5C 0%, #8B0000 100%);
    }
    
    .toast i {
        font-size: 18px;
    }

    /* ================= DESKTOP PREVIEW ================= */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 60px rgba(139, 0, 0, 0.12);
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
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 2000;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    
    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        width: 100%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
        animation: modalSlide 0.3s ease;
    }
    
    @keyframes modalSlide {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--light-gray);
    }
    
    .modal-title {
        font-size: 24px;
        font-weight: 900;
        color: var(--text);
    }
    
    .close-modal {
        background: var(--light);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        font-size: 20px;
        color: var(--text);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .close-modal:hover {
        background: var(--light-gray);
        transform: rotate(90deg);
    }
</style>
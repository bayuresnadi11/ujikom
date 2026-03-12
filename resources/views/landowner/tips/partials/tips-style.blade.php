<style>
    /* VARIABEL CSS DIPERBAIKI */
    :root {
        --primary: #8B1538;  /* Burgundy */
        --primary-dark: #6B0F2A;
        --accent: #C7254E;
        --secondary: #A01B42;
        --danger: #E74C3C;
        --warning: #F39C12;
        --light: #FFF5F7;
        --light-gray: #FFE4E8;
        --text: #2C1810;
        --text-light: #5a3a2a;
        --text-lighter: #8B1538;
        --glass-bg: rgba(255, 255, 255, 0.98);
        --glass-border: rgba(139, 21, 56, 0.08);
        --shadow-sm: 0 2px 12px rgba(139, 21, 56, 0.08);
        --shadow-md: 0 4px 20px rgba(139, 21, 56, 0.12);
        --shadow-lg: 0 8px 32px rgba(139, 21, 56, 0.15);
        --shadow-xl: 0 12px 48px rgba(139, 21, 56, 0.18);
        --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
        --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
        --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);
        --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        --card-radius: 20px;
        --btn-radius: 12px;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
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
        box-shadow: 0 0 40px rgba(139, 21, 56, 0.1);
        overflow: hidden;
    }

    /* HEADER */
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
        padding: 18px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
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
        background: rgba(255, 255, 255, 0.15);
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
    }

    .logo span {
        color: #FFE4E8;
        font-weight: 700;
        background: linear-gradient(135deg, #FFF5F7, #FFE4E8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.12);
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: white;
        padding: 8px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        width: 44px;
        height: 44px;
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
        border: 2px solid var(--primary-dark);
        animation: pulse 2s infinite;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .header-icon:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px) scale(1.05);
    }

    .search-bar {
        padding: 16px 20px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
    }

    .search-container {
        display: flex;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .search-container:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(139, 21, 56, 0.15);
        transform: translateY(-2px);
    }

    .search-input {
        flex: 1;
        padding: 16px 20px;
        border: none;
        background: transparent;
        font-size: 15px;
        outline: none;
        color: var(--text);
        font-weight: 500;
    }

    .search-input::placeholder {
        color: var(--text-light);
        opacity: 0.8;
        font-weight: 400;
    }

    .search-btn {
        background: var(--gradient-accent);
        color: #fff;
        border: none;
        padding: 0 22px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
    }

    .search-btn:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }

    /* MAIN CONTENT */
    .main-content {
        padding-top: 148px;
        padding-bottom: 90px;
    }

    .page-header {
        padding: 24px 20px;
        background: var(--gradient-light);
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(139, 21, 56, 0.05);
    }

    .page-header::before {
        content: "";
        position: absolute;
        top: -80px;
        right: -80px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(139, 21, 56, 0.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header::after {
        content: "";
        position: absolute;
        bottom: -60px;
        left: -60px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(139, 21, 56, 0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-title {
        font-size: 32px;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 15px;
        color: var(--text-light);
        line-height: 1.6;
        font-weight: 400;
        position: relative;
        z-index: 1;
        max-width: 90%;
    }

    /* CATEGORY FILTER */
    .category-filter {
        padding: 18px 0;
        margin-top: 0;
        background: var(--glass-bg);
        position: sticky;
        top: 148px;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.04);
        border-bottom: 1px solid var(--glass-border);
        backdrop-filter: blur(10px);
    }

    .filter-container {
        padding: 0 20px;
        position: relative;
    }

    .filter-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-chips {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: none;
        padding-right: 20px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .filter-chips::-webkit-scrollbar {
        display: none;
    }

    .filter-chip {
        padding: 10px 18px;
        background: white;
        border: 1.5px solid var(--light-gray);
        border-radius: var(--btn-radius);
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        white-space: nowrap;
        flex: 0 0 auto;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.3px;
    }

    .filter-chip:hover {
        border-color: var(--accent);
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .filter-chip.active {
        background: var(--gradient-accent);
        color: white;
        border-color: transparent;
        box-shadow: var(--shadow-md);
        font-weight: 700;
    }

    /* TIPS CONTAINER */
    .tips-container {
        padding: 0 20px 20px;
    }

    /* SECTION TITLE */
    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin: 28px 0 20px;
        padding-bottom: 12px;
        border-bottom: 3px solid;
        border-image: linear-gradient(90deg, var(--accent), rgba(139, 21, 56, 0.2)) 1;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
    }

    .section-title i {
        color: var(--accent);
        font-size: 20px;
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.1), rgba(160, 27, 66, 0.2));
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.15);
    }

    /* TIPS SCROLL CONTAINER */
    .tips-scroll-container {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 20px;
        margin-bottom: 10px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x proximity;
        scroll-padding: 20px;
    }

    .tips-scroll-container::-webkit-scrollbar {
        height: 6px;
    }

    .tips-scroll-container::-webkit-scrollbar-track {
        background: rgba(139, 21, 56, 0.05);
        border-radius: 10px;
    }

    .tips-scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--accent), var(--secondary));
        border-radius: 10px;
    }

    /* TIP CARD - DIPERBAIKI UNTUK TEKS TIDAK TERPOTONG */
    .tip-card {
        flex: 0 0 auto;
        width: 320px;
        min-height: 320px; /* Tinggi minimum lebih besar */
        background: var(--glass-bg);
        border-radius: var(--card-radius);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid var(--glass-border);
        position: relative;
        overflow: visible;
        backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        scroll-snap-align: start;
    }

    .tip-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.02), rgba(107, 15, 42, 0.01));
        z-index: -1;
        border-radius: var(--card-radius);
    }

    .tip-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: var(--gradient-accent);
        border-radius: 5px 0 0 5px;
    }

    .tip-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
        border-color: rgba(139, 21, 56, 0.3);
    }

    /* TIP HEADER - DIPERBAIKI */
    .tip-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
        gap: 12px;
        flex-wrap: wrap; /* Allow wrapping */
        min-height: auto;
    }

    .tip-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text);
        line-height: 1.4;
        flex: 1;
        margin: 0;
        letter-spacing: -0.2px;
        word-break: break-word; /* Break long words */
        overflow-wrap: break-word; /* Prevent overflow */
        hyphens: auto; /* Auto hyphenation */
        min-width: 0; /* Important for flexbox text truncation */
    }

    .tip-category {
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.1), rgba(160, 27, 66, 0.15));
        color: var(--primary-dark);
        font-size: 11px;
        font-weight: 900;
        padding: 6px 14px;
        border-radius: 20px;
        white-space: nowrap;
        border: 1.5px solid rgba(139, 21, 56, 0.25);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 2px 6px rgba(139, 21, 56, 0.1);
        flex-shrink: 0;
        align-self: flex-start;
        margin-left: 8px;
    }

    /* TIP CONTENT - DIPERBAIKI AGAR TEKS TIDAK TERPOTONG */
    .tip-content {
        font-size: 14px;
        color: var(--text);
        line-height: 1.7;
        margin-bottom: 20px;
        padding-left: 4px;
        font-weight: 400;
        letter-spacing: -0.1px;
        flex: 1; /* Ambil sisa ruang */
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 5; /* Maksimal 5 baris */
        -webkit-box-orient: vertical;
        max-height: calc(5 * 1.7em); /* Perhitungan tinggi maksimal */
    }

    /* TIP FOOTER - DIPERBAIKI */
    .tip-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 18px;
        border-top: 1.5px solid rgba(139, 21, 56, 0.06);
        gap: 12px;
        margin-top: auto;
        flex-wrap: wrap; /* Allow wrapping on small screens */
    }

    .tip-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: var(--text-lighter);
        background: rgba(139, 21, 56, 0.03);
        padding: 8px 14px;
        border-radius: 10px;
        font-weight: 500;
        border: 1px solid rgba(139, 21, 56, 0.05);
        flex-shrink: 0;
        white-space: nowrap;
    }

    .tip-meta i {
        color: var(--accent);
        font-size: 14px;
        opacity: 0.9;
    }

    .tip-actions {
        display: flex;
        gap: 10px;
        flex-wrap: nowrap;
    }

    /* TOMBOL - DIPERBAIKI */
    .tip-action-btn {
        background: var(--light);
        border: 1.5px solid var(--light-gray);
        color: var(--text-light);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: var(--btn-radius);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 100px;
        justify-content: center;
        letter-spacing: 0.2px;
        position: relative;
        overflow: hidden;
        z-index: 1;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .tip-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.1), rgba(160, 27, 66, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .tip-action-btn:hover {
        background: white;
        color: var(--text);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .tip-action-btn:hover::before {
        opacity: 1;
    }

    .tip-action-btn.bookmarked {
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.1), rgba(160, 27, 66, 0.15));
        border-color: var(--accent);
        color: var(--primary-dark);
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.15);
    }

    .tip-action-btn.bookmarked i {
        color: var(--accent);
        font-weight: 900;
    }

    .tip-action-btn.bookmarked:hover {
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.15), rgba(160, 27, 66, 0.2));
        transform: translateY(-3px) scale(1.02);
    }

    .tip-action-btn.share-btn:hover {
        background: linear-gradient(135deg, rgba(33, 150, 243, 0.1), rgba(21, 101, 192, 0.05));
        border-color: #2196f3;
        color: #1976d2;
    }

    .tip-action-btn.share-btn:hover i {
        color: #2196f3;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 70px 20px;
        background: var(--glass-bg);
        border-radius: var(--card-radius);
        border: 2px dashed rgba(139, 21, 56, 0.15);
        margin: 40px 0;
        backdrop-filter: blur(10px);
    }

    .empty-state-icon {
        font-size: 64px;
        color: var(--accent);
        margin-bottom: 20px;
        opacity: 0.8;
        background: linear-gradient(135deg, rgba(139, 21, 56, 0.1), rgba(160, 27, 66, 0.2));
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(139, 21, 56, 0.15);
    }

    .empty-state-title {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 12px;
        color: var(--text);
        letter-spacing: -0.3px;
    }

    .empty-state-desc {
        font-size: 15px;
        color: var(--text-light);
        margin-bottom: 28px;
        line-height: 1.6;
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
        font-weight: 400;
    }

    /* BOTTOM NAV */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 14px 0;
        box-shadow: 0 -8px 32px rgba(139, 21, 56, 0.12);
        z-index: 1000;
        border-top: 1.5px solid var(--light-gray);
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.98);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 11px;
        color: var(--text-light);
        text-decoration: none;
        padding: 8px 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 16px;
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 64px;
        font-weight: 500;
    }

    .nav-item.active {
        color: var(--primary);
        transform: translateY(-12px);
    }

    .nav-item.active .nav-icon {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-lg);
        transform: scale(1.1);
    }

    .nav-item.active::after {
        content: "";
        position: absolute;
        top: -6px;
        width: 32px;
        height: 4px;
        background: var(--gradient-accent);
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(139, 21, 56, 0.3);
    }

    .nav-item:hover {
        color: var(--primary);
        background: rgba(139, 21, 56, 0.04);
    }

    .nav-icon {
        font-size: 22px;
        margin-bottom: 6px;
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #FFF5F7, #FFE4E8);
        color: var(--text-light);
        box-shadow: 0 4px 12px rgba(139, 21, 56, 0.08);
        border: 1px solid rgba(139, 21, 56, 0.05);
    }

    /* TOAST NOTIFICATION */
    .toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%) translateY(120px);
        background: var(--gradient-primary);
        color: white;
        padding: 18px 24px;
        border-radius: 16px;
        box-shadow: var(--shadow-xl);
        z-index: 2200;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 14px;
        max-width: 340px;
        width: 90%;
        font-size: 15px;
        pointer-events: none;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    .toast.success {
        background: linear-gradient(135deg, var(--accent) 0%, var(--secondary) 100%);
    }

    .toast.error {
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
    }

    .toast.info {
        background: linear-gradient(135deg, #3498DB 0%, #2980B9 100%);
    }

    .toast.warning {
        background: linear-gradient(135deg, #F39C12 0%, #D68910 100%);
    }

    .toast i {
        font-size: 22px;
        flex-shrink: 0;
    }

    .toast span {
        flex: 1;
        text-align: center;
        letter-spacing: 0.2px;
    }

    /* RESPONSIVE */
    @media (min-width: 600px) {
        .mobile-container {
            max-width: 480px;
            margin: 20px auto;
            box-shadow: 0 0 60px rgba(10, 92, 54, 0.2);
            border-radius: 24px;
            overflow: hidden;
        }

        .mobile-header {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 24px 24px 0 0;
        }

        .category-filter {
            top: 148px;
            border-radius: 0;
        }

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 24px 24px 0 0;
        }

        .toast {
            max-width: 420px;
        }
    }

    @media (max-width: 480px) {
        .tip-card {
            width: 280px;
            min-height: 300px;
            padding: 20px;
        }
        
        .tip-content {
            -webkit-line-clamp: 6; /* Lebih banyak baris untuk mobile */
        }
        
        .tip-action-btn {
            min-width: 85px;
            padding: 9px 16px;
            font-size: 12px;
        }
        
        .tip-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        
        .tip-meta {
            justify-content: center;
        }
        
        .tip-actions {
            justify-content: center;
            width: 100%;
        }
        
        .tip-action-btn {
            flex: 1;
            min-width: 0;
        }
    }

    @media (max-width: 400px) {
        .tip-card {
            width: 260px;
            min-height: 290px;
        }
        
        .tip-title {
            font-size: 16px;
            line-height: 1.3;
        }
        
        .tip-content {
            font-size: 13.5px;
            line-height: 1.6;
            -webkit-line-clamp: 7;
        }
        
        .tip-category {
            font-size: 10px;
            padding: 5px 12px;
        }
        
        .tips-scroll-container {
            gap: 12px;
        }
    }

    @media (max-width: 320px) {
        .tip-card {
            width: 240px;
            min-height: 280px;
            padding: 16px;
        }
        
        .tip-content {
            -webkit-line-clamp: 8;
            font-size: 13px;
        }
        
        .tip-action-btn {
            min-width: 70px;
            padding: 8px 12px;
            font-size: 11px;
        }
        
        .tip-meta {
            font-size: 11px;
            padding: 6px 12px;
        }
        
        .tip-title {
            font-size: 15px;
        }
    }
</style>
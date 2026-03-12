<style>
        /* CSS dari file menu asli Anda */

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            background: linear-gradient(135deg, #FFFFFF 0%, #E6F7ED 100%); /* Burgundy Light Gradient */
            color: #2C1810;
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .mobile-container {
            width: 100%;
            min-height: 100vh;
            border-radius: 0;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            box-shadow: 0 0 35px rgba(139, 21, 56, 0.12); /* Burgundy Shadow */
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
            color: #FFE4E1; /* Misty Rose */
            font-weight: 700;
            background: none;
            -webkit-background-clip: unset;
            -webkit-text-fill-color: #FFE4E1;
            text-shadow: 0 0 10px rgba(255, 228, 225, 0.4);
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
            box-shadow: 0 0 0 3px rgba(199, 37, 78, 0.2); /* Burgundy Accent Shadow */
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

        .main-content {
            padding-top: 50px;
            padding-bottom: 90px;
        }

        .page-header {
            padding: 40px;
            background: var(--gradient-light);
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--light-gray);
        }

        .page-header::before {
            content: "";
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(139, 21, 56, 0.15) 0%, transparent 70%); /* Burgundy Radial */
            border-radius: 50%;
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

        .stats-grid {
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
        }

        .stat-card:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 900;
            color: var(--text);
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-light);
            font-weight: 600;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 0 20px;
            margin-top: 20px;
        }

        .menu-card {
            background: white;
            border-radius: 18px;
            padding: 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            height: 100%;
            border-top: 6px solid var(--accent);
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--accent);
        }

        .menu-card.featured {
            grid-column: span 2;
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 25px;
            border-top: 6px solid var(--secondary);
            text-align: left;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .menu-card.featured .menu-icon {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 0;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .menu-card.featured .menu-content {
            flex: 1;
            text-align: left;
        }

        .menu-card.featured .menu-title {
            color: white;
            font-size: 22px;
        }

        .menu-card.featured .menu-desc {
            color: rgba(255, 255, 255, 0.9);
        }

        .menu-card.featured:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        }

        .menu-icon {
            font-size: 28px;
            width: 70px;
            height: 70px;
            background: var(--gradient-accent);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 15px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .icon-primary { background: var(--gradient-primary); }
        .icon-success { background: linear-gradient(135deg, var(--success) 0%, #A01B42 100%); } /* Burgundy Success */
        .icon-warning { background: linear-gradient(135deg, var(--warning) 0%, #F1C40F 100%); }
        .icon-info { background: linear-gradient(135deg, var(--info) 0%, #5DADE2 100%); }
        .icon-purple { background: linear-gradient(135deg, #8E44AD 0%, #9B59B6 100%); }
        .icon-orange { background: linear-gradient(135deg, #E67E22 0%, #F39C12 100%); }

        .menu-card:hover .menu-icon {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(139, 21, 56, 0.4); /* Burgundy Shadow */
        }

        .menu-content {
            width: 100%;
        }

        .menu-title {
            font-weight: 900;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 16px;
            line-height: 1.3;
        }

        .menu-desc {
            font-size: 12px;
            color: var(--text-light);
            line-height: 1.4;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .menu-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 800;
            background: var(--light);
            color: var(--text-light);
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .menu-card.featured .menu-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .menu-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
            width: 100%;
        }

        .btn-menu {
            flex: 1;
            padding: 10px 12px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--gradient-accent);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary {
            background: var(--light);
            color: var(--text);
            border: 2px solid var(--light-gray);
        }

        .btn-secondary:hover {
            background: var(--light-gray);
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
            box-shadow: 0 -5px 25px rgba(139, 21, 56, 0.15); /* Burgundy Shadow */
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
            background: rgba(139, 21, 56, 0.05); /* Burgundy Hover */
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

        @media (min-width: 600px) {
            .mobile-container {
                max-width: 480px;
                margin: 20px auto;
                box-shadow: 0 0 60px rgba(139, 21, 56, 0.25); /* Burgundy Shadow */
                border-radius: 20px;
                overflow: hidden;
            }


            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
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
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .menu-card.featured {
                grid-column: span 1;
            }
        }

        @media (max-width: 360px) {
            .menu-actions {
                flex-direction: column;
            }

            .btn-menu {
                width: 100%;
            }
        }
    </style>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
            min-height: 100vh;
        }
        
        .dashboard-page {
            padding: 30px;
        }

        
        .page-header {
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }
        
        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a5c37;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .page-title i {
            color: #22c55e;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 16px;
            font-weight: 400;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
            border: 1px solid #e5f5ec;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.6s ease;
        }
        
        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }
        
        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(34, 197, 94, 0.15);
            border-color: #22c55e;
        }
        
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 26px;
            box-shadow: 0 8px 16px rgba(34, 197, 94, 0.25);
        }
        
        .stat-badge {
            background: #f0fdf4;
            color: #16a34a;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .stat-title {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-number {
            font-size: 42px;
            font-weight: 800;
            color: #1a5c37;
            margin-bottom: 12px;
            line-height: 1;
        }
        
        .stat-description {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .stat-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .stat-button:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }
        
        .stat-button i {
            font-size: 16px;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, #1a5c37 0%, #22c55e 100%);
            border-radius: 20px;
            padding: 40px;
            color: white;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }
        
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        .welcome-content {
            position: relative;
            z-index: 1;
        }
        
        .welcome-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        
        .welcome-text {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .detail-stats {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .detail-item {
            background: #f9fafb;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-item i {
            color: #22c55e;
            font-size: 12px;
        }

        .detail-item strong {
            color: #1f2937;
            font-weight: 600;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .welcome-banner {
                padding: 28px;
            }
        }
    </style>
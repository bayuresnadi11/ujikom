<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Kedaluwarsa</title>
    <style>
        :root {
            --primary: #0A5C36;
            --primary-dark: #08482b;
            --warning: #F39C12;
            --light: #f7fdf9;
            --light-gray: #e8f5ef;
            --text: #1a3a27;
            --text-light: #5a7a6a;
            --gradient-warning: linear-gradient(135deg, #F39C12 0%, #E67E22 100%);
            --shadow-lg: 0 12px 40px rgba(10, 92, 54, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--light);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            max-width: 450px;
            width: 100%;
            animation: fadeIn 0.8s ease;
        }

        .error-card {
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: var(--shadow-lg);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--light-gray);
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient-warning);
        }

        .clock-container {
            width: 140px;
            height: 140px;
            margin: 0 auto 32px;
            position: relative;
        }

        .clock-face {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #FEF9E7 0%, #FCF3CF 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: clockPulse 4s ease-in-out infinite;
        }

        .clock-hands {
            position: relative;
            width: 80px;
            height: 80px;
        }

        .hour-hand, .minute-hand {
            position: absolute;
            background: var(--warning);
            border-radius: 4px;
            transform-origin: bottom center;
        }

        .hour-hand {
            width: 6px;
            height: 30px;
            top: 10px;
            left: 37px;
            animation: hourRotate 8s linear infinite;
        }

        .minute-hand {
            width: 4px;
            height: 40px;
            top: 0;
            left: 38px;
            animation: minuteRotate 4s linear infinite;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--primary-dark);
        }

        .error-code {
            display: inline-block;
            background: var(--light-gray);
            color: var(--text-light);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .error-message {
            color: var(--text-light);
            font-size: 16px;
            margin-bottom: 36px;
            line-height: 1.6;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 36px;
            background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(10, 92, 54, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(10, 92, 54, 0.3);
        }

        @keyframes clockPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes hourRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes minuteRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="clock-container">
                <div class="clock-face">
                    <div class="clock-hands">
                        <div class="hour-hand"></div>
                        <div class="minute-hand"></div>
                    </div>
                </div>
            </div>
            
            <h1>Sesi Kedaluwarsa</h1>
            <div class="error-code">419 - Page Expired</div>
            
            <p class="error-message">
                Sesi Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu yang lama. 
                Silakan refresh halaman dan coba lagi.
            </p>
            
            <a href="javascript:location.reload()" class="btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M23 4v6h-6"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Refresh Halaman
            </a>
        </div>
    </div>
</body>
</html>
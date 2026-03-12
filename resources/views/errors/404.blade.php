<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        :root {
            --primary: #0A5C36;
            --primary-dark: #08482b;
            --accent: #2ECC71;
            --light: #f7fdf9;
            --light-gray: #e8f5ef;
            --text: #1a3a27;
            --text-light: #5a7a6a;
            --gradient-primary: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
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
            max-width: 500px;
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
            background: var(--gradient-primary);
        }

        .number-display {
            font-size: 120px;
            font-weight: 900;
            line-height: 1;
            margin: 20px 0;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: numberGlow 3s ease-in-out infinite;
        }

        @keyframes numberGlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .search-icon {
            width: 100px;
            height: 100px;
            margin: 20px auto;
            color: var(--primary);
            animation: searchRotate 4s ease-in-out infinite;
        }

        @keyframes searchRotate {
            0%, 100% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(15deg) scale(1.1); }
            75% { transform: rotate(-15deg) scale(1.1); }
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--primary-dark);
        }

        .error-message {
            color: var(--text-light);
            font-size: 16px;
            margin-bottom: 36px;
            line-height: 1.6;
        }

        .btn-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 6px 20px rgba(10, 92, 54, 0.2);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--text);
            border: 1px solid rgba(10, 92, 54, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary:hover {
            box-shadow: 0 10px 25px rgba(10, 92, 54, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="number-display">404</div>
            
            <div class="search-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </div>
            
            <h1>Halaman Tidak Ditemukan</h1>
            <p class="error-message">
                Halaman yang Anda cari tidak dapat ditemukan. 
                Mungkin telah dipindahkan, dihapus, atau Anda memasukkan URL yang salah.
            </p>
            
            <div class="btn-group">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Gagal - SewaLap</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ================= ROOT VARIABLES ================= */
        :root {
            --primary: #8B1538;  /* Burgundy */
            --primary-dark: #6B0F2A;
            --primary-light: #A01B42;
            --secondary: #A01B42;
            --accent: #C7254E;
            --light: #FFF5F7;
            --light-gray: #FFE4E8;
            --text: #2C1810;
            --text-light: #5a3a2a;
            --success: #8B1538; /* Keeping consistent theme */
            --warning: #F39C12;
            --danger: #E74C3C;
            --danger-light: #fde8e8;
            --danger-dark: #c0392b;
            --gold: #FFD700;
            --info: #3498DB;

            --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
            --gradient-danger: linear-gradient(135deg, #E74C3C 0%, #c0392b 100%);
            --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
            --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
            --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);

            --shadow-sm: 0 2px 12px rgba(139, 21, 56, 0.08);
            --shadow-md: 0 4px 20px rgba(139, 21, 56, 0.12);
            --shadow-lg: 0 8px 30px rgba(139, 21, 56, 0.15);
            --shadow-xl: 0 12px 40px rgba(139, 21, 56, 0.18);
            --shadow-danger: 0 4px 20px rgba(231, 76, 60, 0.15);
        }

        /* ================= BASE STYLES ================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: var(--light);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* ================= BACKGROUND ANIMATION ================= */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            opacity: 0.7;
        }

        .bg-shape {
            position: absolute;
            background: radial-gradient(circle, rgba(231, 76, 60, 0.05) 0%, rgba(231, 76, 60, 0) 70%);
            animation: floatError 15s infinite ease-in-out;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }

        .bg-shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .bg-shape:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -200px;
            right: -200px;
            animation-delay: -5s;
            border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
        }

        @keyframes floatError {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            33% {
                transform: translateY(-20px) rotate(180deg) scale(1.1);
            }
            66% {
                transform: translateY(10px) rotate(360deg) scale(0.9);
            }
        }

        /* ================= MAIN CONTAINER ================= */
        .container {
            max-width: 480px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            position: relative;
            animation: slideUpError 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(231, 76, 60, 0.1);
        }

        @keyframes slideUpError {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ================= HEADER ================= */
        .header {
            background: var(--gradient-danger);
            padding: 30px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #ff9f43;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .logo-icon {
            font-size: 32px;
            color: rgba(255, 255, 255, 0.9);
            animation: shake 2s infinite;
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-2px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(2px);
            }
        }

        .header h1 {
            font-size: 24px;
            color: white;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-weight: 500;
        }

        /* ================= ERROR CARD ================= */
        .error-card {
            padding: 40px 24px;
            text-align: center;
        }

        /* Error Icon */
        .error-icon {
            width: 120px;
            height: 120px;
            background: var(--gradient-danger);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            position: relative;
            animation: scaleInError 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: var(--shadow-danger);
        }

        @keyframes scaleInError {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            70% {
                transform: scale(1.1) rotate(10deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .error-icon::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid rgba(255, 159, 67, 0.5);
            border-radius: 50%;
            animation: pulseError 2s infinite;
        }

        @keyframes pulseError {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .error-icon i {
            font-size: 60px;
            color: white;
            animation: crossmark 0.5s 0.3s both;
        }

        @keyframes crossmark {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        /* Error Message */
        .error-message {
            margin-bottom: 30px;
        }

        .error-message h2 {
            font-size: 28px;
            color: var(--danger);
            margin-bottom: 12px;
            font-weight: 800;
        }

        .error-message p {
            font-size: 16px;
            color: var(--text-light);
            line-height: 1.6;
            max-width: 320px;
            margin: 0 auto;
        }

        /* ================= FOOTER ================= */
        .footer {
            padding: 24px;
            text-align: center;
            background: var(--light);
            border-top: 1px solid var(--light-gray);
            animation: fadeIn 1s 1s both;
        }

        .footer p {
            color: var(--text-light);
            font-size: 14px;
        }

        /* ================= MOBILE OPTIMIZATIONS ================= */
        @media (max-width: 480px) {
            body {
                padding: 16px;
            }
            
            .container {
                border-radius: 20px;
            }
            
            .header {
                padding: 24px 20px;
            }
            
            .logo {
                font-size: 24px;
            }
            
            .logo-icon {
                font-size: 28px;
            }
            
            .header h1 {
                font-size: 22px;
            }
            
            .error-icon {
                width: 100px;
                height: 100px;
            }
            
            .error-icon i {
                font-size: 50px;
            }
            
            .error-message h2 {
                font-size: 24px;
            }
            
            .error-message p {
                font-size: 15px;
                padding: 0 10px;
            }
        }

        @media (max-width: 360px) {
            .header {
                padding: 20px 16px;
            }
            
            .error-card {
                padding: 30px 16px;
            }
            
            .error-icon {
                width: 90px;
                height: 90px;
            }
            
            .error-icon i {
                font-size: 45px;
            }
        }

        /* ================= DARK MODE SUPPORT ================= */
        @media (prefers-color-scheme: dark) {
            body {
                background: #0c1a12;
            }
            
            .container {
                background: #0f2318;
                border-color: rgba(231, 76, 60, 0.2);
            }
            
            .error-message p {
                color: #a8c2b3;
            }
            
            .footer {
                background: #0a1912;
                border-top-color: #133321;
            }
            
            .footer p {
                color: #8ab09a;
            }
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation">
        <div class="bg-shape"></div>
        <div class="bg-shape"></div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-futbol logo-icon"></i>
                Sewa<span>Lap</span>
            </div>
            <h1>Verifikasi Gagal</h1>
            <p>Maaf, terjadi masalah dengan verifikasi</p>
        </div>

        <!-- Error Card -->
        <div class="error-card">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="fas fa-times"></i>
            </div>

            <!-- Error Message -->
            <div class="error-message">
                <h2>Verifikasi Gagal</h2>
                <p>Link verifikasi nomor WhatsApp sudah tidak berlaku atau telah kedaluwarsa.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2024 SewaLap. Hak Cipta Dilindungi.</p>
        </div>
    </div>

    <script>
        // Error icon interaction
        const errorIcon = document.querySelector('.error-icon');
        
        errorIcon.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'scaleInError 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            }, 10);
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add subtle vibration for error state
            if (navigator.vibrate) {
                navigator.vibrate([200, 100, 200]);
            }
        });
    </script>
</body>
</html>
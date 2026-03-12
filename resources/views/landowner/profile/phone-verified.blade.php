<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Berhasil - SewaLap</title>
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
            --gold: #FFD700;
            --info: #3498DB;

            --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
            --gradient-accent: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
            --gradient-light: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
            --gradient-dark: linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%);

            --shadow-sm: 0 2px 12px rgba(139, 21, 56, 0.08);
            --shadow-md: 0 4px 20px rgba(139, 21, 56, 0.12);
            --shadow-lg: 0 8px 30px rgba(139, 21, 56, 0.15);
            --shadow-xl: 0 12px 40px rgba(139, 21, 56, 0.18);
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
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 21, 56, 0.05) 0%, rgba(139, 21, 56, 0) 70%);
            animation: float 20s infinite ease-in-out;
        }

        .bg-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .bg-circle:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -200px;
            right: -200px;
            animation-delay: -5s;
        }

        .bg-circle:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 10%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            33% {
                transform: translateY(-30px) rotate(120deg);
            }
            66% {
                transform: translateY(20px) rotate(240deg);
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
            animation: slideUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(139, 21, 56, 0.1);
        }

        @keyframes slideUp {
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
            background: var(--gradient-dark);
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
            background: var(--accent);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .logo-icon {
            font-size: 32px;
            color: var(--accent);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
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

        /* ================= VERIFICATION CARD ================= */
        .verification-card {
            padding: 40px 24px;
            text-align: center;
        }

        /* Success Icon */
        .success-icon {
            width: 120px;
            height: 120px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            position: relative;
            animation: scaleIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 10px 30px rgba(139, 21, 56, 0.2);
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            70% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .success-icon i {
            font-size: 60px;
            color: white;
            animation: checkmark 0.5s 0.3s both;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Message */
        .message {
            margin-bottom: 40px;
        }

        .message h2 {
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 12px;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .message p {
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            
            .success-icon {
                width: 100px;
                height: 100px;
            }
            
            .success-icon i {
                font-size: 50px;
            }
            
            .message h2 {
                font-size: 24px;
            }
            
            .message p {
                font-size: 15px;
                padding: 0 10px;
            }
        }

        @media (max-width: 360px) {
            .header {
                padding: 20px 16px;
            }
            
            .verification-card {
                padding: 30px 16px;
            }
            
            .success-icon {
                width: 90px;
                height: 90px;
            }
            
            .success-icon i {
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
                border-color: rgba(46, 204, 113, 0.1);
            }
            
            .message p {
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
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-futbol logo-icon"></i>
                Sewa<span>Lap</span>
            </div>
            <h1>Verifikasi Berhasil</h1>
            <p>Nomor telepon Anda telah diverifikasi</p>
        </div>

        <!-- Verification Card -->
        <div class="verification-card">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <!-- Message -->
            <div class="message">
                <h2>Verifikasi Selesai!</h2>
                <p>Nomor WhatsApp Anda telah berhasil diverifikasi dan siap digunakan di platform SewaLap.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2024 SewaLap. Hak Cipta Dilindungi.</p>
        </div>
    </div>

    <script>
        // Simple confetti effect
        function createConfetti() {
            const colors = ['#8B1538', '#A01B42', '#C7254E', '#FFD700'];
            for (let i = 0; i < 30; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '8px';
                confetti.style.height = '8px';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.zIndex = '9999';
                confetti.style.opacity = '0';
                
                document.body.appendChild(confetti);
                
                // Animation
                confetti.animate([
                    { 
                        opacity: 0,
                        transform: 'translateY(0) rotate(0deg)' 
                    },
                    { 
                        opacity: 1,
                        transform: `translateY(${Math.random() * 100 + 50}vh) rotate(${Math.random() * 720}deg)` 
                    }
                ], {
                    duration: Math.random() * 2000 + 1000,
                    easing: 'cubic-bezier(0.215, 0.610, 0.355, 1)'
                });
                
                // Remove after animation
                setTimeout(() => confetti.remove(), 3000);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Create confetti on load
            setTimeout(createConfetti, 300);
            
            // Add subtle vibration if supported
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
        });
    </script>
</body>
</html>
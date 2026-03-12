<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <style>
        :root {
            --primary: #0A5C36;
            --primary-dark: #08482b;
            --primary-light: #2E8B57;
            --secondary: #27AE60;
            --accent: #2ECC71;
            --light: #f7fdf9;
            --light-gray: #e8f5ef;
            --text: #1a3a27;
            --text-light: #5a7a6a;
            --success: #27AE60;
            --warning: #F39C12;
            --danger: #E74C3C;
            --gold: #FFD700;
            --gradient-primary: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
            --gradient-accent: linear-gradient(135deg, #2ECC71 0%, #27AE60 100%);
            --gradient-light: linear-gradient(135deg, #f7fdf9 0%, #e8f5ef 100%);
            --gradient-dark: linear-gradient(135deg, #08482b 0%, #0A5C36 100%);
            --shadow-sm: 0 2px 12px rgba(10, 92, 54, 0.08);
            --shadow-md: 0 4px 20px rgba(10, 92, 54, 0.12);
            --shadow-lg: 0 8px 30px rgba(10, 92, 54, 0.15);
            --shadow-xl: 0 12px 40px rgba(10, 92, 54, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: var(--light);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .error-container {
            max-width: 420px;
            width: 100%;
            animation: slideIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(60px) scale(0.95);
            }
            70% {
                transform: translateY(-10px) scale(1.02);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .error-card {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: var(--shadow-xl);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--light-gray);
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient-primary);
            animation: progressBar 2.5s ease-in-out infinite;
        }

        @keyframes progressBar {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .icon-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 40px;
            perspective: 1000px;
        }

        .icon-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--light-gray);
            animation: circleSpin 15s linear infinite;
        }

        @keyframes circleSpin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .icon-circle:nth-child(2) {
            background: var(--gradient-primary);
            opacity: 0.1;
            animation: circleSpinReverse 20s linear infinite;
        }

        @keyframes circleSpinReverse {
            from {
                transform: rotate(360deg);
            }
            to {
                transform: rotate(0deg);
            }
        }

        .lock-icon {
            position: relative;
            z-index: 3;
            width: 60px;
            height: 60px;
            margin: 30px auto;
            animation: lockShake 4s ease-in-out infinite;
            filter: drop-shadow(0 4px 8px rgba(10, 92, 54, 0.2));
        }

        @keyframes lockShake {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateY(-8px) rotate(0deg);
            }
            20%, 40%, 60%, 80% {
                transform: translateY(-8px) rotate(5deg);
            }
        }

        h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textGlow 3s ease-in-out infinite;
        }

        @keyframes textGlow {
            0%, 100% {
                background-size: 200% 200%;
                background-position: left center;
            }
            50% {
                background-size: 200% 200%;
                background-position: right center;
            }
        }

        .error-code {
            display: inline-block;
            background: var(--light-gray);
            color: var(--text-light);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
            border: 1px solid rgba(10, 92, 54, 0.1);
            animation: codePulse 2s ease-in-out infinite;
        }

        @keyframes codePulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 2px 8px rgba(10, 92, 54, 0.1);
            }
            50% {
                transform: scale(1.03);
                box-shadow: 0 4px 16px rgba(10, 92, 54, 0.15);
            }
        }

        .error-message {
            color: var(--text-light);
            font-size: 17px;
            margin-bottom: 40px;
            padding: 0 10px;
            animation: fadeInUp 0.8s 0.3s both;
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

        .btn-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 18px 40px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            animation: btnFloat 3s ease-in-out infinite;
        }

        @keyframes btnFloat {
            0%, 100% {
                transform: translateY(0);
                box-shadow: var(--shadow-lg);
            }
            50% {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(10, 92, 54, 0.25);
            }
        }

        .btn-logout:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 25px 50px rgba(10, 92, 54, 0.3);
            background: var(--gradient-dark);
            animation-play-state: paused;
        }

        .btn-logout:active {
            transform: translateY(-2px) scale(1.02);
            transition: all 0.1s ease;
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-logout:hover::before {
            left: 100%;
        }

        .btn-icon {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-logout:hover .btn-icon {
            transform: translateX(8px) rotate(15deg);
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.3;
            animation: particleFloat 8s infinite ease-in-out;
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10%, 90% {
                opacity: 0.3;
            }
            50% {
                transform: translateY(-20px) translateX(10px);
                opacity: 0.1;
            }
        }

        .footer {
            margin-top: 45px;
            padding-top: 25px;
            border-top: 1px solid var(--light-gray);
            font-size: 13px;
            color: var(--text-light);
            animation: fadeIn 1s 0.6s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsif untuk mobile */
        @media (max-width: 480px) {
            .error-card {
                padding: 40px 30px;
                border-radius: 20px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            .icon-container {
                width: 100px;
                height: 100px;
                margin-bottom: 35px;
            }
            
            .lock-icon {
                width: 50px;
                height: 50px;
                margin: 25px auto;
            }
            
            .btn-logout {
                padding: 16px 35px;
                width: 100%;
                max-width: 300px;
            }
        }

        @media (max-width: 360px) {
            .error-card {
                padding: 35px 25px;
            }
            
            h1 {
                font-size: 26px;
            }
            
            .error-message {
                font-size: 16px;
            }
            
            .btn-logout {
                padding: 15px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <!-- Particles Background -->
            <div class="particles" id="particles"></div>
            
            <div class="icon-container">
                <div class="icon-circle"></div>
                <div class="icon-circle"></div>
                <svg class="lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    <circle cx="12" cy="16" r="1" fill="currentColor"></circle>
                    <path d="M12 16v2"></path>
                </svg>
            </div>
            
            <h1>Akses Ditolak</h1>
            
            <div class="error-code">
                Error 403 • Forbidden
            </div>
            
            <p class="error-message">
                Anda tidak memiliki izin untuk mengakses halaman ini. Silakan logout dan login kembali dengan akun yang sesuai.
            </p>
            
            <a href="{{ route('logout') }}" class="btn-logout" id="logoutBtn">
                <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout & Login Ulang</span>
            </a>
            
            <div class="footer">
                <p>© {{ app_setting('app_name', 'My App') }} • Sistem Keamanan</p>
            </div>
        </div>
    </div>

    <script>
        // Generate particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random position
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                
                // Random delay for animation
                const delay = Math.random() * 5;
                const duration = 6 + Math.random() * 4;
                
                particle.style.left = `${left}%`;
                particle.style.top = `${top}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                // Random size
                const size = 2 + Math.random() * 4;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random color variation
                const colors = ['#2ECC71', '#27AE60', '#0A5C36', '#2E8B57'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                particle.style.backgroundColor = color;
                particle.style.opacity = 0.2 + Math.random() * 0.3;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Enhanced button click effect
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const btn = this;
            
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = btn.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: clickRipple 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
                z-index: 2;
            `;
            
            btn.appendChild(ripple);
            
            // Button press animation
            btn.style.transform = 'translateY(0) scale(0.95)';
            btn.style.transition = 'transform 0.2s ease';
            
            // Create sparkle effect
            createSparkles(e.clientX, e.clientY);
            
            // Navigate after animation
            setTimeout(() => {
                window.location.href = btn.getAttribute('href');
            }, 500);
            
            // Cleanup
            setTimeout(() => {
                ripple.remove();
                btn.style.transform = '';
            }, 800);
        });
        
        // Sparkle effect on click
        function createSparkles(x, y) {
            for (let i = 0; i < 8; i++) {
                const sparkle = document.createElement('div');
                sparkle.style.cssText = `
                    position: fixed;
                    width: 6px;
                    height: 6px;
                    background: white;
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    transform: translate(${x}px, ${y}px) scale(0);
                    animation: sparkleFly 0.6s ease-out forwards;
                `;
                
                // Random direction
                const angle = Math.random() * Math.PI * 2;
                const distance = 30 + Math.random() * 40;
                
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes sparkleFly {
                        0% {
                            transform: translate(${x}px, ${y}px) scale(0);
                            opacity: 1;
                        }
                        100% {
                            transform: translate(${x + Math.cos(angle) * distance}px, ${y + Math.sin(angle) * distance}px) scale(1);
                            opacity: 0;
                        }
                    }
                `;
                
                document.head.appendChild(style);
                document.body.appendChild(sparkle);
                
                // Remove after animation
                setTimeout(() => {
                    sparkle.remove();
                    style.remove();
                }, 600);
            }
        }
        
        // Add style for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes clickRipple {
                0% {
                    transform: scale(0);
                    opacity: 1;
                }
                100% {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Staggered animation for elements
            const elements = document.querySelectorAll('.icon-container, h1, .error-code, .error-message, #logoutBtn, .footer');
            
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    el.style.transition = 'opacity 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });
    </script>
</body>
</html>

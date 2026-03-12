<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Withdraw - SewaLap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #d1fae5;
            --bg-gradient: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .otp-container {
            max-width: 500px;
            width: 100%;
        }

        .otp-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.15);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .otp-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .otp-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .otp-icon i {
            font-size: 2.5rem;
        }

        .otp-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .otp-subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
        }
        
        .otp-body {
            padding: 2rem;
        }

        .otp-instruction {
            text-align: center;
            margin-bottom: 2rem;
        }

        .otp-instruction p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .otp-instruction .phone-number {
            font-weight: 700;
            color: var(--primary-color);
        }

        .withdraw-info {
            background: #f8fafc;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 2px solid var(--border-color);
        }

        .withdraw-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .withdraw-info-item:last-child {
            margin-bottom: 0;
            padding-top: 8px;
            border-top: 2px solid #e5e7eb;
            font-weight: 700;
        }

        .withdraw-info-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .withdraw-info-value {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .otp-input-wrapper {
            text-align: center;
            margin-bottom: 2rem;
        }

        .otp-input {
            width: 100%;
            max-width: 280px;
            padding: 1rem;
            border: 3px solid var(--border-color);
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 10px;
            transition: all 0.3s ease;
            color: var(--text-primary);
        }
        
        .otp-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
            transform: translateY(-2px);
        }
        
        .countdown-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .countdown-circle {
            position: relative;
            width: 60px;
            height: 60px;
        }

        .countdown-svg {
            transform: rotate(-90deg);
        }

        .countdown-circle-bg {
            fill: none;
            stroke: var(--border-color);
            stroke-width: 4;
        }

        .countdown-circle-progress {
            fill: none;
            stroke: var(--primary-color);
            stroke-width: 4;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s linear;
        }

        .countdown-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        .countdown-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .btn-verify {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            margin-bottom: 1rem;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .btn-resend {
            width: 100%;
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 12px;
            padding: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-resend:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: #d1fae5;
            color: #059669;
            border-left: 4px solid #059669;
        }

        @media (max-width: 576px) {
            .otp-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="otp-card">
            <div class="otp-header">
                <div class="otp-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="otp-title">Verifikasi OTP</h1>
                <p class="otp-subtitle">Verifikasi penarikan saldo Anda</p>
            </div>
            
            <div class="otp-body">
                <div class="otp-instruction">
                    <p>Masukkan 6 digit kode OTP yang dikirim ke</p>
                    <p class="phone-number">WhatsApp Anda</p>
                </div>

                <!-- Withdraw Information -->
                <div class="withdraw-info">
                    <div class="withdraw-info-item">
                        <span class="withdraw-info-label">Jumlah Penarikan:</span>
                        <span class="withdraw-info-value">Rp {{ number_format($withdrawData['amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="withdraw-info-item">
                        <span class="withdraw-info-label">Bank:</span>
                        <span class="withdraw-info-value">{{ $withdrawData['bank_name'] }}</span>
                    </div>
                    <div class="withdraw-info-item">
                        <span class="withdraw-info-label">Rekening:</span>
                        <span class="withdraw-info-value">{{ $withdrawData['account_number'] }}</span>
                    </div>
                    <div class="withdraw-info-item">
                        <span class="withdraw-info-label">Atas Nama:</span>
                        <span class="withdraw-info-value">{{ $withdrawData['account_holder_name'] }}</span>
                    </div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('landowner.withdraw.otp.submit') }}" method="POST">
                    @csrf
                    
                    <div class="otp-input-wrapper">
                        <input
                            type="text"
                            name="otp"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            placeholder="------"
                            required
                            autofocus
                            class="otp-input"
                            id="otpInput"
                        >
                    </div>

                    <div class="countdown-wrapper">
                        <div class="countdown-circle">
                            <svg class="countdown-svg" viewBox="0 0 36 36">
                                <circle class="countdown-circle-bg" cx="18" cy="18" r="16"></circle>
                                <circle class="countdown-circle-progress" cx="18" cy="18" r="16" 
                                    stroke-dasharray="100" stroke-dashoffset="0" id="progressCircle"></circle>
                            </svg>
                            <div class="countdown-text" id="countdown">01:00</div>
                        </div>
                        <p class="countdown-label">sisa waktu</p>
                    </div>

                    <button type="submit" class="btn btn-verify">
                        <i class="bi bi-check-circle me-2"></i>
                        Verifikasi & Ajukan Penarikan
                    </button>
                </form>

                <form action="{{ route('landowner.withdraw.otp.resend') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-resend">
                        <i class="bi bi-arrow-repeat me-2"></i>
                        Kirim Ulang OTP
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function startCountdown(duration, display, progressCircle) {
            let timer = duration, minutes, seconds;
            const totalTime = duration;
            const circumference = 100;
            
            const countdownInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                display.textContent = minutes + ":" + seconds;
                
                const progress = ((totalTime - timer) / totalTime) * 100;
                progressCircle.style.strokeDashoffset = progress;
                
                if (--timer < 0) {
                    clearInterval(countdownInterval);
                    display.textContent = "00:00";
                    display.style.color = '#ef4444';
                }
            }, 1000);
        }
        
        window.onload = function () {
            const oneMinute = 1 * 60;
            const display = document.querySelector('#countdown');
            const progressCircle = document.querySelector('#progressCircle');
            
            progressCircle.style.strokeDasharray = '100';
            progressCircle.style.strokeDashoffset = '0';
            
            startCountdown(oneMinute, display, progressCircle);
        };
        
        const otpInput = document.getElementById('otpInput');
        if (otpInput) {
            otpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            otpInput.addEventListener('keydown', function(e) {
                if (!/^\d$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && 
                    e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' && e.key !== 'Tab') {
                    e.preventDefault();
                }
            });
        }
    </script>
</body>
</html>
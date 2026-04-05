<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Sistem Kost</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --secondary-color: #6b7280;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #d1fae5;
            --shadow-lg: 0 10px 30px rgba(16, 185, 129, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
        }

        .forgot-container {
            max-width: 480px;
            width: 100%;
        }

        .forgot-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
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

        .forgot-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .logo-icon i {
            font-size: 2.5rem;
        }

        .forgot-title {
            color: white;
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .forgot-subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .forgot-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 1.2rem;
            z-index: 10;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px 12px 3rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            margin-bottom: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-submit.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .btn-submit.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        .btn-back {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 12px;
            padding: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-back:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(16, 185, 129, 0.05);
            text-decoration: none;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #059669;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        @media (max-width: 576px) {
            .forgot-body {
                padding: 1.5rem;
            }
            
            .forgot-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="logo-icon">
                    <i class="bi bi-key-fill"></i>
                </div>
                <h1 class="forgot-title">Lupa Password?</h1>
                <p class="forgot-subtitle">
                    Jangan khawatir! Masukkan nomor WhatsApp Anda dan kami akan mengirimkan link reset password.
                </p>
            </div>

            <div class="forgot-body">
                <form action="{{ route('password.whatsapp') }}" method="POST" id="forgotForm">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-phone"></i>
                            </span>
                            <input type="text" 
                                class="form-control" 
                                id="phone" 
                                name="phone" 
                                placeholder="Contoh: 628123456789" 
                                required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="bi bi-send me-2"></i>
                        Kirim Link Reset via WhatsApp
                    </button>

                    <a href="{{ route('login') }}" class="btn btn-back">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Login
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const phone = document.getElementById('phone');
            
            if (!phone.value.trim()) {
                e.preventDefault();
                return;
            }
            
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Mengirim...';
            submitBtn.disabled = true;
        });

        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
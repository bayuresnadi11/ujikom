<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Sistem Kost</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #d1fae5;
            --shadow-lg: 0 10px 30px rgba(16, 185, 129, 0.15);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-primary);
            padding: 1rem;
        }

        .reset-container {
            max-width: 480px;
            width: 100%;
        }

        .reset-card {
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

        .reset-header {
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

        .reset-title {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .reset-subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .reset-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
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
            background: white;
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

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.2rem;
            z-index: 10;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn-primary {
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
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-primary.loading {
            opacity: 0.7;
            pointer-events: none;
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
            border-left: 4px solid #ef4444;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 0.25rem;
        }

        .strength-bar-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }

        @media (max-width: 576px) {
            .reset-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="reset-title">Reset Password</h1>
                <p class="reset-subtitle">Masukkan password baru Anda untuk mengatur ulang akun</p>
            </div>

            <div class="reset-body">
                <form action="{{ route('password.update') }}" method="POST" id="resetForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="phone" value="{{ $phone }}">

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" 
                                class="form-control" 
                                name="password" 
                                id="password"
                                placeholder="Minimal 6 karakter"
                                required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-bar-fill" id="strengthBar"></div>
                            </div>
                            <small id="strengthText" class="text-muted"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" 
                                class="form-control" 
                                name="password_confirmation" 
                                id="password_confirmation"
                                placeholder="Ketik ulang password"
                                required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i>
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'strength-bar-fill';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Password lemah';
                strengthText.style.color = '#ef4444';
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Password sedang';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Password kuat';
                strengthText.style.color = '#10b981';
            }
        });

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== confirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
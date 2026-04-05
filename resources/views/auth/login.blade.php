<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - SewaLap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #10b981;
      --primary-dark: #059669;
      --primary-light: #34d399;
      --primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
      --accent-color: #f59e0b;
      --background-color: #f8fafc;
      --card-background: #ffffff;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --border-focus: #10b981;
      --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
      --shadow-md: 0 6px 12px rgba(16, 185, 129, 0.08);
      --shadow-lg: 0 15px 30px rgba(16, 185, 129, 0.12);
      --shadow-xl: 0 20px 40px rgba(16, 185, 129, 0.15);
      --radius-sm: 10px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --radius-xl: 20px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #f8fafc 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      position: relative;
    }

    /* Background Elements */
    .bg-pattern {
      position: fixed;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
      opacity: 0.5;
    }

    .pattern-dot {
      position: absolute;
      width: 6px;
      height: 6px;
      background: var(--primary-color);
      border-radius: 50%;
      opacity: 0.2;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 10;
      animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
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

    .login-card {
      background: var(--card-background);
      border-radius: var(--radius-xl);
      padding: 3rem;
      box-shadow: var(--shadow-xl);
      border: 1px solid rgba(16, 185, 129, 0.1);
      position: relative;
      overflow: hidden;
    }

    /* Card Accent */
    .card-accent {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--primary-gradient);
    }

    .logo-section {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .logo-wrapper {
      position: relative;
      display: inline-block;
      margin-bottom: 1.5rem;
    }

    .logo-icon {
      width: 72px;
      height: 72px;
      background: var(--primary-gradient);
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      box-shadow: 0 10px 25px rgba(16, 185, 129, 0.25);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .logo-icon:hover {
      transform: scale(1.05) rotate(3deg);
      box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
    }

    .logo-icon i {
      font-size: 2.25rem;
      color: white;
    }

    .logo-title {
      font-family: 'Poppins', sans-serif;
      font-size: 2.25rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }

    .logo-highlight {
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .logo-subtitle {
      color: var(--text-secondary);
      font-size: 0.95rem;
      font-weight: 400;
      opacity: 0.8;
    }

    .form-group {
      margin-bottom: 1.75rem;
    }

    .form-label {
      color: var(--text-primary);
      font-weight: 600;
      margin-bottom: 0.75rem;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-label i {
      color: var(--primary-color);
      font-size: 1rem;
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
      font-size: 1.1rem;
      z-index: 10;
      transition: all 0.2s ease;
    }

    .form-control {
      width: 100%;
      padding: 1rem 1rem 1rem 3.2rem;
      border: 2px solid var(--border-color);
      border-radius: var(--radius-md);
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background-color: #fff;
      color: var(--text-primary);
      font-weight: 500;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--border-focus);
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
      background-color: #fff;
      transform: translateY(-2px);
    }

    .form-control::placeholder {
      color: #9ca3af;
      font-weight: 400;
    }

    .password-container {
      position: relative;
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
      padding: 0.5rem;
      transition: all 0.2s ease;
      font-size: 1.1rem;
      z-index: 10;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
    }

    .password-toggle:hover {
      color: var(--primary-color);
      background: rgba(16, 185, 129, 0.1);
      transform: translateY(-50%) scale(1.1);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      font-size: 0.9rem;
    }

    /* Custom Checkbox */
    .checkbox-container {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      cursor: pointer;
    }

    .checkbox-input {
      display: none;
    }

    .checkbox-custom {
      width: 18px;
      height: 18px;
      border: 2px solid var(--border-color);
      border-radius: 4px;
      position: relative;
      transition: all 0.2s ease;
    }

    .checkbox-input:checked + .checkbox-custom {
      background: var(--primary-color);
      border-color: var(--primary-color);
    }

    .checkbox-input:checked + .checkbox-custom::after {
      content: '✓';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 0.75rem;
      font-weight: bold;
    }

    .checkbox-label {
      color: var(--text-secondary);
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .checkbox-container:hover .checkbox-label {
      color: var(--text-primary);
    }

    .checkbox-container:hover .checkbox-custom {
      border-color: var(--primary-color);
    }

    /* Forgot Password Button */
    .forgot-btn {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      border-radius: 6px;
      background: transparent;
      border: none;
      cursor: pointer;
    }

    .forgot-btn:hover {
      color: var(--primary-dark);
      background: rgba(16, 185, 129, 0.1);
      gap: 0.75rem;
    }

    /* Login Button */
    .login-btn {
      width: 100%;
      background: var(--primary-gradient);
      border: none;
      border-radius: var(--radius-md);
      padding: 1.125rem;
      font-size: 1.05rem;
      font-weight: 700;
      color: white;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      margin-bottom: 1.75rem;
      box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
    }

    .login-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.7s;
    }

    .login-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    }

    .login-btn:hover::before {
      left: 100%;
    }

    .login-btn:active {
      transform: translateY(-1px);
    }

    .login-btn.loading {
      position: relative;
      color: transparent;
      pointer-events: none;
    }

    .login-btn.loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 24px;
      height: 24px;
      margin: -12px 0 0 -12px;
      border: 3px solid transparent;
      border-top: 3px solid white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    .login-btn i {
      font-size: 1.2rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Alert Styles */
    .alert {
      padding: 1rem 1.25rem;
      margin-bottom: 1.75rem;
      border: none;
      border-radius: var(--radius-md);
      font-size: 0.9rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      justify-content: space-between;
      animation: slideDown 0.4s ease;
    }

    .alert > div {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-danger {
      color: #dc2626;
      background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
      border-left: 4px solid #dc2626;
      box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
    }

    .alert-success {
      color: #059669;
      background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
      border-left: 4px solid #059669;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.1);
    }

    /* Register Section */
    .register-section {
      text-align: center;
      padding-top: 1.75rem;
      border-top: 1px solid rgba(229, 231, 235, 0.5);
      position: relative;
    }

    .register-section::before {
      content: '';
      position: absolute;
      top: -1px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 2px;
      background: var(--primary-gradient);
      border-radius: 1px;
    }

    .register-text {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 0.75rem;
      font-weight: 500;
    }

    /* Register Button */
    .register-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.875rem 1.5rem;
      background: white;
      border: 2px solid var(--border-color);
      border-radius: var(--radius-md);
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 700;
      font-size: 0.95rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .register-btn:hover {
      color: var(--primary-dark);
      border-color: var(--primary-color);
      background: rgba(16, 185, 129, 0.05);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
      gap: 1rem;
    }

    .register-btn i {
      font-size: 1.1rem;
      transition: transform 0.3s ease;
    }

    .register-btn:hover i {
      transform: translateX(3px);
    }

    /* Footer */
    .copyright {
      text-align: center;
      margin-top: 2.5rem;
      color: var(--text-secondary);
      font-size: 0.8rem;
      opacity: 0.6;
      font-weight: 500;
      letter-spacing: 0.3px;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 2.5rem 1.75rem;
      }
      
      .logo-title {
        font-size: 2rem;
      }
      
      .logo-icon {
        width: 68px;
        height: 68px;
      }
      
      .logo-icon i {
        font-size: 2rem;
      }
      
      .login-btn {
        padding: 1rem;
      }
    }
  </style>
</head>

<body>
  <!-- Background Pattern -->
  <div class="bg-pattern" id="bgPattern"></div>

  <div class="login-container">
    <div class="login-card">
      <div class="card-accent"></div>
      
      <div class="logo-section">
        <div class="logo-wrapper">
          <div class="logo-icon">
            <i class="bi bi-calendar-check-fill"></i>
          </div>
        </div>
        <h1 class="logo-title">Sewa<span class="logo-highlight">Lap</span></h1>
        <p class="logo-subtitle">Masuk ke akun Anda</p>
      </div>

      <form class="form-horizontal" id="loginForm" action="{{route('login.submit')}}" method="POST">
        @csrf
        
        {{-- Flash messages ditangani oleh SweetAlert2 di bawah --}}

        <div class="form-group">
          <label for="phone" class="form-label">
            <i class="bi bi-whatsapp"></i>
            Nomor WhatsApp
          </label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-phone"></i>
            </span>
            <input type="text" 
              class="form-control" 
              name="phone" 
              id="phone" 
              placeholder="628123456789"
              value="{{ old('phone') }}"
              autofocus>
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">
            <i class="bi bi-key"></i>
            Password
          </label>
          <div class="password-container">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-lock"></i>
              </span>
              <input type="password" 
                     class="form-control" 
                     name="password" 
                     id="password" 
                     placeholder="••••••••">
            </div>
            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-options">
          <label class="checkbox-container">
            <input type="checkbox" class="checkbox-input" id="rememberMe" name="remember">
            <span class="checkbox-custom"></span>
            <span class="checkbox-label">Ingat saya</span>
          </label>
          
          <button type="button" class="forgot-btn" onclick="window.location.href='{{ route('password.request') }}'">
            Lupa password?
            <i class="bi bi-arrow-right-short"></i>
          </button>
        </div>

        <button type="submit" class="login-btn" id="loginBtn">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Masuk Sekarang</span>
        </button>

        <div class="register-section">
          <p class="register-text">Belum punya akun?</p>
          <a href="{{route('register')}}" class="register-btn">
            <span>Daftar Sekarang</span>
            <i class="bi bi-arrow-up-right"></i>
          </a>
        </div>
      </form>

      <div class="copyright">
        © 2024 SewaLap • Sistem Booking Lapangan
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Create background pattern
      createPattern();
      
      // Password toggle
      const passwordInput = document.getElementById('password');
      const toggleButton = document.getElementById('passwordToggle');
      const toggleIcon = toggleButton.querySelector('i');

      toggleButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
          toggleIcon.classList.remove('bi-eye');
          toggleIcon.classList.add('bi-eye-slash');
          toggleButton.setAttribute('aria-label', 'Sembunyikan password');
          toggleButton.style.color = 'var(--primary-color)';
        } else {
          toggleIcon.classList.remove('bi-eye-slash');
          toggleIcon.classList.add('bi-eye');
          toggleButton.setAttribute('aria-label', 'Tampilkan password');
          toggleButton.style.color = 'var(--text-secondary)';
        }
      });

      // Form submission
      const form = document.getElementById('loginForm');
      const loginBtn = document.getElementById('loginBtn');

      form.addEventListener('submit', function(e) {
        const phone = document.getElementById('phone').value.trim();
        const password = document.getElementById('password').value.trim();
        
        // Validation
        if (!phone && !password) {
          e.preventDefault();
          showError('Harap isi no telepon dan password');
          return;
        }
        
        if (!phone) {
          e.preventDefault();
          showError('No telepon belum diisi');
          return;
        }

        if (!password) {
          e.preventDefault();
          showError('Password belum diisi');
          return;
        }

        if (phone.length < 10) {
          e.preventDefault();
          showError('Nomor WhatsApp minimal 10 digit');
          return;
        }

        // Show loading state
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
        loginBtn.querySelector('span').style.opacity = '0';
      });

      // Input focus effects
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        const icon = input.previousElementSibling;
        
        input.addEventListener('focus', function() {
          if (icon) icon.style.color = 'var(--primary-color)';
        });
        
        input.addEventListener('blur', function() {
          if (!this.value && icon) {
            icon.style.color = 'var(--text-secondary)';
          }
        });
      });

      // Phone number formatting
      const phoneInput = document.getElementById('phone');
      phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
          value = '62' + value.substring(1);
        }
        e.target.value = value;
      });

      // =============================================
      // SweetAlert2 Popup Notifications
      // =============================================

      // Error popup (centered modal)
      function showError(message) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: message,
          confirmButtonText: 'OK',
          confirmButtonColor: '#ef4444',
          customClass: {
            popup: 'swal-login-popup',
          },
          showClass: { popup: 'swal-center-show' },
          hideClass: { popup: 'swal-center-hide' },
        });
      }

      // Auto-trigger flash session messages
      @if(session('error'))
        showError(@json(session('error')));
      @endif

      @if(session('gagal'))
        showError(@json(session('gagal')));
      @endif

      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: @json(session('success')),
          confirmButtonText: 'OK',
          confirmButtonColor: '#10b981',
          timer: 3000,
          timerProgressBar: true,
          customClass: {
            popup: 'swal-login-popup',
          },
          showClass: { popup: 'swal-center-show' },
          hideClass: { popup: 'swal-center-hide' },
        });
      @endif

      @if(session('sukses'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: @json(session('sukses')),
          confirmButtonText: 'OK',
          confirmButtonColor: '#10b981',
          timer: 3000,
          timerProgressBar: true,
          customClass: {
            popup: 'swal-login-popup',
          },
          showClass: { popup: 'swal-center-show' },
          hideClass: { popup: 'swal-center-hide' },
        });
      @endif

      // Create background pattern function
      function createPattern() {
        const pattern = document.getElementById('bgPattern');
        const width = window.innerWidth;
        const height = window.innerHeight;
        const dots = Math.floor((width * height) / 10000);
        
        for (let i = 0; i < dots; i++) {
          const dot = document.createElement('div');
          dot.className = 'pattern-dot';
          dot.style.left = `${Math.random() * 100}%`;
          dot.style.top = `${Math.random() * 100}%`;
          dot.style.width = `${4 + Math.random() * 4}px`;
          dot.style.height = dot.style.width;
          dot.style.animationDelay = `${Math.random() * 2}s`;
          pattern.appendChild(dot);
        }
      }

      // Add animation to logo on load
      setTimeout(() => {
        const logoIcon = document.querySelector('.logo-icon');
        if (logoIcon) logoIcon.style.transform = 'scale(1)';
      }, 300);
    });
  </script>

  <style>
    /* SweetAlert2 custom for login page */
    .swal-login-popup {
      border-radius: 20px !important;
      padding: 32px 28px !important;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
      max-width: 340px !important;
      width: 90% !important;
      font-family: 'Inter', sans-serif !important;
    }
    .swal-center-show {
      animation: swalCenterIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }
    .swal-center-hide {
      animation: swalCenterOut 0.2s ease-in !important;
    }
    @keyframes swalCenterIn {
      0%   { opacity: 0; transform: scale(0.8) translateY(-20px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes swalCenterOut {
      0%   { opacity: 1; transform: scale(1); }
      100% { opacity: 0; transform: scale(0.9); }
    }
    .swal2-backdrop-show {
      background: rgba(0, 0, 0, 0.45) !important;
      backdrop-filter: blur(2px) !important;
    }
  </style>
</body>
</html>
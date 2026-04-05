<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi - Sistem Kost</title>
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
      --success-color: #10b981;
      --error-color: #ef4444;
      --bg-light: #f0fdf4;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 20px;
    }

    .registration-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(16, 185, 129, 0.15);
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      overflow: hidden;
    }

    .registration-header {
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
      color: white;
    }

    .header-title {
      font-weight: 700;
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
    }

    .header-subtitle {
      font-size: 0.95rem;
      opacity: 0.95;
    }

    .registration-body {
      padding: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      color: var(--text-primary);
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

    .form-control.is-invalid {
      border-color: var(--error-color);
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

    .btn-register {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      border: none;
      border-radius: 12px;
      padding: 12px 24px;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s ease;
      color: white;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-register:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-register.loading {
      opacity: 0.7;
      pointer-events: none;
    }

    .registration-footer {
      padding: 1.5rem;
      text-align: center;
      border-top: 2px solid var(--border-color);
      font-size: 0.95rem;
      color: var(--text-secondary);
    }

    .footer-link {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
    }

    .footer-link:hover {
      text-decoration: underline;
      color: var(--primary-dark);
    }

    .text-danger {
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }

    .spinner {
      animation: spin 1s linear infinite;
      display: inline-block;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @media (max-width: 576px) {
      .registration-body {
        padding: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <div class="registration-container">
    <div class="registration-header">
      <div class="logo-icon">
        <i class="bi bi-person-plus-fill"></i>
      </div>
      <h1 class="header-title">Buat Akun Baru</h1>
      <p class="header-subtitle">Daftar untuk menjadi buyer kost</p>
    </div>

    <div class="registration-body">
      <form id="registerForm" action="{{route('register.submit')}}" method="POST">
        @csrf

        <div class="form-group">
          <label for="name" class="form-label">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-person"></i>
            </span>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
          </div>
          @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="phone" class="form-label">Nomor WhatsApp</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-phone"></i>
            </span>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" placeholder="Contoh: 628123456789" value="{{ old('phone') }}">
          </div>
          @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="position-relative">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-lock"></i>
              </span>
              <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Minimal 6 karakter">
            </div>
            <button type="button" class="password-toggle" id="togglePassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          @error('password') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-register mt-3" id="submitBtn">
          <span id="submitText">Daftar Sekarang</span>
          <i class="bi bi-arrow-right ms-2"></i>
          <span class="spinner d-none ms-2" id="spinner">
            <i class="bi bi-arrow-repeat"></i>
          </span>
        </button>
      </form>
    </div>

    <div class="registration-footer">
      Sudah punya akun? 
      <a href="{{route('login')}}" class="footer-link">Masuk disini</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('password');
      
      togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        const icon = this.querySelector('i');
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
      });

      const form = document.getElementById('registerForm');
      const submitBtn = document.getElementById('submitBtn');
      const submitText = document.getElementById('submitText');
      const spinner = document.getElementById('spinner');

      form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        submitText.textContent = 'Memproses...';
        spinner.classList.remove('d-none');

        // Reset custom errors from previous submit
        document.querySelectorAll('.js-error').forEach(el => el.remove());
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => input.classList.remove('is-invalid'));

        let isValid = true;
        const requiredFields = ['name', 'phone', 'password'];
        requiredFields.forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
            
            let errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger js-error mt-1';
            errorDiv.style.fontSize = '0.875rem';
            
            if (fieldId === 'name') {
                errorDiv.textContent = 'Nama lengkap belum diisi';
            } else if (fieldId === 'phone') {
                errorDiv.textContent = 'No telepon belum diisi';
            } else if (fieldId === 'password') {
                errorDiv.textContent = 'Password belum diisi';
            }
            
            if (fieldId === 'password') {
              field.closest('.position-relative').after(errorDiv);
            } else {
              field.closest('.input-group').after(errorDiv);
            }
          }
        });

        if (!isValid) {
          e.preventDefault();
          submitBtn.disabled = false;
          submitBtn.classList.remove('loading');
          submitText.textContent = 'Daftar Sekarang';
          spinner.classList.add('d-none');
        }
      });

      const inputs = form.querySelectorAll('input');
      inputs.forEach(input => {
        input.addEventListener('input', function() {
          this.classList.remove('is-invalid');
        });
      });
    });
  </script>
</body>
</html>
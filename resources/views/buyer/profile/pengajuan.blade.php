@extends('layouts.main', ['title' => 'Pengajuan Pemilik Lapangan - SewaLap'])

@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            min-height: 100vh;
            background: #f8f9fa;
            padding: 0;
            margin: 0;
        }

        .mobile-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            padding-top: 70px; /* Space for header */
            padding-bottom: 80px; /* Space for bottom-nav */
        }

        .pengajuan-container {
            width: 100%;
            background: white;
            overflow: hidden;
        }

        .pengajuan-header {
            background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
            padding: 40px 24px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .pengajuan-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .pengajuan-header h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .pengajuan-header p {
            font-size: 14px;
            opacity: 0.95;
            line-height: 1.6;
        }

        /* Progress Steps - Optimized for Mobile */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            padding: 24px 20px 16px;
            background: #f8fdf9;
            border-bottom: 2px solid #e8f5ef;
            position: relative;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            cursor: default;
            transition: transform 0.2s ease;
            will-change: transform;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
            transition: background 0.3s ease;
        }

        .step.active:not(:last-child)::after {
            background: linear-gradient(90deg, #27AE60 0%, #e5e7eb 100%);
        }

        .step.completed:not(:last-child)::after {
            background: #27AE60;
        }

        .step.active .step-number {
            background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(10, 92, 54, 0.25);
        }

        .step.completed .step-number {
            background: #27AE60;
            color: white;
        }

        .step.completed .step-number i {
            display: inline;
        }

        .step.completed .step-number .step-num {
            display: none;
        }

        .step.active .step-label {
            color: #0A5C36;
            font-weight: 700;
        }

        .step.completed .step-label {
            color: #27AE60;
            font-weight: 600;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 700;
            font-size: 15px;
            position: relative;
            z-index: 1;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            will-change: transform;
        }

        .step-number i {
            display: none;
            font-size: 16px;
        }

        .step.active .step-number {
            border-color: rgba(10, 92, 54, 0.15);
        }

        .step-label {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .pengajuan-body {
            padding: 0;
        }

        /* Requirements Box - Mobile Optimized */
        .requirements-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 2px solid #fef3c7;
            border-radius: 0;
            padding: 20px;
            margin: 0;
            border-left: 0;
            border-right: 0;
        }

        .requirements-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #92400e;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 14px;
        }

        .requirements-title i {
            font-size: 18px;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirement-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 8px;
            border-bottom: 1px solid #fef3c7;
            transition: background 0.2s ease;
            border-radius: 6px;
        }

        .requirement-item:last-child {
            border-bottom: none;
        }

        .requirement-item:active {
            background: rgba(255, 255, 255, 0.7);
            transform: scale(0.98);
        }

        .requirement-icon {
            color: #f59e0b;
            font-size: 15px;
            margin-top: 2px;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }

        .requirement-item:active .requirement-icon {
            color: #10b981;
        }

        .requirement-text {
            color: #78350f;
            font-size: 13px;
            line-height: 1.5;
        }

        /* Info Box - Mobile Optimized */
        .info-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid #10b981;
            padding: 18px;
            border-radius: 0;
            margin: 0;
        }

        .info-box-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .info-box-icon {
            color: #10b981;
            font-size: 18px;
        }

        .info-box-title {
            font-weight: 700;
            color: #065f46;
            font-size: 15px;
        }

        .info-box-text {
            color: #047857;
            font-size: 13px;
            line-height: 1.6;
            margin-left: 28px;
        }

        /* Example Box - Mobile Optimized */
        .example-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 0;
            border-left: 4px solid #3b82f6;
            border-radius: 0;
            padding: 18px;
            margin: 0;
        }

        .example-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e40af;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .example-title i {
            font-size: 16px;
            color: #fbbf24;
        }

        .example-text {
            background: white;
            padding: 14px;
            border-radius: 8px;
            color: #1e3a8a;
            font-size: 13px;
            line-height: 1.6;
            font-style: italic;
            border: 2px solid #dbeafe;
            transition: border-color 0.2s ease;
        }

        .example-text:active {
            border-color: #93c5fd;
        }

        /* Form Group */
        .form-group {
            margin: 0;
            padding: 24px;
            background: white;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            font-size: 15px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 160px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            line-height: 1.6;
            background: #f9fafb;
            will-change: border-color;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #27AE60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            background: white;
        }

        .form-textarea.error {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .form-textarea.success {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .char-counter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-top: 8px;
            padding: 0 4px;
        }

        .char-count {
            color: #9ca3af;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .char-count.warning {
            color: #f59e0b;
            font-weight: 700;
        }

        .char-count.error {
            color: #ef4444;
            font-weight: 700;
        }

        .validation-message {
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            opacity: 0;
            transform: translateY(-5px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .validation-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        .validation-message.error {
            color: #ef4444;
        }

        .validation-message.success {
            color: #10b981;
        }

        .validation-message i {
            font-size: 13px;
        }

        /* Form Actions - Mobile Optimized */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 0;
            padding: 20px;
            background: #f9fafb;
            border-top: 2px solid #e5e7eb;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            -webkit-tap-highlight-color: transparent;
            will-change: transform;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-secondary {
            background: white;
            color: #6b7280;
            border: 2px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:active {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(10, 92, 54, 0.25);
            border: none;
        }

        .btn-primary:active:not(:disabled) {
            box-shadow: 0 1px 4px rgba(10, 92, 54, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            filter: grayscale(0.3);
        }

        .btn-primary.loading {
            pointer-events: none;
        }

        .btn-primary.loading::after {
            content: '';
            width: 14px;
            height: 14px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alert Popup */
        .alert-popup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .alert-popup.active {
            opacity: 1;
            visibility: visible;
        }

        .alert-popup-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 350px;
            padding: 32px 24px 24px;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .alert-popup.active .alert-popup-content {
            transform: scale(1);
        }

        .alert-popup-icon {
            font-size: 56px;
            margin-bottom: 16px;
        }

        .alert-popup-icon.success {
            color: #10b981;
        }

        .alert-popup-icon.error {
            color: #ef4444;
        }

        .alert-popup-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1f2937;
        }

        .alert-popup-message {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .alert-popup-button {
            width: 100%;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .alert-popup-button.success {
            background: #10b981;
            color: white;
        }

        .alert-popup-button.success:hover {
            background: #059669;
        }

        .alert-popup-button.error {
            background: #ef4444;
            color: white;
        }

        .alert-popup-button.error:hover {
            background: #dc2626;
        }

        @media (max-width: 480px) {
            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .progress-steps {
                padding: 20px 12px 15px;
            }

            .step-label {
                font-size: 10px;
            }
        }
    </style>
@endpush

@section('content')
<div class="mobile-container">
    <!-- Header -->
    @include('layouts.header', ['showSearch' => false])

    <!-- Success Popup -->
    <div class="alert-popup" id="successPopup">
    <div class="alert-popup-content">
        <i class="fas fa-check-circle alert-popup-icon success"></i>
        <h3 class="alert-popup-title">Berhasil!</h3>
        <p class="alert-popup-message" id="successMessage"></p>
        <button class="alert-popup-button success" onclick="closeSuccessPopup()">OK</button>
    </div>
</div>

<!-- Error Popup -->
<div class="alert-popup" id="errorPopup">
    <div class="alert-popup-content">
        <i class="fas fa-exclamation-circle alert-popup-icon error"></i>
        <h3 class="alert-popup-title">Gagal!</h3>
        <p class="alert-popup-message" id="errorMessage"></p>
        <button class="alert-popup-button error" onclick="closeErrorPopup()">OK</button>
    </div>
</div>

<div class="pengajuan-container">
    <div class="pengajuan-header">
        <div class="pengajuan-icon">
            <i class="fas fa-exchange-alt"></i>
        </div>
        <h1>Pengajuan Pemilik Lapangan</h1>
        <p>Bergabunglah sebagai mitra SewaLap dan mulai kelola lapangan Anda!</p>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="step active" id="step1">
            <div class="step-number">
                <span class="step-num">1</span>
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Ajukan</div>
        </div>
        <div class="step" id="step2">
            <div class="step-number">
                <span class="step-num">2</span>
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Review</div>
        </div>
        <div class="step" id="step3">
            <div class="step-number">
                <span class="step-num">3</span>
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Approved</div>
        </div>
    </div>

    <div class="pengajuan-body">
        <!-- Requirements -->
        <div class="requirements-box">
            <div class="requirements-title">
                <i class="fas fa-clipboard-check"></i>
                <span>Persyaratan Pemilik Lapangan</span>
            </div>
            <ul class="requirements-list">
                <li class="requirement-item">
                    <i class="fas fa-check-circle requirement-icon"></i>
                    <span class="requirement-text">Memiliki lapangan olahraga (futsal, badminton, basket, dll)</span>
                </li>
                <li class="requirement-item">
                    <i class="fas fa-check-circle requirement-icon"></i>
                    <span class="requirement-text">Lokasi lapangan yang jelas dan mudah diakses</span>
                </li>
                <li class="requirement-item">
                    <i class="fas fa-check-circle requirement-icon"></i>
                    <span class="requirement-text">Berkomitmen untuk memberikan layanan terbaik</span>
                </li>
                <li class="requirement-item">
                    <i class="fas fa-check-circle requirement-icon"></i>
                    <span class="requirement-text">Bersedia mengikuti kebijakan SewaLap</span>
                </li>
            </ul>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-box-header">
                <i class="fas fa-info-circle info-box-icon"></i>
                <span class="info-box-title">Informasi Penting</span>
            </div>
            <p class="info-box-text">
                Pengajuan akan ditinjau dalam 1-3 hari kerja. Pastikan alasan yang Anda berikan jelas, detail, dan mencakup informasi penting seperti lokasi, jenis lapangan, dan rencana pengelolaan.
            </p>
        </div>

        <!-- Example Box -->
        <div class="example-box">
            <div class="example-title">
                <i class="fas fa-lightbulb"></i>
                <span>Contoh Alasan yang Baik</span>
            </div>
            <div class="example-text">
                "Saya memiliki 2 lapangan futsal di Bandung, Jalan Sudirman No. 45. Lapangan sudah beroperasi selama 3 tahun dengan fasilitas lengkap seperti ruang ganti, toilet, dan kantin. Saya ingin memperluas jangkauan pelanggan melalui SewaLap dan memberikan kemudahan booking online untuk para penyewa."
            </div>
        </div>

        <form action="{{ route('buyer.landowner.request') }}" method="POST" id="pengajuanForm">
            @csrf
            <div class="form-group">
                <label class="form-label" for="reason">
                    Alasan Pengajuan <span class="required">*</span>
                </label>
                <textarea 
                    name="reason" 
                    id="reason" 
                    class="form-textarea" 
                    required 
                    placeholder="Jelaskan:&#10;• Lokasi lengkap lapangan Anda&#10;• Jenis dan jumlah lapangan&#10;• Fasilitas yang tersedia&#10;• Alasan bergabung dengan SewaLap"
                    maxlength="500"
                    oninput="validateReason()"
                ></textarea>
                <div class="char-counter">
                    <span class="char-count" id="charCount">0/500 karakter</span>
                    <span class="validation-message" id="validationMessage"></span>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('buyer.profile') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                    <i class="fas fa-paper-plane"></i>
                    Ajukan Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- Bottom Navigation -->
    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
<script>
    // Debounce function for performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Auto-grow textarea - optimized
    function autoGrowTextarea() {
        const textarea = document.getElementById('reason');
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 300) + 'px';
    }

    // Cache DOM elements for better performance
    let cachedElements = {};
    
    function getCachedElement(id) {
        if (!cachedElements[id]) {
            cachedElements[id] = document.getElementById(id);
        }
        return cachedElements[id];
    }

    // Real-time validation - debounced for mobile performance
    const validateReason = debounce(function() {
        const textarea = getCachedElement('reason');
        const charCount = getCachedElement('charCount');
        const validationMessage = getCachedElement('validationMessage');
        const submitBtn = getCachedElement('submitBtn');
        const length = textarea.value.length;
        
        // Auto-grow textarea
        autoGrowTextarea();
        
        // Update character count
        charCount.textContent = `${length}/500`;
        
        // Character count styling
        charCount.classList.remove('warning', 'error');
        if (length > 450 && length < 500) {
            charCount.classList.add('warning');
        } else if (length === 500) {
            charCount.classList.add('error');
        }
        
        // Progressive validation
        textarea.classList.remove('success', 'error');
        validationMessage.classList.remove('show');
        
        if (length === 0) {
            submitBtn.disabled = true;
            updateProgressStep(1);
        } else if (length < 50) {
            textarea.classList.add('error');
            validationMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Minimal 50 karakter';
            validationMessage.className = 'validation-message error show';
            submitBtn.disabled = true;
            updateProgressStep(1);
        } else if (length >= 50 && length < 100) {
            textarea.classList.add('success');
            validationMessage.innerHTML = '<i class="fas fa-info-circle"></i> Bagus! Tambah detail lagi';
            validationMessage.className = 'validation-message show';
            validationMessage.style.color = '#f59e0b';
            submitBtn.disabled = false;
            updateProgressStep(1.5);
        } else {
            textarea.classList.add('success');
            validationMessage.innerHTML = '<i class="fas fa-check-circle"></i> Sempurna!';
            validationMessage.className = 'validation-message success show';
            submitBtn.disabled = false;
            updateProgressStep(2);
        }
    }, 150); // Debounce 150ms for better mobile performance

    // Update progress steps - simplified for mobile
    function updateProgressStep(stepLevel) {
        const step1 = getCachedElement('step1');
        const step2 = getCachedElement('step2');
        const step3 = getCachedElement('step3');
        
        // Use CSS classes for better performance
        requestAnimationFrame(() => {
            [step1, step2, step3].forEach(step => {
                step.classList.remove('active', 'completed');
            });
            
            if (stepLevel >= 1) {
                step1.classList.add('completed');
            }
            
            if (stepLevel >= 1.5 && stepLevel < 2) {
                step1.classList.add('completed');
                step2.classList.add('active');
            } else if (stepLevel >= 2) {
                step1.classList.add('completed');
                step2.classList.add('completed');
            }
            
            if (stepLevel === 1) {
                step1.classList.add('active');
            }
        });
    }

    // Popup functions
    function showSuccessPopup(message) {
        const popup = getCachedElement('successPopup');
        const messageElement = getCachedElement('successMessage');
        messageElement.textContent = message;
        popup.classList.add('active');
        updateProgressStep(3);
    }

    function showErrorPopup(message) {
        const popup = getCachedElement('errorPopup');
        const messageElement = getCachedElement('errorMessage');
        messageElement.textContent = message;
        popup.classList.add('active');
    }

    function closeSuccessPopup() {
        getCachedElement('successPopup').classList.remove('active');
    }

    function closeErrorPopup() {
        getCachedElement('errorPopup').classList.remove('active');
    }

    // Event listeners - optimized
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize
        updateProgressStep(1);
        
        // Session messages
        @if(session('success'))
            showSuccessPopup("{{ session('success') }}");
        @endif

        @if(session('error'))
            showErrorPopup("{{ session('error') }}");
        @endif

        // Textarea input - use passive listener for better scroll performance
        const textarea = getCachedElement('reason');
        textarea.addEventListener('input', validateReason, { passive: true });
        
        // Form submission
        getCachedElement('pengajuanForm').addEventListener('submit', function(e) {
            const reason = textarea.value;
            const submitBtn = getCachedElement('submitBtn');
            
            if (reason.length < 50) {
                e.preventDefault();
                showErrorPopup('Minimal 50 karakter!');
                return false;
            }
            
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            submitBtn.disabled = true;
            updateProgressStep(2);
        });

        // Close popups
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSuccessPopup();
                closeErrorPopup();
            }
        });

        const successPopup = getCachedElement('successPopup');
        const errorPopup = getCachedElement('errorPopup');
        
        successPopup.addEventListener('click', function(e) {
            if (e.target === this) closeSuccessPopup();
        });
        
        errorPopup.addEventListener('click', function(e) {
            if (e.target === this) closeErrorPopup();
        });

        // Auto-focus with slight delay for better UX
        setTimeout(() => textarea.focus(), 300);
    });
</script>
@endpush
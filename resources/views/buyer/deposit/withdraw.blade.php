@extends('layouts.main', ['title' => 'Deposit - SewaLap'])

@push('styles')
    @include('buyer.menu.partials.menu-style')
    <style>
        :root {
            --primary-green: #22c55e;
            --green-light: #bbf7d0;
            --green-dark: #16a34a;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        /* Header Navigation */
        .nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            margin-bottom: 16px;
            gap: 12px;
        }

        .nav-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            color: var(--primary-green);
            font-size: 18px;
            text-decoration: none;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .nav-back:active {
            background: var(--gray-100);
            transform: scale(0.95);
        }

        .page-title {
            flex: 1;
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-align: center;
            padding: 0 8px;
        }

        .page-title i {
            color: var(--primary-green);
            font-size: 20px;
        }

        /* Main Container */
        .withdraw-main {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-200);
        }

        /* Balance Card */
        .balance-card {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--green-dark) 100%);
            border-radius: 16px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.2);
            position: relative;
            overflow: hidden;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .balance-title {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .balance-amount {
            font-size: 32px;
            font-weight: 800;
            color: white;
            line-height: 1;
            position: relative;
            z-index: 1;
        }

        /* Info Cards */
        .info-card {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--gray-200);
        }

        .info-card.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fbbf24;
        }

        .info-card.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid var(--primary-green);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .card-header i {
            font-size: 20px;
        }

        .info-card.warning .card-header i {
            color: #92400e;
        }

        .info-card.success .card-header i {
            color: var(--green-dark);
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .info-card.warning .card-title {
            color: #92400e;
        }

        .info-card.success .card-title {
            color: var(--green-dark);
        }

        .card-content {
            font-size: 13px;
            color: var(--gray-700);
            line-height: 1.5;
        }

        .info-card.warning .card-content {
            color: #92400e;
        }

        .info-card.success .card-content {
            color: #065f46;
        }

        /* Rules List */
        .rules-list {
            margin: 16px 0;
        }

        .rules-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 13px;
            color: var(--gray-700);
        }

        .rules-list li i {
            color: var(--primary-green);
            font-size: 14px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* Bank Info Card */
        .bank-card {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #7dd3fc;
        }

        .bank-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .bank-header i {
            color: #0369a1;
            font-size: 20px;
        }

        .bank-title {
            font-size: 16px;
            font-weight: 700;
            color: #0369a1;
        }

        .bank-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bank-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(3, 105, 161, 0.1);
        }

        .bank-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .bank-label {
            font-size: 13px;
            color: #0369a1;
            font-weight: 500;
        }

        .bank-value {
            font-size: 14px;
            font-weight: 600;
            color: #0369a1;
        }

        .bank-note {
            font-size: 12px;
            color: #0369a1;
            opacity: 0.8;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gray-900);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: var(--primary-green);
        }

        .form-control-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1.5px solid var(--gray-200);
            background: var(--gray-50);
            color: var(--gray-900);
            font-size: 15px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-green);
            background: white;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .form-control::placeholder {
            color: var(--gray-700);
            opacity: 0.6;
        }

        .form-control[readonly] {
            background: var(--gray-100);
            color: var(--gray-600);
            cursor: not-allowed;
            border-color: var(--gray-300);
        }

        .input-group {
            display: flex;
            align-items: center;
            background: var(--gray-50);
            border-radius: 12px;
            border: 1.5px solid var(--gray-200);
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .input-group-text {
            padding: 14px 16px;
            background: var(--gray-100);
            color: var(--gray-900);
            font-weight: 600;
            font-size: 15px;
            border-right: 1.5px solid var(--gray-200);
            min-width: 60px;
        }

        .input-group .form-control {
            border: none;
            background: transparent;
            flex: 1;
            padding-left: 0;
        }

        .amount-hint {
            font-size: 12px;
            color: var(--gray-700);
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px 24px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        .submit-btn:active {
            background: var(--green-dark);
            transform: scale(0.98);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: white;
            border: 1.5px solid var(--primary-green);
            border-radius: 12px;
            color: var(--primary-green);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .action-btn:active {
            background: var(--green-light);
            transform: translateY(1px);
        }

        .action-btn.secondary {
            background: var(--gray-100);
            border-color: var(--gray-300);
            color: var(--gray-700);
        }

        .action-btn.secondary:active {
            background: var(--gray-200);
        }

        /* Error Messages */
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .error-message i {
            color: #dc2626;
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .error-text {
            font-size: 13px;
            color: #991b1b;
            line-height: 1.4;
        }

        /* Mobile Optimizations */
        @media (max-width: 480px) {
            .withdraw-main {
                padding: 20px;
                margin-left: -16px;
                margin-right: -16px;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
            
            .balance-card {
                padding: 20px;
                margin-left: -16px;
                margin-right: -16px;
                border-radius: 0;
            }
            
            .balance-amount {
                font-size: 28px;
            }
            
            .nav-header {
                margin-left: -16px;
                margin-right: -16px;
                padding: 16px;
                background: white;
            }
            
            .page-title {
                font-size: 18px;
            }
            
            .submit-btn {
                padding: 18px 24px;
            }
        }

        /* Ensure touch targets are large enough */
        button, 
        .nav-back, 
        .action-btn,
        .submit-btn {
            min-height: 44px;
        }

        /* Remove tap highlight */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container" id="mobileContainer">
        @include('layouts.header')

        <main class="main-content">
            <div class="menu-categories">
                <!-- Header dengan layout yang lebih baik -->
                <div class="nav-header">
                    <!-- Kiri: Back Button -->
                    <a href="{{ route('buyer.deposit.index') }}" class="nav-back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    
                    <!-- Tengah: Title -->
                    <div class="page-title">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>Withdraw Saldo</span>
                    </div>
                    
                    <!-- Kanan: Empty space untuk alignment -->
                    <div style="width: 44px;"></div>
                </div>
                
                <div class="withdraw-main">
                    <!-- Balance Card -->
                    <div class="balance-card">
                        <div class="balance-title">
                            <i class="fas fa-wallet"></i>
                            Tersedia untuk Withdraw
                        </div>
                        <div class="balance-amount">
                            Rp {{ number_format($withdrawableAmount, 0, ',', '.') }}
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="info-card success">
                            <div class="card-header">
                                <i class="fas fa-check-circle"></i>
                                <div class="card-title">Berhasil!</div>
                            </div>
                            <div class="card-content">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="info-card warning">
                            <div class="card-header">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div class="card-title">Perhatian</div>
                            </div>
                            <div class="card-content">
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Info Rules -->
                    <div class="info-card">
                        <div class="card-header">
                            <i class="fas fa-info-circle"></i>
                            <div class="card-title">Informasi Penting</div>
                        </div>
                        <div class="card-content">
                            Saldo yang dapat ditarik hanya berasal dari <strong>Main Bareng</strong> dan <strong>Sparring</strong> dengan booking yang Anda buat sendiri.
                        </div>
                        
                        <ul class="rules-list">
                            <li><i class="fas fa-check-circle"></i> Minimum penarikan: Rp 5.000</li>
                            <li><i class="fas fa-check-circle"></i> Maksimum penarikan: Rp 100.000.000</li>
                            <li><i class="fas fa-clock"></i> Proses penarikan: 1-3 hari kerja</li>
                            <li><i class="fas fa-shield-alt"></i> Verifikasi OTP via WhatsApp</li>
                            <li><i class="fas fa-exclamation-circle"></i> Pastikan data rekening sudah benar</li>
                        </ul>
                    </div>
                    
                    @if($hasPendingOrApproved)
                        <div class="info-card warning">
                            <div class="card-header">
                                <i class="fas fa-clock"></i>
                                <div class="card-title">Sedang Diproses</div>
                            </div>
                            <div class="card-content">
                                <strong>Anda masih memiliki permintaan penarikan yang sedang diproses.</strong>
                                <p style="margin-top: 8px;">Anda dapat membuat permintaan baru setelah permintaan sebelumnya selesai atau ditolak.</p>
                            </div>
                            <a href="{{ route('buyer.deposit.withdraw.riwayat') }}" class="action-btn">
                                <i class="fas fa-history"></i>
                                Lihat Status Penarikan
                            </a>
                        </div>
                    @else
                        @if($withdrawableAmount >= 5000)
                            <!-- Bank Information -->
                            <div class="bank-card">
                                <div class="bank-header">
                                    <i class="fas fa-university"></i>
                                    <div class="bank-title">Data Rekening Bank</div>
                                </div>
                                
                                <div class="bank-details">
                                    <div class="bank-item">
                                        <div class="bank-label">Bank</div>
                                        <div class="bank-value">{{ $user->bank_name ?? '-' }}</div>
                                    </div>
                                    <div class="bank-item">
                                        <div class="bank-label">No. Rekening</div>
                                        <div class="bank-value">{{ $user->account_number ?? '-' }}</div>
                                    </div>
                                    <div class="bank-item">
                                        <div class="bank-label">Nama Pemilik</div>
                                        <div class="bank-value">{{ $user->account_holder_name ?? '-' }}</div>
                                    </div>
                                </div>
                                
                                <div class="bank-note">
                                    <i class="fas fa-info-circle"></i>
                                    Data bank diambil otomatis dari profil Anda
                                </div>
                            </div>
                            
                            @if(!$user->bank_name || !$user->account_number || !$user->account_holder_name)
                                <div class="info-card warning">
                                    <div class="card-header">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <div class="card-title">Data Belum Lengkap</div>
                                    </div>
                                    <div class="card-content">
                                        <strong>Data bank belum lengkap!</strong>
                                        <p style="margin-top: 8px;">Silakan lengkapi data bank Anda di halaman profil terlebih dahulu.</p>
                                    </div>
                                    <a href="{{ route('buyer.profile.edit') }}" class="action-btn">
                                        <i class="fas fa-user"></i>
                                        Ke Halaman Profil
                                    </a>
                                </div>
                            @else
                                <form method="POST" action="{{ route('buyer.deposit.withdraw.store') }}" id="withdrawForm">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="amount">
                                            <i class="fas fa-money-bill-wave"></i>
                                            Jumlah Penarikan
                                        </label>
                                        
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input 
                                                type="number"
                                                id="amount"
                                                name="amount"
                                                class="form-control"
                                                placeholder="Masukkan nominal"
                                                value="{{ old('amount') }}"
                                                min="5000"
                                                max="{{ $withdrawableAmount }}"
                                                step="1000"
                                                required
                                            >
                                        </div>
                                        
                                        <div class="amount-hint">
                                            <i class="fas fa-info-circle"></i>
                                            Maksimal penarikan: <strong>Rp {{ number_format($withdrawableAmount, 0, ',', '.') }}</strong>
                                        </div>
                                        
                                        @error('amount')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <div class="error-text">{{ $message }}</div>
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="bank_name">
                                            <i class="fas fa-university"></i>
                                            Nama Bank
                                        </label>
                                        <div class="form-control-wrapper">
                                            <input 
                                                type="text" 
                                                id="bank_name" 
                                                name="bank_name" 
                                                class="form-control" 
                                                value="{{ $user->bank_name }}"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="account_number">
                                            <i class="fas fa-credit-card"></i>
                                            Nomor Rekening
                                        </label>
                                        <div class="form-control-wrapper">
                                            <input 
                                                type="text" 
                                                id="account_number" 
                                                name="account_number" 
                                                class="form-control" 
                                                value="{{ $user->account_number }}"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="account_holder_name">
                                            <i class="fas fa-user"></i>
                                            Nama Pemilik Rekening
                                        </label>
                                        <div class="form-control-wrapper">
                                            <input 
                                                type="text" 
                                                id="account_holder_name" 
                                                name="account_holder_name" 
                                                class="form-control" 
                                                value="{{ $user->account_holder_name }}"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="submit-btn" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i>
                                        Lanjutkan ke Verifikasi OTP
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="info-card warning">
                                <div class="card-header">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <div class="card-title">Saldo Tidak Mencukupi</div>
                                </div>
                                <div class="card-content">
                                    <strong>Saldo withdraw tidak mencukupi</strong>
                                    <p style="margin-top: 8px;">Saldo yang dapat ditarik minimal Rp 5.000. Saat ini saldo Anda: <strong>Rp {{ number_format($withdrawableAmount, 0, ',', '.') }}</strong></p>
                                </div>
                                <a href="{{ route('buyer.deposit.index') }}" class="action-btn secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    Kembali ke Deposit
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('withdrawForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    submitBtn.disabled = true;
                });
            }
        });
    </script>
@endpush
@extends('layouts.main', ['title' => 'Withdraw Saldo - SewaLap'])

@push('styles')
    @include('landowner.menu.partials.menu-style')
    <style>
        /* ====================
           LOADING & ERROR
        ==================== */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 14px 16px;
            border-radius: 10px;
            margin: 16px 0;
            display: none;
            box-shadow: 0 4px 12px rgba(238, 90, 82, 0.2);
            border-left: 4px solid #fff;
        }

        /* ====================
           HEADER
        ==================== */
        .page-header {
            background: white;
            padding: 24px 20px;
            border-bottom: 1px solid #f1f3f5;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-header a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f8fafc;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-header a:hover {
            background: var(--primary);
            color: white;
            transform: translateX(-2px);
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
        }

        .page-header p {
            color: #7b8a8b;
            font-size: 14px;
            margin: 4px 0 0 0;
        }

        /* ====================
           BALANCE INFO
        ==================== */
        .balance-info-card {
            background: linear-gradient(135deg, #15a837ff 0%, #0fb53cff 100%);
            border-radius: 16px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(21, 168, 55, 0.2);
            position: relative;
            overflow: hidden;
        }

        .balance-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .balance-info-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .balance-info-card h3 i {
            font-size: 18px;
        }

        .balance-amount {
            font-size: 32px;
            font-weight: 800;
            margin: 16px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .balance-info {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 10px 12px;
            border-radius: 8px;
        }

        /* ====================
           ALERT
        ==================== */
        .alert-warning {
            background: linear-gradient(135deg, #ffd93d 0%, #ffa502 100%);
            color: #7d6608;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 4px 12px rgba(255, 217, 61, 0.2);
        }

        .alert-warning .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-warning i {
            font-size: 20px;
            margin-top: 2px;
        }

        .alert-warning h4 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 700;
        }

        .alert-warning p {
            margin: 0;
            font-size: 13px;
            line-height: 1.4;
        }

        /* ====================
           INCOMPLETE DATA CONTAINER
        ==================== */
        .incomplete-data-container {
            background: white;
            border-radius: 16px;
            padding: 48px 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            text-align: center;
        }

        .incomplete-icon {
            font-size: 80px;
            color: #ffa502;
            margin-bottom: 24px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .incomplete-title {
            font-size: 24px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        .incomplete-description {
            font-size: 15px;
            color: #7b8a8b;
            line-height: 1.6;
            margin-bottom: 32px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .incomplete-checklist {
            background: #fef9f3;
            border: 2px solid #ffe8cc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
            text-align: left;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .incomplete-checklist h4 {
            font-size: 14px;
            font-weight: 700;
            color: #f39c12;
            margin-bottom: 16px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #ffe8cc;
        }

        .checklist-item:last-child {
            border-bottom: none;
        }

        .checklist-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .checklist-icon.complete {
            background: #d1fae5;
            color: #059669;
        }

        .checklist-icon.incomplete {
            background: #fee2e2;
            color: #dc2626;
        }

        .checklist-text {
            font-size: 14px;
            color: #2c3e50;
            font-weight: 500;
        }

        .checklist-text.complete {
            text-decoration: line-through;
            opacity: 0.6;
        }

        .btn-complete-profile {
            background: linear-gradient(135deg, #ffa502 0%, #f39c12 100%);
            color: white;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(255, 165, 2, 0.3);
        }

        .btn-complete-profile:hover {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 165, 2, 0.4);
            color: white;
            text-decoration: none;
        }

        /* ====================
           FORM STYLES
        ==================== */
        .form-container {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
        }

        .form-title {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f3f5;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-title i {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .form-label span {
            color: #e74c3c;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control:disabled {
            background-color: #f8fafc;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .form-help {
            font-size: 12px;
            color: #7b8a8b;
            margin-top: 6px;
            display: block;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-text {
            position: absolute;
            left: 16px;
            color: #7b8a8b;
            font-weight: 600;
            pointer-events: none;
        }

        .input-group .form-control {
            padding-left: 60px;
        }

        /* ====================
           BUTTONS
        ==================== */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #15a837ff 0%, #0fb53cff 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(21, 168, 55, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0fb53cff 0%, #0da135ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(21, 168, 55, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-secondary {
            background: #f8fafc;
            color: #5d6d7e;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #f1f3f5;
            color: #2c3e50;
            text-decoration: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
            color: white;
            text-decoration: none;
        }

        /* ====================
           PENDING WITHDRAWAL
        ==================== */
        .pending-withdrawal {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary);
        }

        .pending-withdrawal h3 {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pending-withdrawal h3 i {
            color: var(--primary);
        }

        .withdrawal-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .detail-label {
            font-size: 12px;
            color: #7b8a8b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
        }

        .withdrawal-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #7d6608;
        }

        .status-approved {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #27ae60;
        }

        .status-processed {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
        }

        .status-rejected {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #c0392b;
        }

        /* ====================
           HISTORY LINK
        ==================== */
        .history-link {
            display: block;
            text-align: center;
            padding: 16px;
            background: white;
            border-radius: 16px;
            margin-top: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .history-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }

        .history-link i {
            margin-right: 8px;
        }

        /* ====================
           ANIMATIONS
        ==================== */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }

        /* ====================
           RESPONSIVE
        ==================== */
        @media (max-width: 480px) {
            .page-header {
                padding: 20px 16px;
            }
            
            .page-header h1 {
                font-size: 20px;
            }
            
            .balance-amount {
                font-size: 28px;
            }
            
            .form-container {
                padding: 20px 16px;
            }

            .incomplete-data-container {
                padding: 36px 20px;
            }

            .incomplete-icon {
                font-size: 60px;
            }

            .incomplete-title {
                font-size: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .withdrawal-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        @include('layouts.header')

        <main class="main-content">
            <div class="menu-categories">
                <!-- Page Header -->
                <div class="page-header">
                    <a href="{{ route('landowner.deposit.index') }}">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1>
                            <i class="fas fa-money-bill-wave"></i>
                            Withdraw Saldo
                        </h1>
                        <p>Ajukan penarikan saldo ke rekening bank Anda</p>
                    </div>
                </div>

                <!-- Balance Info -->
                <div class="balance-info-card fade-in">
                    <h3>
                        <i class="fas fa-wallet"></i>
                        Saldo yang Dapat Ditarik
                    </h3>
                    <div class="balance-amount" id="withdrawableAmount">
                        @if($withdrawableAmount > 0)
                            Rp {{ number_format($withdrawableAmount, 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </div>
                    <div class="balance-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Saldo berasal dari pembayaran booking di lapangan Anda</span>
                    </div>
                </div>

                <!-- Cek jika data bank belum lengkap -->
                {{-- Logika Validasi UI: Landowner tidak bisa melakukan withdraw jika info bank di profil belum di atur / lengkap --}}
                @if(!$isBankDataComplete)
                    <div class="incomplete-data-container fade-in">
                        <div class="incomplete-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        
                        <h2 class="incomplete-title">Data Bank Belum Lengkap</h2>
                        
                        <p class="incomplete-description">
                            Untuk dapat melakukan penarikan saldo, Anda perlu melengkapi data rekening bank terlebih dahulu di profil Anda.
                        </p>

                        <div class="incomplete-checklist">
                            <h4>Data yang Diperlukan:</h4>
                            
                            <div class="checklist-item">
                                <div class="checklist-icon {{ !empty($user->bank_name) ? 'complete' : 'incomplete' }}">
                                    <i class="fas {{ !empty($user->bank_name) ? 'fa-check' : 'fa-times' }}"></i>
                                </div>
                                <div class="checklist-text {{ !empty($user->bank_name) ? 'complete' : '' }}">
                                    Nama Bank
                                </div>
                            </div>

                            <div class="checklist-item">
                                <div class="checklist-icon {{ !empty($user->account_number) ? 'complete' : 'incomplete' }}">
                                    <i class="fas {{ !empty($user->account_number) ? 'fa-check' : 'fa-times' }}"></i>
                                </div>
                                <div class="checklist-text {{ !empty($user->account_number) ? 'complete' : '' }}">
                                    Nomor Rekening
                                </div>
                            </div>

                            <div class="checklist-item">
                                <div class="checklist-icon {{ !empty($user->account_holder_name) ? 'complete' : 'incomplete' }}">
                                    <i class="fas {{ !empty($user->account_holder_name) ? 'fa-check' : 'fa-times' }}"></i>
                                </div>
                                <div class="checklist-text {{ !empty($user->account_holder_name) ? 'complete' : '' }}">
                                    Nama Pemilik Rekening
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('landowner.profile.edit') }}" class="btn-complete-profile">
                            <i class="fas fa-user-edit"></i>
                            Lengkapi Data Bank
                        </a>
                    </div>
                @else
                    <!-- Warning jika ada pending withdrawal -->
                    {{-- Logika Validasi Flow: Jika user punya withdraw yang belum selesai, ia tidak diizinkan submit form dobel --}}
                    @if($hasPendingOrApproved)
                        <div class="alert-warning fade-in">
                            <div class="alert-content">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <h4>Permintaan Penarikan Sedang Diproses</h4>
                                    <p>Anda masih memiliki permintaan penarikan yang sedang diproses. Tunggu hingga selesai atau ditolak sebelum membuat permintaan baru.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Show pending withdrawal details -->
                        @php
                            $pendingWithdrawal = \App\Models\WithdrawalRequest::where('user_id', auth()->id())
                                ->whereIn('status', ['pending', 'approved'])
                                ->first();
                        @endphp
                        
                        @if($pendingWithdrawal)
                        <div class="pending-withdrawal fade-in">
                            <h3>
                                <i class="fas fa-clock"></i>
                                Permintaan Sedang Diproses
                            </h3>
                            
                            <div class="withdrawal-details">
                                <div class="detail-item">
                                    <div class="detail-label">Jumlah</div>
                                    <div class="detail-value">Rp {{ number_format($pendingWithdrawal->amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Bank</div>
                                    <div class="detail-value">{{ $pendingWithdrawal->bank_name }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status</div>
                                    <div>
                                        <span class="withdrawal-status status-{{ $pendingWithdrawal->status }}">
                                            @if($pendingWithdrawal->status == 'pending')
                                                <i class="fas fa-clock"></i> Pending
                                            @elseif($pendingWithdrawal->status == 'approved')
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            @elseif($pendingWithdrawal->status == 'processed')
                                                <i class="fas fa-check-double"></i> Diproses
                                            @else
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Tanggal Pengajuan</div>
                                    <div class="detail-value">
                                        {{ $pendingWithdrawal->created_at ? $pendingWithdrawal->created_at->format('d M Y H:i') : '-' }}
                                    </div>
                                </div>
                            </div>
                            
                            <div style="text-align: center;">
                                <a href="{{ route('landowner.withdraw.riwayat') }}" 
                                   class="btn btn-secondary" style="max-width: 200px; margin: 0 auto;">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Withdraw Form -->
                    @if(!$hasPendingOrApproved && $withdrawableAmount > 0)
                    <div class="form-container fade-in">
                        <form action="{{ route('landowner.deposit.withdraw.store') }}" method="POST" id="withdrawForm">
                            @csrf
                            
                            <h2 class="form-title">
                                <i class="fas fa-credit-card"></i>
                                Form Penarikan
                            </h2>

                            @if(session('error'))
                                <div class="error-message" style="display: block;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Amount -->
                            <div class="form-group">
                                <label for="amount" class="form-label">
                                    Jumlah Penarikan <span>*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           id="amount" 
                                           name="amount" 
                                           class="form-control" 
                                           min="5000" 
                                           max="{{ $withdrawableAmount }}" 
                                           step="1000"
                                           required
                                           placeholder="Masukkan jumlah penarikan"
                                           oninput="updateAmountPreview(this.value)">
                                </div>
                                <small class="form-help">
                                    Minimum: Rp 5.000 | Maksimum: <span id="maxAmount">Rp {{ number_format($withdrawableAmount, 0, ',', '.') }}</span>
                                </small>
                                <div id="amountPreview" style="margin-top: 8px; font-size: 14px; color: #7b8a8b; display: none;">
                                    <i class="fas fa-check-circle" style="color: #2ecc71; margin-right: 5px;"></i>
                                    <span>Jumlah yang akan ditarik: <strong id="previewAmount">Rp 0</strong></span>
                                </div>
                            </div>

                            <!-- Bank Name (Read-only, auto-filled from user data) -->
                            <div class="form-group">
                                <label for="bank_name" class="form-label">
                                    Nama Bank
                                </label>
                                <input type="text" 
                                       id="bank_name" 
                                       name="bank_name" 
                                       class="form-control" 
                                       value="{{ $user->bank_name ?? '' }}"
                                       readonly
                                       disabled
                                       style="background-color: #f8fafc; cursor: not-allowed;">
                                <small class="form-help">Data diambil otomatis dari profil Anda</small>
                            </div>

                            <!-- Account Number (Read-only, auto-filled from user data) -->
                            <div class="form-group">
                                <label for="account_number" class="form-label">
                                    Nomor Rekening
                                </label>
                                <input type="text" 
                                       id="account_number" 
                                       name="account_number" 
                                       class="form-control" 
                                       value="{{ $user->account_number ?? '' }}"
                                       readonly
                                       disabled
                                       style="background-color: #f8fafc; cursor: not-allowed;">
                                <small class="form-help">Data diambil otomatis dari profil Anda</small>
                            </div>

                            <!-- Account Holder Name (Read-only, auto-filled from user data) -->
                            <div class="form-group">
                                <label for="account_holder_name" class="form-label">
                                    Nama Pemilik Rekening
                                </label>
                                <input type="text" 
                                       id="account_holder_name" 
                                       name="account_holder_name" 
                                       class="form-control" 
                                       value="{{ $user->account_holder_name ?? '' }}"
                                       readonly
                                       disabled
                                       style="background-color: #f8fafc; cursor: not-allowed;">
                                <small class="form-help">Data diambil otomatis dari profil Anda</small>
                            </div>

                            <!-- Terms -->
                            <div class="form-group" style="margin-top: 24px;">
                                <div style="background: #f8fafc; padding: 16px; border-radius: 10px; border: 1px solid #e9ecef;">
                                    <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-info-circle" style="color: var(--primary); margin-top: 2px;"></i>
                                        <div>
                                            <h4 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #2c3e50;">
                                                Informasi Penting
                                            </h4>
                                            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #5d6d7e;">
                                                <li>Kode OTP akan dikirim ke WhatsApp Anda untuk verifikasi</li>
                                                <li>Penarikan akan diproses dalam 1-3 hari kerja setelah OTP terverifikasi</li>
                                                <li>Pastikan data rekening sudah benar</li>
                                                <li>Biaya transfer ditanggung oleh sistem</li>
                                                <li>Anda hanya bisa memiliki 1 permintaan penarikan aktif</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="btn-group">
                                <a href="{{ route('landowner.deposit.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-paper-plane"></i>
                                    Ajukan Penarikan
                                </button>
                            </div>
                        </form>
                    </div>
                    @elseif(!$hasPendingOrApproved && $withdrawableAmount <= 0)
                    <!-- No withdrawable amount -->
                    <div class="form-container fade-in" style="text-align: center; padding: 48px 24px;">
                        <div style="font-size: 60px; color: #bdc3c7; margin-bottom: 20px;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3 style="color: #2c3e50; margin-bottom: 12px;">Saldo Tidak Tersedia</h3>
                        <p style="color: #7b8a8b; margin-bottom: 24px; line-height: 1.5;">
                            Anda belum memiliki saldo yang dapat ditarik. <br>
                            Saldo akan tersedia setelah ada pembayaran booking di lapangan Anda.
                        </p>
                        <a href="{{ route('landowner.deposit.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Deposit
                        </a>
                    </div>
                    @endif
                @endif

                <!-- Link to Withdraw History -->
                <a href="{{ route('landowner.deposit.withdraw.history') }}" class="history-link fade-in">
                    <i class="fas fa-history"></i>
                    Lihat Riwayat Penarikan
                </a>
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('withdrawForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const amountInput = document.getElementById('amount');
                    const maxAmount = parseFloat('{{ $withdrawableAmount }}');
                    const inputAmount = parseFloat(amountInput.value);
                    
                    if (inputAmount > maxAmount) {
                        e.preventDefault();
                        alert('Jumlah penarikan melebihi saldo yang tersedia (Rp ' + maxAmount.toLocaleString('id-ID') + ')');
                        amountInput.focus();
                        return false;
                    }
                    
                    if (inputAmount < 5000) {
                        e.preventDefault();
                        alert('Jumlah penarikan minimum adalah Rp 5.000');
                        amountInput.focus();
                        return false;
                    }
                    
                    // Show loading on button
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                        submitBtn.disabled = true;
                    }
                });
            }
        });

        function updateAmountPreview(value) {
            const preview = document.getElementById('amountPreview');
            const previewAmount = document.getElementById('previewAmount');
            const maxAmount = parseFloat('{{ $withdrawableAmount }}');
            const inputAmount = parseFloat(value) || 0;
            
            if (inputAmount > 0) {
                preview.style.display = 'block';
                previewAmount.textContent = 'Rp ' + inputAmount.toLocaleString('id-ID');
                
                const totalDeposit = parseFloat('{{ $balanceDetails['total_deposit'] ?? 0 }}');
                const totalWithdrawalProcessed = parseFloat('{{ $balanceDetails['total_withdrawal_processed'] ?? 0 }}');
                const totalWithdrawalPending = parseFloat('{{ $balanceDetails['total_withdrawal_pending'] ?? 0 }}');
                const availableBalance = totalDeposit - totalWithdrawalProcessed;
                const remainingBalance = availableBalance - (totalWithdrawalPending + inputAmount);
                
                if (inputAmount > maxAmount) {
                    previewAmount.style.color = '#e74c3c';
                    preview.querySelector('i').className = 'fas fa-exclamation-circle';
                    preview.querySelector('i').style.color = '#e74c3c';
                    preview.querySelector('span').innerHTML = 'Melebihi saldo ditarik: <strong id="previewAmount">Rp ' + inputAmount.toLocaleString('id-ID') + '</strong>';
                } else {
                    previewAmount.style.color = '#27ae60';
                    preview.querySelector('i').className = 'fas fa-check-circle';
                    preview.querySelector('i').style.color = '#2ecc71';
                    preview.querySelector('span').innerHTML = 
                        'Jumlah ditarik: <strong id="previewAmount">Rp ' + inputAmount.toLocaleString('id-ID') + '</strong><br>' +
                        '<small style="color: #666;">Saldo tersisa: Rp ' + Math.max(0, remainingBalance).toLocaleString('id-ID') + '</small>';
                }
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
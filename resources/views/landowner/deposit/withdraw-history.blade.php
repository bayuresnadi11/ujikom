@extends('layouts.main', ['title' => 'Riwayat Penarikan - SewaLap'])

@push('styles')
    @include('landowner.menu.partials.menu-style')
    <style>
        /* ====================
           VARIABLES
        ==================== */
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #dbeafe;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #06b6d4;
            --info-light: #cffafe;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* ====================
           HEADER
        ==================== */
        .page-header {
            background: linear-gradient(135deg, #16cd96ff 0%, #1cd02bff 100%);
            padding: 28px 20px;
            border-radius: 0 0 24px 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .page-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-header a.back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .page-header a.back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-4px);
        }

        .page-header-text h1 {
            font-size: 24px;
            font-weight: 800;
            color: white;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .page-header-text p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin: 0;
            font-weight: 500;
        }

        /* ====================
           FILTER SECTION
        ==================== */
        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-100);
        }

        .filter-label {
            font-size: 14px;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label i {
            color: var(--primary);
            font-size: 16px;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 18px;
            border-radius: 12px;
            border: 2px solid var(--gray-200);
            background: white;
            color: var(--gray-700);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .filter-btn:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .filter-btn.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .filter-btn .badge {
            background: rgba(0, 0, 0, 0.15);
            color: inherit;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .filter-btn.active .badge {
            background: rgba(255, 255, 255, 0.3);
        }

        /* ====================
           WITHDRAWAL CARDS
        ==================== */
        .withdrawal-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-100);
            margin-bottom: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .withdrawal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--info) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .withdrawal-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .withdrawal-card:hover::before {
            opacity: 1;
        }

        .withdrawal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
        }

        .withdrawal-id {
            color: var(--gray-600);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .withdrawal-id i {
            color: var(--primary);
        }

        .withdrawal-body {
            padding: 24px;
        }

        .withdrawal-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px dashed var(--gray-200);
        }

        .withdrawal-row:last-of-type {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .withdrawal-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-600);
            font-size: 14px;
            font-weight: 600;
        }

        .withdrawal-label i {
            color: var(--primary);
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .withdrawal-value {
            font-weight: 700;
            font-size: 15px;
            text-align: right;
        }

        .withdrawal-amount {
            color: var(--danger);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .withdrawal-bank {
            color: var(--gray-900);
            font-size: 15px;
        }

        .withdrawal-account {
            color: var(--gray-700);
            font-size: 14px;
            line-height: 1.4;
        }

        .withdrawal-date {
            color: var(--gray-500);
            font-size: 13px;
        }

        /* ====================
           STATUS BADGES
        ==================== */
        .withdrawal-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .status-pending {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #78350f;
        }

        .status-approved {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            color: #1e3a8a;
        }

        .status-processed {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
            color: #064e3b;
        }

        .status-rejected {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: #7f1d1d;
        }

        /* ====================
           PHOTO PREVIEW
        ==================== */
        .photo-preview {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid var(--gray-100);
        }

        .photo-label {
            font-size: 14px;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .photo-label i {
            color: var(--success);
            font-size: 16px;
        }

        .photo-container {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .photo-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid var(--gray-200);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md);
        }

        .photo-thumbnail:hover {
            transform: scale(1.08) rotate(2deg);
            border-color: var(--primary);
            box-shadow: var(--shadow-xl);
        }

        .view-photo-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md);
        }

        .view-photo-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
            text-decoration: none;
        }

        .view-photo-btn i {
            font-size: 14px;
        }

        /* ====================
           REJECTION REASON
        ==================== */
        .rejection-reason {
            margin-top: 20px;
            padding: 18px 20px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 16px;
            border: 2px solid #fca5a5;
            position: relative;
            overflow: hidden;
        }

        .rejection-reason::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--danger) 0%, #dc2626 100%);
        }

        .rejection-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--danger);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rejection-label i {
            font-size: 16px;
        }

        .rejection-text {
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            color: #991b1b;
            font-weight: 500;
        }

        /* ====================
           PROCESSED INFO
        ==================== */
        .processed-info {
            margin-top: 20px;
            padding: 18px 20px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 16px;
            border: 2px solid #93c5fd;
            position: relative;
            overflow: hidden;
        }

        .processed-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--info) 0%, #0891b2 100%);
        }

        .processed-label {
            font-size: 13px;
            font-weight: 700;
            color: #075985;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .processed-label i {
            font-size: 16px;
        }

        .processed-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .processed-detail {
            font-size: 13px;
            color: #0c4a6e;
        }

        .processed-detail span {
            display: block;
            color: #0369a1;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .processed-detail strong {
            font-weight: 700;
            font-size: 14px;
        }

        /* ====================
           NO DATA
        ==================== */
        .no-data {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
            border-radius: 24px;
            border: 2px dashed var(--gray-300);
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .no-data::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.03) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .no-data-content {
            position: relative;
            z-index: 1;
        }

        .no-data i {
            font-size: 72px;
            color: var(--gray-400);
            margin-bottom: 24px;
            display: block;
        }

        .no-data h3 {
            color: var(--gray-700);
            margin-bottom: 12px;
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -0.5px;
        }

        .no-data p {
            color: var(--gray-500);
            font-size: 14px;
            line-height: 1.6;
            max-width: 320px;
            margin: 0 auto 24px;
        }

        .no-data a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .no-data a:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        /* ====================
           ANIMATIONS
        ==================== */
        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ====================
           RESPONSIVE
        ==================== */
        @media (max-width: 480px) {
            .page-header {
                padding: 24px 16px;
                border-radius: 0 0 20px 20px;
            }

            .page-header-text h1 {
                font-size: 20px;
            }

            .page-header-text p {
                font-size: 13px;
            }

            .filter-section {
                padding: 16px;
            }

            .filter-buttons {
                gap: 6px;
            }

            .filter-btn {
                padding: 8px 14px;
                font-size: 12px;
            }

            .withdrawal-header,
            .withdrawal-body {
                padding: 16px 18px;
            }

            .withdrawal-amount {
                font-size: 20px;
            }

            .withdrawal-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                margin-bottom: 16px;
                padding-bottom: 16px;
            }

            .withdrawal-value {
                text-align: left;
            }

            .photo-thumbnail {
                width: 80px;
                height: 80px;
            }

            .processed-details {
                grid-template-columns: 1fr;
                gap: 12px;
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
                    <div class="page-header-content">
                        <a href="{{ route('landowner.deposit.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="page-header-text">
                            <h1>
                                <i class="fas fa-history"></i>
                                Riwayat Penarikan
                            </h1>
                            <p>Pantau semua permintaan penarikan Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section slide-in">
                    <div class="filter-label">
                        <i class="fas fa-filter"></i>
                        <span>Filter Status</span>
                    </div>
                    <div class="filter-buttons">
                        <button class="filter-btn active" data-status="all">
                            <i class="fas fa-list"></i>
                            <span>Semua</span>
                            <span class="badge">{{ $withdrawals->count() }}</span>
                        </button>
                        <button class="filter-btn" data-status="pending">
                            <i class="fas fa-clock"></i>
                            <span>Pending</span>
                            <span class="badge">{{ $withdrawals->where('status', 'pending')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-status="approved">
                            <i class="fas fa-check-circle"></i>
                            <span>Disetujui</span>
                            <span class="badge">{{ $withdrawals->where('status', 'approved')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-status="processed">
                            <i class="fas fa-check-double"></i>
                            <span>Diproses</span>
                            <span class="badge">{{ $withdrawals->where('status', 'processed')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-status="rejected">
                            <i class="fas fa-times-circle"></i>
                            <span>Ditolak</span>
                            <span class="badge">{{ $withdrawals->where('status', 'rejected')->count() }}</span>
                        </button>
                    </div>
                </div>

                <!-- Withdrawal History -->
                <div id="withdrawalHistory">
                    @forelse($withdrawals as $withdrawal)
                    <div class="withdrawal-card fade-in" style="animation-delay: {{ $loop->index * 50 }}ms;" data-status="{{ $withdrawal->status }}">
                        <div class="withdrawal-header">
                            <span class="withdrawal-id">
                                <i class="fas fa-hashtag"></i>
                                {{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="withdrawal-status status-{{ $withdrawal->status }}">
                                @if($withdrawal->status == 'pending')
                                    <i class="fas fa-clock"></i> Pending
                                @elseif($withdrawal->status == 'approved')
                                    <i class="fas fa-check-circle"></i> Disetujui
                                @elseif($withdrawal->status == 'processed')
                                    <i class="fas fa-check-double"></i> Diproses
                                @elseif($withdrawal->status == 'rejected')
                                    <i class="fas fa-times-circle"></i> Ditolak
                                @endif
                            </span>
                        </div>
                        
                        <div class="withdrawal-body">
                            <div class="withdrawal-row">
                                <div class="withdrawal-label">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Jumlah</span>
                                </div>
                                <div class="withdrawal-value withdrawal-amount">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="withdrawal-row">
                                <div class="withdrawal-label">
                                    <i class="fas fa-university"></i>
                                    <span>Bank</span>
                                </div>
                                <div class="withdrawal-value withdrawal-bank">
                                    {{ $withdrawal->bank_name }}
                                </div>
                            </div>
                            
                            <div class="withdrawal-row">
                                <div class="withdrawal-label">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Rekening</span>
                                </div>
                                <div class="withdrawal-value withdrawal-account">
                                    {{ $withdrawal->account_number }}<br>
                                    <small>{{ $withdrawal->account_holder_name }}</small>
                                </div>
                            </div>
                            
                            <div class="withdrawal-row">
                                <div class="withdrawal-label">
                                    <i class="far fa-clock"></i>
                                    <span>Tanggal Pengajuan</span>
                                </div>
                                <div class="withdrawal-value withdrawal-date">
                                    {{ $withdrawal->created_at ? $withdrawal->created_at->format('d M Y H:i') : '-' }}
                                </div>
                            </div>
                            
                            <!-- Photo Proof -->
                            @if($withdrawal->photo)
                            <div class="photo-preview">
                                <div class="photo-label">
                                    <i class="fas fa-image"></i>
                                    <span>Bukti Transfer</span>
                                </div>
                                <div class="photo-container">
                                    <img src="{{ asset('storage/' . $withdrawal->photo) }}" 
                                         alt="Bukti Transfer" 
                                         class="photo-thumbnail"
                                         onclick="openPhoto('{{ asset('storage/' . $withdrawal->photo) }}')">
                                    <a href="{{ asset('storage/' . $withdrawal->photo) }}" 
                                       target="_blank"
                                       class="view-photo-btn">
                                        <i class="fas fa-expand-alt"></i>
                                        <span>Lihat Full Size</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Rejection Reason -->
                            @if($withdrawal->status == 'rejected' && $withdrawal->rejection_reason)
                            <div class="rejection-reason">
                                <div class="rejection-label">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Alasan Penolakan</span>
                                </div>
                                <p class="rejection-text">{{ $withdrawal->rejection_reason }}</p>
                            </div>
                            @endif
                            
                            <!-- Processed Info -->
                            @if($withdrawal->status == 'processed')
                            <div class="processed-info">
                                <div class="processed-label">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Informasi Proses</span>
                                </div>
                                <div class="processed-details">
                                    <div class="processed-detail">
                                        <span>Tanggal Diproses</span>
                                        <strong>{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y H:i') : '-' }}</strong>
                                    </div>
                                    <div class="processed-detail">
                                        <span>Diproses Oleh</span>
                                        <strong>{{ $withdrawal->processedBy->name ?? 'Admin' }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="no-data fade-in">
                        <div class="no-data-content">
                            <i class="fas fa-history"></i>
                            <h3>Belum Ada Riwayat</h3>
                            <p>Anda belum memiliki riwayat penarikan saldo. Mulai ajukan penarikan pertama Anda sekarang!</p>
                            <a href="{{ route('landowner.withdraw.saldo') }}">
                                <i class="fas fa-plus-circle"></i>
                                <span>Ajukan Penarikan</span>
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    <script>
        // Photo Preview
        function openPhoto(url) {
            window.open(url, '_blank', 'width=800,height=600');
        }

        // Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const withdrawalCards = document.querySelectorAll('.withdrawal-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.getAttribute('data-status');

                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Filter cards
                    withdrawalCards.forEach(card => {
                        const cardStatus = card.getAttribute('data-status');
                        
                        if (status === 'all') {
                            card.style.display = 'block';
                            // Re-trigger animation
                            card.style.animation = 'none';
                            setTimeout(() => {
                                card.style.animation = '';
                            }, 10);
                        } else {
                            if (cardStatus === status) {
                                card.style.display = 'block';
                                // Re-trigger animation
                                card.style.animation = 'none';
                                setTimeout(() => {
                                    card.style.animation = '';
                                }, 10);
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });

                    // Check if there are visible cards
                    const visibleCards = Array.from(withdrawalCards).filter(card => card.style.display !== 'none');
                    const noDataElement = document.querySelector('.no-data');
                    
                    if (visibleCards.length === 0 && !noDataElement) {
                        const withdrawalHistory = document.getElementById('withdrawalHistory');
                        const noData = document.createElement('div');
                        noData.className = 'no-data fade-in';
                        noData.innerHTML = `
                            <div class="no-data-content">
                                <i class="fas fa-filter"></i>
                                <h3>Tidak Ada Data</h3>
                                <p>Tidak ada riwayat penarikan dengan status ${getStatusLabel(status)}.</p>
                            </div>
                        `;
                        withdrawalHistory.appendChild(noData);
                    } else if (visibleCards.length > 0 && noDataElement) {
                        noDataElement.remove();
                    }
                });
            });

            function getStatusLabel(status) {
                const labels = {
                    'pending': 'Pending',
                    'approved': 'Disetujui',
                    'processed': 'Diproses',
                    'rejected': 'Ditolak'
                };
                return labels[status] || status;
            }
        });
    </script>
@endpush
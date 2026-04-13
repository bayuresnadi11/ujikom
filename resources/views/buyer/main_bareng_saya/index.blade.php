{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'Main Bareng Saya - SewaLap'])

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling dari partial --}}
    @include('buyer.main_bareng_saya.partials.style')
    <style>
        /* ================= VARIABLES ================= */
        /* Definisi variabel warna utama untuk konsistensi tema */
        :root {
            --primary: #0A5C36;
            --primary-dark: #08482B;
            --secondary: #27AE60;
            --accent: #2ECC71;
            --light: #F8F9FA;
            --light-gray: #E9ECEF;
            --text: #212529;
            --text-light: #6C757D;
            --danger: #E74C3C;
            --gold: #FFD700;

            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            --gradient-light: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
            --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);

            --shadow-sm: 0 1px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 3px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 6px 24px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 9px 36px rgba(0, 0, 0, 0.15);
        }

        /* ================= GLOBAL ================= */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: var(--text);
        }

        /* Container utama dengan gaya mobile */
        .mobile-container {
            width: 100%;
            min-height: 100vh;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            overflow-x: hidden;
            box-shadow: 0 0 20px rgba(10, 92, 54, 0.08);
            max-width: 500px;
        }

        /* ================= PAGE HEADER ================= */
        .page-header {
            padding: 20px 16px 0;
            margin-bottom: 20px;
        }

        /* Judul halaman dengan gradien */
        .page-title {
            font-size: 28px;
            font-weight: 900;
            color: #0A5C36;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.4;
            margin: 0;
            font-weight: 500;
        }

        /* Header top untuk logo dan aksi */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .logo {
            font-size: 18px;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .logo span {
            color: #a3e9c0;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            gap: 8px;
        }

        .header-icon {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: white;
            padding: 6px;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Badge notifikasi */
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--danger);
            color: white;
            font-size: 9px;
            font-weight: 800;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--primary);
        }

        .header-icon:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .search-bar {
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.1);
        }

        .search-container {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .search-container:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
        }

        .search-input {
            flex: 1;
            padding: 14px;
            border: none;
            background: transparent;
            font-size: 14px;
            outline: none;
            color: var(--text);
            font-weight: 500;
        }

        .search-input::placeholder {
            color: var(--text-light);
            opacity: 0.8;
            font-size: 13px;
        }

        .search-btn {
            background: var(--gradient-accent);
            color: #fff;
            border: none;
            padding: 0 16px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-btn:hover {
            opacity: 0.9;
        }

        /* ================= MAIN ================= */
        .main-content {
            padding: 65px 0 80px;
            min-height: 100vh;
        }

        /* ================ CARD CONTAINER ================ */
        .card-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 24px;
            padding: 0 16px;
        }

        /* Kartu main bareng */
        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--light-gray);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .card-image {
            position: relative;
            height: 160px;
            overflow: hidden;
            background: var(--light);
        }

        .venue-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-booking-code {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(10, 92, 54, 0.9);
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .card-header {
            padding: 16px;
            border-bottom: 1px solid var(--light-gray);
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Container badge */
        .card-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* Styling dasar badge */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Variasi warna badge */
        .badge-public {
            background: #E8F5E9;
            color: #0A5C36;
        }

        .badge-private {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-paid {
            background: #E3F2FD;
            color: #1565C0;
        }

        .badge-free {
            background: #F3E5F5;
            color: #7B1FA2;
        }

        .badge-active {
            background: #E8F5E9;
            color: #0A5C36;
        }

        .badge-pending {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-canceled {
            background: #FFEBEE;
            color: #C62828;
        }

        .badge-gender {
            background: var(--light);
            color: var(--primary);
        }

        .badge-cost {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-host-yes {
            background: #E3F2FD;
            color: #1565C0;
        }

        .badge-host-no {
            background: #FFEBEE;
            color: #C62828;
        }

        .badge-today {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-upcoming {
            background: #E8F5E9;
            color: #0A5C36;
        }

        /* Body kartu */
        .card-body {
            padding: 16px;
        }

        /* Baris informasi dalam kartu */
        .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .card-row:last-child {
            border-bottom: none;
        }

        .card-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            font-size: 13px;
        }

        .card-value {
            font-weight: 600;
            color: var(--text);
            font-size: 13px;
            text-align: right;
        }

        /* Footer kartu */
        .card-footer {
            padding: 16px;
            background: var(--light);
            border-top: 1px solid var(--light-gray);
        }

        /* Container tombol aksi */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Tombol aksi umum */
        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            justify-content: center;
        }

        .btn-view {
            background: var(--light);
            color: var(--primary);
            border: 1px solid var(--light-gray);
        }

        .btn-join {
            background: #E8F5E9;
            color: #0A5C36;
            border: 1px solid #C8E6C9;
        }

        .btn-apply {
            background: #E3F2FD;
            color: #1565C0;
            border: 1px solid #BBDEFB;
        }

        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ================ NO DATA STATE ================ */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }

        .no-data i {
            font-size: 48px;
            margin-bottom: 16px;
            color: var(--light-gray);
        }

        .no-data h3 {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .no-data p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* ================ PAGINATION ================ */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 0 16px;
        }

        .pagination-link {
            padding: 6px 12px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            color: var(--text);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .pagination-link:hover {
            background: var(--light);
            border-color: var(--primary);
        }

        .pagination-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ================ SEARCH BAR ================ */
        .actions-bar {
            margin-bottom: 20px;
            padding: 12px 16px;
            background: var(--light);
            border-top: 1px solid var(--light-gray);
            border-bottom: 1px solid var(--light-gray);
        }

        .search-box {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        /* ================ CREATE BUTTON ================ */
        .create-button-container {
            padding: 0 16px 20px;
        }

        .btn-create {
            width: 100%;
            padding: 14px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-create:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* ================ FILTER BADGE ================ */
        .filter-info {
            padding: 0 16px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-light);
        }

        .filter-badge {
            background: var(--light);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            color: var(--primary);
            border: 1px solid var(--light-gray);
        }

        /* ================= RESPONSIVE ================= */
        @media (min-width: 480px) {
            .mobile-container {
                max-width: 480px;
                margin: 10px auto;
                box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
                border-radius: 20px;
                overflow: hidden;
            }

            .mobile-header {
                max-width: 480px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 0;
            }

            .bottom-nav {
                max-width: 480px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 20px 20px 0 0;
            }
        }

        @media (max-width: 350px) {
            .logo {
                font-size: 16px;
            }

            .logo-icon {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        /* ================ SWEETALERT2 CUSTOM STYLE ================ */
        .swal2-popup {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            border-radius: 12px !important;
        }

        .swal2-confirm {
            background: var(--gradient-primary) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            background: var(--light) !important;
            border: 1px solid var(--light-gray) !important;
            border-radius: 8px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            color: var(--text) !important;
        }

        /* ================ PAYMENT BUTTON ================ */
        .btn-pay {
            background: #2E7D32;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
            flex: 1;
            justify-content: center;
        }
        .btn-pay:hover {
            background: #1B5E20;
            transform: translateY(-1px);
        }
        .btn-pay small {
            display: block;
            font-size: 10px;
            margin-top: 2px;
        }

        /* ================= LOADING OVERLAY ================= */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            color: white;
            text-align: center;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid var(--secondary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .loading-text {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .loading-subtext {
            font-size: 12px;
            opacity: 0.8;
            max-width: 250px;
            line-height: 1.5;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Status badge untuk pembayaran */
        .badge-paid {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #C8E6C9;
        }
        .badge-pending-pay {
            background: #FFF3E0;
            color: #E65100;
            border: 1px solid #FFE0B2;
        }
        
        /* Status badge untuk play together */
        .badge-status-pending { background: #E3F2FD; color: #1976D2; border: 1px solid #BBDEFB; }
        .badge-status-active { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
        .badge-status-completed { background: #F3E5F5; color: #7B1FA2; border: 1px solid #E1BEE7; }
        .badge-status-canceled { background: #FFEBEE; color: #D32F2F; border: 1px solid #FFCDD2; }

        /* QR Modal */
        .qr-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .qr-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            position: relative;
            max-width: 300px;
            width: 90%;
        }

        .qr-close {
            position: absolute;
            top: 8px;
            right: 12px;
            cursor: pointer;
            font-size: 22px;
        }

        .btn-show-qr {
            margin-left: 10px;
            padding: 6px 12px;
            border-radius: 20px;
            border: none;
            background: rgba(52, 152, 219, .15);
            color: #3498db;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
@endpush


@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        {{-- Header - include dari layout terpisah --}}
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Main Bareng Saya</h1>
                <p class="page-subtitle">
                    Kelola main bareng yang Anda buat dan ikuti
                </p>
            </div>

            <!-- ====================== TABS NAVIGATION ====================== -->
            {{-- Tab untuk memfilter tampilan: dibuat, disetujui, pending --}}
            <div class="tabs">
                <a href="{{ route('buyer.main_bareng_saya.index', ['filter' => 'created']) }}" 
                   class="tab-btn {{ $filter === 'created' ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i> Saya Buat
                </a>
                <a href="{{ route('buyer.main_bareng_saya.index', ['filter' => 'joined']) }}" 
                   class="tab-btn {{ $filter === 'joined' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> Disetujui
                </a>
                <a href="{{ route('buyer.main_bareng_saya.index', ['filter' => 'pending']) }}" 
                   class="tab-btn {{ $filter === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Pending
                </a>
            </div>

            <!-- Filter Info -->
            <div class="filter-info">
                <i class="fas fa-filter"></i>
                Menampilkan: 
                <span class="filter-badge">
                    @if($filter === 'created')
                        Main Bareng yang Saya Buat
                    @elseif($filter === 'joined')
                        Main Bareng yang Saya Ikuti (Disetujui)
                    @else
                        Main Bareng yang Saya Ikuti (Pending)
                    @endif
                </span>
            </div>

            <!-- Actions Bar (Search & Create Button) -->
            <div class="actions-bar">
                {{-- Form pencarian --}}
                <form method="GET" action="{{ route('buyer.main_bareng_saya.index') }}" class="search-box">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <input type="text" 
                           class="search-input" 
                           name="search" 
                           placeholder="Cari berdasarkan lokasi atau venue..." 
                           value="{{ $search }}"
                           id="searchInput">
                </form>
                {{-- Tombol buat main bareng baru --}}
                <button class="btn-add" onclick="window.location.href='{{ route('buyer.venue.index') }}'">
                    <i class="fas fa-plus"></i> Buat Baru
                </button>
            </div>

            <!-- Card Container -->
            <div class="card-container" id="cardContainer">
                {{-- Looping data playTogethers dari controller --}}
                @forelse($playTogethers as $playTogether)
                    @php
                        $booking = $playTogether->booking;
                        $venue = $booking->venue ?? null;
                        $category = $venue->category ?? null;
                        $creator = $playTogether->creator;
                        
                        // Cek apakah user adalah host (pembuat)
                        $isHost = $playTogether->created_by == auth()->id();
                        
                        // Ambil informasi partisipasi user
                        $userParticipant = $playTogether->participants()
                            ->where('user_id', auth()->id())
                            ->first();
                        
                        // Hitung peserta yang sudah disetujui
                        $approvedParticipantsCount = $playTogether->participants()
                            ->where('approval_status', 'approved')
                            ->count();
                            
                        // Cek apakah tanggal main hari ini atau besok
                        $eventDate = \Carbon\Carbon::parse($playTogether->date);
                        $isToday = $eventDate->isToday();
                        $isTomorrow = $eventDate->isTomorrow();
                        $isCriticalTime = $isToday || $isTomorrow;

                        // Kondisi untuk menampilkan tombol "Sesuaikan Biaya" (khusus host)
                        $canAdjustPrice =
                            $isHost &&
                            in_array($playTogether->payment_scheme, ['participant', null]) &&
                            $approvedParticipantsCount < $playTogether->max_participants &&
                            $isCriticalTime &&
                            in_array($playTogether->status, ['pending', 'active']);
                    @endphp
                    
                    @if($venue && $category)
                        <div class="card" data-id="{{ $playTogether->id }}">
                            <!-- Card Image -->
                            <div class="card-image">
                                @if($venue->photo)
                                    <img src="{{ asset('storage/' . $venue->photo) }}" 
                                         alt="{{ $venue->venue_name }}" class="venue-image" />
                                @else
                                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                         alt="{{ $venue->venue_name }}" class="venue-image" />
                                @endif
                            </div>

                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-futbol"></i>
                                    {{ $venue->venue_name }}
                                </div>
                                <div class="card-badges">
                                    {{-- LABEL STATUS PLAY TOGETHER --}}
                                    <span class="badge badge-status-{{ $playTogether->status }}">
                                        {{ strtoupper($playTogether->status) }}
                                    </span>

                                    {{-- Badge HOST jika user adalah pembuat --}}
                                    @if($isHost)
                                        <span class="badge badge-host-yes">
                                            <i class="fas fa-crown"></i> HOST
                                        </span>
                                    @endif
                                    
                                    {{-- Badge status partisipasi user (jika bukan host) --}}
                                    @if($userParticipant && !$isHost)
                                        @if($userParticipant->approval_status === 'pending')
                                            <span class="badge" style="background: #FFF3E0; color: #E65100; border: 1px solid #FFE0B2;">
                                                <i class="fas fa-clock"></i> PENDING
                                            </span>
                                        @elseif($userParticipant->approval_status === 'approved')
                                            <span class="badge badge-active">
                                                <i class="fas fa-check"></i> APPROVED
                                            </span>
                                        @elseif($userParticipant->approval_status === 'rejected')
                                            <span class="badge badge-canceled">
                                                <i class="fas fa-times"></i> REJECTED
                                            </span>
                                        @endif
                                    @endif

                                    {{-- Badge Privacy (Public/Private) --}}
                                    <span class="badge badge-{{ $playTogether->privacy === 'public' ? 'public' : 'private' }}">
                                        <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i> 
                                        {{ strtoupper($playTogether->privacy) }}
                                    </span>
                                    
                                    {{-- Badge Tipe (Paid/Free) --}}
                                    <span class="badge badge-{{ $playTogether->type === 'paid' ? 'paid' : 'free' }}">
                                        <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i> 
                                        {{ strtoupper($playTogether->type) }}
                                    </span>
                                    
                                    {{-- Badge khusus jika hari ini atau besok --}}
                                    @if($isToday)
                                        <span class="badge badge-today">
                                            <i class="fas fa-calendar-day"></i> HARI INI
                                        </span>
                                    @elseif($isTomorrow)
                                        <span class="badge badge-upcoming">
                                            <i class="fas fa-calendar-alt"></i> BESOK
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- Tanggal main --}}
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Tanggal
                                    </div>
                                    <div class="card-value">
                                        {{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}
                                    </div>
                                </div>

                                {{-- Kategori lapangan --}}
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-tag"></i>
                                        Kategori
                                    </div>
                                    <div class="card-value">
                                        {{ $category->name ?? 'Tidak ada kategori' }}
                                    </div>
                                </div>

                                {{-- Jumlah peserta --}}
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-users"></i>
                                        Peserta
                                    </div>
                                    <div class="card-value">
                                        {{ $approvedParticipantsCount }} / {{ $playTogether->max_participants }} orang
                                    </div>
                                </div>

                                {{-- Perhitungan biaya untuk participant --}}
                                @php
                                    $lapangPerOrang = $playTogether->price_per_person ?? 0;
                                    $joinPerOrang = 0;

                                    if ($playTogether->type === 'paid') {
                                        $joinPerOrang = $playTogether->price_per_person_input 
                                            ?? $playTogether->join_price 
                                            ?? $lapangPerOrang;
                                    }

                                    $biayaPerOrang = $lapangPerOrang + $joinPerOrang;
                                @endphp

                                {{-- Tampilkan biaya jika user adalah participant yang sudah approved --}}
                                @if($userParticipant && $userParticipant->approval_status === 'approved')
                                    @php
                                        $showPayButton = $booking->shouldShowPayButtonFor(auth()->id());
                                        $lapangFee = $booking->getLapangPerPerson();
                                        $joinFee = $booking->getJoinFee();
                                        $biayaPerOrang = $lapangFee + $joinFee;
                                        $displayTotal = $booking->getDisplayTotalAmount();

                                        $payLabel = 'Bayar Bagian Saya';
                                        if($lapangFee > 0 && $joinFee > 0) {
                                            $payLabel = 'Bayar Lapang + Join';
                                        } elseif($lapangFee > 0) {
                                            $payLabel = 'Bayar Lapang';
                                        } elseif($joinFee > 0) {
                                            $payLabel = 'Bayar Join';
                                        }
                                    @endphp

                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-money-bill-wave"></i>
                                            Biaya
                                        </div>
                                        <div class="detail-value">
                                            @if($playTogether->type === 'paid')
                                                <span class="badge badge-cost">
                                                    Rp {{ number_format($playTogether->price_per_person, 0, ',', '.') }}/orang
                                                </span>
                                            @else
                                                <span class="badge badge-free">GRATIS</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Tampilkan host (jika user bukan host) --}}
                                @if(!$isHost)
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-user"></i>
                                        Host
                                    </div>
                                    <div class="card-value">
                                        {{ $creator->name ?? 'Tidak diketahui' }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <div class="action-buttons">
                                    {{-- Tombol Detail --}}
                                    <button class="btn-action btn-view" onclick="window.location.href='{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}'">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>

                                    {{-- TOMBOL SESUAIKAN BIAYA (HOST ONLY) --}}
                                    @if($canAdjustPrice && $playTogether->payment_type !== 'adjusted')
                                        <button class="btn-action" style="background:#FF9800;color:white;"
                                            onclick="recalculatePriceAlert({{ $playTogether->id }}, {{ (int) ($booking->amount ?? $booking->schedule->rental_price) }}, {{ (int) $playTogether->price_per_person }}, {{ $approvedParticipantsCount }}, {{ $playTogether->max_participants }})">
                                            <i class="fas fa-calculator"></i> Sesuaikan Biaya
                                        </button>
                                    @elseif($playTogether->payment_type === 'adjusted')
                                        <button class="btn-action" style="background:#BDBDBD;color:white;" disabled>
                                            <i class="fas fa-calculator"></i> Biaya Sudah Disesuaikan
                                        </button>
                                    @endif

                                    {{-- Tombol Bayar untuk participant --}}
                                    @if($userParticipant && $userParticipant->approval_status === 'approved')
                                        @php
                                            $showPayButton = $booking->shouldShowPayButtonFor(auth()->id());
                                            $remainingToPay = $booking->getParticipantPaymentAmount(auth()->id());
                                            
                                            $lapangFee = $booking->getLapangPerPerson();
                                            $joinFee = $booking->getJoinFee();
                                            
                                            $payLabel = 'Bayar Bagian Saya';
                                            if($lapangFee > 0 && $joinFee > 0) {
                                                $payLabel = 'Bayar Lapang + Join';
                                            } elseif($lapangFee > 0) {
                                                $payLabel = 'Bayar Lapang';
                                            } elseif($joinFee > 0) {
                                                $payLabel = 'Bayar Join';
                                            }
                                        @endphp

                                        @if($showPayButton && $remainingToPay > 0)
                                            <button class="btn-pay" onclick="payParticipant({{ $playTogether->id }})">
                                                <i class="fas fa-wallet"></i> {{ $payLabel }}
                                                <small style="display: block; font-size: 10px; margin-top: 2px;">
                                                    Rp {{ number_format($booking->getDisplayTotalAmount(), 0, ',', '.') }}
                                                </small>
                                            </button>
                                        @endif
                                    @endif

                                    {{-- Tombol Lihat QR Code (jika booking sudah lunas) --}}
                                    @php
                                        $canShowQr = $booking->booking_payment === 'full';
                                    @endphp

                                    @if($canShowQr)
                                        <a href="{{ route('buyer.main_bareng_saya.qr', $playTogether->id) }}" 
                                            class="btn-show-qr">
                                            <i class="fas fa-qrcode"></i> Lihat QR
                                        </a>
                                    @endif

                                    {{-- Tombol Edit dan Hapus (hanya untuk host) --}}
                                    @php
                                        // Cek apakah semua peserta sudah lunas
                                        $participants = $playTogether->participants()
                                            ->where('approval_status', 'approved')
                                            ->where('user_id', '!=', $playTogether->created_by)
                                            ->get();

                                        if($participants->isEmpty()) {
                                            $allPaid = false;
                                        } else {
                                            $pendingParticipants = $participants->filter(function($p){
                                                return $p->payment_status !== 'paid';
                                            })->count();
                                            $allPaid = $pendingParticipants === 0;
                                        }
                                    @endphp

                                    @if($isHost)
                                        @if(!$allPaid)
                                            <button class="btn-action btn-edit" onclick="window.location.href='{{ route('buyer.main_bareng_saya.edit', $playTogether->id) }}'">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn-action btn-delete" onclick="confirmDelete({{ $playTogether->id }}, '{{ $venue->venue_name }}')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        @else
                                            <button class="btn-action btn-edit" disabled title="Tidak bisa diedit, semua peserta sudah bayar">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn-action btn-delete" disabled title="Tidak bisa dihapus, semua peserta sudah bayar">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    {{-- Tampilan jika tidak ada data --}}
                    <div class="no-data">
                        <i class="fas fa-futbol"></i>
                        <h3>Tidak ada Main Bareng</h3>
                        <p>
                            @if($filter === 'created')
                                Anda belum membuat main bareng apapun
                            @elseif($filter === 'joined')
                                Anda belum mengikuti main bareng yang disetujui
                            @else
                                Anda tidak memiliki pengajuan main bareng yang pending
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($playTogethers->hasPages())
                <div class="pagination">
                    @if($playTogethers->onFirstPage())
                        <span class="pagination-link disabled">‹</span>
                    @else
                        <a href="{{ $playTogethers->appends(['filter' => $filter, 'search' => $search])->previousPageUrl() }}" class="pagination-link">‹</a>
                    @endif

                    @foreach(range(1, $playTogethers->lastPage()) as $page)
                        @if($page == $playTogethers->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $playTogethers->appends(['filter' => $filter, 'search' => $search])->url($page) }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($playTogethers->hasMorePages())
                        <a href="{{ $playTogethers->appends(['filter' => $filter, 'search' => $search])->nextPageUrl() }}" class="pagination-link">›</a>
                    @else
                        <span class="pagination-link disabled">›</span>
                    @endif
                </div>
            @endif

            <!-- QR Modal -->
            <div class="qr-modal" id="qrModal">
                <div class="qr-content">
                    <div id="qrCodeContainer" style="margin-top: 10px;"></div>
                    <p id="qrText" style="margin-top:15px;font-weight:600"></p>
                </div>
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
        
        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Confirm Delete Modal -->
        <div class="modal-overlay confirm-modal" id="confirmModal">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="confirm-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="confirm-text">
                        <h3 id="confirmTitle">Hapus Main Bareng</h3>
                        <p id="confirmMessage">Apakah Anda yakin ingin menghapus main bareng ini?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel" onclick="closeConfirmModal()">Batal</button>
                    <button class="btn-submit" id="confirmDeleteBtn" style="background: var(--danger);">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memasukkan file JavaScript untuk fungsi profil dari partial --}}
    @include('buyer.main_bareng_saya.partials.script')
    
    {{-- SweetAlert2 library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Midtrans Snap JS untuk pembayaran --}}
    @php
        $snapJsUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>

    <script>
        /**
         * FUNGSI RECALCULATE PRICE ALERT (UNTUK HOST)
         * Menampilkan konfirmasi SweetAlert sebelum host menyesuaikan biaya patungan
         * @param {number} id - ID play together
         * @param {number} lapangPrice - Harga lapangan total
         * @param {number} joinPrice - Biaya join per orang
         * @param {number} currentParticipants - Jumlah peserta saat ini
         * @param {number} maxParticipants - Maksimal peserta
         */
        function recalculatePriceAlert(
            id,
            lapangPrice,
            joinPrice,
            currentParticipants,
            maxParticipants
        ) {
            const totalTarget = lapangPrice + (joinPrice * currentParticipants);
            const newPrice = Math.ceil(totalTarget / currentParticipants);
       
            Swal.fire({
                title: 'Sesuaikan Biaya Patungan?',
                html: `
                    <div style="text-align:left;font-size:0.9rem">
                        <p>Peserta saat ini <b>${currentParticipants}/${maxParticipants}</b></p>
                        <div style="background:#f8f9fa;padding:10px;border-radius:8px;margin:10px 0">
                            <div>Lapang: <b>Rp ${lapangPrice.toLocaleString('id-ID')}</b></div>
                            <div>Join: <b>Rp ${joinPrice.toLocaleString('id-ID')}</b> × ${currentParticipants}</div>
                            <hr>
                            <div>Total: <b>Rp ${totalTarget.toLocaleString('id-ID')}</b></div>
                        </div>
                        <div>
                            Biaya baru per orang:
                            <h3 style="color:#2E7D32">
                                Rp ${newPrice.toLocaleString('id-ID')}
                            </h3>
                        </div>
                        <p style="color:#d32f2f;font-weight:600">
                            * Semua pembayaran peserta akan di-reset ke PENDING.
                            Peserta yang sudah bayar hanya membayar selisih.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2E7D32',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sesuaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(`/buyer/main-bareng-saya/${id}/recalculate-price`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', `Biaya baru: Rp ${data.new_price.toLocaleString('id-ID')}`, 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                    });
                }
            });
        }

        /**
         * FUNGSI PEMBAYARAN PESERTA (payParticipant)
         * Mengambil snap token dari server dan membuka popup pembayaran Midtrans
         * @param {number} playTogetherId - ID play together
         */
        function payParticipant(playTogetherId) {
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            fetch(`/buyer/main-bareng-saya/${playTogetherId}/get-snap-token`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                updatePaymentStatus(playTogetherId, result);
                            },
                            onPending: function(result) {
                                updatePaymentStatus(playTogetherId, result);
                            },
                            onError: function(result) {
                                console.error(result);
                                showToast('Pembayaran gagal. Silakan coba lagi.', 'error');
                                btn.disabled = false;
                                btn.innerHTML = originalContent;
                            },
                            onClose: function() {
                                showToast('Pembayaran dibatalkan.', 'warning');
                                btn.disabled = false;
                                btn.innerHTML = originalContent;
                            }
                        });
                    } else {
                        showToast(data.message || 'Gagal memproses pembayaran.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan sistem.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                });
        }

        /**
         * FUNGSI UPDATE STATUS PEMBAYARAN
         * Mengirim request ke server untuk memperbarui status pembayaran setelah transaksi
         * @param {number} playTogetherId - ID play together
         * @param {object} result - Hasil transaksi dari Midtrans
         */
        function updatePaymentStatus(playTogetherId, result) {
            fetch(`/buyer/main-bareng-saya/${playTogetherId}/update-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    status: result.transaction_status,
                    gross_amount: result.gross_amount || 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Status pembayaran diperbarui.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message || 'Gagal memperbarui pembayaran.', 'error');
                }
            })
            .catch(err => console.error('Status update failed:', err));
        }

        /**
         * FUNGSI TOAST NOTIFICATION
         * Menampilkan notifikasi sementara
         * @param {string} message - Pesan notifikasi
         * @param {string} type - Tipe notifikasi (success/error)
         */
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 
                         (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
            
            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    
    {{-- QR Code library --}}
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <script>
    /**
     * FUNGSI SHOW QR
     * Menampilkan modal dengan QR code untuk absensi peserta
     * @param {string} code - Kode yang akan di-generate menjadi QR
     */
    function showQr(code) {
        const qrModal = document.getElementById('qrModal');
        const qrContainer = document.getElementById('qrCodeContainer');
        const qrText = document.getElementById('qrText');

        qrModal.style.display = 'flex';
        qrContainer.innerHTML = ''; // Kosongkan sebelum generate QR baru
        qrText.innerText = code;

        new QRCode(qrContainer, {
            text: code,
            width: 200,
            height: 200
        });
    }

    // Tutup modal saat klik di area gelap (overlay)
    const qrModal = document.getElementById('qrModal');
    qrModal.addEventListener('click', function(e) {
        if (e.target === this) { // klik hanya di overlay
            qrModal.style.display = 'none';
            document.getElementById('qrCodeContainer').innerHTML = '';
        }
    });

    // Klik konten QR → jangan tutup modal (biarkan tetap terbuka)
    const qrContent = document.querySelector('.qr-content');
    qrContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    </script>

@endpush
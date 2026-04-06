@extends('layouts.main', ['title' => 'Main Bareng - SewaLap'])

@push('styles')
    <style>
        /* ================= VARIABLES ================= */
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

        /* ================= HEADER ================= */
        .mobile-container {
            width: 100%;
            min-height: 100vh;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            overflow-x: hidden;
        }

        .mobile-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--gradient-dark);
            z-index: 1100;
            box-shadow: var(--shadow-md);
        }

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
            padding: 120px 0 80px;
            min-height: 100vh;
        }


        /* ================ MAIN CONTENT STYLING FOR MAIN BARENG ================ */
        .page-header {
            margin-bottom: 24px;
            text-align: center;
            padding: 0 16px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.5;
        }

        /* ================ CARD CONTAINER ================ */
        .card-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 24px;
            padding: 0 16px;
        }

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

        .card-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

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

        .card-body {
            padding: 16px;
        }

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

        .card-footer {
            padding: 16px;
            background: var(--light);
            border-top: 1px solid var(--light-gray);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

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
    </style>
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Main Bareng</h1>
                <p class="page-subtitle">
                    Temukan dan bergabung dengan sesi main bareng yang akan datang
                </p>
            </div>

            <!-- Filter Info -->
            <div class="filter-info">
                <i class="fas fa-filter"></i>
                Menampilkan Main Bareng dari:
                <span class="filter-badge">{{ \Carbon\Carbon::today()->translatedFormat('d M Y') }} - seterusnya</span>
            </div>

            <!-- Create Main Bareng Button -->
            @auth
                @if(auth()->user()->role === 'buyer')
                    <div class="create-button-container">
                        <button class="btn-create" onclick="window.location.href='{{ route('buyer.main_bareng_saya.index') }}'">
                            <i class="fas fa-circle"></i>Main Bareng Saya
                        </button>
                    </div>
                @endif
            @else
                <div class="create-button-container">
                    <button class="btn-create" onclick="window.location.href='{{ route('login') }}'">
                        <i class="fas fa-circle"></i>Login untuk Main Bareng Saya
                    </button>
                </div>
            @endauth

            <!-- Search Bar -->
            <div class="actions-bar">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <form method="GET" action="{{ auth()->check() && auth()->user()->role === 'buyer' ? route('buyer.main_bareng.index') : route('guest.main_bareng.index') }}" id="searchForm">
                        <input type="text" class="search-input" name="search" placeholder="Cari main bareng..."
                            id="searchMainBareng" value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            <!-- Card Container -->
            <div class="card-container">
                @forelse($playTogethers as $playTogether)
                    @php
                        $booking = $playTogether->booking;
                        $venue = $booking->venue ?? null;
                        $category = $venue->category ?? null;
                        $creator = $playTogether->creator;

                        // Hitung peserta yang sudah approved
                        $approvedParticipants = $playTogether->participants()
                            ->where('approval_status', 'approved')
                            ->count();

                        // Cek apakah tanggal hari ini atau besok
                        $eventDate = \Carbon\Carbon::parse($playTogether->date);
                        $today = \Carbon\Carbon::today();
                        $tomorrow = \Carbon\Carbon::tomorrow();

                        $isToday = $eventDate->isToday();
                        $isTomorrow = $eventDate->isTomorrow();
                    @endphp

                    @if($venue && $category)
                        <div class="card">
                            <!-- Card Image -->
                            <div class="card-image">
                                <img src="{{ $venue->photo ? asset('storage/' . $venue->photo) : 'https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}"
                                    alt="{{ $venue->venue_name }}" class="venue-image" />
                            </div>

                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-futbol"></i>
                                    {{ $venue->venue_name }}
                                </div>
                                <div class="card-badges">
                                    <span class="badge badge-{{ $playTogether->privacy === 'public' ? 'public' : 'private' }}">
                                        <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i>
                                        {{ strtoupper($playTogether->privacy) }}
                                    </span>
                                    <span class="badge badge-{{ $playTogether->type === 'paid' ? 'paid' : 'free' }}">
                                        <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i>
                                        {{ strtoupper($playTogether->type) }}
                                    </span>
                                    <span class="badge badge-{{ $playTogether->status }}">
                                        {{ strtoupper($playTogether->status) }}
                                    </span>
                                    @if($isToday)
                                        <span class="badge badge-today">
                                            <i class="fas fa-calendar-day"></i> HARI INI
                                        </span>
                                    @elseif($isTomorrow)
                                        <span class="badge badge-upcoming">
                                            <i class="fas fa-calendar-alt"></i> BESOK
                                        </span>
                                    @endif

                                    @auth
                                        @php
                                            $currentUserParticipant = $playTogether->participants
                                                ->where('user_id', auth()->id())
                                                ->where('approval_status', 'approved')
                                                ->first();
                                        @endphp
                                        @if($currentUserParticipant)
                                            @if($currentUserParticipant->payment_status === 'paid')
                                                <span class="badge badge-active">
                                                    <i class="fas fa-check-circle"></i> LUNAS
                                                </span>
                                            @elseif($currentUserParticipant->payment_status === 'pending' && $playTogether->type === 'paid')
                                                <span class="badge badge-pending">
                                                    <i class="fas fa-clock"></i> BELUM LUNAS
                                                </span>
                                            @endif
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Tanggal
                                    </div>
                                    <div class="card-value">
                                        {{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}
                                        @if($isToday)
                                            <span class="badge badge-today" style="margin-left: 8px;">Hari Ini</span>
                                        @elseif($isTomorrow)
                                            <span class="badge badge-upcoming" style="margin-left: 8px;">Besok</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-tag"></i>
                                        Kategori
                                    </div>
                                    <div class="card-value">
                                        {{ $category->name ?? 'Tidak ada kategori' }}
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-users"></i>
                                        Peserta
                                    </div>
                                    <div class="card-value">
                                        {{ $approvedParticipants }} / {{ $playTogether->max_participants }} orang
                                    </div>
                                </div>

                                @php
                                    $lapangPerOrang = $playTogether->price_per_person ?? 0;
                                    $joinPerOrang = 0;

                                    if ($playTogether->type === 'paid') {
                                        // fallback: join = lapang kalau join belum disimpan
                                        $joinPerOrang = $playTogether->price_per_person_input 
                                            ?? $playTogether->join_price 
                                            ?? $lapangPerOrang;
                                    }

                                    $biayaPerOrang = $lapangPerOrang + $joinPerOrang;
                                @endphp

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Biaya
                                    </div>
                                    <div class="card-value">
                                        @if($biayaPerOrang > 0)
                                            <span class="badge badge-cost">
                                                Rp {{ number_format($biayaPerOrang, 0, ',', '.') }} / orang
                                            </span>
                                        @else
                                            <span class="badge badge-free">GRATIS</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-venus-mars"></i>
                                        Jenis Kelamin
                                    </div>
                                    <div class="card-value">
                                        <span class="badge badge-gender">
                                            @if($playTogether->gender === 'male')
                                                <i class="fas fa-mars"></i> Laki-laki
                                            @elseif($playTogether->gender === 'female')
                                                <i class="fas fa-venus"></i> Perempuan
                                            @else
                                                <i class="fas fa-venus-mars"></i> Campur
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-user"></i>
                                        Host
                                    </div>
                                    <div class="card-value">
                                        {{ $creator->name ?? 'Tidak diketahui' }}
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-user-check"></i>
                                        Persetujuan Host
                                    </div>
                                    <div class="card-value">
                                        @if($playTogether->host_approval)
                                            <span class="badge badge-host-yes">
                                                <i class="fas fa-user-check"></i> Diperlukan
                                            </span>
                                        @else
                                            <span class="badge badge-host-no">
                                                <i class="fas fa-bolt"></i> Auto Join
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="action-buttons">
                                    @php
                                        $detailRoute = auth()->check() && auth()->user()->role === 'buyer' 
                                            ? route('buyer.main_bareng.show', $playTogether->id) 
                                            : route('guest.main_bareng.show', $playTogether->id);
                                        
                                        // Check if user is participant and approved
                                        $userParticipant = null;
                                        $showPayButton = false;
                                        $remainingToPay = 0;
                                        
                                        if (auth()->check()) {
                                            $userParticipant = $playTogether->participants
                                                ->where('user_id', auth()->id())
                                                ->where('approval_status', 'approved')
                                                ->first();
                                            
                                            if ($userParticipant) {
                                                $showPayButton = $booking->shouldShowPayButtonFor(auth()->id());
                                                $remainingToPay = $booking->getParticipantPaymentAmount(auth()->id());
                                            }
                                        }
                                    @endphp
                                    <button class="btn-action btn-view" onclick="window.location.href='{{ $detailRoute }}'">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                    
                                    @if($showPayButton)
                                        <button class="btn-pay" onclick="payParticipant({{ $playTogether->id }})">
                                            <i class="fas fa-wallet"></i> Bayar
                                            @if($remainingToPay > 0)
                                                <small>{{ number_format($biayaPerOrang, 0, ',', '.') }}</small>
                                            @endif
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="no-data">
                        <i class="fas fa-futbol"></i>
                        <h3>Tidak ada Main Bareng</h3>
                        <p>Belum ada Main Bareng dengan tanggal {{ \Carbon\Carbon::today()->translatedFormat('d M Y') }} atau
                            setelahnya.</p>
                        <p style="font-size: 12px; color: var(--text-light); justify-content: center;">
                            <span class="fas fa-info-circle"></span> Hanya menampilkan Main Bareng yang akan datang
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
                        <a href="{{ $playTogethers->previousPageUrl() }}" class="pagination-link">‹</a>
                    @endif

                    @foreach(range(1, $playTogethers->lastPage()) as $page)
                        @if($page == $playTogethers->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $playTogethers->url($page) }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($playTogethers->hasMorePages())
                        <a href="{{ $playTogethers->nextPageUrl() }}" class="pagination-link">›</a>
                    @else
                        <span class="pagination-link disabled">›</span>
                    @endif
                </div>
            @endif
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')

        <!-- Toast Notification Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="loading-spinner"></div>
            <div class="loading-text" id="loadingText">Memproses...</div>
            <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- MIDTRANS SNAP JS --}}
    @php
        $setting = \App\Models\Setting::first();
        $isProduction = $setting->midtrans_is_production ?? false;
        $snapJsUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $midtransClientKey = $setting->midtrans_client_key ?? '';
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>

    <script>
        // Search function dengan delay untuk performa
        let searchTimeout;
        const searchInput = document.getElementById('searchMainBareng');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        }

        /**
         * Participant Payment Logic
         */
        function payParticipant(playTogetherId) {
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            
            showLoading('Mendapatkan data pembayaran...');

            fetch(`/buyer/main-bareng-saya/${playTogetherId}/get-snap-token`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
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
                            },
                            onClose: function() {
                                showToast('Pembayaran dibatalkan.', 'warning');
                            }
                        });
                    } else {
                        showToast(data.message || 'Gagal memproses pembayaran.', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan sistem.', 'error');
                });
        }

        function updatePaymentStatus(playTogetherId, result) {
            showLoading('Sinkronisasi status pembayaran...');

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
                hideLoading();
                if (data.success) {
                    showToast('Status pembayaran diperbarui.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message || 'Gagal memperbarui pembayaran.', 'error');
                }
            })
            .catch(err => {
                hideLoading();
                console.error('Status update failed:', err);
                showToast('Gagal memperbarui status pembayaran.', 'error');
            });
        }

        // Toast notification
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

            toast.innerHTML = `
                    <i class="fas ${icon}"></i>
                    <div>${message}</div>
                `;

            container.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                toast.style.animation = 'toastSlide 0.3s reverse';
                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }, duration);
        }

        // Header search function
        function handleSearch() {
            const searchTerm = document.querySelector('.mobile-header .search-input');
            if (searchTerm && searchTerm.value.trim()) {
                showToast(`Mencari: ${searchTerm.value}`, 'info');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Add enter key support for header search
            const headerSearchInput = document.querySelector('.mobile-header .search-input');
            if (headerSearchInput) {
                headerSearchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        handleSearch();
                    }
                });
            }
        });

        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            if (overlay && text) {
                text.textContent = message;
                overlay.style.display = 'flex';
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }
    </script>
@endpush
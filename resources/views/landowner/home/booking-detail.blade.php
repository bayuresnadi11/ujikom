@extends('layouts.main', ['title' => 'Detail Booking'])

@push('styles')
    @include('landowner.home.partials.home-style')
    <style>
        .detail-page-header {
            background: var(--gradient-dark);
            color: white;
            padding: 24px 20px;
            padding-top: 90px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 5px;
        }

        .btn-back-white {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back-white:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-3px);
            color: white;
        }

        .header-title-large {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .detail-content {
            padding: 0 20px 40px;
            margin-top: -20px;
        }

        /* Hero Status Card */
        .status-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            margin-bottom: 24px;
            position: relative;
            z-index: 10;
        }

        .status-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .status-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .status-subtitle {
            font-size: 13px;
            color: var(--text-light);
            background: var(--light);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Status Colors */
        .status-wrapper-upcoming .status-icon-wrapper {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
        }
        .status-wrapper-upcoming .status-title { color: #2980b9; }

        .status-wrapper-active .status-icon-wrapper {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }
        .status-wrapper-active .status-title { color: var(--primary); }

        .status-wrapper-completed .status-icon-wrapper {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            box-shadow: 0 10px 20px rgba(127, 140, 141, 0.3);
        }
        .status-wrapper-completed .status-title { color: #7f8c8d; }

        /* Detail Sections */
        .detail-section {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed var(--light-gray);
        }

        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 14px;
        }

        .section-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        /* Info Rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 14px;
            color: var(--text-light);
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            text-align: right;
        }

        .info-value.highlight {
            color: var(--primary);
            font-size: 16px;
            font-weight: 800;
        }

        /* User Profile */
        .user-card {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .user-img {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            object-fit: cover;
            background: var(--light);
        }

        .user-details h4 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 2px 0;
            color: var(--text);
        }

        .user-details p {
            font-size: 13px;
            color: var(--text-light);
            margin: 0;
        }

        .btn-wa {
            background: #25D366;
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            border: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.2);
            transition: all 0.3s ease;
        }

        .btn-wa:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
            background: #20BD5A;
            color: white;
        }

    </style>
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header Cashier Style -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.home') }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <main class="main-content">
            @php
                $jadwal = $booking->schedule;
                $venue = $jadwal->section->venue;
                $user = $booking->user;
                
                // Status Logic
                $isToday = \Carbon\Carbon::parse($jadwal->date)->isToday();
                $isTomorrow = \Carbon\Carbon::parse($jadwal->date)->isTomorrow();
                $isPast = \Carbon\Carbon::parse($jadwal->date)->isPast() && !$isToday;

                $statusText = 'Berlangsung';
                $wrapperClass = 'status-wrapper-active';
                $icon = 'fas fa-check-circle';

                if ($isPast) {
                     $statusText = 'Selesai';
                     $wrapperClass = 'status-wrapper-completed';
                     $icon = 'fas fa-flag-checkered';
                } elseif ($isToday) {
                     $statusText = 'Berlangsung';
                     $wrapperClass = 'status-wrapper-active';
                     $icon = 'fas fa-check-circle';
                } elseif ($isTomorrow) {
                     $statusText = 'Segera';
                     $wrapperClass = 'status-wrapper-upcoming';
                     $icon = 'fas fa-hourglass-half';
                } else {
                     // Future
                     $statusText = 'Segera';
                     $wrapperClass = 'status-wrapper-upcoming';
                     $icon = 'fas fa-hourglass-half';
                }
            @endphp

            <!-- Page Header -->
            <section class="section-title" style="margin-top: 0; margin-bottom: 20px; border: none; padding-bottom: 0;">
                <div>
                    <h1 style="font-size: 24px; font-weight: 800; margin: 0; color: var(--text);">Detail Booking</h1>
                    <p style="font-size: 14px; color: var(--text-light); margin: 5px 0 0 0;">
                        #{{ $booking->id }} • {{ \Carbon\Carbon::parse($jadwal->date)->translatedFormat('d F Y') }}
                    </p>
                </div>
            </section>

            <div class="detail-content" style="padding: 0 20px 40px; margin-top: 0;">
            <!-- Status Card -->
            <div class="status-card {{ $wrapperClass }}" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 25px; text-align: left;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="status-icon-wrapper" style="width: 50px; height: 50px; margin: 0; font-size: 24px;">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div>
                        <div class="status-title" style="font-size: 18px; margin-bottom: 2px;">{{ $statusText }}</div>
                        <div class="status-subtitle" style="font-size: 12px;">Kode Booking: #{{ $booking->id }}</div>
                    </div>
                </div>
            </div>

                <!-- User Info -->
                <div class="detail-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-user"></i></div>
                        <div class="section-name">Informasi Penyewa</div>
                    </div>
                    <div class="user-card" style="margin-bottom: 5px;">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" class="user-img" alt="{{ $user->name }}">
                        @else
                            <div class="user-img" style="display: flex; align-items: center; justify-content: center; font-size: 20px; color: #aaa;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div class="user-details">
                            <h4 style="font-size: 18px;">{{ $user->name }}</h4>
                            <p style="font-size: 14px; margin-bottom: 2px;">{{ $user->email }}</p>
                            <p style="font-size: 14px; color: var(--text-light);"><i class="fas fa-phone-alt" style="font-size: 12px; margin-right: 5px;"></i> {{ $user->phone_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Venue Info -->
                <div class="detail-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-map-marked-alt"></i></div>
                        <div class="section-name">Detail Lapangan</div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Venue</span>
                        <span class="info-value">{{ $venue->venue_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Lapangan</span>
                        <span class="info-value">{{ $jadwal->section->section_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($jadwal->date)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jam</span>
                        <span class="info-value">
                            {{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Durasi</span>
                        <span class="info-value">{{ $jadwal->rental_duration }} Jam</span>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="detail-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-receipt"></i></div>
                        <div class="section-name">Pembayaran</div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Harga</span>
                        <span class="info-value highlight">Rp {{ number_format($jadwal->rental_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Metode</span>
                        <span class="info-value">Transfer Bank</span>
                    </div>
                    <div class="info-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #eee;">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span style="background: rgba(46, 204, 113, 0.15); color: #27ae60; padding: 6px 14px; border-radius: 12px; font-weight: 700; font-size: 12px;">
                                LUNAS
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('layouts.bottom-nav')

    @endsection

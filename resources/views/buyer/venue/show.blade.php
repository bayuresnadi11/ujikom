{{--
=============================================================================
VIEW: DETAIL VENUE
Halaman untuk menampilkan detail lengkap dari sebuah venue/lapangan
Menampilkan informasi venue, daftar lapangan (section), dan jadwal yang tersedia
=============================================================================
--}}

{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'Detail Venue'])

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling venue dari partial --}}
    @include('buyer.venue.partials.venue-style')
@endpush

{{-- Section konten utama --}}
@section('content')
    {{-- CSRF token untuk keamanan AJAX request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="mobile-container">
        <!-- ====================== HEADER ====================== -->
        {{-- Header sederhana dengan judul halaman --}}
        <header class="mobile-header">
            <div class="header-top">
                <h2 class="header-title">Detail Venue</h2>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content" style="padding-top: 45px;">
            <!-- ====================== VENUE HERO IMAGE ====================== -->
            {{-- Gambar utama venue dengan overlay informasi --}}
            <div class="venue-detail-image">
                <img src="{{ asset('storage/' . $venue->photo) }}" alt="{{ $venue->venue_name }}">
                <div class="venue-detail-overlay">
                    {{-- Badge kategori venue --}}
                    <div class="venue-category-badge">
                        <i class="fas fa-tag"></i>
                        {{ $venue->category->category_name ?? 'N/A' }}
                    </div>
                    {{-- Badge rating (jika ada) --}}
                    @if($venue->rating)
                        <div class="venue-rating-large-show">
                            <i class="fas fa-star"></i>
                            {{ number_format($venue->rating, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- ====================== VENUE INFORMATION ====================== -->
            {{-- Section informasi utama venue --}}
            <section class="venue-info-section">
                {{-- Nama venue --}}
                <h1 class="venue-detail-title">{{ $venue->venue_name }}</h1>
                
                {{-- Lokasi venue --}}
                <div class="venue-detail-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $venue->location }}</span>
                </div>
                
                {{-- Pemilik venue --}}
                <div class="venue-detail-owner">
                    <i class="fas fa-user-circle"></i>
                    <span>Pemilik: {{ $venue->creator->name ?? 'N/A' }}</span>
                </div>

                {{-- Deskripsi venue --}}
                <div class="venue-description-box">
                    <h3><i class="fas fa-info-circle"></i> Deskripsi</h3>
                    <p>{{ $venue->description }}</p>
                </div>
            </section>

            <!-- ====================== DAFTAR LAPANGAN (SECTIONS) ====================== -->
            <section class="venue-sections-section">
                {{-- Judul section dengan jumlah lapangan --}}
                <h3 class="section-heading">
                    <i class="fas fa-layer-group"></i>
                    Daftar Lapangan Tersedia ({{ $venue->venueSections->count() }})
                </h3>
                
                @if($venue->venueSections->count() > 0)
                    <div class="sections-list">
                        {{-- Looping semua section (lapangan) dalam venue --}}
                        @foreach($venue->venueSections as $section)
                            <div class="section-item">
                                {{-- Header section: nama lapangan dan harga per jam --}}
                                <div class="section-item-header">
                                    <h4>{{ $section->section_name }}</h4>
                                    <span class="section-price">
                                        Rp {{ number_format($section->price_per_hour) }}/jam
                                    </span>
                                </div>
                                {{-- Deskripsi lapangan --}}
                                <p class="section-item-desc">{{ $section->description }}</p>
                                
                                <!-- ====================== JADWAL TERSEDIA ====================== -->
                                <div class="section-schedules-container">
                                    <h5 class="section-schedules-title">
                                        <i class="fas fa-calendar-alt"></i>
                                        Jadwal Tersedia ({{ $section->venueSchedules()->where('available', true)->where('date', '>=', now()->toDateString())->count() }})
                                    </h5>
                                    
                                    @php
                                        // Ambil maksimal 3 jadwal yang tersedia (tanggal dari hari ini ke depan)
                                        $availableSchedules = $section->venueSchedules()
                                            ->where('available', true)
                                            ->where('date', '>=', now()->toDateString())
                                            ->orderBy('date', 'asc')
                                            ->orderBy('start_time', 'asc')
                                            ->limit(3)
                                            ->get();
                                    @endphp
                                    
                                    @if($availableSchedules->count() > 0)
                                        {{-- Tampilkan jadwal dalam bentuk mini cards --}}
                                        <div class="schedule-mini-cards">
                                            @foreach($availableSchedules as $schedule)
                                                <div class="schedule-mini-card">
                                                    {{-- Tanggal jadwal --}}
                                                    <div class="schedule-mini-date">
                                                        <div class="mini-date-day">{{ \Carbon\Carbon::parse($schedule->date)->format('d') }}</div>
                                                        <div class="mini-date-month">{{ \Carbon\Carbon::parse($schedule->date)->format('M') }}</div>
                                                    </div>
                                                    {{-- Detail jadwal: waktu dan harga --}}
                                                    <div class="schedule-mini-details">
                                                        <div class="schedule-mini-time">
                                                            <i class="far fa-clock"></i>
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                        </div>
                                                        <div class="schedule-mini-price">
                                                            Rp {{ number_format($schedule->rental_price, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        {{-- Tombol booking (berbeda untuk guest dan user login) --}}
                                        <div class="section-item-footer">
                                            @guest
                                                {{-- Guest: arahkan ke halaman login --}}
                                                <a href="{{ route('login') }}" 
                                                   class="btn-booking-section">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                    Login untuk Booking
                                                </a>
                                            @else
                                                {{-- User login: arahkan ke halaman create booking --}}
                                                <a href="{{ route('buyer.booking.create', ['venue_id' => $venue->id, 'section_id' => $section->id]) }}" 
                                                   class="btn-booking-section">
                                                    <i class="fas fa-calendar-plus"></i>
                                                    Booking Sekarang
                                                </a>
                                            @endguest
                                        </div>
                                    @else
                                        {{-- Tidak ada jadwal tersedia --}}
                                        <div class="empty-schedule-notice">
                                            <i class="fas fa-calendar-times"></i>
                                            <span>Belum ada jadwal tersedia</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Tidak ada lapangan dalam venue --}}
                    <div class="empty-state-small">
                        <i class="fas fa-layer-group"></i>
                        <p>Belum ada lapangan tersedia</p>
                    </div>
                @endif
            </section>

            <!-- ====================== TOMBOL KEMBALI ====================== -->
            <div class="venue-action-buttons">
                <button class="btn-back" onclick="window.location.href='{{ route('buyer.venue.index') }}'">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </button>
            </div>
        </main>

        {{-- Bottom Navigation Bar --}}
        @include('layouts.bottom-nav')
    </div>
@endsection

{{-- Section untuk menambahkan JavaScript khusus halaman ini --}}
@push('scripts')
    {{-- Memasukkan file JavaScript untuk fungsi venue dari partial --}}
    @include('buyer.venue.partials.venue-script')
    
    {{-- CSS tambahan untuk styling komponen di halaman detail --}}
    <style>
    /* Container jadwal dalam section */
    .section-schedules-container {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }
    
    /* Judul jadwal section */
    .section-schedules-title {
        font-size: 14px;
        color: var(--text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    
    /* Container kartu jadwal mini */
    .schedule-mini-cards {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    /* Kartu jadwal mini individual */
    .schedule-mini-card {
        background: rgba(39, 174, 96, 0.05);
        border: 1px solid rgba(39, 174, 96, 0.2);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    /* Bagian tanggal pada kartu jadwal mini */
    .schedule-mini-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--primary);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 6px;
        flex-shrink: 0;
    }
    
    .mini-date-day {
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
    }
    
    .mini-date-month {
        font-size: 11px;
        text-transform: uppercase;
        margin-top: 2px;
    }
    
    /* Detail jadwal */
    .schedule-mini-details {
        flex: 1;
    }
    
    .schedule-mini-time {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .schedule-mini-price {
        font-size: 14px;
        color: var(--primary);
        font-weight: 700;
    }
    
    /* Notifikasi tidak ada jadwal */
    .empty-schedule-notice {
        text-align: center;
        padding: 20px;
        background: rgba(241, 196, 15, 0.1);
        border-radius: 8px;
        color: var(--warning);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .empty-schedule-notice i {
        font-size: 24px;
    }
    
    /* Tombol booking section */
    .btn-booking-section {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        text-decoration: none;
    }
    
    /* Efek hover tombol booking */
    .btn-booking-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
    }
    
    /* Footer section item */
    .section-item-footer {
        margin-top: 12px;
    }
    </style>
@endpush
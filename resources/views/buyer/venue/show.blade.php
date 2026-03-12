@extends('layouts.main', ['title' => 'Detail Venue'])

@push('styles')
    @include('buyer.venue.partials.venue-style')
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="mobile-container">
        <header class="mobile-header">
            <div class="header-top">
                <h2 class="header-title">Detail Venue</h2>
            </div>
        </header>

        <main class="main-content" style="padding-top: 45px;">
            <div class="venue-detail-image">
                <img src="{{ asset('storage/' . $venue->photo) }}" alt="{{ $venue->venue_name }}">
                <div class="venue-detail-overlay">
                    <div class="venue-category-badge">
                        <i class="fas fa-tag"></i>
                        {{ $venue->category->category_name ?? 'N/A' }}
                    </div>
                    @if($venue->rating)
                        <div class="venue-rating-large-show">
                            <i class="fas fa-star"></i>
                            {{ number_format($venue->rating, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <section class="venue-info-section">
                <h1 class="venue-detail-title">{{ $venue->venue_name }}</h1>
                <div class="venue-detail-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $venue->location }}</span>
                </div>
                
                <div class="venue-detail-owner">
                    <i class="fas fa-user-circle"></i>
                    <span>Pemilik: {{ $venue->creator->name ?? 'N/A' }}</span>
                </div>

                <div class="venue-description-box">
                    <h3><i class="fas fa-info-circle"></i> Deskripsi</h3>
                    <p>{{ $venue->description }}</p>
                </div>
            </section>

            <section class="venue-sections-section">
                <h3 class="section-heading">
                    <i class="fas fa-layer-group"></i>
                    Daftar Lapangan Tersedia ({{ $venue->venueSections->count() }})
                </h3>
                
                @if($venue->venueSections->count() > 0)
                    <div class="sections-list">
                        @foreach($venue->venueSections as $section)
                            <div class="section-item">
                                <div class="section-item-header">
                                    <h4>{{ $section->section_name }}</h4>
                                    <span class="section-price">
                                        Rp {{ number_format($section->price_per_hour) }}/jam
                                    </span>
                                </div>
                                <p class="section-item-desc">{{ $section->description }}</p>
                                
                                <div class="section-schedules-container">
                                    <h5 class="section-schedules-title">
                                        <i class="fas fa-calendar-alt"></i>
                                        Jadwal Tersedia ({{ $section->venueSchedules()->where('available', true)->count() }})
                                    </h5>
                                    
                                    @php
                                        $availableSchedules = $section->venueSchedules()
                                            ->where('available', true)
                                            ->where('date', '>=', now()->toDateString())
                                            ->orderBy('date', 'asc')
                                            ->orderBy('start_time', 'asc')
                                            ->limit(3)
                                            ->get();
                                    @endphp
                                    
                                    @if($availableSchedules->count() > 0)
                                        <div class="schedule-mini-cards">
                                            @foreach($availableSchedules as $schedule)
                                                <div class="schedule-mini-card">
                                                    <div class="schedule-mini-date">
                                                        <div class="mini-date-day">{{ \Carbon\Carbon::parse($schedule->date)->format('d') }}</div>
                                                        <div class="mini-date-month">{{ \Carbon\Carbon::parse($schedule->date)->format('M') }}</div>
                                                    </div>
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
                                        
                                        <div class="section-item-footer">
                                            @guest
                                            <a href="{{ route('login') }}" 
                                               class="btn-booking-section">
                                                <i class="fas fa-sign-in-alt"></i>
                                                Login untuk Booking
                                            </a>
                                            @else
                                            <a href="{{ route('buyer.booking.create', ['venue_id' => $venue->id, 'section_id' => $section->id]) }}" 
                                               class="btn-booking-section">
                                                <i class="fas fa-calendar-plus"></i>
                                                Booking Sekarang
                                            </a>
                                            @endguest
                                        </div>
                                    @else
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
                    <div class="empty-state-small">
                        <i class="fas fa-layer-group"></i>
                        <p>Belum ada lapangan tersedia</p>
                    </div>
                @endif
            </section>

            <div class="venue-action-buttons">
                <button class="btn-back" onclick="window.location.href='{{ route('buyer.venue.index') }}'">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </button>
            </div>
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('buyer.venue.partials.venue-script')
    
    <style>
    .section-schedules-container {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }
    
    .section-schedules-title {
        font-size: 14px;
        color: var(--text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }
    
    .schedule-mini-cards {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .schedule-mini-card {
        background: rgba(39, 174, 96, 0.05);
        border: 1px solid rgba(39, 174, 96, 0.2);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
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
    
    .btn-booking-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
    }
    
    .section-item-footer {
        margin-top: 12px;
    }
    </style>
@endpush
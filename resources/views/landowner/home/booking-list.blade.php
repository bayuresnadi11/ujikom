@extends('layouts.main', ['title' => 'Daftar Booking - SewaLap'])

@push('styles')
    @include('landowner.home.partials.home-style')
    <style>
        .page-header {
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .page-subtitle {
            font-size: 14px;
            color: var(--text-light);
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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="section-title" style="margin-top: 0; margin-bottom: 24px; border: none; padding-bottom: 0;">
                <div>
                    <h1 style="font-size: 24px; font-weight: 800; margin: 0; color: var(--text);">Daftar Booking</h1>
                    <p style="font-size: 14px; color: var(--text-light); margin: 5px 0 0 0;">
                        Lihat semua riwayat pemesanan
                    </p>
                </div>
            </section>

            <!-- Booking List -->
            <div class="bookings-container" style="padding: 0 20px;">
                @if($bookings->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-3x"></i>
                        <h3>Belum Ada Booking</h3>
                        <p>Belum ada riwayat booking yang tercatat.</p>
                        <button class="btn-primary" onclick="window.location.href='{{ route('landowner.home') }}'">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                    </div>
                @else
                    <div class="bookings-list">
                        @foreach($bookings as $jadwal)
                            @php
                                // Tentukan ikon berdasarkan jenis olahraga
                                $sportIcon = 'fas fa-futbol'; 
                                $sportClass = 'sport-futsal';

                                if ($jadwal->section && $jadwal->section->venue) {
                                    $jenisOlahraga = strtolower($jadwal->section->venue->category->name ?? $jadwal->section->venue->jenis_olahraga ?? '');

                                    if (str_contains($jenisOlahraga, 'badminton')) {
                                        $sportIcon = 'fas fa-table-tennis';
                                        $sportClass = 'sport-badminton';
                                    } elseif (str_contains($jenisOlahraga, 'basket')) {
                                        $sportIcon = 'fas fa-basketball-ball';
                                        $sportClass = 'sport-basket';
                                    } elseif (str_contains($jenisOlahraga, 'tennis')) {
                                        $sportIcon = 'fas fa-tennis-ball';
                                        $sportClass = 'sport-tennis';
                                    } elseif (str_contains($jenisOlahraga, 'voli')) {
                                        $sportIcon = 'fas fa-volleyball-ball';
                                        $sportClass = 'sport-voli';
                                    }
                                }

                                // Format tanggal & jam
                                $tanggal = \Carbon\Carbon::parse($jadwal->date)->translatedFormat('d M Y');
                                $jamMulai = \Carbon\Carbon::parse($jadwal->start_time)->format('H:i');
                                $jamSelesai = \Carbon\Carbon::parse($jadwal->end_time)->format('H:i');

                                // Ambil booking pertama
                                $booking = $jadwal->bookings->first();

                                // Nama pengguna
                                $userName = optional(optional($booking)->user)->name ?? 'Guest';

                                // Nama venue/lapangan
                                $venueName = $jadwal->section && $jadwal->section->venue
                                    ? $jadwal->section->venue->venue_name
                                    : 'Venue';
                                
                                $sectionName = $jadwal->section
                                    ? $jadwal->section->section_name
                                    : 'Lapangan';

                                // Status booking LOGIC
                                $isToday = \Carbon\Carbon::parse($jadwal->date)->isToday();
                                $isTomorrow = \Carbon\Carbon::parse($jadwal->date)->isTomorrow();
                                $isPast = \Carbon\Carbon::parse($jadwal->date)->isPast() && !$isToday;

                                $statusClass = 'status-confirmed'; // Default
                                $statusText = 'Booked';

                                if ($isPast) {
                                     $statusClass = 'status-completed'; // Gray
                                     $statusText = 'Selesai';
                                } elseif ($isToday) {
                                     $statusClass = 'status-confirmed'; // Green
                                     $statusText = 'Berlangsung';
                                } elseif ($isTomorrow) {
                                     $statusClass = 'status-upcoming'; 
                                     $statusText = 'Segera';
                                } else {
                                     $statusClass = 'status-upcoming';
                                     $statusText = 'Segera';
                                }
                            @endphp

                            <style>
                                /* Local override for specific booking card enhancement if needed */
                                .booking-item {
                                    background: white;
                                    border: 1px solid rgba(0,0,0,0.05) !important;
                                    border-radius: 18px !important;
                                    padding: 16px !important;
                                    margin-bottom: 12px;
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
                                }
                                .booking-item:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
                                    border-color: var(--primary) !important;
                                }
                                .status-upcoming {
                                    background: rgba(52, 152, 219, 0.1);
                                    color: #3498db;
                                }
                            </style>

                            <div class="booking-item" onclick="window.location.href='{{ route('landowner.booking.show', optional($booking)->id ?? '#') }}'">
                                <div class="booking-sport {{ $sportClass }}">
                                    <i class="{{ $sportIcon }}"></i>
                                </div>
                                <div class="booking-info">
                                    <div class="booking-title" style="font-size: 16px;">{{ $userName }}</div>
                                    <div style="font-size: 13px; color: var(--text-light); margin-bottom: 8px;">{{ $venueName }} - {{ $sectionName }}</div>
                                    
                                    <div class="booking-details">
                                        <span><i class="far fa-calendar"></i> {{ $tanggal }}</span>
                                        <span><i class="far fa-clock"></i> {{ $jamMulai }}-{{ $jamSelesai }}</span>
                                        <span style="color: var(--primary); font-weight: 700;"><i class="fas fa-tag"></i> Rp {{ number_format($jadwal->rental_price, 0, ',', '.') }}</span>
                                    </div>
                                    <span class="booking-status {{ $statusClass }}" style="margin-top: 10px;">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination with premium numbering style --}}
                    <div style="margin-top: 20px;">
                        {{ $bookings->links('vendor.pagination.premium') }}
                    </div>
                @endif
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('buyer.home.partials.home-script')
@endpush

@extends('layouts.main', ['title' => 'Dashboard - SewaLap'])

@push('styles')
    @include('landowner.home.partials.home-style')
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <h1 class="welcome-title">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="welcome-subtitle">Kelola semua venue dan fasilitas olahraga Anda dengan mudah dan efisien</p>
            </section>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number">{{ $totalBookingHariIni ?? 0 }}</div>
                    <div class="stat-label">Booking Hari Ini</div>
                </div>
                <div class="stat-card" onclick="window.location.href='{{ route('landowner.report-lapangan.index') }}'">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-number">{{ number_format(($pendapatanBulanIni ?? 0) / 1000000, 1) }}jt</div>
                    <div class="stat-label">Pendapatan</div>
                </div>
                <div class="stat-card" onclick="window.location.href='{{ route('landowner.venue.index') }}'">
                    <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="stat-number">{{ $totalVenues ?? 0 }}</div>
                    <div class="stat-label">Total Venue</div>
                </div>
            </div>

            <!-- Daftar Venue -->
            <h2 class="section-title">
                Daftar Venue Saya
                <a href="{{ route('landowner.venue.index') }}" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </h2>

            <div class="venues-container">
                @if(($venues ?? collect())->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-store fa-3x"></i>
                        <h3>Belum Ada Venue</h3>
                        <p>Anda belum memiliki venue. Tambahkan venue pertama Anda untuk mulai mengelola jadwal booking dan mendapatkan pendapatan.</p>
                        <button class="btn-primary" onclick="window.location.href='{{ route('landowner.venue.create') }}'">
                            <i class="fas fa-plus"></i> Tambah Venue Pertama
                        </button>
                    </div>
                @else
                    <div class="venues-slider" id="venuesSlider">
                        @foreach($venues as $venue)
                            @php
                                // Menggunakan venue_sections_count (snake_case from withCount)
                                $sectionCount = $venue->venue_sections_count ?? 0;

                                // Tentukan ikon berdasarkan category atau jenis olahraga
                                $sportIcon = 'map-marker-alt'; // default

                                // Jika Anda memiliki data category atau jenis olahraga di venue
                                if($venue->category) {
                                    $categoryName = strtolower($venue->category->category_name ?? '');
                                    if(str_contains($categoryName, 'futsal') || str_contains($categoryName, 'sepak bola')) {
                                        $sportIcon = 'futbol';
                                    } elseif(str_contains($categoryName, 'badminton')) {
                                        $sportIcon = 'table-tennis';
                                    } elseif(str_contains($categoryName, 'basket')) {
                                        $sportIcon = 'basketball-ball';
                                    } elseif(str_contains($categoryName, 'tennis')) {
                                        $sportIcon = 'tennis-ball';
                                    } elseif(str_contains($categoryName, 'voli')) {
                                        $sportIcon = 'volleyball-ball';
                                    }
                                }

                                // Ambil nama venue - sesuai dengan field di model
                                $venueName = $venue->venue_name ?? 'Venue Tanpa Nama';
                                $location = $venue->location ?? 'Belum diatur';
                                $categoryName = $venue->category->category_name ?? 'Multi Sport';

                                // Ambil foto-foto venue
                                $photos = $venue->photos ?? collect();
                                $hasMultiplePhotos = $photos->count() > 1;
                            @endphp

                            <div class="venue-card" onclick="window.location.href='{{ route('landowner.venue.show', $venue->id) }}'">
                                <!-- Photo Carousel -->
                                <div class="venue-img-carousel" data-venue-id="{{ $venue->id }}">
                                    @if($photos->count() > 0)
                                        @foreach($photos as $index => $photo)
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                                 class="venue-carousel-img {{ $index === 0 ? 'active' : '' }}"
                                                 alt="{{ $venueName }} - Foto {{ $index + 1 }}">
                                        @endforeach
                                        @if($hasMultiplePhotos)
                                            <div class="carousel-indicators">
                                                @foreach($photos as $index => $photo)
                                                    <span class="indicator {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                                                @endforeach
                                            </div>
                                            <div class="carousel-counter">{{ $photos->count() }} foto</div>
                                        @endif
                                    @elseif($venue->photo)
                                        <img src="{{ asset('storage/' . $venue->photo) }}" class="venue-carousel-img active" alt="{{ $venueName }}">
                                    @else
                                        <div class="venue-carousel-img active venue-placeholder">
                                            <i class="fas fa-{{ $sportIcon }}"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="venue-info">
                                    <div class="venue-name">{{ $venueName }}</div>
                                    <div class="venue-type">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ Str::limit($location, 20) }} • {{ $categoryName }}
                                    </div>
                                    <div class="venue-details">
                                        <div class="venue-facilities">{{ $sectionCount }} Lapangan</div>
                                        @if(isset($venue->status))
                                            <div class="venue-status status-{{ $venue->status }}">
                                                @if($venue->status == 'active')
                                                    Aktif
                                                @elseif($venue->status == 'inactive')
                                                    Nonaktif
                                                @elseif($venue->status == 'maintenance')
                                                    Maintenance
                                                @else
                                                    {{ $venue->status }}
                                                @endif
                                            </div>
                                        @else
                                            <div class="venue-status status-active">
                                                Aktif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Card Tambah Venue Baru -->
                        <div class="venue-card" onclick="window.location.href='{{ route('landowner.venue.create') }}'">
                            <div class="venue-img venue-placeholder">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="venue-info">
                                <div class="venue-name">Tambah Venue Baru</div>
                                <div class="venue-type">
                                    <i class="fas fa-plus-circle"></i> Klik untuk tambah venue baru
                                </div>
                                <div class="venue-details">
                                    <div class="venue-facilities">-</div>
                                    <div class="venue-status status-upcoming">Baru</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Booking Terbaru -->
            <div class="bookings-section">
                <h2 class="section-title" style="margin: 0 0 24px 0; padding-bottom: 16px;">
                    Booking Terbaru
                    <a href="{{ route('landowner.booking.index') }}" class="view-all">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </h2>

                @if(($bookingTerbaru ?? collect())->isEmpty())
                    <div class="empty-state-small">
                        <i class="fas fa-calendar fa-2x"></i>
                        <p>Belum ada booking terbaru</p>
                        <p style="font-size: 13px; margin-top: 8px; color: var(--text-light);">Booking akan muncul di sini setelah ada pemesanan</p>
                    </div>
                @else
                    @foreach($bookingTerbaru as $jadwal)
                        @php
                            // Tentukan ikon berdasarkan jenis olahraga
                            $sportIcon = 'fas fa-futbol'; // default
                            $sportClass = 'sport-futsal';

                            if ($jadwal->section && $jadwal->section->venue) {
                                $jenisOlahraga = strtolower($jadwal->section->venue->jenis_olahraga ?? '');

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
                            $jamMulai = $jadwal->start_time;
                            $jamSelesai = $jadwal->end_time;

                            // Ambil booking pertama (karena hasMany)
                            $booking = $jadwal->bookings->first();

                            // Nama pengguna
                            $userName = optional(optional($booking)->user)->name ?? 'Guest';

                            // Nama venue/lapangan
                            $venueName = $jadwal->section && $jadwal->section->venue
                                ? $jadwal->section->venue->nama_lapangan
                                : 'Venue';

                            $sectionName = $jadwal->section
                                ? $jadwal->section->nama_section
                                : 'Lapangan';

                            // Status booking
                            $isToday = \Carbon\Carbon::parse($jadwal->date)->isToday();
                            $isTomorrow = \Carbon\Carbon::parse($jadwal->date)->isTomorrow();
                            $isPast = \Carbon\Carbon::parse($jadwal->date)->isPast() && !$isToday;

                            $statusClass = 'status-confirmed'; // Default Green (Berlangsung/Confirmed)
                            $statusText = 'Berlangsung';

                            if ($isPast) {
                                 $statusClass = 'status-completed'; // Gray
                                 $statusText = 'Selesai';
                            } elseif ($isToday) {
                                 $statusClass = 'status-confirmed'; // Green
                                 $statusText = 'Berlangsung';
                            } elseif ($isTomorrow) {
                                 $statusClass = 'status-upcoming'; // Blue
                                 $statusText = 'Segera';
                            } else {
                                 $statusClass = 'status-upcoming';
                                 $statusText = 'Segera';
                            }
                        @endphp

                        <div class="booking-item" >
                            <div class="booking-sport {{ $sportClass }}">
                                <i class="{{ $sportIcon }}"></i>
                            </div>
                            <div class="booking-info">
                                <div class="booking-title">{{ $userName }} - {{ $venueName }}</div>
                                <div class="booking-details">
                                    <span><i class="far fa-calendar"></i> {{ $tanggal }}</span>
                                    <span><i class="far fa-clock"></i> {{ $jamMulai }}-{{ $jamSelesai }}</span>
                                    <span><i class="fas fa-map-marker-alt"></i> {{ $sectionName }}</span>
                                    <span><i class="fas fa-money-bill-wave"></i> Rp {{ number_format($jadwal->rental_price, 0, ',', '.') }}</span>
                                </div>
                                <span class="booking-status {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Tips Section -->
            <div class="tips-section">
                <h2 class="tips-title">Tips Manajemen Venue</h2>
                <p class="tips-desc">Optimalkan pendapatan venue Anda dengan mengelola jadwal secara efisien, memberikan promo di jam sepi, menjaga kualitas fasilitas, dan meningkatkan rating venue untuk menarik lebih banyak pelanggan.</p>
                <button class="btn-tips" onclick="window.location.href='{{ route('landowner.tips.index') }}'">
                    <i class="fas fa-lightbulb"></i> Lihat Tips Lengkap
                </button>
            </div>

        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('landowner.home.partials.home-script')
@endpush
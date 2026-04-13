{{-- ============================================
    VIEW: BUYER HOME (HALAMAN BERANDA)
    MENGEXTEND LAYOUT UTAMA DENGAN TITLE "Beranda - SewaLap"
    ============================================ --}}
@extends('layouts.main', ['title' => 'Beranda - SewaLap'])

{{-- ============================================
    PUSH SECTION UNTUK STYLES (CSS TAMBAHAN)
    ============================================ --}}
@push('styles')
    {{-- MEMASUKKAN FILE STYLE KHUSUS UNTUK HALAMAN HOME --}}
    @include('buyer.home.partials.home-style')
    
    <!-- CSS UNTUK OWL CAROUSEL (SLIDER) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

@endpush

{{-- ============================================
    SECTION KONTEN UTAMA
    ============================================ --}}
@section('content')
    <!-- KONTAINER UTAMA APLIKASI (MOBILE-FIRST) -->
    <div class="mobile-container" id="mobileContainer">
        
        <!-- HEADER HALAMAN -->
        @include('layouts.header')

        <!-- KONTEN UTAMA -->
        <main class="main-content">
            
            {{-- ========================================
                BAGIAN HERO (BANNER UTAMA)
            ======================================== --}}
            <section class="hero-section">
                <h1 class="hero-title">Sewa Lapangan Olahraga Online dengan Mudah</h1>
                <p class="hero-subtitle">Temukan dan booking lapangan olahraga favoritmu dalam hitungan detik. Pilihan
                    lengkap dari futsal, badminton, basket, hingga tenis dengan harga terbaik.</p>
                {{-- 
                TOMBOL CTA (SEMENTARA DI-NONAKTIFKAN)
                <div class="cta-buttons">
                    <button class="btn-primary" onclick="window.location.href='{{ route('buyer.venue.index') }}'">
                        <i class="fas fa-search"></i> Cari Lapangan
                    </button>
                </div> --}}
            </section>

            {{-- ========================================
                STATISTIK CEPAT (JUMLAH LAPANGAN, PENGGUNA, BOOKING)
            ======================================== --}}
            <div class="stats-grid">
                {{-- STATISTIK LAPANGAN --}}
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-futbol"></i></div>
                    <div class="stat-number">{{ number_format($stats['venues_count']) }}</div>
                    <div class="stat-label">Lapangan</div>
                </div>
                
                {{-- STATISTIK PENGGUNA --}}
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">{{ number_format($stats['users_count']) }}</div>
                    <div class="stat-label">Pengguna</div>
                </div>
                
                {{-- STATISTIK BOOKING --}}
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number">{{ number_format($stats['bookings_count']) }}</div>
                    <div class="stat-label">Booking</div>
                </div>
            </div>

            {{-- ========================================
                BAGIAN FITUR UNGGULAN SEWALAP
            ======================================== --}}
            <h2 class="section-title">Keunggulan SewaLap</h2>
            <div class="features-grid">
                {{-- FITUR 1: BOOKING INSTAN --}}
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <div class="feature-title">Booking Instan</div>
                    <div class="feature-desc">Booking lapangan hanya dalam 3 klik dengan konfirmasi real-time</div>
                </div>

                {{-- FITUR 2: PEMBAYARAN AMAN --}}
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="feature-title">Pembayaran Aman</div>
                    <div class="feature-desc">Transaksi aman dengan sistem terenkripsi dan garansi uang kembali</div>
                </div>

                {{-- FITUR 3: HARGA TERBAIK --}}
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-percentage"></i></div>
                    <div class="feature-title">Harga Terbaik</div>
                    <div class="feature-desc">Harga kompetitif dengan promo dan diskon menarik setiap hari</div>
                </div>

                {{-- FITUR 4: SUPPORT 24/7 --}}
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <div class="feature-title">Support 24/7</div>
                    <div class="feature-desc">Tim customer service siap membantu kapanpun Anda butuhkan</div>
                </div>
            </div>

            {{-- ========================================
                BAGIAN DAFTAR LAPANGAN TERSEDIA
            ======================================== --}}
            <h2 class="section-title">
                Lapangan Tersedia
                {{-- LINK "LIHAT SEMUA" UNTUK MELIHAT SEMUA LAPANGAN --}}
                <a href="{{ route('buyer.venue.index') }}" class="view-all">Lihat Semua <i
                        class="fas fa-arrow-right"></i></a>
            </h2>

            {{-- FILTER BERDASARKAN KATEGORI LAPANGAN --}}
            <div class="category-filter">
                {{-- TOMBOL FILTER "SEMUA" --}}
                <button class="filter-btn active" onclick="filterByCategory('all')" data-category="all">
                    <i class="fas fa-th"></i>
                    Semua
                </button>
                
                {{-- LOOPING SEMUA KATEGORI YANG TERSEDIA --}}
                @foreach($categories as $category)
                    <button class="filter-btn" onclick="filterByCategory({{ $category->id }})"
                        data-category="{{ $category->id }}">
                        <i class="fas fa-tag"></i>
                        {{ $category->category_name }}
                    </button>
                @endforeach
            </div>

            {{-- KONTAINER UNTUK MENAMPILKAN DAFTAR LAPANGAN --}}
            <div class="categories-scroll" id="venues-container">
                @forelse($venues as $venue)
                    {{-- KARTU LAPANGAN --}}
                    <div class="category-card" data-category-id="{{ $venue->category_id }}"
                        onclick="window.location.href='{{ route('buyer.venue.show', $venue->id) }}'">
                        {{-- GAMBAR LAPANGAN (DARI STORAGE ATAU PLACEHOLDER) --}}
                        <img src="{{ $venue->photo ? asset('storage/' . $venue->photo) : 'https://images.unsplash.com/photo-1519861531473-920034658307?w=400' }}"
                            class="category-img" alt="{{ $venue->venue_name }}">
                        {{-- NAMA LAPANGAN --}}
                        <div class="category-name">{{ $venue->venue_name }}</div>
                        {{-- DESKRIPSI SINGKAT (DIPOTONG 50 KARAKTER) --}}
                        <div class="category-desc">{{ Str::limit($venue->description, 50) }}</div>
                    </div>
                @empty
                    {{-- TAMPILAN JIKA BELUM ADA LAPANGAN --}}
                    <div class="category-card full-width-card">
                        <div class="category-name">Belum ada lapangan tersedia</div>
                    </div>
                @endforelse
            </div>

            {{-- ========================================
                BAGIAN KOMUNITAS OLAHRAGA
            ======================================== --}}
            <h2 class="section-title">
                Komunitas
                {{-- LINK "LIHAT SEMUA" UNTUK MELIHAT SEMUA KOMUNITAS --}}
                <a href="{{ route('buyer.communities.index') }}" class="view-all">Lihat Semua <i
                        class="fas fa-arrow-right"></i></a>
            </h2>
            
            {{-- SLIDER KOMUNITAS (MENGGUNAKAN OWL CAROUSEL) --}}
            <div class="communities-slider">
                @forelse($communities as $community)
                    {{-- KARTU KOMUNITAS --}}
                    <div class="community-card"
                        onclick="window.location.href='{{ route('buyer.communities.show', $community->id) }}'">
                        <div class="community-header">
                            {{-- LOGO KOMUNITAS (DARI STORAGE ATAU PLACEHOLDER) --}}
                            <img src="{{ $community->logo ? asset('storage/' . $community->logo) : 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400' }}"
                                class="community-logo" alt="{{ $community->name }}">
                            <div class="community-info">
                                <div class="community-name">{{ $community->name }}</div>
                                {{-- KATEGORI KOMUNITAS (DEFAULT "UMUM" JIKA TIDAK ADA) --}}
                                <div class="community-category">{{ $community->category ? $community->category->name : 'Umum' }}
                                </div>
                            </div>
                        </div>
                        {{-- DESKRIPSI SINGKAT KOMUNITAS (60 KARAKTER) --}}
                        <div class="community-desc">{{ Str::limit($community->description, 60) }}</div>
                        <div class="community-stats">
                            {{-- JUMLAH MEMBER KOMUNITAS --}}
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $community->members()->count() }} member</span>
                            </div>
                            {{-- JUMLAH EVENT PLAY TOGETHER --}}
                            <div class="stat-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $community->playTogetherEvents()->count() }} event</span>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- TAMPILAN JIKA BELUM ADA KOMUNITAS --}}
                    <div class="category-card full-width-card">
                        <div class="category-name">Belum ada komunitas tersedia</div>
                    </div>
                @endforelse
            </div>
        </main>

        {{-- BOTTOM NAVIGATION BAR (NAVIGASI BAWAH) --}}
        @include('layouts.bottom-nav')
    </div>
@endsection

{{-- ============================================
    PUSH SECTION UNTUK SCRIPTS (JAVASCRIPT TAMBAHAN)
    ============================================ --}}
@push('scripts')
    {{-- MEMASUKKAN FILE SCRIPT KHUSUS UNTUK HALAMAN HOME --}}
    @include('buyer.home.partials.home-script')
@endpush
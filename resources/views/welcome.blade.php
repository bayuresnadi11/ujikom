@extends('layouts.main', ['title' => 'Beranda - SewaLap'])

@push('styles')
    @include('buyer.home.partials.home-style')
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hero -->
            <section class="hero-section">
                <h1 class="hero-title">Sewa Lapangan Olahraga Online dengan Mudah</h1>
                <p class="hero-subtitle">Temukan dan booking lapangan olahraga favoritmu dalam hitungan detik. Pilihan
                    lengkap dari futsal, badminton, basket, hingga tenis dengan harga terbaik.</p>
                <div class="cta-buttons">
                    <form action="{{ route('login') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Masuk
                        </button>
                    </form>

                    <form action="{{ route('register') }}" method="GET">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Daftar
                        </button>
                    </form>
                </div>
            </section>


            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-futbol"></i></div>
                    <div class="stat-number">{{ number_format($stats['venues_count']) }}</div>
                    <div class="stat-label">Lapangan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">{{ number_format($stats['users_count']) }}</div>
                    <div class="stat-label">Pengguna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number">{{ number_format($stats['bookings_count']) }}</div>
                    <div class="stat-label">Booking</div>
                </div>
            </div>

            <!-- Features -->
            <h2 class="section-title">Keunggulan SewaLap</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <div class="feature-title">Booking Instan</div>
                    <div class="feature-desc">Booking lapangan hanya dalam 3 klik dengan konfirmasi real-time</div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="feature-title">Pembayaran Aman</div>
                    <div class="feature-desc">Transaksi aman dengan sistem terenkripsi dan garansi uang kembali</div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-percentage"></i></div>
                    <div class="feature-title">Harga Terbaik</div>
                    <div class="feature-desc">Harga kompetitif dengan promo dan diskon menarik setiap hari</div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <div class="feature-title">Support 24/7</div>
                    <div class="feature-desc">Tim customer service siap membantu kapanpun Anda butuhkan</div>
                </div>
            </div>


            <!-- Venues Section -->
            <h2 class="section-title">
                Lapangan Tersedia
                <a href="{{ route('buyer.venue.index') }}" class="view-all">Lihat Semua <i
                        class="fas fa-arrow-right"></i></a>
            </h2>

            <!-- Category Filter -->
            <div class="category-filter">
                <button class="filter-btn active" onclick="filterByCategory('all')" data-category="all">
                    <i class="fas fa-th"></i>
                    Semua
                </button>
                @foreach($categories as $category)
                    <button class="filter-btn" onclick="filterByCategory({{ $category->id }})"
                        data-category="{{ $category->id }}">
                        <i class="fas fa-tag"></i>
                        {{ $category->category_name }}
                    </button>
                @endforeach
            </div>
            
            <div class="categories-scroll" id="venues">
                @forelse($venues as $venue)
                    <div class="category-card" onclick="window.location.href='{{ route('buyer.venue.show', $venue->id) }}'">
                        <img src="{{ $venue->photo ? asset('storage/' . $venue->photo) : 'https://images.unsplash.com/photo-1519861531473-920034658307?w=400' }}"
                            class="category-img" alt="{{ $venue->venue_name }}">
                        <div class="category-name">{{ $venue->venue_name }}</div>
                        <div class="category-desc">{{ Str::limit($venue->description, 50) }}</div>
                    </div>
                @empty
                    <div class="category-card full-width-card">
                        <div class="category-name">Belum ada lapangan tersedia</div>
                    </div>
                @endforelse
            </div>


            <!-- Communities Section -->
            <h2 class="section-title">
                Komunitas
                <a href="{{ route('buyer.communities.index') }}" class="view-all">Lihat Semua <i
                        class="fas fa-arrow-right"></i></a>
            </h2>
            <div class="communities-slider">
                @forelse($communities as $community)
                    <div class="community-card"
                        onclick="window.location.href='{{ route('buyer.communities.show', $community->id) }}'">
                        <div class="community-header">
                            <img src="{{ $community->logo ? asset('storage/' . $community->logo) : 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400' }}"
                                class="community-logo" alt="{{ $community->name }}">
                            <div class="community-info">
                                <div class="community-name">{{ $community->name }}</div>
                                <div class="community-category">{{ $community->category ? $community->category->name : 'Umum' }}
                                </div>
                            </div>
                        </div>
                        <div class="community-desc">{{ Str::limit($community->description, 60) }}</div>
                        <div class="community-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $community->members()->count() }} member</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $community->playTogetherEvents()->count() }} event</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="category-card full-width-card">
                        <div class="category-name">Belum ada komunitas tersedia</div>
                    </div>
                @endforelse
            </div>



        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('buyer.home.partials.home-script')
    </script>
@endpush
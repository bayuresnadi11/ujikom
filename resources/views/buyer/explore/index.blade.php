@extends('layouts.main', ['title' => 'Explore - SewaLap'])

@push('styles')
    @include('buyer.explore.partials.explore-style')
@endpush

@section('content')
    <!-- Main App Container --> 
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Explore Header -->
            <div class="content-padding">
                <div class="explore-header">
                    <h1 class="explore-title">Jelajahi Lapangan</h1>
                    <p class="explore-subtitle">
                        Temukan lapangan olahraga terbaik di sekitarmu
                    </p>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-section">
                <div class="search-bar">
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Cari lapangan, lokasi, atau olahraga..." id="searchInput">
                            <button class="search-btn" onclick="performSearch()" aria-label="Search">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="quick-filters">
                        <div class="filters-scroll">
                            <button class="filter-chip active" onclick="setFilter('all')">
                                <i class="fas fa-layer-group"></i>
                                <span>Semua</span>
                            </button>
                            <button class="filter-chip" onclick="setFilter('nearby')">
                                <i class="fas fa-location-dot"></i>
                                <span>Terdekat</span>
                            </button>
                            <button class="filter-chip" onclick="setFilter('popular')">
                                <i class="fas fa-fire"></i>
                                <span>Populer</span>
                            </button>
                            <button class="filter-chip" onclick="setFilter('filter_promo')">
                                <i class="fas fa-tag"></i>
                                <span>Promo</span>
                            </button>
                            <button class="filter-chip" onclick="setFilter('rating')">
                                <i class="fas fa-star"></i>
                                <span>Rating Tinggi</span>
                            </button>
                            <button class="filter-chip" onclick="setFilter('filter_24jam')">
                                <i class="fas fa-clock"></i>
                                <span>24 Jam</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sports Categories -->
            <div class="sports-categories">
                <div class="section-header">
                    <div class="section-header-text">
                        <h2 class="section-title">
                            <span class="section-icon"><i class="fas fa-futbol"></i></span>
                            Kategori Olahraga
                        </h2>
                        <p class="section-subtitle">Pilih kategori yang ingin Anda cari</p>
                    </div>
                </div>

                <div class="sports-container">
                    <div class="sports-scroll">

                        <div class="sport-card active" data-category="all">
                            <div class="sport-card-content">
                                <div class="sport-icon"><i class="fas fa-globe"></i></div>
                                <div class="sport-name">Semua</div>
                            </div>
                        </div>

                        @foreach($categories as $category)
                            <div class="sport-card" data-category="{{ $category->id }}">
                                <div class="sport-card-content">

                                    <div class="sport-icon">
                                        @if($category->logo)
                                            <img src="{{ asset('storage/'.$category->logo) }}"
                                                alt="{{ $category->category_name }}"
                                                class="sport-icon-img">
                                        @else
                                            <i class="fas fa-dumbbell"></i>
                                        @endif
                                    </div>

                                    <div class="sport-name">{{ $category->category_name }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            
            <!-- Lapangan Terpopuler Slider -->
            <div class="recommendations-slider">
                <div class="section-header">
                    <div class="section-header-text">
                        <h2 class="section-title">
                            <span class="section-icon"><i class="fas fa-th-list"></i></span>
                            Daftar Lapangan
                        </h2>
                        <p class="section-subtitle">Pilih dari semua lapangan yang tersedia</p>
                    </div>
                    @if($venues->count() > 0)
                    <a href="{{ route('buyer.venue.index') }}" class="view-all-btn">
                        Lihat Semua <i class="fas fa-chevron-right"></i>
                    </a>
                    @endif
                </div>

                @if($venues->count() > 0)
                    {{-- GRID VIEW --}}
                    <div class="venues-grid" id="venuesSlider" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; padding: 0 5px;">
                        @foreach($venues as $venue)
                            <div class="venue-card" data-category="{{ $venue->category_id }}" style="background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s;">
                                <div class="card-image" style="position: relative; height: 120px;">
                                    <img src="{{ asset('storage/'.$venue->photo) }}"
                                        alt="{{ $venue->venue_name }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                    
                                    <div class="category-badge" style="position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.9); padding: 4px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; color: #333;">
                                        {{ $venue->category->category_name ?? 'Umum' }}
                                    </div>
                                </div>

                                <div class="card-content" style="padding: 12px;">
                                    <h3 style="font-size: 14px; font-weight: 700; margin: 0 0 5px 0; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $venue->venue_name }}</h3>

                                    <div class="rating" style="display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
                                        <i class="fas fa-star" style="color: #fbbf24; font-size: 12px;"></i>
                                        <span style="font-size: 12px; font-weight: 600; color: #475569;">{{ number_format($venue->rating ?? 0, 1) }}</span>
                                    </div>

                                    <div class="location" style="display: flex; align-items: center; gap: 5px; color: #64748b; font-size: 11px; margin-bottom: 12px;">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $venue->location }}</span>
                                    </div>

                                    <a href="{{ route('buyer.venue.show',$venue->id) }}" style="display: block; width: 100%; padding: 8px 0; background: #4f46e5; color: white; text-align: center; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none;">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- EMPTY STATE FILTER (Hidden by default, shown by JS when filter doesn't match) --}}
                    <div id="emptyFilterMessage" style="display: none;">
                        <div class="empty-wrapper">
                            <div class="placeholder-box">
                                <i class="fas fa-star-half-alt"></i>
                                <h3>Tidak ada lapangan di kategori ini</h3>
                                <p>Silakan pilih kategori lain</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 20px; display: flex; justify-content: center;">
                        {{ $venues->links() }}
                    </div>
                @else
                    {{-- EMPTY STATE GLOBAL (Only shown when no venues from backend) --}}
                    <div class="empty-wrapper">
                        <div class="placeholder-box">
                            <i class="fas fa-star-half-alt"></i>
                            <h3>Belum ada lapangan tersedia</h3>
                        </div>
                    </div>
                @endif
            </div>

        

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('buyer.explore.partials.explore-script')
@endpush
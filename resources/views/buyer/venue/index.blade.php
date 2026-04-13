{{--
=============================================================================
VIEW: VENUE LIST (DAFTAR VENUE/LAPANGAN)
Halaman untuk menampilkan daftar semua venue/lapangan yang tersedia
User dapat melihat daftar venue, memfilter berdasarkan kategori, dan mengklik venue untuk melihat detail
=============================================================================
--}}

{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'Venue List'])

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling venue dari partial --}}
    @include('buyer.venue.partials.venue-style')
@endpush

{{-- Section konten utama --}}
@section('content')
    <!-- CSRF Token untuk JavaScript -->
    {{-- Meta tag CSRF token untuk keamanan AJAX request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ====================== TOAST NOTIFICATION ====================== -->
    {{-- Toast notification untuk menampilkan pesan sementara --}}
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span>Notification message</span>
    </div>

    <!-- ====================== MAIN CONTAINER ====================== -->
    <div class="mobile-container">
        <!-- Header -->
        {{-- Memasukkan header dari layout terpisah --}}
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- ====================== PAGE HEADER ====================== -->
            <section class="page-header">
                <h1 class="page-title">Daftar Venue</h1>
                <p class="page-subtitle">Temukan lapangan terbaik untuk bermain</p>
            </section>

            <!-- ====================== CATEGORY FILTER ====================== -->
            {{-- Filter kategori venue (Semua, Futsal, Badminton, Basket, dll) --}}
            <div class="category-filter">
                {{-- Tombol filter "Semua" (aktif secara default) --}}
                <button class="filter-btn active" onclick="filterByCategory('all')" data-category="all">
                    <i class="fas fa-th"></i>
                    Semua
                </button>
                {{-- Looping semua kategori yang tersedia --}}
                @foreach($categories as $category)
                    <button class="filter-btn" onclick="filterByCategory({{ $category->id }})" data-category="{{ $category->id }}">
                        <i class="fas fa-tag"></i>
                        {{ $category->category_name }}
                    </button>
                @endforeach
            </div>

            <!-- ====================== FLASH MESSAGES ====================== -->
            {{-- Menampilkan pesan sukses dari session (jika ada) --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            {{-- Menampilkan pesan error dari session (jika ada) --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- ====================== VENUE CARDS ====================== -->
            {{-- Container untuk kartu-kartu venue --}}
            <div class="section-cards-container" id="venueCards">
                @if($venues->count() > 0)
                    {{-- Looping data venue dari controller --}}
                    @foreach($venues as $venue)
                        {{-- Kartu venue individual, dapat diklik untuk melihat detail --}}
                        <div class="section-card venue-card" 
                             data-venue-name="{{ strtolower($venue->venue_name) }}"
                             data-category-id="{{ $venue->category_id }}"
                             onclick="window.location.href='{{ Auth::check() && Auth::user()->role === 'buyer' ? route('buyer.venue.show', $venue->id) : route('guest.venue.show', $venue->id) }}'">
                            
                            <!-- ====================== VENUE IMAGE ====================== -->
                            <div class="venue-image">
                                {{-- Gambar venue (dari storage) --}}
                                <img src="{{ asset('storage/' . $venue->photo) }}" alt="{{ $venue->venue_name }}">
                                
                                {{-- Badge kategori venue --}}
                                <div class="venue-category-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ $venue->category->category_name ?? 'N/A' }}
                                </div>
                                
                                {{-- Badge rating (jika ada) --}}
                                @if($venue->rating)
                                    <div class="venue-rating">
                                        <i class="fas fa-star"></i>
                                        {{ number_format($venue->rating, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- ====================== VENUE HEADER ====================== -->
                            <div class="section-header">
                                <div class="section-title-wrapper">
                                    {{-- Nama venue --}}
                                    <h3 class="section-title">{{ $venue->venue_name }}</h3>
                                    {{-- Lokasi venue --}}
                                    <div class="section-venue">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $venue->location }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ====================== VENUE CONTENT ====================== -->
                            <div class="section-content">
                                {{-- Deskripsi venue (dibatasi 100 karakter) --}}
                                <div class="section-description">
                                    {{ Str::limit($venue->description, 100) }}
                                </div>
                                
                                <!-- ====================== VENUE BADGES ====================== -->
                                <div class="section-badges">
                                    {{-- Badge jumlah lapangan yang tersedia --}}
                                    <span class="mini-badge">
                                        <i class="fas fa-layer-group"></i>
                                        {{ $venue->venueSections->count() }} Lapangan
                                    </span>
                                    
                                    @php
                                        // Hitung apakah ada jadwal yang tersedia untuk venue ini
                                        $hasAvailableSchedule = $venue->venueSections->sum(function($section) {
                                            return $section->venueSchedules->count();
                                        }) > 0;
                                    @endphp

                                    {{-- Badge status ketersediaan --}}
                                    @if($hasAvailableSchedule)
                                        <span class="mini-badge" style="background: rgba(46, 204, 113, 0.1); color: #27ae60; border-color: rgba(46, 204, 113, 0.2);">
                                            <i class="fas fa-calendar-check"></i>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="mini-badge-ban">
                                            <i class="fas fa-ban"></i>
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- ====================== VENUE FOOTER ====================== -->
                            <div class="section-footer">
                                <div class="section-date">
                                    <i class="fas fa-user"></i>
                                    {{-- Nama pemilik/creator venue --}}
                                    {{ $venue->creator->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- ====================== EMPTY STATE ====================== -->
                    {{-- Tampilan ketika tidak ada venue --}}
                    <div class="empty-state">
                        <i class="fas fa-map-marked-alt empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum ada venue</h3>
                        <p class="empty-state-desc">Venue akan muncul di sini ketika tersedia</p>
                    </div>
                @endif
            </div>
        </main>

        <!-- ====================== BOTTOM NAV ====================== -->
        {{-- Navigasi bawah (bottom navigation bar) --}}
        @include('layouts.bottom-nav')
    </div>
@endsection

{{-- Section untuk menambahkan JavaScript khusus halaman ini --}}
@push('scripts')
    {{-- Memasukkan file JavaScript untuk fungsi venue dari partial --}}
    @include('buyer.venue.partials.venue-script')
@endpush
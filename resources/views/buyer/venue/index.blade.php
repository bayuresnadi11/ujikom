@extends('layouts.main', ['title' => 'Venue List'])

@push('styles')
    @include('buyer.venue.partials.venue-style')
@endpush

@section('content')
    <!-- CSRF Token untuk JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span>Notification message</span>
    </div>

    <!-- Main Container -->
    <div class="mobile-container">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Daftar Venue</h1>
                <p class="page-subtitle">Temukan lapangan terbaik untuk bermain</p>
            </section>

            <!-- Category Filter -->
            <div class="category-filter">
                <button class="filter-btn active" onclick="filterByCategory('all')" data-category="all">
                    <i class="fas fa-th"></i>
                    Semua
                </button>
                @foreach($categories as $category)
                    <button class="filter-btn" onclick="filterByCategory({{ $category->id }})" data-category="{{ $category->id }}">
                        <i class="fas fa-tag"></i>
                        {{ $category->category_name }}
                    </button>
                @endforeach
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Venue Cards -->
            <div class="section-cards-container" id="venueCards">
                @if($venues->count() > 0)
                    @foreach($venues as $venue)
                        <div class="section-card venue-card" 
                             data-venue-name="{{ strtolower($venue->venue_name) }}"
                             data-category-id="{{ $venue->category_id }}"
                             onclick="window.location.href='{{ Auth::check() && Auth::user()->role === 'buyer' ? route('buyer.venue.show', $venue->id) : route('guest.venue.show', $venue->id) }}'">
                            <!-- Venue Image -->
                            <div class="venue-image">
                                <img src="{{ asset('storage/' . $venue->photo) }}" alt="{{ $venue->venue_name }}">
                                <div class="venue-category-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ $venue->category->category_name ?? 'N/A' }}
                                </div>
                                @if($venue->rating)
                                    <div class="venue-rating">
                                        <i class="fas fa-star"></i>
                                        {{ number_format($venue->rating, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Venue Header -->
                            <div class="section-header">
                                <div class="section-title-wrapper">
                                    <h3 class="section-title">{{ $venue->venue_name }}</h3>
                                    <div class="section-venue">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $venue->location }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Venue Content -->
                            <div class="section-content">
                                <div class="section-description">
                                    {{ Str::limit($venue->description, 100) }}
                                </div>
                                
                                <!-- Venue Badges -->
                                <div class="section-badges">
                                    <span class="mini-badge">
                                        <i class="fas fa-layer-group"></i>
                                        {{ $venue->venueSections->count() }} Lapangan
                                    </span>
                                    @php
                                        // Calculate total available schedules
                                        $hasAvailableSchedule = $venue->venueSections->sum(function($section) {
                                            return $section->venueSchedules->count();
                                        }) > 0;
                                    @endphp

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
                            
                            <!-- Venue Footer -->
                            <div class="section-footer">
                                <div class="section-date">
                                    <i class="fas fa-user"></i>
                                    {{ $venue->creator->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-map-marked-alt empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum ada venue</h3>
                        <p class="empty-state-desc">Venue akan muncul di sini ketika tersedia</p>
                    </div>
                @endif
            </div>
        </main>

         <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('buyer.venue.partials.venue-script')
@endpush
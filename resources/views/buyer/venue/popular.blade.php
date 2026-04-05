@extends('layouts.main', ['title' => 'Venue Populer'])

@push('styles')
    @include('buyer.venue.partials.venue-style')
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="mobile-container">

    <!-- Header -->
    @include('layouts.header')

    <!-- Main -->
    <main class="main-content">

        <!-- Page Header -->
        <section class="page-header">
            <h1 class="page-title">Venue Populer</h1>
            <p class="page-subtitle">Lapangan dengan rating 4.0 ke atas</p>
        </section>

        <!-- Cards -->
        <div class="section-cards-container" id="venueCards">
            @forelse($venues as $venue)
                <div class="section-card venue-card"
                    data-venue-name="{{ strtolower($venue->venue_name) }}"
                    onclick="window.location.href='{{ route('buyer.venue.show', $venue->id) }}'">

                    <!-- Image -->
                    <div class="venue-image">
                        <img src="{{ asset('storage/'.$venue->photo) }}" alt="{{ $venue->venue_name }}">

                        <div class="venue-category-badge">
                            <i class="fas fa-tag"></i>
                            {{ $venue->category->category_name ?? '-' }}
                        </div>

                        <div class="venue-rating">
                            <i class="fas fa-star"></i>
                            {{ number_format($venue->rating, 1) }}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="section-header">
                        <h3 class="section-title">{{ $venue->venue_name }}</h3>
                        <div class="section-venue">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $venue->location }}
                        </div>
                    </div>

                    <div class="section-content">
                        {{ Str::limit($venue->description, 90) }}
                    </div>

                    <div class="section-footer">
                        <span>
                            <i class="fas fa-user"></i>
                            {{ $venue->creator->name ?? '-' }}
                        </span>

                        <button class="btn-detail"
                            onclick="event.stopPropagation(); window.location.href='{{ route('buyer.venue.show', $venue->id) }}'">
                            Detail
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-fire empty-state-icon"></i>
                    <h3>Belum ada venue populer</h3>
                    <p>Venue dengan rating ≥ 4 akan muncul di sini</p>
                </div>
            @endforelse
        </div>

    </main>

    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
    @include('buyer.venue.partials.venue-script')
@endpush
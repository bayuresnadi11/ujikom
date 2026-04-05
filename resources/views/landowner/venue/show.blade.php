@extends('layouts.main', ['title' => 'Detail Venue - SewaLap'])
@push('styles')
    @include('landowner.home.partials.home-style')
    <style>


        .venue-detail-card {
            background: white;
            border-radius: 24px;
            margin: 0 16px 24px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid rgba(139, 21, 56, 0.05);
        }

        .hero-container {
            position: relative;
            padding: 16px;
        }

        .venue-hero-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: var(--shadow-sm);
        }

        .venue-main-info {
            padding: 0 20px 20px;
            border-bottom: 2px dashed var(--light-gray);
        }

        .venue-name {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .venue-badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .info-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--light);
            border-radius: 10px;
            font-size: 13px;
            color: var(--text-light);
            font-weight: 600;
        }

        .info-badge i {
            color: var(--accent);
        }

        .card-divider {
            height: 1px;
            background: var(--light-gray);
            margin: 0 20px;
        }

        .venue-section-block {
            padding: 24px 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .block-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .venue-description-text {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
            padding: 16px;
            background: var(--light);
            border-radius: 14px;
            border-left: 4px solid var(--accent);
        }

        .field-list {
            display: grid;
            gap: 12px;
            margin-bottom: 20px;
        }

        .field-item {
            display: flex;
            align-items: center;
            padding: 14px;
            background: white;
            border-radius: 14px;
            border: 1px solid var(--light-gray);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .field-item:hover {
            border-color: var(--accent);
            background: var(--light);
            transform: translateX(5px);
        }

        .field-icon {
            width: 42px;
            height: 42px;
            background: var(--gradient-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 14px;
        }

        .field-name {
            font-weight: 700;
            color: var(--text);
            font-size: 15px;
        }

        .section-count {
            background: var(--gradient-accent);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .field-list {
            display: grid;
            gap: 12px;
            margin-bottom: 20px;
        }

        .field-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }
        
        .field-item:hover {
            border-color: var(--primary);
            background: white;
            transform: translateX(3px);
        }

        .field-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 15px;
        }

        .field-info {
            flex: 1;
        }

        .field-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
            font-size: 15px;
        }

        .field-price {
            font-size: 13px;
            color: var(--primary);
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .empty-state p {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .add-field-btn {
            display: block;
            background: var(--light);
            color: var(--primary);
            border: 2px dashed var(--accent);
            padding: 18px;
            border-radius: 16px;
            text-align: center;
            font-weight: 700;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .add-field-btn:hover {
            background: var(--gradient-light);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .loading-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }

        .placeholder-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(110deg, #f5f5f5 30%, #eaeaea 50%, #f5f5f5 70%);
            border-radius: 15px;
            background-size: 200% 100%;
            animation: loading 1.5s infinite linear;
        }
        
        @keyframes loading {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }

        .mobile-container {
            padding-top: 64px; /* Space for fixed header */
        }
    </style>
@endpush
@section('header')
    @include('layouts.header', [
        'theme' => 'dark', 
        'title' => 'Detail Venue', 
        'backUrl' => route('landowner.home')
    ])
@endsection

@section('content')
    <div class="mobile-container">
        
        <div class="venue-detail-card">
            <!-- Hero Block -->
            <div class="hero-container">
                @php
                    $photos = $venue->photos ?? collect();
                    $mainPhoto = $photos->first() ? asset('storage/' . $photos->first()->photo_path) : ($venue->photo ? asset('storage/' . $venue->photo) : null);
                @endphp
                
                @if($mainPhoto)
                    <img src="{{ $mainPhoto }}" alt="{{ $venue->venue_name }}" class="venue-hero-image">
                @else
                    <div class="venue-hero-image placeholder-image"></div>
                @endif
            </div>

            <!-- Basic Info Block -->
            <div class="venue-main-info">
                <h1 class="venue-name">{{ $venue->venue_name }}</h1>
                <div class="venue-badge-row">
                    <div class="info-badge">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ Str::limit($venue->location, 35) }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Block -->
            <div class="venue-section-block">
                @if($venue->description)
                    <div class="venue-description-text">
                        {{ $venue->description }}
                    </div>
                @endif

                <div class="section-header">
                    <h3 class="block-title">
                        <i class="fas fa-futbol" style="color: var(--primary);"></i>
                        Lapangan
                    </h3>
                    <span class="section-count">{{ $venue->venueSections->count() }} Lapangan</span>
                </div>

                @if($venue->venueSections->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-futbol"></i>
                        <p>Belum ada lapangan</p>
                    </div>
                @else
                    <div class="field-list">
                        @foreach($venue->venueSections as $section)
                            <div class="field-item">
                                <div class="field-icon">
                                    <i class="fas fa-futbol"></i>
                                </div>
                                <div class="field-info" style="flex: 1;">
                                    <div class="field-name">{{ $section->section_name ?? 'Lapangan ' . ($loop->iteration) }}</div>
                                </div>
                                <div class="field-actions">
                                    <a href="{{ route('landowner.schedule-lapangan.index', ['venue_id' => $venue->id]) }}" class="info-badge" style="background: var(--gradient-light); color: var(--primary); text-decoration: none;">
                                        Lihat Jadwal
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Spacer untuk bottom nav -->
        <div style="height: 20px;"></div>
    </div>
    
    <!-- Bottom Nav -->
    @include('layouts.bottom-nav')
@endsection

@push('scripts')
<script>
    // Smooth scroll and loading effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add fade-in animation
        const elements = document.querySelectorAll('.venue-hero, .section-card');
        elements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(10px)';
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        });
        
        setTimeout(() => {
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }, 100);
    });
</script>
@endpush
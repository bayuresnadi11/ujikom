@extends('layouts.main', ['title' => 'Sparring'])

@push('styles')
@include('buyer.communities.partials.communities-style')
<style>
    /* Override FAB container to stay within mobile container width */
    .fab-container {
        position: fixed !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 100% !important;
        max-width: 500px !important;
        bottom: 90px !important; /* Raised to avoid bottom nav */
        right: auto !important;
        pointer-events: none !important; /* Allow clicking through empty space */
        z-index: 1000;
        padding-right: 20px; /* Right margin effectively */
    }

    .fab-main {
        position: absolute !important;
        right: 20px !important;
        bottom: 0 !important;
        pointer-events: auto !important; /* Re-enable clicks */
    }

    .fab-menu {
        pointer-events: auto !important;
        right: 20px !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 480px) {
        .fab-container {
            bottom: 110px !important;
        }
    }

    /* Shrink Bottom Nav to prevent overlap - Adjusted Size */
    .bottom-nav {
        padding: 6px 0 10px !important;
        height: auto !important;
        min-height: 0 !important;
    }
    
    .nav-icon {
        width: 39px !important;
        height: 39px !important;
        font-size: 17px !important;
        margin-bottom: 3px !important;
    }

    .nav-item {
        padding: 5px 6px !important;
        font-size: 10px !important;
    }
    
    .nav-item.active {
        transform: translateY(-5px) !important;
    }

    /* Push Page Header down to avoid overlap with fixed navbar */
    .page-header {
        padding-top: 85px !important;
    }
</style>
@endpush

@section('content')
<div class="mobile-container" id="mobileContainer">
    @include('layouts.header', ['showSearch' => false])

        <main class="main-content" style="padding: 20px;">
        <div style="text-align: center; margin-top: 40px;">
            <div style="font-size: 64px; color: #667eea; margin-bottom: 20px;">
                <i class="fas fa-users"></i>
            </div>
            <h2>Sparring</h2>
            <p style="color: #666; margin-bottom: 30px;">
                Fitur untuk menemukan lawan main atau tim untuk latihan bersama
            </p>
            <p style="color: #999; font-style: italic;">
                Fitur ini sedang dalam pengembangan
            </p>
        </div>
    </main>

   <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
</div>
@endsection

@extends('layouts.main', ['title' => 'Aktivitas - ' . $community->name])

@push('styles')
<style>
    /* ================= VARIABLES ================= */
    :root {
        --primary: #0A5C36;
        --primary-dark: #08472a;
        --primary-light: #0e6b40;
        --secondary: #2ecc71;
        --accent: #27ae60;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --info: #3498db;
        --light: #F8F9FA;
        --light-gray: #E9ECEF;
        --border: #E6F7EF;
        --text: #1A3A27;
        --text-light: #6C757D;
        --text-lighter: #8A9C93;
        --shadow-sm: 0 1px 3px rgba(10, 92, 54, 0.1);
        --shadow-md: 0 2px 8px rgba(10, 92, 54, 0.15);
        --shadow-lg: 0 4px 12px rgba(10, 92, 54, 0.2);
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 10px;
        --radius-xl: 12px;
        --radius-2xl: 16px;
    }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        color: var(--text);
        line-height: 1.6;
    }

    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        max-width: 100%;
    }

    /* Responsive classes will be at the bottom for correct override behavior */

    /* ================= VISUAL HEADER ================= */
    .visual-header {
        position: relative;
        width: 100%;
        height: 240px;
        background-color: #333;
        background-size: cover;
        background-position: center;
        overflow: hidden;
    }
    
    .visual-header::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.1) 100%);
    }

    .visual-top-bar {
        position: absolute;
        top: 0; left: 0; width: 100%;
        padding: 40px 16px 20px;
        display: flex; justify-content: space-between; align-items: center;
        z-index: 10;
    }

    .visual-btn {
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2); color: white;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 18px;
    }

    /* PROFILE SECTION */
    .visual-profile-section {
        position: relative;
        margin-top: -60px;
        padding: 0 20px 20px;
        text-align: center;
        z-index: 20;
    }

    .visual-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 16px;
        border-radius: 50%;
        padding: 4px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .visual-avatar {
        width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
    }

    .visual-name {
        font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px;
    }

    .visual-meta {
        font-size: 13px; color: var(--text-light); margin-bottom: 20px;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }

    .visual-meta span:not(:last-child)::after {
        content: '•'; margin-left: 8px; color: #ccc;
    }

    .visual-main-action {
        padding: 0 20px;
        margin-bottom: 20px;
    }

    /* ================= FILTERS SECTION ================= */
    .filters-section {
        display: flex;
        gap: 12px;
        padding: 0 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .filter-select {
        flex: 1;
        padding: 10px 16px;
        border: 1px solid #eee;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #666;
        background: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .filter-select i {
        font-size: 12px;
        color: #999;
    }

    /* ================= FILTER BOTTOM SHEET ================= */
    .filter-option-group {
        padding: 16px 0;
        border-bottom: 1px dashed #eee;
    }

    .filter-option-group:last-child {
        border-bottom: none;
    }

    .filter-option-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        cursor: pointer;
    }

    .filter-option-item.indented {
        padding-left: 40px;
    }

    .filter-option-label {
        font-size: 15px;
        font-weight: 600;
        color: #1A3A27;
    }

    .filter-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #DEE2E6;
        border-radius: 50%;
        position: relative;
    }

    .filter-option-item.active .filter-radio {
        border-color: var(--primary);
    }

    .filter-option-item.active .filter-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background: var(--primary);
        border-radius: 50%;
    }

    .btn-apply-filter {
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        border: none;
        font-size: 15px;
        font-weight: 700;
        background: var(--primary);
        color: white;
        margin-top: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-apply-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* Status Filter Specific Styles (Matches Image) */
    .btn-status-apply {
        background: var(--primary); /* Changed to Green */
        color: white;
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        border: none;
        font-weight: 700;
        font-size: 16px;
        margin-top: 10px;
        transition: all 0.2s ease;
    }

    .btn-status-apply:active {
        background: var(--primary-dark);
        transform: scale(0.98);
    }

    .status-radio {
        width: 22px;
        height: 22px;
        border: 2px solid #DEE2E6;
        border-radius: 50%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-option-item.active .status-radio {
        border-color: var(--primary); /* Changed to Green */
    }

    .filter-option-item.active .status-radio::after {
        content: '';
        width: 12px;
        height: 12px;
        background: var(--primary); /* Changed to Green */
        border-radius: 50%;
    }

    .modal-header-status {
        border-bottom: 1px dashed #F1F3F5;
        padding-bottom: 16px;
        margin-bottom: 8px;
    }

    /* ================= ACTIVITY CONTENT ================= */
    .activity-content {
        padding: 40px 16px;
        text-align: center;
    }

    .activity-illustration {
        width: 240px;
        height: auto;
        margin-bottom: 24px;
        mix-blend-mode: multiply;
    }

    .activity-title {
        font-size: 20px;
        font-weight: 800;
        color: #1A3A27;
        margin-bottom: 8px;
    }

    .activity-desc {
        font-size: 14px;
        color: #6C757D;
        max-width: 280px;
        margin: 0 auto 28px;
        line-height: 1.6;
    }

    .btn-create-activity {
        background: var(--primary);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: var(--radius-lg);
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
    }

    .btn-create-activity:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: var(--primary-dark);
    }

    /* ================= BOTTOM NAV ================= */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 0 10px;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 1000;
        border-top: 1px solid var(--light-gray);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        padding: 4px 8px;
        transition: all 0.2s ease;
        border-radius: var(--radius-md);
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 48px;
        color: #999;
    }

    .nav-item.active {
        color: var(--primary);
    }

    .nav-item.active .nav-icon {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
        transform: scale(1.05);
    }

    .nav-item.active::after {
        content: "";
        position: absolute;
        top: -4px;
        width: 24px;
        height: 3px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .nav-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        background: var(--light);
        color: var(--text-light);
    }

    .nav-label {
        font-size: 10px;
        font-weight: 700;
    }

    /* ================ ACTIVITY CARD STYLES (Copied from show.blade.php) ================ */
    .card-container-activities {
        display: grid; /* Changed from 'display: none' to allow activities to show */
        grid-template-columns: 1fr;
        gap: 16px;
        margin-top: 16px;
        text-align: left;
    }

    .activity-card {
        background: white;
        border: 1px solid #E6F7EF;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(10, 92, 54, 0.05);
    }

    .activity-card-image {
        position: relative;
        height: 180px;
        width: 100%;
    }

    .activity-venue-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .activity-card-booking-code {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(10, 92, 54, 0.9);
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        z-index: 2;
    }

    .activity-card-header {
        padding: 16px 16px 8px;
    }

    .activity-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #1A3A27;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .activity-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
    }

    .activity-badge-public { background: #E8F5E9; color: #0A5C36; }
    .activity-badge-private { background: #FFF3E0; color: #E65100; }
    .activity-badge-paid { background: #E3F2FD; color: #1565C0; }
    .activity-badge-free { background: #F3E5F5; color: #7B1FA2; }
    .activity-badge-today { background: #FFF3E0; color: #E65100; }
    .activity-badge-upcoming { background: #E8F5E9; color: #0A5C36; }
    .activity-badge-active { background: #E8F5E9; color: #0A5C36; }
    .activity-badge-pending { background: #FFF3E0; color: #E65100; }
    .activity-badge-canceled { background: #FFEBEE; color: #C62828; }
    .activity-badge-community { background: #E3F2FD; color: #1565C0; }
    .activity-badge-gender { background: #F8F9FA; color: #0A5C36; }
    .activity-badge-cost { background: #FFF3E0; color: #E65100; }
    .activity-badge-host-yes { background: #E3F2FD; color: #1565C0; border: 1px solid #BBDEFB; }
    .activity-badge-host-no { background: #F8F9FA; color: #6C757D; border: 1px solid #E9ECEF; }

    .activity-card-body {
        padding: 0 16px 16px;
    }

    .activity-card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #F1F3F5;
    }

    .activity-card-row:last-child {
        border-bottom: none;
    }

    .activity-card-label {
        font-size: 13px;
        color: #6C757D;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-card-value {
        font-size: 13px;
        font-weight: 700;
        color: #1A3A27;
    }

    .activity-card-footer {
        padding: 12px 16px;
        border-top: 1px solid #F1F3F5;
    }

    .activity-btn-view {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        background: white;
        color: #0A5C36;
        border: 1px solid #E6F7EF;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .activity-btn-view:hover {
        background: #f8faf9;
        border-color: #0A5C36;
    }

    /* ================= ACTIVITY BOTTOM SHEET (Copied from show.blade.php) ================= */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: flex-end;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        max-width: 480px;
        width: 100%;
        background: white;
        border-radius: 28px 28px 0 0;
        padding-bottom: env(safe-area-inset-bottom, 24px);
        box-shadow: 0 -8px 32px rgba(0,0,0,0.1);
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        padding: 24px 20px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: #1A3A27;
    }

    .modal-close {
        background: none; border: none; font-size: 20px; color: #999; cursor: pointer;
    }

    .activity-type-card {
        display: flex; align-items: center; padding: 16px;
        border: 2px solid #F1F3F5; border-radius: 16px; margin-bottom: 12px;
        cursor: pointer; transition: all 0.2s ease;
    }

    .activity-type-card.active {
        border-color: var(--primary); background: rgba(10, 92, 54, 0.02);
    }

    .activity-type-icon-wrapper {
        width: 40px; height: 40px; background: #F8F9FA; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 16px; color: var(--primary); font-size: 18px;
    }

    .activity-type-card.active .activity-type-icon-wrapper {
        background: var(--primary); color: white;
    }

    .activity-type-info { flex: 1; text-align: left; }
    .activity-type-name { font-size: 15px; font-weight: 700; color: #1A3A27; }
    .activity-type-desc { font-size: 12px; color: #6C757D; }
    .activity-radio-indicator { width: 22px; height: 22px; border: 2px solid #DEE2E6; border-radius: 50%; }
    .activity-type-card.active .activity-radio-indicator { background: var(--primary); border-color: var(--primary); }
    
    .btn-next-activity {
        width: 100%; padding: 16px; border-radius: 12px; border: none;
        font-size: 15px; font-weight: 700; background: #DEE2E6; color: #ADB5BD;
        margin-top: 12px; cursor: not-allowed;
    }

    .btn-next-activity.active {
        background: var(--primary); color: white; cursor: pointer;
    }

    /* ================= RESPONSIVE OVERRIDES ================= */
    @media (min-width: 480px) {
        .mobile-container {
            max-width: 480px;
            margin: 10px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: var(--radius-2xl);
            overflow: hidden;
        }

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .modal-overlay .modal-content {
            max-width: 480px;
        }
    }
</style>
@endpush

@section('content')
<div class="mobile-container">
    {{-- Header Visual --}}
    <div class="visual-header" 
         @if($community->background_image)
         style="background-image: url('{{ asset('storage/' . $community->background_image) }}');"
         @else
         style="background: linear-gradient(135deg, #0A5C36 0%, #08472a 100%);"
         @endif>
        <div class="visual-top-bar">
            <a href="{{ route('buyer.communities.show', $community->id) }}" class="visual-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div style="display: flex; gap: 12px;">
                <button class="visual-btn"><i class="fas fa-share-alt"></i></button>
                @if($isManager)
                <a href="{{ route('buyer.communities.edit', $community->id) }}" class="visual-btn">
                    <i class="fas fa-cog"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Profile Section --}}
    <div class="visual-profile-section">
        <div class="visual-avatar-wrapper">
            <img src="{{ $community->logo ? asset('storage/' . $community->logo) : asset('images/default-logo.png') }}" class="visual-avatar" alt="Logo">
        </div>
        
        <h1 class="visual-name">{{ $community->name }}</h1>
        <div class="visual-meta">
            <span>{{ $community->category->category_name ?? 'Olahraga' }}</span>
            <span>{{ ucfirst($community->type) }}</span>
            <span>{{ $community->location ?? 'Belum ada lokasi' }}</span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filters-section">
        <div class="filter-select" onclick="openFilterModal()">
            <span id="currentActivityFilter">Open Play</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="filter-select" onclick="openStatusFilterModal()">
            <span id="currentStatusFilter">Semua Status</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>

    {{-- Activity Content --}}
    <div class="activity-content">
        {{-- List Section --}}
        <div id="activityListSection" class="card-container-activities" style="{{ $activities->count() > 0 ? 'display: grid;' : 'display: none;' }}">
            @foreach($activities as $playTogether)
                @php
                    $booking = $playTogether->booking;
                    $venue = $booking->venue ?? null;
                    $category = $venue->category ?? null;
                    $creator = $playTogether->creator;
                    
                    $approvedParticipants = $playTogether->participants()
                        ->where('approval_status', 'approved')
                        ->count();
                        
                    $eventDate = \Carbon\Carbon::parse($playTogether->date);
                    $isToday = $eventDate->isToday();
                    $isTomorrow = $eventDate->isTomorrow();
                @endphp

                @if($venue)
                    <div class="activity-card" data-type="main-bareng" data-date="{{ $playTogether->date->format('Y-m-d') }}">
                        <div class="activity-card-image">
                            <img src="{{ $venue->photo ? asset('storage/' . $venue->photo) : 'https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}" 
                                 alt="{{ $venue->venue_name }}" class="activity-venue-image" />
                            <div class="activity-card-booking-code">{{ $booking->ticket_code ?? 'BK-' . str_pad($playTogether->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div class="activity-card-header">
                            <div class="activity-card-title">
                                <i class="fas fa-futbol"></i>
                                {{ $venue->venue_name }}
                            </div>
                            <div class="activity-card-badges">
                                <span class="activity-badge activity-badge-{{ $playTogether->privacy }}">
                                    <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i> 
                                    {{ strtoupper($playTogether->privacy) }}
                                </span>
                                <span class="activity-badge activity-badge-{{ $playTogether->type }}">
                                    <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i> 
                                    {{ strtoupper($playTogether->type) }}
                                </span>
                                <span class="activity-badge activity-badge-{{ $playTogether->status }}">
                                    {{ strtoupper($playTogether->status) }}
                                </span>
                                @if($isToday)
                                    <span class="activity-badge activity-badge-today">
                                        <i class="fas fa-calendar-day"></i> HARI INI
                                    </span>
                                @elseif($isTomorrow)
                                    <span class="activity-badge activity-badge-upcoming">
                                        <i class="fas fa-calendar-alt"></i> BESOK
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="activity-card-body">
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-calendar-alt"></i> Tanggal
                                </div>
                                <div class="activity-card-value">
                                    {{ $eventDate->translatedFormat('d M Y') }}
                                    @if($isToday)
                                        <span class="activity-badge activity-badge-today" style="margin-left: 5px;">HARI INI</span>
                                    @endif
                                </div>
                            </div>
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-tag"></i> Kategori
                                </div>
                                <div class="activity-card-value">
                                    {{ $category->category_name ?? 'Olahraga' }}
                                </div>
                            </div>
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-users"></i> Peserta
                                </div>
                                <div class="activity-card-value">
                                    {{ $approvedParticipants }} / {{ $playTogether->max_participants }} orang
                                </div>
                            </div>
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-money-bill-wave"></i> Biaya
                                </div>
                                <div class="activity-card-value">
                                    @if($playTogether->type === 'paid')
                                        <span class="activity-badge activity-badge-cost">
                                            Rp {{ number_format($playTogether->price_per_person, 0, ',', '.') }}/orang
                                        </span>
                                    @else
                                        <span class="activity-badge activity-badge-free">GRATIS</span>
                                    @endif
                                </div>
                            </div>
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-user"></i> Host
                                </div>
                                <div class="activity-card-value">
                                    {{ $creator->name ?? 'Tidak diketahui' }}
                                </div>
                            </div>
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-venus-mars"></i> Jenis Kelamin
                                </div>
                                <div class="activity-card-value">
                                    <span class="activity-badge activity-badge-gender">
                                        @if($playTogether->gender === 'male')
                                            <i class="fas fa-mars"></i> Laki-laki
                                        @elseif($playTogether->gender === 'female')
                                            <i class="fas fa-venus"></i> Perempuan
                                        @else
                                            <i class="fas fa-venus-mars"></i> Campur
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="activity-card-row">
                                <div class="activity-card-label">
                                    <i class="fas fa-user-check"></i> Persetujuan Host
                                </div>
                                <div class="activity-card-value">
                                    @if($playTogether->host_approval)
                                        <span class="activity-badge activity-badge-host-yes">
                                            <i class="fas fa-user-check"></i> DIPERLUKAN
                                        </span>
                                    @else
                                        <span class="activity-badge activity-badge-host-no">
                                            <i class="fas fa-bolt"></i> AUTO JOIN
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="activity-card-footer">
                            <a href="{{ route('buyer.communities.main_bareng.show_community', [$community->id, $playTogether->id]) }}" class="activity-btn-view">
                                <i class="fas fa-eye"></i> Detail Aktivitas
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Empty State Section --}}
        <div id="activityEmptySection" class="activity-empty-state" style="{{ $activities->count() > 0 ? 'display: none;' : 'display: block;' }}">
            <img src="{{ asset('images/activity-empty.png') }}" alt="Empty Illustration" class="activity-illustration">
            <h3 class="activity-title">Mulai buat aktivitas!</h3>
            <p class="activity-desc">
                Semua dimulai dari aktivitas. Yuk, mulai buat aktivitas pertamamu sekarang.
            </p>
            <button class="btn-create-activity" onclick="openActivityOptionsModal()">
                Buat Aktivitas <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <div style="height: 100px;"></div>

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        <a href="{{ route('buyer.communities.show', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-user-circle"></i></div>
            <span class="nav-label">Profil</span>
        </a>
        <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-calendar-alt"></i></div>
            <span class="nav-label">Aktivitas</span>
        </a>
        <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-users"></i></div>
            <span class="nav-label">Anggota</span>
        </a>
    </nav>

    {{-- Activity Options Modal --}}
    <div class="modal-overlay activity-options-modal" id="activityOptionsModal" onclick="closeActivityOptionsModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">Aktivitas</h2>
                <button class="modal-close" onclick="closeActivityOptionsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 12px 20px 24px;">
                <div class="activity-type-card" onclick="selectType(this, 'main-bareng')">
                    <div class="activity-type-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="activity-type-info">
                        <div class="activity-type-name">Main Bareng</div>
                        <div class="activity-type-desc">Buat aktivitas dan undang partisipan untuk bergabung.</div>
                    </div>
                    <div class="activity-radio-indicator"></div>
                </div>

                <div class="activity-type-card" onclick="selectType(this, 'sparring')">
                    <div class="activity-type-icon-wrapper">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="activity-type-info">
                        <div class="activity-type-name">Sparring</div>
                        <div class="activity-type-desc">Temukan lawan tanding untuk komunitasmu.</div>
                    </div>
                    <div class="activity-radio-indicator"></div>
                </div>

                <button id="btnNextActivity" class="btn-next-activity" onclick="handleNextActivity()" disabled>
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    {{-- Status Filter Modal --}}
    <div class="modal-overlay" id="statusFilterModal" onclick="closeStatusFilterModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header modal-header-status">
                <h2 class="modal-title">Status</h2>
                <button class="modal-close" onclick="closeStatusFilterModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 0 20px 24px;">
                <div class="filter-option-group" style="border-bottom: none;">
                    <div class="filter-option-item active" onclick="selectStatusFilter(this, 'all')">
                        <span class="filter-option-label">Semua status</span>
                        <div class="status-radio"></div>
                    </div>
                    <div class="filter-option-item" onclick="selectStatusFilter(this, 'upcoming')">
                        <span class="filter-option-label">Akan Datang</span>
                        <div class="status-radio"></div>
                    </div>
                    <div class="filter-option-item" onclick="selectStatusFilter(this, 'completed')">
                        <span class="filter-option-label">Selesai</span>
                        <div class="status-radio"></div>
                    </div>
                </div>

                <button class="btn-status-apply" onclick="applyStatusFilter()">
                    Terapkan
                </button>
            </div>
        </div>
    </div>

    {{-- Activity Type Filter Modal --}}
    <div class="modal-overlay" id="filterModal" onclick="closeFilterModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">Aktivitas</h2>
                <button class="modal-close" onclick="closeFilterModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 0 20px 24px;">
                <div class="filter-option-group">
                    <div class="filter-option-item" onclick="selectFilter(this, 'tanding')">
                        <span class="filter-option-label">Tanding</span>
                        <div class="filter-radio"></div>
                    </div>
                    <div class="filter-option-item indented" onclick="selectFilter(this, 'sparring')">
                        <span class="filter-option-label" style="font-weight: 500;">Sparring</span>
                        <div class="filter-radio"></div>
                    </div>
                    <div class="filter-option-item indented" onclick="selectFilter(this, 'kompetisi')">
                        <span class="filter-option-label" style="font-weight: 500;">Kompetisi</span>
                        <div class="filter-radio"></div>
                    </div>
                </div>
                
                <div class="filter-option-group">
                    <div class="filter-option-item" onclick="selectFilter(this, 'main-bareng')">
                        <span class="filter-option-label">Main Bareng</span>
                        <div class="filter-radio"></div>
                    </div>
                </div>

                <button class="btn-apply-filter" onclick="applyFilter()">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openActivityOptionsModal() {
        document.getElementById('activityOptionsModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeActivityOptionsModal() {
        document.getElementById('activityOptionsModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function openFilterModal() {
        document.getElementById('filterModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function openStatusFilterModal() {
        document.getElementById('statusFilterModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeStatusFilterModal() {
        document.getElementById('statusFilterModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    let selectedFilterValue = 'main-bareng';
    let selectedFilterText = 'Main Bareng';
    let selectedStatusValue = 'all';
    let selectedStatusText = 'Semua Status';

    function selectFilter(element, value) {
        document.querySelectorAll('#filterModal .filter-option-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');
        selectedFilterValue = value;
        selectedFilterText = element.querySelector('.filter-option-label').innerText;
    }

    function selectStatusFilter(element, value) {
        document.querySelectorAll('#statusFilterModal .filter-option-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');
        selectedStatusValue = value;
        selectedStatusText = element.querySelector('.filter-option-label').innerText;
    }

    function applyFilter() {
        document.getElementById('currentActivityFilter').innerText = selectedFilterText;
        closeFilterModal();
        runCombinedFilter();
    }

    function applyStatusFilter() {
        document.getElementById('currentStatusFilter').innerText = selectedStatusText;
        closeStatusFilterModal();
        runCombinedFilter();
    }

    function runCombinedFilter() {
        const listSection = document.getElementById('activityListSection');
        const emptySection = document.getElementById('activityEmptySection');
        const cards = document.querySelectorAll('.activity-card');
        const todayStr = '{{ \Carbon\Carbon::today()->toDateString() }}';
        
        let hasVisibleCards = false;

        cards.forEach(card => {
            const type = card.dataset.type;
            const dateStr = card.dataset.date;
            
            let typeMatch = false;
            let statusMatch = false;

            // 1. Check Type Match
            if (selectedFilterValue === 'main-bareng') {
                if (type === 'main-bareng') typeMatch = true;
            } else {
                // Sparring/Tanding/Kompetisi flow later
                typeMatch = false; 
            }

            // 2. Check Status Match
            if (selectedStatusValue === 'all') {
                statusMatch = true;
            } else if (selectedStatusValue === 'upcoming') {
                statusMatch = dateStr >= todayStr;
            } else if (selectedStatusValue === 'completed') {
                statusMatch = dateStr < todayStr;
            }

            // 3. combined logic
            if (typeMatch && statusMatch) {
                card.style.display = 'block';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        if (hasVisibleCards) {
            listSection.style.display = 'grid';
            emptySection.style.display = 'none';
        } else {
            listSection.style.display = 'none';
            emptySection.style.display = 'block';
            
            const titleElement = emptySection.querySelector('.activity-title');
            const descElement = emptySection.querySelector('.activity-desc');
            
            if (selectedFilterValue === 'main-bareng') {
                titleElement.textContent = 'Mulai buat aktivitas!';
                descElement.textContent = 'Semua dimulai dari aktivitas. Yuk, mulai buat aktivitas pertamamu sekarang.';
                
                if (selectedStatusValue !== 'all') {
                    titleElement.textContent = 'Tidak ada aktivitas ' + selectedStatusText.toLowerCase();
                    descElement.textContent = 'Ayo buat aktivitas baru untuk komunitasmu!';
                }
            } else {
                titleElement.textContent = 'Belum ada ' + selectedFilterText;
                descElement.textContent = 'Ayo cari atau buat ' + selectedFilterText + ' pertama untuk komunitasmu!';
            }
        }
    }

    let selectedActivityType = null;
    function selectType(element, type) {
        document.querySelectorAll('.activity-type-card').forEach(card => card.classList.remove('active'));
        element.classList.add('active');
        selectedActivityType = type;
        const nextBtn = document.getElementById('btnNextActivity');
        nextBtn.classList.add('active');
        nextBtn.disabled = false;
    }

    function handleNextActivity() {
        if (!selectedActivityType) return;
        if (selectedActivityType === 'main-bareng') {
            window.location.href = "{{ route('buyer.main_bareng.index') }}";
        } else {
            alert('Fitur Sparring akan segera tersedia!');
            closeActivityOptionsModal();
        }
    }

    // Initialize default active filter
    document.addEventListener('DOMContentLoaded', () => {
        const defaultFilter = document.querySelector('.filter-option-item[onclick*="main-bareng"]');
        if (defaultFilter) {
            selectFilter(defaultFilter, 'main-bareng');
            applyFilter(); // Ensure view is updated on load
        }
    });
</script>
@endpush

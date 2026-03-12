@extends('layouts.admin')

@push('styles')
    @include('admin.dashboard.partials.style')
@endpush

@section('content')
    <div class="dashboard-page">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-line"></i>
                Dashboard Sewa Lapangan
            </h1>
            <p class="page-subtitle">Selamat datang kembali! Kelola lapangan dan pemesanan Anda dengan mudah</p>
        </div>

        <div class="welcome-banner">
            <div class="welcome-content">
                <h2 class="welcome-title">👋 Selamat Datang!</h2>
                <p class="welcome-text">
                    Sistem manajemen sewa lapangan yang modern dan efisien. 
                    Pantau semua aktivitas bisnis Anda dalam satu platform.
                </p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-database"></i>
                        Real-time
                    </div>
                </div>
                <h3 class="stat-title">Total Kategori</h3>
                <div class="stat-number">{{ $totalCategories }}</div>
                <p class="stat-description">Kategori lapangan yang tersedia</p>
                <a href="{{ route('admin.category.index') }}" class="stat-button">
                    Lihat Kategori
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-shield-alt"></i>
                        Aktif
                    </div>
                </div>
                <h3 class="stat-title">Total Komunitas</h3>
                <div class="stat-number">{{ $totalCommunities}}</div>
                <p class="stat-description">Komunitas terdaftar di sistem</p>
                <div class="detail-stats">
                    <div class="detail-item">
                        <i class="fas fa-globe"></i>
                        Public: <strong>{{ $communityPublic }}</strong>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-lock"></i>
                        Private: <strong>{{ $communityPrivate }}</strong>
                    </div>
                </div>
                <a href="{{ route('admin.community.index') }}" class="stat-button">
                    Lihat Komunitas
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-chart-line"></i>
                        Terdaftar
                    </div>
                </div>
                <h3 class="stat-title">Total Users</h3>
                <div class="stat-number">{{ $totalUsers }}</div>
                <p class="stat-description">Pengguna terdaftar dalam sistem</p>
                <div class="detail-stats">
                    <div class="detail-item">
                        <i class="fas fa-user"></i>
                        Penyewa: <strong>{{ $totalPenyewa }}</strong>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-user-tie"></i>
                        Pemilik: <strong>{{ $totalPemilik }}</strong>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-user-shield"></i>
                        Admin: <strong>{{ $totalAdmin }}</strong>
                    </div>
                </div>
                <a href="{{ route('admin.user.index') }}" class="stat-button">
                    Lihat Users
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
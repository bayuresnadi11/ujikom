@extends('layouts.main', ['title' => 'Menu'])

@push('styles')
    @include('landowner.menu.partials.menu-style')
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Menu landowner</h1>
                <p class="page-subtitle">Kelola semua kebutuhan bisnis lapangan olahraga Anda</p>
            </section>

            <!-- Menu Grid -->
            <div class="menu-grid">
                <!-- Featured Card - Kelola Lapangan -->
                <div class="menu-card featured" onclick="window.location.href='{{ route('landowner.lapangan.index') }}'">
                    <div class="menu-icon icon-primary"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="menu-content">
                        <div class="menu-badge">Fitur Utama</div>
                        <div class="menu-title">Kelola Venue</div>
                        <div class="menu-desc">Tambah, edit, dan kelola semua lapangan Anda</div>
                        <div class="menu-actions">
                            <button class="btn-menu btn-primary"
                                onclick="event.stopPropagation(); window.location.href='{{ route('landowner.lapangan.index') }}'">
                                <i class="fas fa-cogs"></i> Kelola
                            </button>
                           
                        </div>
                    </div>
                </div>

                <!-- Kelola Section -->
                <div class="menu-card" onclick="window.location.href='{{ route('landowner.section-lapangan.index') }}'">
                    <div class="menu-icon icon-success"><i class="fas fa-layer-group"></i></div>
                    <div class="menu-content">
                        <div class="menu-title">Kelola Lapangan</div>
                        <div class="menu-desc">Kelola semua lapangan dalam venue Anda</div>
                        <div class="menu-actions">
                            <button class="btn-menu btn-primary"
                                onclick="event.stopPropagation(); window.location.href='{{ route('landowner.section-lapangan.index') }}'">
                                <i class="fas fa-cogs"></i> Kelola
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Jadwal -->
                <div class="menu-card" onclick="window.location.href='{{ route('landowner.schedule.index') }}'">
                    <div class="menu-icon icon-warning"><i class="fas fa-calendar-alt"></i></div>
                    <div class="menu-content">
                        <div class="menu-title">Jadwal & Booking</div>
                        <div class="menu-desc">Kelola jadwal dan pemesanan lapangan</div>
                        <div class="menu-actions">
                            <button class="btn-menu btn-primary"
                                onclick="event.stopPropagation(); window.location.href='{{ route('landowner.schedule.index') }}'">
                                <i class="fas fa-calendar-check"></i> Lihat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Laporan & Analitik -->
                <div class="menu-card" onclick="window.location.href='{{ route('landowner.report-lapangan.index') }}'">
                    <div class="menu-icon icon-info"><i class="fas fa-chart-bar"></i></div>
                    <div class="menu-content">
                        <div class="menu-title">Laporan & Analitik</div>
                        <div class="menu-desc">Analisis performa bisnis dan pendapatan</div>
                        <div class="menu-actions">
                            <button class="btn-menu btn-primary"
                                onclick="event.stopPropagation(); window.location.href='{{ route('landowner.report-lapangan.index') }}'">
                                Lihat
                            </button>
                        </div>
                    </div>
                </div>

              
                <!-- Kelola Kasir -->
                <div class="menu-card" onclick="window.location.href='{{ route('landowner.cashier.index') }}'">
                    <div class="menu-icon icon-purple"><i class="fas fa-user-tie"></i></div>
                    <div class="menu-content">
                        <div class="menu-title">Kelola Kasir</div>
                        <div class="menu-desc">Buat dan kelola akun kasir untuk venue Anda</div>
                        <div class="menu-actions">
                            <button class="btn-menu btn-primary"
                                onclick="event.stopPropagation(); window.location.href='{{ route('landowner.cashier.index') }}'">
                                <i class="fas fa-user-plus"></i> Kelola
                            </button>
                        </div>
                    </div>
                </div>

                {{-- <!-- Bantuan & Support -->
                <div class="menu-card" onclick="showToast('Halaman Bantuan akan segera hadir', 'info')">
                    <div class="menu-icon icon-orange"><i class="fas fa-headset"></i></div>
                    <div class="menu-content">
                        <div class="menu-title">Bantuan & Support</div>
                        <div class="menu-desc">Panduan, FAQ, dan dukungan teknis</div>
                        <div class="menu-actions">
                            <button class="btn-menu btn-primary"
                                onclick="event.stopPropagation(); showToast('Fitur segera hadir', 'info')">
                                <i class="fas fa-question-circle"></i> Bantuan
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>

           

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection
    
@push('scripts')
    @include('landowner.menu.partials.menu-script')
@endpush
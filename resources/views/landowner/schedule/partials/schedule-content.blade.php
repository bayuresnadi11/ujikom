{{-- schedule-content.blade.php --}}
<!-- CSRF Token untuk JavaScript -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Toast Container -->
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span>Notification message</span>
</div>

<!-- Main App Container -->
<div class="mobile-container">
    <!-- Header -->
    <header class="mobile-header">
        <div class="header-top">
            <a href="{{ route('landowner.home') }}" class="logo">
                <i class="fas fa-futbol logo-icon"></i>
                Sewa<span>Lap</span>
            </a>
            <div class="header-actions">
                <button class="header-icon" onclick="refreshData()" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button class="header-icon" onclick="toggleAutoRefresh()" title="Auto Refresh" id="autoRefreshBtn">
                    <i class="fas fa-redo"></i>
                </button>
            </div>
        </div>
        <div class="search-bar">
            <div class="search-container">
                <input class="search-input" placeholder="Cari jadwal..." id="searchSchedule" onkeyup="searchSchedule()">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <h1 class="page-title">Jadwal & Booking</h1>
            <p class="page-subtitle" id="realTimeStatus">
            </p>
        </section>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number" id="totalBookingToday">{{ $totalBookingHariIni }}</div>
                <div class="stat-label">Booking Hari Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="jadwalAktifCount">0</div>
                <div class="stat-label">Jadwal Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-number">{{ $fieldList->sum('venue_sections_count') }}</div>
                <div class="stat-label">Total Lapangan</div>
            </div>
            <div class="stat-card" onclick="getSectionStats()" style="cursor: pointer;">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number" id="statsCounter">-</div>
                <div class="stat-label">Live Stats</div>
            </div>
        </div>

        <!-- Field & Section Selection -->
        <div class="venue-selector-card">
            <div class="venue-selector-header">
                <i class="fas fa-filter venue-selector-icon"></i>
                <h3 class="venue-selector-title">Filter Venue & Lapangan</h3>
            </div>
            
            <div class="venue-selector-form">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Pilih Venue
                    </label>
                    <select id="fieldFilter" class="venue-select" onchange="loadSections(this.value)">
                        <option value="">-- Pilih Venue --</option>
                        @forelse($fieldList as $field)
                            <option value="{{ $field->id }}" data-field="{{ $field->venue_name }}">
                                {{ $field->venue_name }}
                            </option>
                        @empty
                            <option value="">Belum ada lapangan</option>
                        @endforelse
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-layer-group"></i> Pilih Lapangan
                    </label>
                    <select id="sectionFilter" class="venue-select" onchange="loadSchedules()" disabled>
                        <option value="">-- Pilih Lapangan --</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Schedule Container -->
        <div id="scheduleContainer" style="display: none;">
            <!-- Section Header -->
            <div class="venue-selector-card" style="margin-bottom: 20px;">
                <div class="venue-selector-header">
                    <div style="flex: 1;">
                        <h3 class="venue-selector-title" id="selectedSectionTitle"></h3>
                        <p class="page-subtitle" id="selectedSectionInfo" style="margin-top: 5px;"></p>
                        <div class="real-time-indicator" id="lastUpdatedTime">
                            <i class="fas fa-sync-alt"></i>
                            <span>Updated: Just now</span>
                        </div>
                    </div>
                </div>
                <div class="venue-info-actions" style="margin-top: 15px; gap: 10px; flex-wrap: wrap;">
                    <button class="btn-add" onclick="showAddScheduleModal()" style="flex: 1; min-width: 140px;">
                        <i class="fas fa-plus-circle"></i> Generate Jadwal
                    </button>
                    <button class="clear-venue-btn" onclick="showQuickAddModal()" style="flex: 1; min-width: 140px;">
                        <i class="fas fa-bolt"></i> Tambah Manual
                    </button>
                    <button class="clear-venue-btn" onclick="exportSchedule()" style="flex: 1; min-width: 100px;">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
            </div>

            <!-- Schedule Controls - HANYA TANGGAL -->
            <div class="venue-selector-card" style="margin-bottom: 20px;">
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <!-- Date Filter -->
                    <div class="date-filter-container" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label class="form-label" style="margin-bottom: 8px; display: block;">
                                <i class="fas fa-calendar-alt"></i> Filter Tanggal
                            </label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="date" id="dateFilter" class="form-control" 
                                       value="{{ date('Y-m-d') }}" 
                                       style="flex: 1; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; color: #333;">
                                <div style="display: flex; gap: 8px;">
                                    <button class="date-picker-btn" onclick="loadSchedules()" 
                                            style="padding: 10px 16px; background: linear-gradient(135deg, #A01B42 0%, #8B1538 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <button class="clear-venue-btn" onclick="clearDateFilter()"
                                            style="padding: 10px 16px; background: #f8f9fa; color: #666; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Date Display -->
                    <div class="date-display" id="currentDateDisplay" 
                         style="background: #f8f9fa; padding: 12px 16px; border-radius: 8px; text-align: center; font-weight: 600; color: #333; border: 2px solid #e0e0e0; font-size: 16px;">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </div>
                    
                    <!-- Filter Status -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="margin-bottom: 8px; display: block;">
                            <i class="fas fa-filter"></i> Filter Status
                        </label>
                        <select id="filterStatus" class="venue-select" onchange="filterSchedule()"
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; font-size: 14px; color: #333;">
                            <option value="">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="booked">Dipesan</option>
                            <option value="expired">Sudah Lewat</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Auto Refresh Info -->
            <div id="autoRefreshInfo" class="auto-refresh-info" style="display: none;">
                <i class="fas fa-redo"></i> Auto-refresh aktif (30 detik)
            </div>

            <!-- Schedule Stats -->
            <div id="scheduleStats" class="venue-selector-card" style="margin-bottom: 20px; display: none;">
                <div class="section-date">
                    <i class="fas fa-chart-pie"></i>
                    <span id="statsText">Loading statistics...</span>
                </div>
            </div>

            <!-- Schedule List -->
            <div id="scheduleList" class="section-cards-container">
                <!-- Loading or empty state will be inserted here -->
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state">
            <i class="fas fa-calendar-times empty-state-icon"></i>
            <h3 class="empty-state-title">Pilih Venue & Lapangan</h3>
            <p class="empty-state-desc">Silakan pilih venue dan lapangan untuk melihat atau mengelola jadwal</p>
            @if($fieldList->isEmpty())
                <button class="btn-add" onclick="window.location.href='{{ route('landowner.venue.index') }}'" style="margin-top: 20px;">
                    <i class="fas fa-plus"></i> Tambah Venue
                </button>
            @endif
        </div>
    </main>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="{{ route('landowner.home') }}" class="nav-item">
            <i class="fas fa-home nav-icon"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ url('/landowner/menu') }}" class="nav-item active">
            <i class="fas fa-layer-group nav-icon"></i>
            <span>Menu</span>
        </a>
        <a href="{{ url('/landowner/chat') }}" class="nav-item">
            <i class="fas fa-comments nav-icon"></i>
            <span>Chat</span>
        </a>
        <a href="{{ route('landowner.profile') }}" class="nav-item">
            <i class="fas fa-user-circle nav-icon"></i>
            <span>Profil</span>
        </a>
    </nav>

    <!-- Include Modals -->
    @include('landowner.schedule.partials.modals.add-schedule')
    @include('landowner.schedule.partials.modals.quick-add')
    @include('landowner.schedule.partials.modals.edit-schedule')

    @include('landowner.schedule.partials.modals.stats')
    @include('landowner.schedule.partials.modals.loading')
    @include('landowner.schedule.partials.modals.batch-status')
</div>
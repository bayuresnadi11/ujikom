@extends('layouts.main', ['title' => 'Schedule & Booking'])

@push('styles')
    @include('landowner.schedule.partials.schedule-style')
@endpush

@section('content')
<!-- CSRF Token untuk JavaScript -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Main App Container -->
<div class="mobile-container">
    <!-- Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <h1 class="page-title">Jadwal & Booking</h1>
            <p class="page-subtitle" id="realTimeStatus">Kelola jadwal lapangan Anda</p>
        </section>
        
        {{-- Notifikasi ditangani otomatis oleh komponen toast-alert di layout --}}

        <!-- Stats Overview -->
        <div class="stats-grid">
            {{-- Menampilkan sekilas jumlah booking hari ini dan total lapangan yang dikelola --}}
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number" id="totalBookingToday">{{ $totalBookingHariIni }}</div>
                <div class="stat-label">Booking Hari Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                {{-- Menghitung total semua lapangan berdasarkan atribut venue_sections_count --}}
                <div class="stat-number">{{ $fieldList->sum('venue_sections_count') }}</div>
                <div class="stat-label">Total Lapangan</div>
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
                            <option value="{{ $field->id }}" data-field="{{ $field->venue_name }}" {{ request('venue_id') == $field->id ? 'selected' : '' }}>
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
            <!-- Actions -->
            <div class="venue-selector-card" style="margin-bottom: 20px;">
                <div class="venue-info-actions" style="margin-top: 15px; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('landowner.schedule.generate') }}" class="btn-add" style="flex: 1; min-width: 140px; text-decoration:none;">
                        <i class="fas fa-magic"></i> Generate Jadwal
                    </a>
                    <a href="{{ route('landowner.schedule.create') }}" class="clear-venue-btn" style="flex: 1; min-width: 140px; text-align:center; justify-content:center; text-decoration:none;">
                        <i class="fas fa-plus"></i> Tambah Manual
                    </a>
                </div>
            </div>

            <!-- Date Filter & List (Moved from partial) -->
            <div class="venue-selector-card" style="margin-bottom: 20px;">
                <div class="form-group">
                     <label class="form-label">
                        <i class="fas fa-calendar-alt"></i> Filter Tanggal
                    </label>
                    <input type="date" id="dateFilter" class="form-control" value="{{ date('Y-m-d') }}" onchange="loadSchedules()">
                </div>
                <div class="date-display" id="currentDateDisplay" style="text-align:center; font-weight:bold; padding:10px;"></div>
            </div>
            
            <!-- Schedule List -->
            <div id="scheduleList" class="section-cards-container">
                <!-- Loaded via AJAX -->
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state">
            <i class="fas fa-calendar-times empty-state-icon"></i>
            <h3 class="empty-state-title">Pilih Venue & Lapangan</h3>
            <p class="empty-state-desc">Silakan pilih venue dan lapangan untuk melihat atau mengelola jadwal</p>
        </div>
    </main>

    <!-- Bottom Nav -->
    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
@include('landowner.schedule.partials.schedule-script')
    <script>
        // Fungsi untuk memperbarui link URL pada tombol "Generate Jadwal" & "Tambah Manual" agar membawa parameter venue & section
        function updateActionLinks() {
            const venueId = document.getElementById('fieldFilter').value;
            const sectionId = document.getElementById('sectionFilter').value;
            
            const generateBtn = document.querySelector('a[href*="landowner/schedule/generate"]');
            const createBtn = document.querySelector('a[href*="landowner/schedule/create"]');
            
            // Menyusun query parameter berdasarkan pilihan Dropdown
            let queryParams = [];
            if (venueId) queryParams.push(`venue_id=${venueId}`);
            if (sectionId) queryParams.push(`section_id=${sectionId}`);
            
            const queryString = queryParams.length > 0 ? '?' + queryParams.join('&') : '';
            
            const generateBaseUrl = "{{ route('landowner.schedule.generate') }}";
            const createBaseUrl = "{{ route('landowner.schedule.create') }}";
            
            // Terapkan URL baru beserta query param ke dalam tombol
            if (generateBtn) generateBtn.href = generateBaseUrl + queryString;
            if (createBtn) createBtn.href = createBaseUrl + queryString;
        }

        // Hook into existing loadSections and loadSchedules or observe changes
        // Since loadSections is defined in partial, we can add an event listener to the selects
        document.getElementById('fieldFilter').addEventListener('change', updateActionLinks);
        document.getElementById('sectionFilter').addEventListener('change', updateActionLinks);
        
        // Call once on load
        document.addEventListener('DOMContentLoaded', updateActionLinks);
    </script>
@endpush
@extends('layouts.main', ['title' => 'Lapangan'])

@push('styles')
    @include('landowner.section.partials.section-style')
@endpush

@section('content')
    <!-- CSRF Token untuk JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Notifikasi ditangani otomatis oleh komponen toast-alert di layout --}}

    <!-- Main Container -->
    <div class="mobile-container">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Lapangan</h1>
                <p class="page-subtitle">Kelola semua lapangan dalam venue Anda</p>
            </section>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card" onclick="window.location.href='{{ route('landowner.venue.index') }}'">
                    <div class="stat-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-number">{{ $totalVenues }}</div>
                    <div class="stat-label">Total Venue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-number">{{ $totalSections }}</div>
                    <div class="stat-label">Total Lapangan</div>
                </div>
            </div>

            <!-- Venue Selector -->
            <div class="venue-selector-card">
                <div class="venue-selector-header">
                    <i class="fas fa-map-marker-alt venue-selector-icon"></i>
                    <h3 class="venue-selector-title">Pilih Venue</h3>
                </div>
                
                @if($venues->isNotEmpty())
                    <form method="GET" action="{{ route('landowner.section-lapangan.index') }}" class="venue-selector-form">
                        <div class="form-group">
                            <select name="venue_id" class="venue-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->venue_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('venue_id') && $selectedVenue)
                            <div class="venue-info">
                                <div class="venue-info-content">
                                    <i class="fas fa-info-circle venue-info-icon"></i>
                                    <div class="venue-info-text">
                                        Mengelola lapangan untuk: <strong>{{ $selectedVenue->venue_name }}</strong>
                                    </div>
                                </div>
                                <div class="venue-info-actions">
                                    <button type="button" onclick="window.location.href='{{ route('landowner.section-lapangan.index') }}'" class="clear-venue-btn">
                                        <i class="fas fa-sync-alt"></i> Pilih Venue Lain
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                @else
                    <div class="empty-venue-state">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Belum ada venue. Tambahkan venue terlebih dahulu untuk mulai mengelola lapangan.</p>
                        <a href="{{ route('landowner.venue.index') }}" class="btn-primary">
                            <i class="fas fa-plus"></i> Tambah Venue Pertama
                        </a>
                    </div>
                @endif
            </div>

            {{-- Notifikasi ditangani otomatis oleh komponen toast-alert di layout --}}

            <!-- Section Cards -->
            @if(request('venue_id') && $venues->isNotEmpty())
                <div class="action-bar">
                    <button class="btn-add" onclick="window.location.href='{{ route('landowner.section-lapangan.create', ['venue_id' => request('venue_id')]) }}'">
                        <i class="fas fa-plus-circle"></i> Tambah Lapangan Baru
                    </button>
                </div>

                <div class="section-cards-container" id="sectionCards">
                    @if($sections->count() > 0)
                        @foreach($sections as $section)
                            <div class="section-card" 
                                 data-section-name="{{ strtolower($section->section_name) }}"
                                 onclick="window.location.href='{{ route('landowner.section-lapangan.edit', $section->id) }}'">
                                <!-- Section Header -->
                                <div class="section-header">
                                    <div class="section-title-wrapper">
                                        <h3 class="section-title">{{ $section->section_name }}</h3>
                                        <div class="section-venue">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $section->venue->venue_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="section-actions">
                                        <button class="card-action-btn btn-edit-card" 
                                                onclick="event.stopPropagation(); window.location.href='{{ route('landowner.section-lapangan.edit', $section->id) }}'" 
                                                title="Edit Section">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('landowner.section-lapangan.destroy', $section->id) }}" method="POST" 
                                              style="display: inline;" 
                                              onsubmit="event.preventDefault(); confirmDelete('Hapus Lapangan?', 'Apakah Anda yakin ingin menghapus lapangan \'{{ $section->section_name }}\'? Semua jadwal terkait akan dihapus.').then(res => { if(res.isConfirmed) this.submit(); })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="card-action-btn btn-delete-card" 
                                                    onclick="event.stopPropagation();"
                                                    title="Hapus Section">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Section Content -->
                                <div class="section-content">
                                    @if($section->description)
                                        <div class="section-description">
                                            {{ $section->description }}
                                        </div>
                                    @endif
                                    
                                    <!-- Simple Section Badges -->
                                   
                                </div>
                                
                                <!-- Section Footer -->
                                <div class="section-footer">
                                    <div class="section-date">
                                        <i class="far fa-calendar"></i>
                                        {{ $section->created_at->format('d M Y') }}
                                    </div>
                                    <div class="section-status">
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <i class="fas fa-layer-group empty-state-icon"></i>
                            <h3 class="empty-state-title">Belum ada lapangan</h3>
                            <p class="empty-state-desc">Tambahkan lapangan pertama Anda untuk venue ini</p>
                            <button class="btn-add" onclick="window.location.href='{{ route('landowner.section-lapangan.create', ['venue_id' => request('venue_id')]) }}'">
                                <i class="fas fa-plus-circle"></i> Tambah Lapangan Pertama
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State - Pilih Venue -->
                <div class="empty-state">
                    <i class="fas fa-map-marker-alt empty-state-icon"></i>
                    <h3 class="empty-state-title">Pilih Venue Terlebih Dahulu</h3>
                    <p class="empty-state-desc">Silakan pilih venue dari dropdown di atas untuk melihat atau mengelola lapangan</p>
                </div>
            @endif
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('landowner.section.partials.section-script')
@endpush
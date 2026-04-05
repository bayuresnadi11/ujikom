@extends('layouts.main', ['title' => 'Kelola Venue'])

{{-- Menghitung total lapangan (sections) dan total jadwal booking dari keseluruhan venue yang dimiliki --}}
@php
    $totalSections = 0;
    $totalBookings = 0;
    foreach($venues as $venue) {
        $totalSections += $venue->venue_sections_count ?? 0;
        $totalBookings += $venue->bookings_count ?? 0;
    }
@endphp


@push('styles')
    @include('landowner.venue.partials.venue-style')
    <style>
        /* Slider Styles */
        .venue-slider {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .venue-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: none;
        }
        .venue-slide.active {
            opacity: 1;
            display: block;
        }
        .venue-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .slider-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 5px;
            z-index: 2;
        }
        .slider-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background 0.3s;
        }
        .slider-dot.active {
            background: white;
            transform: scale(1.2);
        }

        /* Section Management Styles */
        .section-management {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: none;
        }
        
        .section-management.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .section-management-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .section-management-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-management-title i {
            color: var(--primary);
        }
        
        .section-list {
            margin-top: 15px;
        }
        
        .section-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary);
            transition: all 0.2s;
        }
        
        .section-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .section-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .section-name {
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }
        
        .section-actions {
            display: flex;
            gap: 8px;
        }
        
        .section-action-btn {
            background: none;
            border: none;
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        
        .section-action-btn.edit {
            background: #ffc107;
            color: white;
        }
        
        .section-action-btn.delete {
            background: #dc3545;
            color: white;
        }
        
        .section-action-btn:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
        
        .section-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
            margin-top: 5px;
        }
        
        .empty-sections {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }
        
        .empty-sections i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #dee2e6;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .clear-venue-btn {
            background: #fff;
            border: 1px solid #ddd;
            color: #666;
            padding: 10px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .clear-venue-btn:hover {
            background: #f1f1f1;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    </style>
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
                <h1 class="page-title">Kelola Venue</h1>
                <p class="page-subtitle">Kelola semua venue dan lapangan Anda</p>
            </section>

            <!-- Stats Overview -->
            <div class="stats-overview">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $venues->count() }}</div>
                        <div class="stat-label">Total Venue</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalSections }}</div>
                        <div class="stat-label">Total Lapangan</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <button class="btn-add" onclick="window.location.href='{{ route('landowner.venue.create') }}'">
                    <i class="fas fa-plus-circle"></i> Tambah Venue Baru
                </button>
            </div>

            <!-- Venue List -->
            @if($venues->count() > 0)
                <div class="venue-list" id="venueCards">
                    {{-- Me-looping semua data venue yang ada dan menampilkan masing-masing dalam wujud Card UI --}}
                    @foreach($venues as $venue)
                        @php
                            // Format nama kategori agar bisa dijadikan class CSS
                            $categoryClass = strtolower($venue->category->category_name ?? 'umum');
                            $categoryClass = preg_replace('/[^a-z]/', '', $categoryClass);
                            $status = 'active';
                           
                            
                            // Ambil photos

                            $photos = $venue->photos;
                            if ($photos->isEmpty() && $venue->photo) {
                                $photos = collect([ (object)['photo_path' => $venue->photo] ]);
                            }
                            
                            // Ambil sections untuk venue ini
                            $venueSections = $venue->venueSections ?? collect([]);
                        @endphp
                        
                        <div class="venue-card {{ $categoryClass }}" 
                             data-venue-name="{{ strtolower($venue->venue_name) }}" 
                             data-venue-location="{{ strtolower($venue->location) }}"
                             data-category="{{ $categoryClass }}">
                            
                            <!-- Venue Image Slider -->
                            <div class="venue-image-container" id="slider-{{ $venue->id }}">
                                @if($photos->count() > 0)
                                    <div class="venue-slider">
                                        @foreach($photos as $index => $photo)
                                            <div class="venue-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                     alt="{{ $venue->venue_name }}"
                                                     onerror="this.parentElement.innerHTML='<div class=\'no-photo-placeholder\'><i class=\'fas fa-image\'></i><span>Error loading</span></div>'">
                                            </div>
                                        @endforeach
                                        
                                        @if($photos->count() > 1)
                                            <div class="slider-dots">
                                                @foreach($photos as $index => $photo)
                                                    <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" 
                                                         onclick="event.stopPropagation(); changeSlide({{ $venue->id }}, {{ $index }})"></div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="no-photo-placeholder">
                                        <i class="fas fa-image"></i>
                                        <span>Tidak ada foto</span>
                                    </div>
                                @endif
                                
                                <!-- Venue Badge -->
                                <div class="venue-badge">
                                    <i class="fas fa-tag"></i> {{ $venue->category->category_name ?? 'Umum' }}
                                </div>
                                
                                <!-- Rating -->
                                @if($venue->rating)
                                    <div class="venue-rating">
                                        <i class="fas fa-star"></i>
                                        {{ number_format($venue->rating, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Venue Info -->
                            <div class="venue-info">
                                <!-- Header -->
                                <div class="venue-header">
                                    <h3 class="venue-name">{{ $venue->venue_name }}</h3>
                                </div>
                                
                                <!-- Location -->
                                <div class="venue-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $venue->location }}
                                </div>
                                
                                <!-- Description -->
                                <p class="venue-description">
                                    {{ Str::limit($venue->description, 100) }}
                                </p>
                                
                                <!-- Meta Info -->
                                <div class="venue-meta">
                                    <div class="venue-meta-item">
                                        <i class="fas fa-layer-group meta-icon"></i>
                                        <span class="meta-value">{{ $venue->venue_sections_count ?? 0 }}</span>
                                        <span class="meta-label">Lapangan</span>
                                    </div>
                                    <div class="venue-meta-item">
                                        <i class="fas fa-calendar-alt meta-icon"></i>
                                        <span class="meta-value">{{ $venue->bookings_count ?? 0 }}</span>
                                        <span class="meta-label">Booking</span>
                                    </div>
                                    <div class="venue-meta-item">
                                        <i class="fas fa-star meta-icon"></i>
                                        <span class="meta-value">{{ $venue->rating ? number_format($venue->rating, 1) : '0.0' }}</span>
                                        <span class="meta-label">Rating</span>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="venue-actions">
                                    <button class="btn-venue-action btn-warning" 
                                            onclick="window.location.href='{{ route('landowner.venue.edit', $venue->id) }}'">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('landowner.venue.destroy', $venue->id) }}" method="POST" 
                                          style="display: contents;" 
                                          onsubmit="event.preventDefault(); confirmDelete('Hapus Venue?', 'Apakah Anda yakin ingin menghapus venue \'{{ $venue->venue_name }}\'? Semua lapangan dan jadwal terkait akan dihapus.').then(res => { if(res.isConfirmed) this.submit(); })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-venue-action btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                    
                                    <button class="btn-venue-action btn-info manage-sections-btn" 
                                            data-venue-id="{{ $venue->id }}"
                                            data-venue-name="{{ $venue->venue_name }}">
                                        <i class="fas fa-layer-group"></i> Kelola Lapangan
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section Management Panel (hidden by default) -->
                        <div class="section-management" id="section-management-{{ $venue->id }}">
                            <div class="section-management-header">
                                <h3 class="section-management-title">
                                    <i class="fas fa-layer-group"></i>
                                    Lapangan untuk {{ $venue->venue_name }}
                                </h3>
                                <button class="btn-add btn-sm" onclick="showAddSectionForm({{ $venue->id }})">
                                    <i class="fas fa-plus"></i> Tambah Lapangan
                                </button>
                            </div>
                            
                            <!-- Form Tambah Lapangan (hidden by default) -->
                            <div class="form-section" id="add-section-form-{{ $venue->id }}" style="display: none;">
                                <h4 style="margin-bottom: 15px; color: #333;">
                                    <i class="fas fa-plus-circle"></i> Tambah Lapangan Baru
                                </h4>
                                <form action="{{ route('landowner.section-lapangan.store') }}" method="POST" id="add-section-form-submit-{{ $venue->id }}" onsubmit="return validateAddSection(event, {{ $venue->id }})">
                                    @csrf
                                    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                                    
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-signature"></i>
                                            Nama Lapangan *
                                        </label>
                                        <input type="text" class="form-control" 
                                               name="section_name" id="add-section-name-{{ $venue->id }}"
                                               placeholder="Contoh: Lapangan A">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-align-left"></i>
                                            Deskripsi *
                                        </label>
                                        <textarea class="form-control" 
                                                  name="description" id="add-section-description-{{ $venue->id }}" rows="3" 
                                                  placeholder="Deskripsi lapangan..."></textarea>
                                    </div>
                                    
                                    <div class="form-group" style="display: flex; gap: 10px; margin-top: 15px;">
                                        <button type="submit" class="btn-add">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <button type="button" class="clear-venue-btn" onclick="hideAddSectionForm({{ $venue->id }})">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- List of Sections -->
                            <div class="section-list">
                                @if($venueSections->count() > 0)
                                    @foreach($venueSections as $section)
                                        <div class="section-item" id="section-{{ $section->id }}">
                                            <div class="section-item-header">
                                                <h4 class="section-name">{{ $section->section_name }}</h4>
                                                <div class="section-actions">
                                                    <button class="section-action-btn edit" 
                                                            onclick="editSection({{ $section->id }}, '{{ $section->section_name }}', '{{ $section->description }}')">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('landowner.section-lapangan.destroy', $section->id) }}" 
                                                          method="POST" 
                                                          onsubmit="event.preventDefault(); confirmDelete('Hapus Lapangan?', 'Apakah Anda yakin ingin menghapus lapangan \'{{ $section->section_name }}\'? Semua jadwal terkait akan dihapus.').then(res => { if(res.isConfirmed) this.submit(); })"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="section-action-btn delete">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            @if($section->description)
                                                <div class="section-description">
                                                    {{ $section->description }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Form Edit Section (hidden by default) -->
                                        <div class="form-section" id="edit-section-form-{{ $section->id }}" style="display: none;">
                                            <h4 style="margin-bottom: 15px; color: #333;">
                                                <i class="fas fa-edit"></i> Edit Lapangan
                                            </h4>
                                            <form action="{{ route('landowner.section-lapangan.update', $section->id) }}" method="POST" id="edit-section-form-submit-{{ $section->id }}" onsubmit="return validateEditSection(event, {{ $section->id }})">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-signature"></i>
                                                        Nama Lapangan *
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           name="section_name" 
                                                           id="edit-section-name-{{ $section->id }}"
                                                           value="{{ $section->section_name }}">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-align-left"></i>
                                                        Deskripsi *
                                                    </label>
                                                    <textarea class="form-control" 
                                                              name="description" 
                                                              id="edit-section-description-{{ $section->id }}"
                                                              rows="3">{{ $section->description }}</textarea>
                                                </div>
                                                
                                                <div class="form-group" style="display: flex; gap: 10px; margin-top: 15px;">
                                                    <button type="submit" class="btn-add">
                                                        <i class="fas fa-save"></i> Simpan Perubahan
                                                    </button>
                                                    <button type="button" class="clear-venue-btn" onclick="hideEditSectionForm({{ $section->id }})">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-sections" id="empty-sections-{{ $venue->id }}">
                                        <i class="fas fa-layer-group"></i>
                                        <p>Belum ada lapangan untuk venue ini</p>
                                        <button class="btn-add btn-sm" onclick="showAddSectionForm({{ $venue->id }})">
                                            <i class="fas fa-plus"></i> Tambah Lapangan Pertama
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-futbol empty-state-icon"></i>
                    <h3 class="empty-state-title">Belum ada venue</h3>
                    <p class="empty-state-desc">Tambahkan venue pertama Anda untuk memulai</p>
                    <button class="btn-add" onclick="window.location.href='{{ route('landowner.venue.create') }}'">
                        <i class="fas fa-plus-circle"></i> Tambah Venue Pertama
                    </button>
                </div>
            @endif
        </main>

        <!-- Bottom Nav akan dimasukkan otomatis oleh layout -->
         @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('landowner.venue.partials.venue-script')
    <script>
        // Auto slider logic
        const sliders = {};

        function initSlider(venueId, totalSlides) {
            if (totalSlides <= 1) return;
            
            sliders[venueId] = {
                current: 0,
                total: totalSlides,
                interval: setInterval(() => nextSlide(venueId), 3000)
            };
        }
        
        function nextSlide(venueId) {
            if (!sliders[venueId]) return;
            const next = (sliders[venueId].current + 1) % sliders[venueId].total;
            changeSlide(venueId, next);
        }

        function changeSlide(venueId, index) {
            if (!sliders[venueId]) return;
            
            sliders[venueId].current = index;
            
            const container = document.getElementById(`slider-${venueId}`);
            if (!container) return;
            
            const slides = container.querySelectorAll('.venue-slide');
            slides.forEach(s => s.classList.remove('active'));
            if (slides[index]) slides[index].classList.add('active');
            
            const dots = container.querySelectorAll('.slider-dot');
            dots.forEach(d => d.classList.remove('active'));
            if (dots[index]) dots[index].classList.add('active');
        }

        // Section Management Functions
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sliders
            const sliderContainers = document.querySelectorAll('.venue-image-container');
            sliderContainers.forEach(container => {
                const id = container.id.replace('slider-', '');
                const slides = container.querySelectorAll('.venue-slide').length;
                if (slides > 1) {
                    initSlider(id, slides);
                }
            });
            
            // Manage sections button click
            document.querySelectorAll('.manage-sections-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const venueId = this.getAttribute('data-venue-id');
                    toggleSectionManagement(venueId);
                });
            });
            
            // Close section management when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.section-management') && 
                    !e.target.closest('.manage-sections-btn')) {
                    document.querySelectorAll('.section-management').forEach(panel => {
                        panel.classList.remove('active');
                    });
                }
            });
        });
        
        function toggleSectionManagement(venueId) {
            const panel = document.getElementById(`section-management-${venueId}`);
            const isActive = panel.classList.contains('active');
            
            // Close all other panels
            document.querySelectorAll('.section-management').forEach(p => {
                p.classList.remove('active');
            });
            
            // Toggle current panel
            if (!isActive) {
                panel.classList.add('active');
                panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
        
        function showAddSectionForm(venueId) {
            document.getElementById(`add-section-form-${venueId}`).style.display = 'block';
            
            // Hide header button if exists (global add button)
            // Note: In empty state, the header button might not be the concern, but let's focus on the empty state button logic
            
            // Hide empty state container if exists
            const emptyState = document.getElementById(`empty-sections-${venueId}`);
            if (emptyState) {
                emptyState.style.display = 'none';
            }

            document.getElementById(`add-section-form-${venueId}`).scrollIntoView({ behavior: 'smooth' });
        }
        
        function hideAddSectionForm(venueId) {
            document.getElementById(`add-section-form-${venueId}`).style.display = 'none';
            
            // Show empty state container if exists
            const emptyState = document.getElementById(`empty-sections-${venueId}`);
            if (emptyState) {
                emptyState.style.display = 'block';
            }
        }
        
        function editSection(sectionId, currentName, currentDescription) {
            // Hide all edit forms first
            document.querySelectorAll('.form-section[id^="edit-section-form-"]').forEach(form => {
                form.style.display = 'none';
            });
            
            const form = document.getElementById(`edit-section-form-${sectionId}`);
            form.style.display = 'block';
            form.scrollIntoView({ behavior: 'smooth' });
            
            // Set values
            document.getElementById(`edit-section-name-${sectionId}`).value = currentName;
            document.getElementById(`edit-section-description-${sectionId}`).value = currentDescription || '';
        }
        
        function hideEditSectionForm(sectionId) {
            document.getElementById(`edit-section-form-${sectionId}`).style.display = 'none';
        }
        
        // confirmDelete is now handled globally by toast-alert component

        function validateAddSection(e, venueId) {
            const form = document.getElementById(`add-section-form-submit-${venueId}`);
            form.querySelectorAll('.js-error').forEach(el => el.remove());
            form.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            const nameInput = document.getElementById(`add-section-name-${venueId}`);
            const descInput = document.getElementById(`add-section-description-${venueId}`);
            
            if (!nameInput.value.trim()) {
                nameInput.style.borderColor = '#E74C3C';
                let formGroup = nameInput.closest('.form-group');
                if (formGroup) {
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Nama lapangan belum diisi`;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }

            if (!descInput.value.trim()) {
                descInput.style.borderColor = '#E74C3C';
                let formGroup = descInput.closest('.form-group');
                if (formGroup) {
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Deskripsi belum diisi`;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
            return true;
        }

        function validateEditSection(e, sectionId) {
            const form = document.getElementById(`edit-section-form-submit-${sectionId}`);
            form.querySelectorAll('.js-error').forEach(el => el.remove());
            form.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            const nameInput = document.getElementById(`edit-section-name-${sectionId}`);
            const descInput = document.getElementById(`edit-section-description-${sectionId}`);
            
            if (!nameInput.value.trim()) {
                nameInput.style.borderColor = '#E74C3C';
                let formGroup = nameInput.closest('.form-group');
                if (formGroup) {
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Nama lapangan belum diisi`;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }

            if (!descInput.value.trim()) {
                descInput.style.borderColor = '#E74C3C';
                let formGroup = descInput.closest('.form-group');
                if (formGroup) {
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Deskripsi belum diisi`;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
            return true;
        }
    </script>
@endpush
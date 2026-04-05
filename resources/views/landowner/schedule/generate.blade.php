@extends('layouts.main', ['title' => 'Generate Jadwal'])

@push('styles')
    @include('landowner.schedule.partials.schedule-style')
    <style>
        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-top: 8px;
        }
        .day-checkbox {
            display: none;
        }
        .day-label {
            display: block;
            padding: 10px 0;
            text-align: center;
            background: white;
            border: 2px solid var(--light-gray);
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 13px;
            transition: all 0.2s;
        }
        .day-checkbox:checked + .day-label {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.schedule.index') }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Generate Jadwal</h1>
                <p class="page-subtitle">
                    @if(isset($selectedSection) && $selectedSection)
                        <span style="display: flex; align-items: center; gap: 6px; font-weight: 600; color: var(--primary);">
                            <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i>
                            {{ $selectedSection->venue->venue_name }} - {{ $selectedSection->section_name }}
                        </span>
                    @else
                        Buat jadwal otomatis untuk lapangan Anda
                    @endif
                </p>
            </section>


                    <form action="{{ route('landowner.schedule.generate') }}" method="POST" id="generateScheduleForm" novalidate>
                    @csrf
                    
                    @if(isset($selectedSection) && $selectedSection)
                        <!-- Hidden Inputs - Context Preserved -->
                        <input type="hidden" name="venue_id" value="{{ $selectedSection->venue_id }}">
                        <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">
                    @else
                        <!-- Section 1: Pilih Lapangan (Only if not pre-selected) -->
                        <div class="form-section-card">
                            <h3 class="data-group-title">
                                <i class="fas fa-map-marker-alt"></i> Pilih Lapangan
                            </h3>
                            
                            <div class="form-group">
                                <label class="form-label">Venue</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-building input-icon"></i>
                                    <select id="venue_id" class="venue-select form-control with-icon" onchange="loadSections(this.value)">
                                        <option value="">-- Pilih Venue --</option>
                                        @foreach($venues as $venue)
                                            <option value="{{ $venue->id }}" {{ $selectedVenueId == $venue->id ? 'selected' : '' }}>{{ $venue->venue_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Lapangan</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-layer-group input-icon"></i>
                                    <select name="section_id" id="section_id" class="venue-select form-control with-icon" {{ !$selectedVenueId ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Lapangan --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Section 2: Periode Tanggal -->
                    <div class="form-section-card">
                        <h3 class="data-group-title">
                            <i class="fas fa-calendar-alt"></i> Periode Tanggal
                        </h3>
                        
                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label class="form-label">Dari Tanggal</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-calendar-check input-icon"></i>
                                    <input type="date" name="start_date" class="form-control with-icon" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sampai Tanggal</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-calendar-times input-icon"></i>
                                    <input type="date" name="end_date" class="form-control with-icon" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Hari Aktif</label>
                            <div class="days-grid">
                                {{-- Melakukan loop untuk checkbox hari agar user bisa memilih hari aktif (generate massal) --}}
                                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $index => $day)
                                    <label>
                                        <input type="checkbox" name="active_days[]" value="{{ $index }}" class="day-checkbox" checked>
                                        <span class="day-label">{{ $day }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Waktu & Harga -->
                    <div class="form-section-card">
                        <h3 class="data-group-title">
                            <i class="fas fa-clock"></i> Waktu & Harga
                        </h3>
                        
                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label class="form-label">Jam Mulai</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-hourglass-start input-icon"></i>
                                    <input type="time" name="start_time" class="form-control with-icon" value="08:00">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jam Selesai</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-hourglass-end input-icon"></i>
                                    <input type="time" name="end_time" class="form-control with-icon" value="22:00">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Durasi per Sesi (Jam)</label>
                            <div class="input-group-icon">
                                <i class="fas fa-stopwatch input-icon"></i>
                                <input type="number" name="rental_duration" class="form-control with-icon" value="1" min="1" max="24">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Harga Sewa (Rp)</label>
                            <div class="input-group-icon">
                                <i class="fas fa-money-bill-wave input-icon"></i>
                                <input type="number" name="rental_price" class="form-control with-icon" min="0" placeholder="Contoh: 150000">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status Awal</label>
                            <div class="input-group-icon">
                                <i class="fas fa-toggle-on input-icon"></i>
                                <select name="available" class="form-control with-icon">
                                    <option value="1">Tersedia</option>
                                    <option value="0">Tidak Tersedia (Booked/Maintenance)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-add" style="box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);">
                            <i class="fas fa-magic"></i> Generate Jadwal
                        </button>
                    </div>
                    </div>
                </form>
                </div>
            </div>
        </main>
    </div>
            @include('layouts.bottom-nav')

    @push('scripts')
    <script>
        function loadSections(venueId, selectedSectionId = null) {
            const sectionSelect = document.getElementById('section_id');
            sectionSelect.innerHTML = '<option value="">Loading...</option>';
            sectionSelect.disabled = true;

            if (!venueId) {
                sectionSelect.innerHTML = '<option value="">-- Pilih Lapangan --</option>';
                return;
            }

            fetch(`{{ url('landowner/schedule/sections') }}/${venueId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        sectionSelect.innerHTML = '<option value="">-- Pilih Lapangan --</option>';
                        data.sections.forEach(section => {
                            const isSelected = selectedSectionId && selectedSectionId == section.id ? 'selected' : '';
                            sectionSelect.innerHTML += `<option value="${section.id}" ${isSelected}>${section.section_name}</option>`;
                        });
                        sectionSelect.disabled = false;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Auto-load if params exist
        document.addEventListener('DOMContentLoaded', function() {
            const selectedVenueId = "{{ $selectedVenueId ?? '' }}";
            const selectedSectionId = "{{ $selectedSectionId ?? '' }}";
            
            if (selectedVenueId) {
                const venueSelect = document.getElementById('venue_id');
                if (venueSelect) {
                    venueSelect.value = selectedVenueId;
                    loadSections(selectedVenueId, selectedSectionId);
                }
            }
        });

        document.getElementById('generateScheduleForm').addEventListener('submit', function(e) {
            // Menghapus elemen error sebelumnya sebelum melakukan validasi ulang
            document.querySelectorAll('.js-error').forEach(el => el.remove());
            document.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            
            // Fungsi internal untuk menampilkan pesan error sesuai elemen input yang kosong/salah value
            function addError(element, message) {
                element.style.borderColor = '#E74C3C';
                let formGroup = element.closest('.form-group');
                if (formGroup) {
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }

            const venueSelect = document.getElementById('venue_id');
            const sectionSelect = document.getElementById('section_id');
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');
            const startTimeInput = document.querySelector('input[name="start_time"]');
            const endTimeInput = document.querySelector('input[name="end_time"]');
            const rentalDurationInput = document.querySelector('input[name="rental_duration"]');
            const rentalPriceInput = document.querySelector('input[name="rental_price"]');

            if (venueSelect && !venueSelect.value) {
                addError(venueSelect, 'Venue belum dipilih');
            }
            if (sectionSelect && !sectionSelect.value) {
                addError(sectionSelect, 'Lapangan belum dipilih');
            }
            if (startDateInput && !startDateInput.value) {
                addError(startDateInput, 'Tanggal mulai belum diisi');
            }
            if (endDateInput && !endDateInput.value) {
                addError(endDateInput, 'Tanggal selesai belum diisi');
            }
            if (startTimeInput && !startTimeInput.value) {
                addError(startTimeInput, 'Jam mulai belum diisi');
            }
            if (endTimeInput && !endTimeInput.value) {
                addError(endTimeInput, 'Jam selesai belum diisi');
            }
            if (rentalDurationInput && !rentalDurationInput.value) {
                addError(rentalDurationInput, 'Durasi per sesi belum diisi');
            }
            if (rentalPriceInput && !rentalPriceInput.value) {
                addError(rentalPriceInput, 'Harga sewa belum diisi');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    </script>
    @endpush
@endsection

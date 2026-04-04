@extends('layouts.main', ['title' => 'Tambah Jadwal'])

@push('styles')
    @include('landowner.schedule.partials.schedule-style')
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.schedule.index', ['venue_id' => $selectedVenueId, 'section_id' => $selectedSectionId]) }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Tambah Jadwal</h1>
                <p class="page-subtitle">
                    @if(isset($selectedSection) && $selectedSection)
                        <span style="display: flex; align-items: center; gap: 6px; font-weight: 600; color: var(--primary);">
                            <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i>
                            {{ $selectedSection->venue->venue_name }} - {{ $selectedSection->section_name }}
                        </span>
                    @else
                        Buat slot jadwal manual satu per satu
                    @endif
                </p>
            </section>

            <div class="venue-list">
                <div class="venue-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
                <form action="{{ route('landowner.schedule.store') }}" method="POST" class="animate-fade-in" id="manualScheduleForm" novalidate>
                    @csrf
                    
                    @if(isset($selectedSection) && $selectedSection)
                        <input type="hidden" name="venue_id" value="{{ $selectedSection->venue_id }}">
                        <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">

                    @endif

                    <!-- Section 2: Waktu & Tanggal -->
                    <div class="form-section-card">
                        <h3 class="data-group-title">
                            <i class="fas fa-clock"></i> Waktu & Tanggal
                        </h3>

                        <div class="form-group">
                            <label class="form-label">Tanggal</label>
                            <div class="input-group-icon">
                                <i class="fas fa-calendar-day input-icon"></i>
                                <input type="date" name="date" class="form-control with-icon" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label class="form-label">Jam Mulai</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-hourglass-start input-icon"></i>
                                    <input type="time" name="start_time" class="form-control with-icon">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jam Selesai</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-hourglass-end input-icon"></i>
                                    <input type="time" name="end_time" class="form-control with-icon">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Harga & Status -->
                    <div class="form-section-card">
                        <h3 class="data-group-title">
                            <i class="fas fa-tag"></i> Harga & Status
                        </h3>

                        <div class="form-group">
                            <label class="form-label">Harga Sewa (Rp)</label>
                            <div class="input-group-icon">
                                <i class="fas fa-money-bill-wave input-icon"></i>
                                <input type="number" name="rental_price" class="form-control with-icon" min="0" placeholder="Contoh: 150000">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="input-group-icon">
                                <i class="fas fa-info-circle input-icon"></i>
                                <select name="available" class="form-control with-icon">
                                    <option value="1">Tersedia</option>
                                    <option value="0">Tidak Tersedia</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-add" style="box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);">
                            <i class="fas fa-save"></i> Simpan Jadwal
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

        document.getElementById('manualScheduleForm').addEventListener('submit', function(e) {
            document.querySelectorAll('.js-error').forEach(el => el.remove());
            document.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            
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

            const dateInput = document.querySelector('input[name="date"]');
            const startTimeInput = document.querySelector('input[name="start_time"]');
            const endTimeInput = document.querySelector('input[name="end_time"]');
            const rentalPriceInput = document.querySelector('input[name="rental_price"]');

            if (!dateInput.value) {
                addError(dateInput, 'Tanggal belum diisi');
            }
            if (!startTimeInput.value) {
                addError(startTimeInput, 'Jam mulai belum diisi');
            }
            if (!endTimeInput.value) {
                addError(endTimeInput, 'Jam selesai belum diisi');
            } else if (startTimeInput.value && startTimeInput.value >= endTimeInput.value) {
                addError(startTimeInput, 'Waktu tidak valid');
                addError(endTimeInput, 'Waktu tidak valid');
            }
            if (!rentalPriceInput.value) {
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

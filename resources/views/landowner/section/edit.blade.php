@extends('layouts.main', ['title' => 'Edit Lapangan'])

@push('styles')
    @include('landowner.section.partials.section-style')
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.section-lapangan.index', ['venue_id' => $section->venue_id]) }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Edit Lapangan</h1>
                <p class="page-subtitle">Venue: {{ $section->venue->venue_name }}</p>
            </section>

            <div class="section-list">
                <div class="section-card" style="padding: 20px; border-top: none;">
                    <form action="{{ route('landowner.section-lapangan.update', $section->id) }}" method="POST" id="sectionForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="venue_id" value="{{ $section->venue_id }}">
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-signature"></i>
                                Nama Lapangan *
                            </label>
                            <input type="text" class="form-control @error('section_name') is-invalid @enderror" 
                                   name="section_name" id="section_name" value="{{ old('section_name', $section->section_name) }}"
                                   placeholder="Contoh: Lapangan A">
                            @error('section_name')
                                <div class="invalid-feedback" style="color: var(--danger); font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Deskripsi *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" id="description" rows="4" 
                                      placeholder="Deskripsi lapangan...">{{ old('description', $section->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback" style="color: var(--danger); font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn-add">
                                <i class="fas fa-save"></i> Perbarui Lapangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
            @include('layouts.bottom-nav')

    @endsection

    @push('scripts')
    <script>
        document.getElementById('sectionForm').addEventListener('submit', function(e) {
            // Clear previous errors
            document.querySelectorAll('.js-error').forEach(el => el.remove());
            document.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            
            function addError(elementId, message) {
                let element = document.getElementById(elementId);
                if (!element) return;
                
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

            if (!document.getElementById('section_name').value.trim()) {
                addError('section_name', 'Nama lapangan belum diisi');
            }
            if (!document.getElementById('description').value.trim()) {
                addError('description', 'Deskripsi belum diisi');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    </script>
    @endpush

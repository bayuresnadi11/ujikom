@extends('layouts.main', ['title' => 'Edit Venue'])

@push('styles')
    @include('landowner.venue.partials.venue-style')
    <style>
        .photo-item {
            position: relative;
        }
        .btn-delete-photo {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(231, 76, 60, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }
        .photo-deleted {
            opacity: 0.3;
            filter: grayscale(100%);
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.venue.index') }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Edit Venue</h1>
                <p class="page-subtitle">Perbarui informasi venue Anda</p>
            </section>

            <div class="venue-list">
                <div class="venue-card animate-fade-in" style="padding: 0; border: none; background: transparent; box-shadow: none;">
                    <form action="{{ route('landowner.venue.update', $venue->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="deleted_photos" id="deletedPhotos" value="">
                        
                        <!-- Section 1: Informasi Dasar -->
                        <div class="form-section-card">
                            <h3 class="data-group-title">
                                <i class="fas fa-info-circle"></i> Informasi Dasar
                            </h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Venue</label>
                                    <div class="input-group-icon">
                                        <i class="fas fa-chess-board input-icon"></i>
                                        <input type="text" class="form-control with-icon @error('venue_name') is-invalid @enderror" 
                                               name="venue_name" value="{{ old('venue_name', $venue->venue_name) }}"
                                               placeholder="Nama venue Anda..." required>
                                    </div>
                                    @error('venue_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Kategori</label>
                                    <div class="input-group-icon">
                                        <i class="fas fa-tags input-icon"></i>
                                        <select class="form-control with-icon @error('category_id') is-invalid @enderror" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id', $venue->category_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Lokasi</label>
                                <div class="input-group-icon">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text" class="form-control with-icon @error('location') is-invalid @enderror" 
                                           name="location" value="{{ old('location', $venue->location) }}"
                                           placeholder="Alamat lengkap..." required>
                                </div>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Section 2: Detail -->
                        <div class="form-section-card">
                            <h3 class="data-group-title">
                                <i class="fas fa-align-left"></i> Detail Venue
                            </h3>
                            
                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="5" 
                                          style="border-radius: 14px; padding: 16px; line-height: 1.6;"
                                          placeholder="Deskripsi venue..." required>{{ old('description', $venue->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Section 3: Galeri Foto -->
                        <div class="form-section-card">
                            <h3 class="data-group-title">
                                <i class="fas fa-camera"></i> Galeri Foto
                            </h3>
                            
                            <!-- Existing Photos -->
                            <div class="existing-photos" style="margin-bottom: 25px;">
                                <div style="font-size: 13px; color: var(--text); font-weight: 600; margin-bottom: 15px;">Foto Saat Ini</div>
                                <div class="preview-grid {{ $venue->photos->count() == 1 ? 'one-photo' : ($venue->photos->count() == 2 ? 'two-photos' : '') }}">
                                    @if($venue->photos->count() > 0)
                                        @foreach($venue->photos as $photo)
                                            <div class="preview-item photo-item" id="photo-{{ $photo->id }}" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto Venue" style="width: 100%; height: 100%; object-fit: cover;">
                                                <button type="button" class="preview-remove btn-delete-photo" onclick="markPhotoDeleted({{ $photo->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Fallback for legacy photo -->
                                         @if($venue->photo)
                                            <div class="preview-item" style="border-radius: 12px; overflow: hidden;">
                                                <img src="{{ asset('storage/' . $venue->photo) }}" alt="Legacy Photo">
                                                <div class="preview-label">Legacy Photo</div>
                                            </div>
                                         @else
                                            <div class="empty-state-small" style="grid-column: 1 / -1; padding: 20px;">
                                                <i class="fas fa-image" style="font-size: 24px; margin-bottom: 8px;"></i>
                                                <p>Belum ada foto.</p>
                                            </div>
                                         @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Upload New Photos -->
                            <div style="font-size: 13px; color: var(--text); font-weight: 600; margin-bottom: 10px;">Tambah Foto Baru</div>
                            <div class="drop-zone" id="dropZone" style="background: #f8f9fa; border: 2px dashed #cbd5e0;">
                                <div class="drop-zone-content">
                                    <div class="icon-circle">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="drop-zone-text">Upload Foto Baru</div>
                                    <div class="drop-zone-subtext">Tap atau drag & drop (Max 5 foto)</div>
                                </div>
                                <input type="file" id="new_photos" name="new_photos[]" accept="image/jpeg,image/png,image/jpg" multiple hidden>
                            </div>
                            
                            <div id="photoCounter" class="photo-counter-badge" style="display: none; margin-top: 15px;">
                                <i class="fas fa-check-circle"></i> <span id="photoCount">0</span> foto baru dipilih
                            </div>
                            
                            <div id="photoPreview" class="preview-grid" style="margin-top: 20px;"></div>
                            
                            @error('new_photos')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions" style="position: sticky; bottom: 20px; z-index: 100;">
                            <button type="submit" class="btn-add" style="box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);">
                                <i class="fas fa-save"></i> Perbarui Venue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
            @include('layouts.bottom-nav')

    <!-- Script untuk multi-photo upload dengan drag & drop -->
    <script>
        let deletedPhotoIds = [];
        let selectedFiles = [];
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('new_photos');
        const previewContainer = document.getElementById('photoPreview');
        const photoCounter = document.getElementById('photoCounter');
        const photoCount = document.getElementById('photoCount');

        // Mark photo as deleted
        function markPhotoDeleted(id) {
            const el = document.getElementById(`photo-${id}`);
            const btn = el.querySelector('.btn-delete-photo');
            
            if (deletedPhotoIds.includes(id)) {
                // Undo delete
                deletedPhotoIds = deletedPhotoIds.filter(pid => pid !== id);
                el.classList.remove('photo-deleted');
                btn.innerHTML = '<i class="fas fa-times"></i>';
                btn.style.background = 'rgba(231, 76, 60, 0.9)';
            } else {
                // Mark delete
                deletedPhotoIds.push(id);
                el.classList.add('photo-deleted');
                btn.innerHTML = '<i class="fas fa-undo"></i>';
                btn.style.background = 'rgba(52, 152, 219, 0.9)';
            }
            
            document.getElementById('deletedPhotos').value = deletedPhotoIds.join(',');
        }

        // Click to select files
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag and drop events
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = Array.from(e.dataTransfer.files).filter(file => 
                file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/jpg'
            );
            
            addFiles(files);
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            addFiles(Array.from(e.target.files));
        });

        function addFiles(files) {
            // Add new files to selected files (max 5)
            files.forEach(file => {
                if (selectedFiles.length < 5) {
                    // Check if file already exists
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                }
            });

            updateFileInput();
            renderPreviews();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreviews();
        }

        function updateFileInput() {
            // Create a new DataTransfer to update the file input
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;

            // Update counter
            if (selectedFiles.length > 0) {
                photoCounter.style.display = 'block';
                photoCount.textContent = selectedFiles.length;
            } else {
                photoCounter.style.display = 'none';
            }
        }

        function renderPreviews() {
            previewContainer.innerHTML = '';
            
            // Set grid class based on number of photos
            previewContainer.className = 'preview-grid';
            if (selectedFiles.length === 1) {
                previewContainer.classList.add('one-photo');
            } else if (selectedFiles.length === 2) {
                previewContainer.classList.add('two-photos');
            }

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <button type="button" class="preview-remove" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="preview-label">Foto Baru ${index + 1}</div>
                    `;
                    previewContainer.appendChild(previewItem);
                }
                
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection

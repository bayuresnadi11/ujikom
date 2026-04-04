@extends('layouts.main', ['title' => 'Tambah Venue Baru'])

@push('styles')
    @include('landowner.venue.partials.venue-style')
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
                <h1 class="page-title">Tambah Venue</h1>
                <p class="page-subtitle">Isi formulir berikut untuk menambahkan venue baru</p>
            </section>

            <div class="venue-list">
                <div class="venue-card animate-fade-in" style="padding: 0; border: none; background: transparent; box-shadow: none;">
                    <form action="{{ route('landowner.venue.store') }}" method="POST" enctype="multipart/form-data" id="venueForm">
                        @csrf
                        
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
                                               name="venue_name" id="venue_name" value="{{ old('venue_name') }}"
                                               placeholder="Nama venue Anda...">
                                    </div>
                                    @error('venue_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Kategori</label>
                                    <div class="input-group-icon">
                                        <i class="fas fa-tags input-icon"></i>
                                        <select class="form-control with-icon @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                           name="location" id="location" value="{{ old('location') }}"
                                           placeholder="Alamat lengkap...">
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
                                          name="description" id="description" rows="5" 
                                          style="border-radius: 14px; padding: 16px; line-height: 1.6;"
                                          placeholder="Jelaskan fasilitas, keunggulan, dan info penting lainnya...">{{ old('description') }}</textarea>
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
                            
                            <div class="drop-zone" id="dropZone" style="background: #f8f9fa; border: 2px dashed #cbd5e0;">
                                <div class="drop-zone-content">
                                    <div class="icon-circle">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="drop-zone-text">Upload Foto Venue</div>
                                    <div class="drop-zone-subtext">Tap atau drag & drop (Max 5 foto)</div>
                                </div>
                                <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/jpg" multiple hidden>
                            </div>
                            
                            <div id="photoCounter" class="photo-counter-badge" style="display: none; margin-top: 15px;">
                                <i class="fas fa-check-circle"></i> <span id="photoCount">0</span> foto dipilih
                            </div>
                            
                            <div id="photoPreview" class="preview-grid" style="margin-top: 20px;"></div>
                            
                            @error('photos')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                            @error('photos.*')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions" style="position: sticky; bottom: 20px; z-index: 100;">
                            <button type="submit" class="btn-add" style="box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);">
                                <i class="fas fa-save"></i> Simpan Venue
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
        let selectedFiles = [];
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('photos');
        const previewContainer = document.getElementById('photoPreview');
        const photoCounter = document.getElementById('photoCounter');
        const photoCount = document.getElementById('photoCount');

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
            let tooManyFiles = false;

            // Add new files to selected files (max 5)
            files.forEach(file => {
                if (selectedFiles.length < 5) {
                    // Check if file already exists
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                } else {
                    tooManyFiles = true;
                }
            });

            if (tooManyFiles) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hanya bisa mengunggah maksimal 5 foto!'
                    });
                } else {
                    alert('Hanya bisa mengunggah maksimal 5 foto!');
                }
            }

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
                        <div class="preview-label">Foto ${index + 1}</div>
                    `;
                    previewContainer.appendChild(previewItem);
                }
                
                reader.readAsDataURL(file);
            });
        }

        // Custom form validation
        document.getElementById('venueForm').addEventListener('submit', function(e) {
            // Clear previous errors
            document.querySelectorAll('.js-error').forEach(el => el.remove());
            document.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            
            function addError(elementId, message, isFile = false) {
                let element = document.getElementById(elementId);
                if (!element) return;
                
                if (isFile) {
                    let container = document.getElementById(elementId).parentElement;
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                    container.appendChild(errorDiv);
                } else {
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
                }
                isValid = false;
            }

            if (!document.getElementById('venue_name').value.trim()) {
                addError('venue_name', 'Nama venue belum diisi');
            }
            if (!document.getElementById('category_id').value) {
                addError('category_id', 'Kategori belum dipilih');
            }
            if (!document.getElementById('location').value.trim()) {
                addError('location', 'Lokasi belum diisi');
            }
            if (!document.getElementById('description').value.trim()) {
                addError('description', 'Deskripsi belum diisi');
            }
            
            // Validation for photos
            if (selectedFiles.length === 0) {
                addError('dropZone', 'Minimal 1 foto venue harus diupload', true);
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection

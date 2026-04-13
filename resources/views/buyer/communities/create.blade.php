@extends('layouts.main', ['title' => 'Buat Komunitas'])

@push('styles')
@include('buyer.communities.partials.communities-style')
<style>
    /* Override FAB container to stay within mobile container width */
    .fab-container {
        position: fixed !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 100% !important;
        max-width: 500px !important;
        bottom: 90px !important; /* Raised to avoid bottom nav */
        right: auto !important;
        pointer-events: none !important; /* Allow clicking through empty space */
        z-index: 1000;
        padding-right: 20px; /* Right margin effectively */
        display: none !important;
    }

    /* Push Page Header down to avoid overlap with fixed navbar */
    .page-header {
        padding: 85px 20px 0 !important;
        text-align: left;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .page-subtitle {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
        margin-bottom: 5px;
    }


    /* ================= FORM STYLES ================= */
    .form-container {
        padding: 10px 16px 120px; /* Increased bottom padding to 120px */
    }


    .form-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        border: 1px solid var(--light-gray);
        transition: all 0.2s ease;
        margin-bottom: 16px;
    }

    .form-card:hover {
        border-color: var(--primary);
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: var(--text);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--secondary);
        font-size: 13px;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--light-gray);
        border-radius: 6px;
        font-size: 13px;
        background: white;
        color: var(--text);
        transition: all 0.2s ease;
        font-family: inherit;
        font-weight: 500;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-light);
        opacity: 0.7;
    }

    .form-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%230a5c36' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
        padding-right: 40px;
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
        line-height: 1.5;
    }

    /* ================= FILE UPLOAD ================= */
    .file-upload-container {
        position: relative;
        margin-top: 10px;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px;
        background: #F8F9FA;
        border: 1.5px dashed var(--light-gray);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        justify-content: center;
    }

    .file-upload-label:hover {
        border-color: var(--secondary);
        background: white;
    }

    .file-upload-icon {
        color: var(--secondary);
        font-size: 20px;
    }

    .file-upload-text {
        color: var(--text);
        font-weight: 600;
        font-size: 12px;
    }

    .file-upload-hint {
        color: var(--text-light);
        font-size: 11px;
        margin-top: 6px;
        text-align: center;
    }

    .file-preview-container {
        margin-top: 12px;
        text-align: center;
    }

    .file-preview {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        background: var(--light-gray);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .file-preview:hover {
        transform: scale(1.05);
    }

    /* ================= TYPE SELECTION ================= */
    .type-selection {
        display: flex;
        gap: 12px;
        margin-top: 8px;
    }

    .type-option {
        position: relative;
        flex: 1;
    }

    .type-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .type-card {
        padding: 16px;
        background: #F8F9FA;
        border: 1.5px solid var(--light-gray);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        height: 100%;
        width: 100%;
    }

    .type-card:hover {
        border-color: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .type-option input[type="radio"]:checked + .type-card {
        border-color: var(--secondary);
        background: rgba(46, 204, 113, 0.08);
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .type-icon {
        font-size: 22px;
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .type-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .type-desc {
        font-size: 10px;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* ================= FORM ACTIONS ================= */
    .form-actions {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--light-gray);
        display: flex;
        gap: 10px;
    }

    .btn-submit {
        flex: 1;
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-secondary {
        flex: 1;
        background: #F8F9FA;
        color: var(--text);
        border: 1px solid var(--light-gray);
        padding: 14px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        text-align: center;
    }

    .btn-secondary:hover {
        background: white;
        border-color: var(--light-gray);
        transform: translateY(-1px);
    }

    /* ================= ERROR STATES ================= */
    .error-message {
        color: #E74C3C;
        font-size: 11px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }

    .error-message i {
        font-size: 11px;
    }

    .form-control.error,
    .form-select.error {
        border-color: #E74C3C;
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast {
        position: fixed;
        bottom: 90px;
        right: 16px;
        background: white;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid var(--secondary);
        display: flex;
        align-items: center;
        gap: 8px;
        transform: translateX(400px);
        transition: transform 0.2s ease;
        opacity: 0;
        pointer-events: auto;
        z-index: 3000;
        max-width: 280px;
        font-size: 12px;
    }

    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        background: #E8F5E9;
        color: var(--secondary);
    }

    .toast.error .toast-icon {
        background: #FFEBEE;
        color: #E74C3C;
    }

    .toast-content {
        flex: 1;
    }

    .toast-content h4 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
    }

    .toast-content p {
        margin: 0;
        font-size: 11px;
        color: var(--text-light);
    }

    /* ================= CITY MODAL ================= */
    .city-modal {
        position: fixed;
        bottom: -100%;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 500px;
        height: 70vh;
        background: white;
        z-index: 2000;
        border-radius: 8px 8px 0 0;
        transition: bottom 0.3s ease;
        display: flex;
        flex-direction: column;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
    }

    .city-modal.active {
        bottom: 0;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1999;
        display: none;
    }

    .modal-overlay.active {
        display: block;
    }

    .modal-header {
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--light-gray);
    }

    .modal-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 18px;
        color: var(--text-light);
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
    }

    .search-container {
        padding: 12px;
    }

    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100% !important;
        padding: 10px 14px 10px 32px !important;
        border: 1px solid var(--light-gray) !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        outline: none !important;
    }

    .search-input:focus {
        border-color: var(--secondary) !important;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        color: var(--text-light);
        font-size: 12px;
    }

    .city-list {
        flex: 1;
        overflow-y: auto;
        padding: 0;
    }

    .city-item {
        padding: 12px 16px;
        border-bottom: 1px solid var(--light-gray);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .city-item:hover {
        background: #F8F9FA;
    }

    .city-name {
        font-size: 13px;
        color: var(--text);
        font-weight: 500;
    }

    .location-icon-input {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary);
        font-size: 14px;
        pointer-events: none;
    }

        .btn-submit,
        .btn-secondary {
            width: 100%;
        }
    }

    /* Refined Approval Style from Image */
    .approval-card-container {
        margin-top: 15px;
        padding: 5px 0 5px 15px;
        border-left: 4px solid #E9ECEF;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .approval-info-content {
        flex: 1;
    }

    .approval-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
        display: block;
    }

    .approval-section-desc {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* Switch Style */
    .switch-container {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin-left: 15px;
    }

    .switch-container input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    .switch-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .switch-slider {
        background-color: var(--secondary);
    }

    input:checked + .switch-slider:before {
        transform: translateX(20px);
    }
</style>
@endpush

@section('content')
<div class="mobile-container" id="mobileContainer">
    @include('layouts.header', ['showSearch' => false])

    <main class="main-content">
        <!-- ================= PAGE HEADER ================= -->
        <section class="page-header">
            <h1 class="page-title">Buat Komunitas Baru</h1>
            <p class="page-subtitle">Buat komunitas Anda sendiri dan kelola anggota dengan mudah</p>
        </section>

        <!-- ================= FORM CONTAINER ================= -->
        <div class="form-container">
            <form action="{{ route('buyer.communities.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                @csrf

                <div class="form-card">
                    <!-- Kategori -->
                    <div class="form-group">
                        <label class="form-label" for="category_id">
                            <i class="fas fa-tag"></i> Kategori
                        </label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Nama Komunitas -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <i class="fas fa-users"></i> Nama Komunitas
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') error @enderror"
                            placeholder="Masukkan nama komunitas"
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label class="form-label" for="description">
                            <i class="fas fa-align-left"></i> Deskripsi
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            class="form-control form-textarea @error('description') error @enderror"
                            placeholder="Deskripsikan komunitas Anda"
                            rows="3"
                        >{{ old('description') }}</textarea>
                        <small class="text-muted" style="display: block; margin-top: 6px; font-size: 10px; color: var(--text-light);">
                            Jelaskan tujuan dan aktivitas komunitas Anda
                        </small>
                        @error('description')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Kota -->
                    <div class="form-group" onclick="openCityModal()">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Kota
                        </label>
                        <div style="position: relative;">
                            <input type="text" id="cityDisplay" class="form-control" placeholder="Pilih Kota" value="{{ old('location') }}" readonly style="background: white; cursor: pointer;">
                            <input type="hidden" name="location" id="cityValue" value="{{ old('location') }}">
                            <i class="fas fa-crosshairs location-icon-input"></i>
                        </div>
                        @error('location')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Logo Komunitas -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-image"></i> Logo Komunitas
                        </label>
                        <div class="file-upload-container">
                            <label for="logo" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                <div>
                                    <div class="file-upload-text">Klik untuk upload</div>
                                    <div class="file-upload-hint">JPG, PNG, GIF (Max: 2MB)</div>
                                </div>
                            </label>
                            <input
                                type="file"
                                name="logo"
                                id="logo"
                                accept="image/*"
                                onchange="previewLogo(event)"
                                style="display: none;"
                            >
                        </div>

                        <div id="logoPreviewContainer" class="file-preview-container" style="display: none;">
                            <img id="logoPreview" class="file-preview" alt="Logo Preview">
                            <div class="file-upload-hint" style="margin-top: 8px;">
                                Klik untuk mengganti
                            </div>
                        </div>

                        @error('logo')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tipe Komunitas -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-cog"></i> Tipe Komunitas
                        </label>
                        <div class="type-selection">
                            <!-- Public -->
                            <div class="type-option">
                                <input
                                    type="radio"
                                    name="type"
                                    id="type-public"
                                    value="public"
                                    {{ old('type') == 'public' ? 'checked' : '' }}
                                    onchange="toggleApprovalOption()"
                                >
                                <label for="type-public" class="type-card">
                                    <i class="fas fa-globe type-icon"></i>
                                    <div class="type-title">Publik</div>
                                    <div class="type-desc">Bergabung tanpa persetujuan</div>
                                </label>
                            </div>

                            <!-- Private -->
                            <div class="type-option">
                                <input
                                    type="radio"
                                    name="type"
                                    id="type-private"
                                    value="private"
                                    {{ old('type') == 'private' ? 'checked' : '' }}
                                    onchange="toggleApprovalOption()"
                                >
                                <label for="type-private" class="type-card">
                                    <i class="fas fa-lock type-icon"></i>
                                    <div class="type-title">Pribadi</div>
                                    <div class="type-desc">Perlu persetujuan pengelola</div>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Requires Approval Choice (Only for Public) -->
                    <div class="form-group" id="approval-section" style="display: none;">
                        <div class="approval-card-container">
                            <div class="approval-info-content">
                                <span class="approval-section-title">Perlu Persetujuan</span>
                                <span class="approval-section-desc">Member harus meminta izin untuk bergabung</span>
                            </div>
                            <label class="switch-container">
                                <input type="checkbox" name="requires_approval" id="requires_approval" value="1" {{ old('requires_approval') ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="fas fa-plus-circle"></i> Buat
                        </button>
                        <a href="{{ route('buyer.communities.index') }}" class="btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </main>

    @include('layouts.bottom-nav')
</div>

<!-- CITY SELECTION MODAL -->
<div class="modal-overlay" id="modalOverlay" onclick="closeCityModal()"></div>
<div class="city-modal" id="cityModal">
    <div class="modal-header">
        <span class="modal-title">Pilih Kota</span>
        <button class="close-modal" onclick="closeCityModal()">&times;</button>
    </div>
    <div class="search-container">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="citySearch" placeholder="Cari Kota / Kabupaten..." onkeyup="filterCities()">
        </div>
    </div>
    <div class="city-list" id="cityList">
        <div style="padding: 16px; text-align: center; color: var(--text-light); font-size: 12px;">Loading kota...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createForm');
        const submitBtn = document.getElementById('submitBtn');
        const logoInput = document.getElementById('logo');
        const logoPreviewContainer = document.getElementById('logoPreviewContainer');
        const logoPreview = document.getElementById('logoPreview');

        // ================= LOGO PREVIEW =================
        window.previewLogo = function(event) {
            const input = event.target;
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                // Check file size
                if (file.size > maxSize) {
                    showToast('Ukuran file maksimal 2MB', 'error');
                    input.value = '';
                    logoPreviewContainer.style.display = 'none';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showToast('Format file harus JPG, PNG, atau GIF', 'error');
                    input.value = '';
                    logoPreviewContainer.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreviewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            }
        }

        // Click logo preview to trigger file input
        logoPreviewContainer.addEventListener('click', function() {
            logoInput.click();
        });

        // ================= APPROVAL OPTION TOGGLE =================
        window.toggleApprovalOption = function() {
            const publicRadio = document.getElementById('type-public');
            const approvalSection = document.getElementById('approval-section');
            
            if (publicRadio.checked) {
                approvalSection.style.display = 'block';
            } else {
                approvalSection.style.display = 'none';
                document.getElementById('requires_approval').checked = false;
            }
        }

        // Initialize state
        toggleApprovalOption();

        // ================= FORM VALIDATION =================
        form.addEventListener('submit', function(e) {
            const category = document.getElementById('category_id');
            const name = document.getElementById('name');
            const description = document.getElementById('description');
            const typePublic = document.getElementById('type-public');
            const typePrivate = document.getElementById('type-private');
            const cityValue = document.getElementById('cityValue');
            
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.form-control.error, .form-select.error').forEach(el => {
                el.classList.remove('error');
            });
            document.querySelectorAll('.js-error').forEach(el => el.remove());

            function addError(elementId, message) {
                let element = document.getElementById(elementId);
                let formGroup = element.closest('.form-group');
                let errorDiv = document.createElement('div');
                errorDiv.className = 'error-message js-error mt-1';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                formGroup.appendChild(errorDiv);
            }
            
            // Validate category
            if (!category.value) {
                isValid = false;
                category.classList.add('error');
                addError('category_id', 'Kategori belum dipilih');
            }
            
            // Validate name
            if (!name.value.trim()) {
                isValid = false;
                name.classList.add('error');
                addError('name', 'Nama komunitas belum diisi');
            } else if (name.value.trim().length < 3) {
                isValid = false;
                name.classList.add('error');
                addError('name', 'Nama komunitas minimal 3 karakter');
            }
            
            // Validate description
            if (!description.value.trim()) {
                isValid = false;
                description.classList.add('error');
                addError('description', 'Deskripsi belum diisi');
            } else if (description.value.trim().length < 10) {
                isValid = false;
                description.classList.add('error');
                addError('description', 'Deskripsi minimal 10 karakter');
            }

            // Validate city
            if (!cityValue.value.trim()) {
                isValid = false;
                addError('cityValue', 'Kota belum diisi');
            }
            
            // Validate type
            if (!typePublic.checked && !typePrivate.checked) {
                isValid = false;
                addError('type-public', 'Tipe komunitas belum dipilih');
            }
            
            if (!isValid) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat...';
        });

        // ================= TOAST NOTIFICATION =================
        window.showToast = function(message, type = 'success') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) existingToast.remove();

            // Create new toast
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                </div>
                <div class="toast-content">
                    <p>${message}</p>
                </div>
            `;

            document.body.appendChild(toast);

            // Show toast
            setTimeout(() => toast.classList.add('show'), 10);

            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 200);
            }, 3000);
        }

        // ================= INPUT VALIDATION LIVE =================
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                }
            });
        });

        // ================= KEYBOARD SHORTCUTS =================
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to submit
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                if (form.checkValidity()) {
                    form.submit();
                }
            }
            
            // Escape to go back
            if (e.key === 'Escape') {
                e.preventDefault();
                window.history.back();
            }
        });

        // ================= AUTO-FOCUS =================
        setTimeout(() => {
            document.getElementById('name').focus();
        }, 300);
    });

    // CITY SELECTION LOGIC
    let indonesianCities = [];

    async function loadCities() {
        try {
            const response = await fetch('/data/indonesia-cities.json');
            indonesianCities = await response.json();
            renderCities(indonesianCities.slice(0, 50)); 
        } catch (error) {
            console.error('Error loading cities:', error);
            document.getElementById('cityList').innerHTML = '<div style="padding: 16px; text-align: center; color: #E74C3C; font-size: 12px;">Gagal memuat data kota</div>';
        }
    }

    function renderCities(cities) {
        const list = document.getElementById('cityList');
        if (cities.length === 0) {
            list.innerHTML = '<div style="padding: 16px; text-align: center; color: var(--text-light); font-size: 12px;">Kota tidak ditemukan</div>';
            return;
        }

        list.innerHTML = cities.map(city => `
            <div class="city-item" onclick="selectCity('${city.province}', '${city.name}')">
                <div class="city-name">${city.province}, ${city.name}</div>
            </div>
        `).join('');
    }

    function filterCities() {
        const query = document.getElementById('citySearch').value.toLowerCase();
        if (query.length < 2) {
            renderCities(indonesianCities.slice(0, 50));
            return;
        }

        const filtered = indonesianCities.filter(city => 
            city.name.toLowerCase().includes(query) || 
            city.province.toLowerCase().includes(query)
        );
        renderCities(filtered.slice(0, 50));
    }

    window.openCityModal = function() {
        document.getElementById('cityModal').classList.add('active');
        document.getElementById('modalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
        if (indonesianCities.length === 0) loadCities();
    }

    window.closeCityModal = function() {
        document.getElementById('cityModal').classList.remove('active');
        document.getElementById('modalOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    window.selectCity = function(province, city) {
        const fullLocation = `${province}, ${city}`;
        document.getElementById('cityDisplay').value = fullLocation;
        document.getElementById('cityValue').value = fullLocation;
        closeCityModal();
    }
</script>
@endpush
@extends('layouts.admin', ['title' => 'Pengaturan | Admin Panel'])

@push('styles')
    @include('admin.setting.partials.style')
@endpush

@section('content')
    <div class="admin-container fade-page">
        <!-- Header -->
        <div class="page-header-box">
            <h2 class="page-title">
                <i class="fas fa-cog me-3"></i>Pengaturan
            </h2>
        </div>

        <!-- Settings Form -->
        <form 
            action="{{ $setting ? route('admin.setting.update') : route('admin.setting.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="settings-form"
        >
            @csrf
            @if($setting)
                @method('PUT')
            @endif

            <!-- Settings Panel -->
            <div class="settings-panel">
                <!-- Panel Header -->
                <div class="settings-header">
                    <h2>Manajemen Konfigurasi</h2>
                </div>

                <!-- Tabs Navigation -->
                <div class="settings-tabs">
                    <button type="button" class="settings-tab active" data-tab="general">
                        <i class="fas fa-globe"></i> General
                    </button>
                    <button type="button" class="settings-tab" data-tab="whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp Gateway
                    </button>
                    <button type="button" class="settings-tab" data-tab="payment">
                        <i class="fas fa-credit-card"></i> Payment Gateway
                    </button>
                </div>

                <!-- Tab Contents -->
                <div class="settings-content">
                    <!-- General Settings Tab -->
                    <div id="general" class="tab-content active">
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Informasi Aplikasi</h3>
                                    <p class="section-desc">
                                        Konfigurasikan nama aplikasi, logo, dan informasi dasar Anda.
                                    </p>
                                </div>
                            </div>

                            <!-- Application Name -->
                            <div class="form-group">
                                <label class="form-label">
                                    Nama Aplikasi <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <i class="fas fa-signature input-icon"></i>
                                    <input 
                                        type="text" 
                                        name="app_name"
                                        class="form-control form-control-lg input-with-icon" 
                                        placeholder="Masukan Nama Aplikasi Anda"
                                        value="{{ old('app_name', $setting->app_name ?? '') }}"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Application Logo -->
                            <div class="form-group">
                                <label class="form-label">Logo Aplikasi</label>

                                <!-- Logo Container -->
                                <div class="logo-container">
                                    <!-- Current Logo -->
                                    @if(!empty($setting?->app_logo))
                                        <div class="current-logo-section">
                                            <p class="logo-label">Logo saat ini:</p>
                                            <div class="logo-preview-box">
                                                <img 
                                                    src="{{ asset('storage/logo/' . $setting->app_logo) }}" 
                                                    alt="Current Logo"
                                                    class="current-logo"
                                                >
                                                <div class="logo-info">
                                                    <span class="logo-name">{{ $setting->app_logo }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Upload Area -->
                                    <div class="upload-section">
                                        <div class="upload-area" id="uploadArea">
                                            <div class="upload-content">
                                                <div class="upload-icon">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </div>
                                                <div class="upload-text">
                                                    <h5>Upload New Logo</h5>
                                                    <p class="upload-instruction">
                                                        <span class="drag-text">Drag & drop your file here or</span>
                                                        <span class="browse-text">browse</span>
                                                    </p>
                                                    <p class="upload-requirements">
                                                        PNG, JPG, WEBP • Max 2MB • Recommended: 512×512px
                                                    </p>
                                                </div>
                                            </div>
                                            <input 
                                                type="file" 
                                                name="app_logo"
                                                id="logoUpload" 
                                                accept="image/png,image/jpeg,image/webp"
                                                class="file-input"
                                            >
                                        </div>

                                        <!-- New Logo Preview -->
                                        <div class="new-logo-preview" id="newLogoPreview">
                                            <p class="logo-label">New Logo Preview:</p>
                                            <div class="preview-container">
                                                <img 
                                                    src="" 
                                                    alt="Logo Preview" 
                                                    class="preview-img"
                                                    id="logoPreviewImg"
                                                >
                                                <div class="preview-info">
                                                    <span class="file-name" id="fileName">No file selected</span>
                                                    <button type="button" class="remove-btn" id="removeLogo">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Gateway Tab -->
                    <div id="whatsapp" class="tab-content">
                        @include('admin.setting.partials.whatsapp-settings')
                    </div>

                    <!-- Payment Gateway Tab -->
                    <div id="payment" class="tab-content">
                        @include('admin.setting.partials.payment-settings')
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-bar">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Information Box -->
        <div class="info-box">
            <div class="info-content">
                <i class="fas fa-info-circle info-icon"></i>
                <div>
                    <h5 class="info-title">Informasi Pengaturan</h5>
                    <p class="info-text">
                        Semua pengaturan disimpan dengan aman menggunakan enkripsi. Perubahan akan berlaku segera setelah disimpan. 
                        Kami merekomendasikan untuk menguji perubahan konfigurasi di lingkungan staging sebelum diterapkan ke lingkungan produksi.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.setting.partials.script')
@endpush
@extends('layouts.main')

@push('styles')
<style>
    :root {
        --primary: #0A5C36;
        --primary-dark: #08472a;
        --primary-light: #0e6b40;
        --secondary: #2ecc71;
        --accent: #27ae60;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --info: #3498db;
        --light: #F8F9FA;
        --light-gray: #E9ECEF;
        --border: #E6F7EF;
        --text: #1A3A27;
        --text-light: #6C757D;
        --text-lighter: #8A9C93;
        --shadow-sm: 0 1px 3px rgba(10, 92, 54, 0.1);
        --shadow-md: 0 2px 8px rgba(10, 92, 54, 0.15);
        --shadow-lg: 0 4px 12px rgba(10, 92, 54, 0.2);
        --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        --gradient-light: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 10px;
        --radius-xl: 12px;
        --radius-2xl: 16px;
        --radius-3xl: 20px;
    }

    body {
        background: #f0f2f5; /* Light gray background for desktop area */
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
    }

    .mobile-frame {
        width: 100%;
        max-width: 480px; /* Standardize width to match other pages */
        background: #ffffff;
        min-height: 100vh;
        position: relative;
        box-shadow: 0 0 40px rgba(0,0,0,0.1);
        padding-bottom: 100px; /* Reduced since save button is no longer fixed */
    }

    /* ================= HEADER ================= */
    .settings-header {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px; /* Reverted width */
        height: 60px;
        background: white;
        display: flex;
        align-items: center;
        padding: 0 16px;
        z-index: 1000;
        box-shadow: 0 1px 0 rgba(0,0,0,0.05);
    }

    .back-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text);
        margin-right: 16px;
        cursor: pointer;
        padding: 8px;
        margin-left: -8px;
    }

    .header-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }

    /* ================= IMAGE UPLOAD ================= */
    .profile-section {
        margin-top: 64px; /* Reduced gap further (Header 60px + 4px) */
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .profile-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .profile-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .camera-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        background: rgba(0,0,0,0.6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid white;
        cursor: pointer;
    }

    /* ================= FORM STYLES ================= */
    .form-container {
        padding: 0 20px;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-label {
        position: absolute;
        top: -8px;
        left: 12px;
        background: white;
        padding: 0 4px;
        font-size: 11px;
        color: #999;
        font-weight: 500;
        z-index: 5;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        font-size: 14px;
        color: var(--text);
        outline: none;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23999%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 16px top 50%;
        background-size: 10px auto;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
        padding-top: 14px;
    }

    .char-count {
        text-align: right;
        font-size: 11px;
        color: #999;
        margin-top: 4px;
    }

    .location-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 16px;
    }

    /* ================= PRIVACY SECTION ================= */
    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin: 24px 0 16px;
    }

    .privacy-option {
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        cursor: pointer;
    }

    .privacy-option.active {
        border-color: var(--primary);
    }

    .privacy-content {
        flex: 1;
    }

    .privacy-header {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    .privacy-desc {
        font-size: 12px;
        color: #777;
        line-height: 1.4;
    }

    .radio-circle {
        width: 18px;
        height: 18px;
        border: 2px solid #ccc;
        border-radius: 50%;
        margin-left: 12px;
        margin-top: 2px;
        position: relative;
    }

    /* Custom Radio Styling */
    input[type="radio"]:checked + .privacy-content + .radio-circle {
        border-color: var(--primary); /* Changed to primary (green) */
    }

    input[type="radio"]:checked + .privacy-content + .radio-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary); /* Changed to primary (green) */
    }

    input[type="radio"] {
        display: none;
    }

    /* ================= DELETE SECTION ================= */
    .delete-item {
        border-top: 1px solid #F0F0F0;
        border-bottom: 1px solid #F0F0F0;
        padding: 16px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        cursor: pointer;
    }

    .delete-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }
    
    .delete-sublabel {
        font-size: 11px;
        color: #999;
        margin-top: 2px;
    }

    /* ================= STICKY BUTTON ================= */
    .btn-save {
        width: 100%;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 16px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 30px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(10, 92, 54, 0.2);
        transition: all 0.2s ease;
    }

    .btn-save:active {
        transform: scale(0.98);
        background: var(--primary-dark);
    }

    /* ================= BOTTOM NAV ================= */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 0 10px;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 1001; /* Higher than bottom-bar */
        border-top: 1px solid var(--light-gray);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        padding: 4px 8px;
        transition: all 0.2s ease;
        border-radius: var(--radius-md);
        position: relative;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 48px;
        color: #999;
    }

    .nav-item.active {
        color: var(--primary);
    }

    .nav-item.active .nav-icon {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
        transform: scale(1.05);
    }

    .nav-item.active::after {
        content: "";
        position: absolute;
        top: -4px;
        width: 24px;
        height: 3px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .nav-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        background: var(--light);
        color: var(--text-light);
    }

    .nav-label {
        font-size: 10px;
        font-weight: 700;
    }

    @media (max-width: 360px) {
        .bottom-nav {
            padding: 6px 0 8px;
        }

        .nav-item {
            padding: 3px 6px;
            min-width: 40px;
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .nav-label {
            font-size: 9px;
        }

        .bottom-bar {
            bottom: 58px;
        }
    }

    .btn-save {
        width: 100%;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px; /* Reduced padding for smaller look */
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    /* ================= CITY MODAL ================= */
    .city-modal {
        position: fixed;
        bottom: -100%;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        height: 80vh;
        background: white;
        z-index: 2000;
        border-radius: 20px 20px 0 0;
        transition: bottom 0.3s ease;
        display: flex;
        flex-direction: column;
        box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
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
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-drag-handle {
        width: 40px;
        height: 4px;
        background: #ddd;
        border-radius: 2px;
        margin: 10px auto 0;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 20px;
        color: #999;
        cursor: pointer;
    }

    .search-container {
        padding: 16px;
    }

    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 40px;
        border: 1.5px solid #E0E0E0;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #A00000; /* Matching the red border in the image */
    }

    .search-icon {
        position: absolute;
        left: 14px;
        color: #999;
    }

    .city-list {
        flex: 1;
        overflow-y: auto;
        padding: 0 16px;
    }

    .city-item {
        padding: 16px 0;
        border-bottom: 1px solid #f9f9f9;
        cursor: pointer;
    }

    .city-name {
        font-size: 14px;
        color: #555;
    }

    /* ================= DELETE CONFIRMATION MODAL ================= */
    .delete-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2500;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .delete-modal-overlay.active {
        display: flex;
    }

    .delete-modal {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 340px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .delete-modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
    }

    .delete-modal-message {
        font-size: 14px;
        color: #666;
        line-height: 1.5;
        margin-bottom: 24px;
    }

    .delete-modal-buttons {
        display: flex;
        gap: 12px;
    }

    .delete-modal-btn {
        flex: 1;
        padding: 14px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        border: 2px solid;
        transition: all 0.2s ease;
    }

    .delete-modal-btn.cancel {
        background: white;
        color: var(--primary);
        border-color: var(--primary);
    }

    .delete-modal-btn.cancel:hover {
        background: #f0faf5;
    }

    .delete-modal-btn.confirm {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }

    .delete-modal-btn.confirm:hover {
        background: #c0392b;
    }

    .delete-modal-btn:active {
        transform: scale(0.98);
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast-notification {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        background: #1A3A27;
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        z-index: 3000;
        max-width: 90%;
        width: auto;
        min-width: 280px;
        text-align: center;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        pointer-events: none;
    }

    .toast-notification.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .toast-notification.success {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    }

    .toast-notification.error {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .toast-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-message {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
    }
    /* Custom Approval Checkbox */
    .approval-option {
        margin-top: -8px;
        margin-bottom: 20px;
        padding: 12px 16px;
        background: #fdfdfd;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .approval-option:hover {
        background: #f0faf5;
        border-color: var(--primary);
    }

    .approval-checkbox {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .approval-label-content {
        display: flex;
        flex-direction: column;
    }

    .approval-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .approval-desc {
        font-size: 11px;
        color: var(--text-light);
    }
    .loading-overlay {
        position: fixed; /* Changed from absolute/relative to fixed for full desktop */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        color: white;
        text-align: center;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    .loading-text {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .loading-subtext {
        font-size: 12px;
        opacity: 0.8;
        max-width: 250px;
        line-height: 1.5;
        padding: 0 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')

<div class="mobile-frame">
    <!-- HEADER -->
    <div class="settings-header">
        <button class="back-btn" onclick="window.history.back()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="header-title">Pengaturan Komunitas</div>
    </div>

    <form action="{{ route('buyer.communities.update', $community->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        <!-- PROFILE IMAGE -->
        <div class="profile-section">
            <div class="profile-wrapper" onclick="document.getElementById('logoInput').click()">
                <img src="{{ $community->logo ? asset('storage/' . $community->logo) : asset('images/default-logo.png') }}" 
                     alt="Community Logo" 
                     class="profile-image" 
                     id="logoPreview">
                <div class="camera-icon">
                    <i class="fas fa-camera"></i>
                </div>
                <input type="file" name="logo" id="logoInput" hidden accept="image/*" onchange="previewImage(this)">
            </div>
        </div>

        <div class="form-container">
            
            <!-- CATEGORY -->
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $community->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- NAME -->
            <div class="form-group">
                <label class="form-label">Nama Komunitas</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $community->name) }}" required>
            </div>

            <!-- CITY -->
            <div class="form-group" onclick="openCityModal()">
                <label class="form-label">Kota</label>
                <div style="position: relative;">
                    <input type="text" id="cityDisplay" class="form-control" value="{{ old('location', $community->location ?? 'Belum dipilih') }}" readonly style="background: white; cursor: pointer;">
                    <input type="hidden" name="location" id="cityValue" value="{{ old('location', $community->location) }}">
                    <i class="fas fa-crosshairs location-icon"></i>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label class="form-label">Deskripsi Komunitas</label>
                <textarea name="description" class="form-control" onkeyup="countChars(this)">{{ old('description', $community->description) }}</textarea>
                <div class="char-count" id="charCount">Minimal 30 Karakter</div>
            </div>

            <!-- PRIVACY -->
            <div class="section-title">Privasi Komunitas</div>

            <label class="privacy-option" onclick="selectPrivacy('public')">
                <input type="radio" name="type" value="public" id="privacyPublic" {{ $community->type == 'public' ? 'checked' : '' }} onchange="toggleApprovalOption()">
                <div class="privacy-content">
                    <span class="privacy-header">Publik</span>
                    <span class="privacy-desc">Anggota dapat bergabung ke komunitas tanpa persetujuan admin.</span>
                </div>
                <div class="radio-circle"></div>
            </label>

            <!-- Approval Option for Public -->
            <div id="approvalSection" style="display: {{ $community->type == 'public' ? 'flex' : 'none' }};">
                <label class="approval-option" style="width: 100%;">
                    <input type="checkbox" name="requires_approval" value="1" class="approval-checkbox" {{ $community->requires_approval ? 'checked' : '' }}>
                    <div class="approval-label-content">
                        <span class="approval-title">Perlu Persetujuan</span>
                        <span class="approval-desc">Admin harus menyetujui member baru</span>
                    </div>
                </label>
            </div>

            <label class="privacy-option" onclick="selectPrivacy('private')">
                <input type="radio" name="type" value="private" id="privacyPrivate" {{ $community->type == 'private' ? 'checked' : '' }} onchange="toggleApprovalOption()">
                <div class="privacy-content">
                    <span class="privacy-header">Pribadi</span>
                    <span class="privacy-desc">Hanya anggota dengan persetujuan admin yang dapat bergabung.</span>
                </div>
                <div class="radio-circle"></div>
            </label>

            <!-- DELETE SECTION -->
            <div class="delete-item" onclick="confirmCommunityDelete()">
                <div>
                    <span class="delete-label">Hapus Komunitas</span>
                    <span class="delete-sublabel">Komunitas yang sudah dihapus tidak bisa dikembalikan</span>
                </div>
                <i class="fas fa-chevron-right" style="color: #999; font-size: 12px;"></i>
            </div>


            <!-- SAVE BUTTON (Moved here from bottom-bar) -->
            <button type="submit" class="btn-save">Simpan Komunitas</button>
        </div>

    </form>

    <!-- BOTTOM NAVIGATION -->
    <nav class="bottom-nav">
        <a href="{{ route('buyer.communities.show', $community->id) }}" class="nav-item active">
            <div class="nav-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <span class="nav-label">Profil</span>
        </a>
        
        <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="nav-label">Aktivitas</span>
        </a>
        
        <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span class="nav-label">Anggota</span>
        </a>
        
        <a href="#" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <span class="nav-label">Kompetisi</span>
        </a>
        
        <a href="{{ route('buyer.communities.galeri', $community->id) }}" class="nav-item">
            <div class="nav-icon">
                <i class="fas fa-images"></i>
            </div>
            <span class="nav-label">Galeri</span>
        </a>
    </nav>
</div>

<!-- CITY SELECTION MODAL -->
<div class="modal-overlay" id="modalOverlay" onclick="closeCityModal()"></div>
<div class="city-modal" id="cityModal">
    <div class="modal-drag-handle"></div>
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
        <!-- Results will be injected here -->
        <div style="padding: 20px; text-align: center; color: #999;">Loading cities...</div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div class="toast-notification" id="toastNotification">
    <div class="toast-content">
        <i class="fas fa-check-circle toast-icon" id="toastIcon"></i>
        <div class="toast-message" id="toastMessage"></div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="delete-modal-overlay" id="deleteModalOverlay">
    <div class="delete-modal">
        <div class="delete-modal-title">Hapus Komunitas</div>
        <div class="delete-modal-message">Apakah Kamu yakin ingin meghapus komunitas {{ $community->name }}?</div>
        <div class="delete-modal-buttons">
            <button type="button" class="delete-modal-btn cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="delete-modal-btn confirm" onclick="executeDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

<form id="deleteForm" action="{{ route('buyer.communities.destroy', $community->id) }}" method="POST" style="display: none;">
    @method('DELETE')
</form>

</div>

<!-- LOADING OVERLAY - Moved outside mobile-frame for full desktop coverage -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text" id="loadingText">Memproses...</div>
    <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
</div>

@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function countChars(textarea) {
        // Implement char counting logic if needed, currently valid placeholder
    }

    function selectPrivacy(type) {
        // Just allows clicking the row to check radio
        document.querySelectorAll('.privacy-option').forEach(el => el.classList.remove('active'));
        // The radio change will trigger toggleApprovalOption if added to onclick or via listener
    }

    function toggleApprovalOption() {
        const isPublic = document.getElementById('privacyPublic').checked;
        const approvalSection = document.getElementById('approvalSection');
        
        if (isPublic) {
            approvalSection.style.display = 'flex';
        } else {
            approvalSection.style.display = 'none';
        }

        // Handle styling of parent labels
        document.querySelectorAll('.privacy-option').forEach(el => {
            const radio = el.querySelector('input[type="radio"]');
            if(radio.checked) {
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });
    }
    
    // Initial active state update
    document.addEventListener('DOMContentLoaded', function() {
        const checked = document.querySelector('input[name="type"]:checked');
        if(checked) {
            checked.closest('.privacy-option').classList.add('active');
        }
    });

    function confirmCommunityDelete() {
        // Show custom modal instead of browser confirm
        document.getElementById('deleteModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModalOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function executeDelete() {
        // Close modal first
        closeDeleteModal();
        
        // Show loading overlay
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
        
        // Use AJAX to handle JSON response properly
        const form = document.getElementById('deleteForm');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success toast and redirect
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("buyer.communities.index") }}';
                }, 1500);
            } else {
                // Hide loading if error
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                showToast(data.message || 'Gagal menghapus komunitas', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Hide loading if error
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            showToast('Terjadi kesalahan saat menghapus komunitas', 'error');
        });
    }

    // Toast Notification Function
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');
        
        // Set message
        toastMessage.textContent = message;
        
        // Set icon and type
        if (type === 'success') {
            toastIcon.className = 'fas fa-check-circle toast-icon';
            toast.className = 'toast-notification success';
        } else {
            toastIcon.className = 'fas fa-exclamation-circle toast-icon';
            toast.className = 'toast-notification error';
        }
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // CITY SELECTION LOGIC
    let indonesianCities = [];

    async function loadCities() {
        try {
            const response = await fetch('/data/indonesia-cities.json');
            indonesianCities = await response.json();
            renderCities(indonesianCities.slice(0, 50)); // Initial top 50
        } catch (error) {
            console.error('Error loading cities:', error);
            document.getElementById('cityList').innerHTML = '<div style="padding: 20px; text-align: center; color: red;">Gagal memuat data kota</div>';
        }
    }

    function renderCities(cities) {
        const list = document.getElementById('cityList');
        if (cities.length === 0) {
            list.innerHTML = '<div style="padding: 20px; text-align: center; color: #999;">Kota tidak ditemukan</div>';
            return;
        }

        list.innerHTML = cities.map(city => `
            <div class="city-item" onclick="selectCity('${city.province}', '${city.name}')">
                <div class="city-name">${city.province} , ${city.name}</div>
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

    function openCityModal() {
        document.getElementById('cityModal').classList.add('active');
        document.getElementById('modalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
        if (indonesianCities.length === 0) loadCities();
    }

    function closeCityModal() {
        document.getElementById('cityModal').classList.remove('active');
        document.getElementById('modalOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function selectCity(province, city) {
        const fullLocation = `${province} , ${city}`;
        document.getElementById('cityDisplay').value = fullLocation;
        document.getElementById('cityValue').value = fullLocation;
        closeCityModal();
    }
</script>
@endpush
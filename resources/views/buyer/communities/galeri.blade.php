@extends('layouts.main', ['title' => 'Galeri - ' . $community->name])

@push('styles')
<style>
    /* ================= VARIABLES ================= */
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
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        background: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
        color: var(--text);
        line-height: 1.6;
    }

    .mobile-container {
        width: 100%;
        min-height: 100vh;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        max-width: 100%;
    }

    /* ================= VISUAL HEADER ================= */
    .visual-header {
        position: relative;
        width: 100%;
        height: 240px;
        background-color: #333;
        background-size: cover;
        background-position: center;
        overflow: hidden;
    }
    
    .visual-header::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.1) 100%);
    }

    .visual-top-bar {
        position: absolute;
        top: 0; left: 0; width: 100%;
        padding: 40px 16px 20px;
        display: flex; justify-content: space-between; align-items: center;
        z-index: 10;
    }

    .visual-btn {
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2); color: white;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 18px;
    }

    /* PROFILE SECTION */
    .visual-profile-section {
        position: relative;
        margin-top: -60px;
        padding: 0 20px 20px;
        text-align: center;
        z-index: 20;
    }

    .visual-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 16px;
        border-radius: 50%;
        padding: 4px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .visual-avatar {
        width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
    }

    .visual-name {
        font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px;
    }

    .visual-meta {
        font-size: 13px; color: var(--text-light); margin-bottom: 20px;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }

    .visual-meta span:not(:last-child)::after {
        content: '•'; margin-left: 8px; color: #ccc;
    }

    .visual-main-action {
        padding: 0 20px;
        margin-bottom: 20px;
    }

    .btn-chat-large {
        width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 12px;
        background: white; color: var(--text); font-weight: 700; font-size: 14px;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none; transition: all 0.2s ease;
    }

    /* ================= GALLERY SECTION ================= */
    .gallery-section {
        padding: 24px 16px;
        border-top: 1px solid #f0f0f0;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .gallery-item {
        position: relative;
        aspect-ratio: 1/1;
        border-radius: 12px;
        overflow: hidden;
        background: #f8f9fa;
        cursor: pointer;
    }

    .gallery-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-add-item {
        width: 100%;
        height: 100%;
        background: white;
        border: 2px dashed #eee;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-add-item:active {
        transform: scale(0.95);
        border-color: var(--primary);
    }

    .btn-add-item i {
        font-size: 28px;
        color: #999;
        margin-bottom: 4px;
    }

    .btn-add-item .plus-badge {
        position: absolute;
        bottom: 25%;
        right: 25%;
        background: var(--danger);
        color: white;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid white;
    }

    /* ================= BOTTOM NAV ================= */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 8px 0 10px;
        box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
        z-index: 1000;
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

    /* ================= MODAL BOTTOM SHEET ================= */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: flex-end;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        max-width: 480px;
        width: 100%;
        background: white;
        border-radius: 28px 28px 0 0;
        padding: 20px 20px env(safe-area-inset-bottom, 24px);
        box-shadow: 0 -8px 32px rgba(0,0,0,0.1);
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-handle {
        width: 40px;
        height: 4px;
        background: #eee;
        border-radius: 2px;
        margin: 0 auto 20px;
    }

    .modal-option {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        cursor: pointer;
        border-radius: 12px;
        transition: background 0.2s ease;
    }

    .modal-option:active {
        background: #f8f9fa;
    }

    .modal-option i {
        font-size: 20px;
        color: #333;
        width: 24px;
        text-align: center;
    }

    .modal-option span {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    /* ================= IMAGE VIEWER MODAL ================= */
    .viewer-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: black;
        z-index: 3000;
        flex-direction: column;
    }

    .viewer-overlay.active {
        display: flex;
    }

    .viewer-header {
        width: 100%;
        padding: 40px 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, transparent 100%);
    }

    .viewer-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
    }

    .viewer-index {
        font-size: 16px;
        font-weight: 600;
    }

    .viewer-main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .viewer-img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .viewer-nav {
        position: absolute;
        top: 0;
        height: 100%;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.5);
        font-size: 24px;
        cursor: pointer;
        z-index: 10;
    }

    .viewer-nav-prev { left: 0; }
    .viewer-nav-next { right: 0; }

    .viewer-footer {
        padding: 20px 16px 40px;
        display: flex;
        justify-content: center;
        gap: 8px;
        overflow-x: auto;
        background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 100%);
    }

    .viewer-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        opacity: 0.5;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .viewer-thumb.active {
        opacity: 1;
        border-color: white;
    }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 480px) {
        .mobile-container {
            max-width: 480px;
            margin: 10px auto;
            box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
            border-radius: var(--radius-2xl);
            overflow: hidden;
        }

        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .viewer-overlay {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
        }
    }
</style>
@endpush

@section('content')
<div class="mobile-container">
    {{-- Header Visual --}}
    <div class="visual-header" 
         @if($community->background_image)
         style="background-image: url('{{ asset('storage/' . $community->background_image) }}');"
         @else
         style="background: linear-gradient(135deg, #0A5C36 0%, #08472a 100%);"
         @endif>
        <div class="visual-top-bar">
            <a href="{{ route('buyer.communities.show', $community->id) }}" class="visual-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div style="display: flex; gap: 12px;">
                <button class="visual-btn"><i class="fas fa-share-alt"></i></button>
                @if($isManager)
                <a href="{{ route('buyer.communities.edit', $community->id) }}" class="visual-btn">
                    <i class="fas fa-cog"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Profile Section --}}
    <div class="visual-profile-section">
        <div class="visual-avatar-wrapper">
            <img src="{{ $community->logo ? asset('storage/' . $community->logo) : asset('images/default-logo.png') }}" class="visual-avatar" alt="Logo">
        </div>
        
        <h1 class="visual-name">{{ $community->name }}</h1>
        <div class="visual-meta">
            <span>{{ $community->category->category_name ?? 'Olahraga' }}</span>
            <span>{{ ucfirst($community->type) }}</span>
            <span>{{ $community->location ?? 'Belum ada lokasi' }}</span>
        </div>
        
        <div class="visual-main-action">
            @if($isMember || $isManager)
            <a href="{{ route('buyer.communities.chat', $community->id) }}" class="btn-chat-large">
                <i class="far fa-comment-dots"></i> Chat
            </a>
            @endif
        </div>
    </div>

    {{-- Gallery Section --}}
    <div class="gallery-section">
        <div class="gallery-grid" id="galleryGrid">
            {{-- Tombol Tambah --}}
            @if($isManager)
            <div class="gallery-item">
                <div class="btn-add-item" onclick="openAddModal()">
                    <i class="far fa-image"></i>
                    <div class="plus-badge">
                        <i class="fas fa-plus" style="font-size: 8px; color: white;"></i>
                    </div>
                </div>
            </div>
            @endif

            {{-- Gallery Items from Database --}}
            @foreach($galleries as $gallery)
            <div class="gallery-item" onclick="openViewer({{ $loop->index }})">
                <img src="{{ asset('storage/' . $gallery->image_path) }}" class="gallery-img" alt="Gallery" data-id="{{ $gallery->id }}">
            </div>
            @endforeach
        </div>
    </div>

    <div style="height: 100px;"></div>

    {{-- Hidden File Inputs --}}
    <input type="file" id="galleryInput" style="display: none;" accept="image/*" onchange="handleFileUpload(this)">
    <input type="file" id="cameraInput" style="display: none;" accept="image/*" capture="environment" onchange="handleFileUpload(this)">

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        <a href="{{ route('buyer.communities.show', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-user-circle"></i></div>
            <span class="nav-label">Profil</span>
        </a>
        <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-calendar-alt"></i></div>
            <span class="nav-label">Aktivitas</span>
        </a>
        <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="nav-item">
            <div class="nav-icon"><i class="fas fa-users"></i></div>
            <span class="nav-label">Anggota</span>
        </a>
        <a href="#" class="nav-item">
            <div class="nav-icon"><i class="fas fa-trophy"></i></div>
            <span class="nav-label">Kompetisi</span>
        </a>
        <a href="{{ route('buyer.communities.galeri', $community->id) }}" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-images"></i></div>
            <span class="nav-label">Galeri</span>
        </a>
    </nav>

    {{-- Bottom Sheet Modal --}}
    <div class="modal-overlay" id="addModal" onclick="closeAddModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-handle"></div>
            
            <button class="modal-option" onclick="triggerCameraInput()">
                <i class="fas fa-camera"></i>
                <span>Ambil Foto</span>
            </button>
            <button class="modal-option" onclick="triggerFileInput()">
                <i class="far fa-images"></i>
                <span>Tambahkan dari Galeri</span>
            </button>
        </div>
    </div>

    {{-- Image Viewer Modal --}}
    <div class="viewer-overlay" id="imageViewer">
        <div class="viewer-header">
            <div class="viewer-btn" onclick="closeViewer()">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="viewer-index" id="viewerIndex">1/1</div>
            <div style="display: flex; gap: 8px;">
                <div class="viewer-btn"><i class="fas fa-share-alt"></i></div>
                @if($isManager)
                <div class="viewer-btn" onclick="deleteCurrentImage()"><i class="fas fa-trash-alt"></i></div>
                @endif
            </div>
        </div>
        
        <div class="viewer-main">
            <div class="viewer-nav viewer-nav-prev" onclick="prevImage()"><i class="fas fa-chevron-left"></i></div>
            <img src="" id="viewerMainImg" class="viewer-img">
            <div class="viewer-nav viewer-nav-next" onclick="nextImage()"><i class="fas fa-chevron-right"></i></div>
        </div>
        
        <div class="viewer-footer" id="viewerThumbs">
            <!-- Thumbnails filled by JS -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const communityId = {{ $community->id }};
    const isManager = {{ $isManager ? 'true' : 'false' }};
    let galleryData = @json($galleries->map(fn($g) => ['id' => $g->id, 'url' => asset('storage/' . $g->image_path)]));
    let currentIndex = 0;

    // ================= MODAL ADD FUNCTIONS =================
    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
        document.body.style.overflow = '';
    }

    function triggerFileInput() {
        document.getElementById('galleryInput').click();
        closeAddModal();
    }

    function triggerCameraInput() {
        document.getElementById('cameraInput').click();
        closeAddModal();
    }

    // ================= UPLOAD FUNCTIONS =================
    async function handleFileUpload(input) {
        if (!input.files || !input.files[0]) return;
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Simple toast or indicator
        // showLoading();

        try {
            const response = await fetch(`{{ route('buyer.communities.galeri.store', $community->id) }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Add to local data
                galleryData.unshift({ id: data.gallery_id, url: data.image_url });
                
                // Refresh Grid
                renderGrid();
                input.value = '';
            } else {
                alert('Gagal mengupload gambar: ' + (data.message || 'Error tidak diketahui'));
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('Terjadi kesalahan saat mengupload gambar.');
        }
    }

    function renderGrid() {
        const grid = document.getElementById('galleryGrid');
        
        // Clear except the add button
        const addButton = grid.querySelector('.gallery-item:first-child');
        grid.innerHTML = '';
        if (isManager && addButton) {
            grid.appendChild(addButton);
        }

        galleryData.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'gallery-item';
            div.onclick = () => openViewer(index);
            div.innerHTML = `<img src="${item.url}" class="gallery-img" alt="Gallery" data-id="${item.id}">`;
            grid.appendChild(div);
        });
    }

    // ================= VIEWER FUNCTIONS =================
    function openViewer(index) {
        currentIndex = index;
        const viewer = document.getElementById('imageViewer');
        viewer.classList.add('active');
        document.body.style.overflow = 'hidden';
        updateViewer();
    }

    function closeViewer() {
        const viewer = document.getElementById('imageViewer');
        viewer.classList.remove('active');
        document.body.style.overflow = '';
    }

    function updateViewer() {
        const current = galleryData[currentIndex];
        if (!current) return;

        document.getElementById('viewerMainImg').src = current.url;
        document.getElementById('viewerIndex').innerText = `${currentIndex + 1}/${galleryData.length}`;
        
        // Render Thumbnails
        const thumbContainer = document.getElementById('viewerThumbs');
        thumbContainer.innerHTML = '';
        galleryData.forEach((item, idx) => {
            const img = document.createElement('img');
            img.src = item.url;
            img.className = `viewer-thumb ${idx === currentIndex ? 'active' : ''}`;
            img.onclick = () => {
                currentIndex = idx;
                updateViewer();
            };
            thumbContainer.appendChild(img);
        });

        // Scroll thumb into view
        const activeThumb = thumbContainer.querySelector('.viewer-thumb.active');
        if (activeThumb) activeThumb.scrollIntoView({ behavior: 'smooth', inline: 'center' });
    }

    function nextImage() {
        if (currentIndex < galleryData.length - 1) {
            currentIndex++;
            updateViewer();
        }
    }

    function prevImage() {
        if (currentIndex > 0) {
            currentIndex--;
            updateViewer();
        }
    }

    async function deleteCurrentImage() {
        if (!confirm('Hapus gambar ini dari galeri?')) return;
        
        const current = galleryData[currentIndex];
        try {
            const response = await fetch(`{{ url('/buyer/communities') }}/${communityId}/galeri/${current.id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                galleryData.splice(currentIndex, 1);
                if (galleryData.length === 0) {
                    closeViewer();
                } else {
                    if (currentIndex >= galleryData.length) currentIndex = galleryData.length - 1;
                    updateViewer();
                }
                renderGrid();
            }
        } catch (error) {
            console.error('Delete error:', error);
            alert('Gagal menghapus gambar.');
        }
    }

    // Touch Swipe Logic for Viewer
    let touchStartX = 0;
    let touchEndX = 0;

    const viewerMain = document.querySelector('.viewer-main');
    viewerMain.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX, false);
    viewerMain.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);

    function handleSwipe() {
        if (touchEndX < touchStartX - 50) nextImage();
        if (touchEndX > touchStartX + 50) prevImage();
    }

    // Keyboard navigation
    document.addEventListener('keydown', e => {
        if (!document.getElementById('imageViewer').classList.contains('active')) return;
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'Escape') closeViewer();
    });
</script>
@endpush

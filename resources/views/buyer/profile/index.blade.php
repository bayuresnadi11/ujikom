{{--
=============================================================================
VIEW: PROFIL BUYER (HALAMAN PROFIL PENYEWA)
Halaman untuk menampilkan profil user dengan role buyer/penyewa
Menampilkan informasi profil, saldo deposit, dan menu pengaturan
=============================================================================
--}}

{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'Profil - SewaLap'])

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling profil dari partial --}}
    @include('buyer.profile.partials.profile-style')
    {{-- Memasukkan file CSS untuk styling visual header dari partial --}}
    @include('buyer.profile.partials.visual-header-style')
    <style>
        /* ================= GUEST OVERLAY STYLES ================= */
        /* Efek blur untuk konten yang hanya bisa diakses user login */
        .guest-blur {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }
        
        .guest-overlay-container {
            position: relative;
            overflow: hidden;
        }

        /* Overlay yang muncul untuk guest (belum login) */
        .guest-locked-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            backdrop-filter: blur(2px);
        }

        .guest-locked-icon {
            font-size: 40px;
            color: #95a5a6;
            margin-bottom: 10px;
        }

        .guest-locked-text {
            color: #7f8c8d;
            font-weight: 600;
            font-size: 14px;
        }

        /* ================= LOADING OVERLAY ================= */
        .loading-overlay {
            position: fixed;
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
            border-top: 5px solid #2ecc71;
            border-radius: 50%;
            animation: spin-overlay 1s linear infinite;
            margin-bottom: 20px;
        }

        .loading-text {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .loading-subtext {
            font-size: 14px;
            opacity: 0.8;
            padding: 0 40px;
        }

        @keyframes spin-overlay {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <!-- ====================== SUCCESS POPUP ====================== -->
    {{-- Popup custom untuk menampilkan pesan sukses --}}
    <div class="alert-popup" id="successPopup">
        <div class="alert-popup-content">
            <i class="fas fa-check-circle alert-popup-icon success"></i>
            <h3 class="alert-popup-title">Berhasil!</h3>
            <p class="alert-popup-message" id="successMessage"></p>
            <button class="alert-popup-button success" onclick="closeSuccessPopup()">
                OK
            </button>
        </div>
    </div>

    <!-- ====================== ERROR POPUP ====================== -->
    {{-- Popup custom untuk menampilkan pesan error --}}
    <div class="alert-popup" id="errorPopup">
        <div class="alert-popup-content">
            <i class="fas fa-exclamation-circle alert-popup-icon error"></i>
            <h3 class="alert-popup-title">Gagal!</h3>
            <p class="alert-popup-message" id="errorMessage"></p>
            <button class="alert-popup-button error" onclick="closeErrorPopup()">
                OK
            </button>
        </div>
    </div>

    {{-- Modal untuk pengajuan menjadi pemilik lapangan (hanya untuk user login) --}}
    @auth
    <div class="modal" id="switchModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ajukan sebagai Pemilik Lapangan</h3>
                <button class="modal-close" onclick="closeSwitchModal()">&times;</button>
            </div>

            <form id="switchForm" action="{{ route('buyer.landowner.request') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="reason">Alasan Pengajuan</label>
                    <textarea name="reason" id="reason" rows="4" required
                        placeholder="Jelaskan mengapa Anda ingin menjadi pemilik lapangan..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeSwitchModal()">Batal</button>
                    <button type="submit" class="btn-primary">Ajukan</button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        {{-- Memasukkan header dari layout terpisah, dengan search dinonaktifkan --}}
        @include('layouts.header', ['showSearch' => false])

        <!-- Main Content -->
        <main class="main-content">
            {{-- Menampilkan pesan sukses dari session (jika ada) --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        showSuccessPopup("{{ session('success') }}");
                    });
                </script>
            @endif

            {{-- Menampilkan pesan error dari session (jika ada) --}}
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        showErrorPopup("{{ session('error') }}");
                    });
                </script>
            @endif

            <!-- ====================== VISUAL HEADER WITH BACKGROUND ====================== -->
            {{-- Header visual dengan background gambar atau gradien --}}
            <section class="visual-header" style="{{ auth()->check() && $user->background ? "background-image: url('" . asset('storage/' . $user->background) . "');" : 'background: linear-gradient(135deg, #0A5C36 0%, #14452F 100%);' }}">
                @auth
                <div class="visual-top-bar">
                    {{-- Tombol untuk mengedit profil (ke halaman edit) --}}
                    <a href="{{ route('buyer.profile.edit') }}" class="visual-btn">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>
                <!-- Camera Button -->
                {{-- Tombol kamera untuk mengubah background header --}}
                <button class="camera-btn" onclick="openBackgroundModal()" title="Ubah Background">
                    <i class="fas fa-camera"></i>
                </button>
                @endauth
            </section>

            <!-- ====================== PROFILE SECTION ====================== -->
            {{-- Section foto profil dan nama user --}}
            <section class="visual-profile-section">
                <div class="visual-avatar-wrapper">
                    {{-- Link ke halaman edit profil (hanya untuk user login) --}}
                    <a href="{{ auth()->check() ? route('buyer.profile.edit') : '#' }}" style="display: block; width: 100%; height: 100%;">
                        @if(auth()->check() && $user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="visual-avatar">
                        @else
                            <div class="visual-avatar" style="display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="font-size: 40px; color: #ccc;"></i>
                            </div>
                        @endif
                    </a>
                </div>

                {{-- Nama user atau "Guest" jika belum login --}}
                <h1 class="visual-name">{{ auth()->check() ? ($user->name ?? 'User') : 'Guest' }}</h1>

                @auth
                {{-- Badge status akun terverifikasi --}}
                <div style="display: flex; align-items: center; justify-content: center; gap: 6px; color: var(--success); font-size: 13px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                    <span>Akun Terverifikasi</span>
                </div>
                @endauth
            </section>

            <!-- ====================== DEPOSIT BALANCE CARD ====================== -->
            {{-- Kartu saldo deposit dengan fitur toggle visibility --}}
            <section class="deposit-summary">
                <div class="deposit-card">
                    <div class="deposit-info">
                        <div class="deposit-label">Saldo Deposit</div>
                        {{-- data-original menyimpan nilai asli untuk toggle --}}
                        <div class="deposit-amount" data-original="Rp {{ number_format($balance, 0, ',', '.') }}">
                             Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                    @auth
                    <div style="display: flex; gap: 10px;">
                        {{-- Tombol toggle untuk menyembunyikan/menampilkan saldo --}}
                        <button type="button" class="deposit-action" onclick="toggleDeposit()" style="border: none; cursor: pointer; padding: 0.6rem 0.8rem; justify-content: center;">
                            <i class="fas fa-eye" id="depositToggleIcon"></i>
                        </button>
                        {{-- Tombol detail deposit --}}
                        <a href="{{ route('buyer.deposit.index') }}" class="deposit-action">
                            <i class="fas fa-wallet"></i>
                            <span>Detail</span>
                        </a>
                    </div>
                    @else
                    {{-- Untuk guest: tombol login --}}
                    <button type="button" onclick="window.location.href='{{ route('login') }}'" class="deposit-action" style="border:none; cursor:pointer;">
                        Login
                    </button>
                    @endauth
                </div>
            </section>

            <!-- ====================== PROFILE DETAILS ====================== -->
            {{-- Section detail informasi profil user --}}
            <section class="profile-details guest-overlay-container">
                @guest
                {{-- Overlay untuk guest (belum login) --}}
                <div class="guest-locked-overlay">
                    <i class="fas fa-lock guest-locked-icon"></i>
                    <span class="guest-locked-text">Login untuk melihat detail</span>
                </div>
                @endguest

                <h2 class="details-title {{ auth()->check() ? '' : 'guest-blur' }}">
                    <i class="fas fa-user-circle"></i>
                    Detail Profil
                </h2>
                <ul class="details-list {{ auth()->check() ? '' : 'guest-blur' }}">
                    {{-- Nomor Telepon --}}
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Telepon</div>
                            <div class="detail-value" id="detailPhone">{{ auth()->check() ? $user->phone : '-' }}</div>
                        </div>
                    </li>
                    {{-- Alamat --}}
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value" id="detailAddress">{{ auth()->check() ? ($user->address ?? 'belum di atur') : '-' }}</div>
                        </div>
                    </li>
                    {{-- Jenis Kelamin (dengan konversi teks) --}}
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Jenis Kelamin</div>
                            <div class="detail-value" id="detailGender">
                                @if(auth()->check())
                                    @php
                                        $gender = $user->gender ?? 'belum di atur';
                                        $genderText = '';
                                        switch ($gender) {
                                            case 'male':
                                                $genderText = 'Laki-laki';
                                                break;
                                            case 'female':
                                                $genderText = 'Perempuan';
                                                break;
                                            default:
                                                $genderText = 'Lainnya';
                                                break;
                                        }
                                    @endphp
                                    {{ $genderText }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </li>
                    {{-- Tanggal Bergabung --}}
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Bergabung Sejak</div>
                            <div class="detail-value">{{ auth()->check() ? $user->created_at->format('d F Y') : '-' }}</div>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- ====================== MENU PENGATURAN & BANTUAN ====================== -->
            {{-- Menu pengaturan, bantuan, switch role, dan logout --}}
            <section class="settings-menu guest-overlay-container">
                @guest
                {{-- Overlay transparan untuk guest --}}
                <div class="guest-locked-overlay" style="background: rgba(255, 255, 255, 0.4);">
                    {{-- Transparent overlay to prevent clicks but show content vaguely --}}
                </div>
                @endguest

                <h2 class="menu-title {{ auth()->check() ? '' : 'guest-blur' }}">
                    <i class="fas fa-cog"></i>
                    Pengaturan & Bantuan
                </h2>
                <ul class="menu-list {{ auth()->check() ? '' : 'guest-blur' }}">
                    {{-- Menu Booking Saya --}}
                    <li class="menu-list-item" onclick="window.location.href='{{ route('buyer.booking.index') }}'">
                        <div class="menu-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="menu-content">
                            <div class="menu-item-title">Booking Saya</div>
                            <div class="menu-item-desc">Daftar booking anda</div>
                        </div>
                        <div class="menu-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </li>
                    {{-- Menu Bantuan --}}
                    <li class="menu-list-item" onclick="openHelpCenter()">
                        <div class="menu-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="menu-content">
                            <div class="menu-item-title">Bantuan</div>
                            <div class="menu-item-desc">Dapatkan bantuan dan panduan penggunaan</div>
                        </div>
                        <div class="menu-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </li>
                    
                    @auth
                        @php
                            $user = auth()->user();
                            $isLandowner = $user->canSwitchToLandowner(); // Cek apakah user memiliki izin sebagai landowner
                            $hasPending = $user->hasPendingLandownerRequest(); // Cek apakah ada pengajuan pending
                        @endphp

                        @if($hasPending)
                            {{-- Tampilkan status pending (sedang menunggu persetujuan admin) --}}
                            <div class="menu-list-item disabled" style="opacity: 0.6; cursor: not-allowed;">
                                <div class="menu-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="menu-content">
                                    <div class="menu-item-title">Menunggu Persetujuan</div>
                                    <div class="menu-item-desc">Pengajuan Anda sedang ditinjau admin</div>
                                </div>
                            </div>
                        @elseif($isLandowner)
                            {{-- Sudah punya izin landowner - bisa langsung switch --}}
                            <div class="menu-list-item" onclick="directSwitchToLandowner()" style="cursor: pointer;">
                                <div class="menu-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="menu-content">
                                    <div class="menu-item-title">Switch ke Pemilik</div>
                                    <div class="menu-item-desc">Langsung beralih ke mode pemilik</div>
                                </div>
                                <div class="menu-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        @else
                            {{-- Belum punya izin - harus ajukan terlebih dahulu --}}
                            <div class="menu-list-item" onclick="showSwitchModal()" style="cursor: pointer;">
                                <div class="menu-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="menu-content">
                                    <div class="menu-item-title">Ajukan sebagai Pemilik</div>
                                    <div class="menu-item-desc">Ajukan untuk menjadi pemilik lapangan</div>
                                </div>
                                <div class="menu-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        @endif

                        {{-- Form logout tersembunyi --}}
                        <form action="{{ route('logout') }}" method="POST" id="logoutForm" style="display: none;">
                            @csrf
                        </form>
                        {{-- Menu Logout --}}
                        <li class="logout-menu-item" onclick="confirmLogout(event)">
                            <div class="logout-menu-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div class="menu-content">
                                <div class="logout-menu-title">Keluar</div>
                            </div>
                        </li>
                    @endauth
                </ul>
                
                @guest
                {{-- Tombol Login untuk guest (di luar area blur) --}}
                <div style="margin-top: 20px; padding: 0 16px;">
                    <a href="{{ route('login') }}" class="btn-primary" style="display: flex; align-items: center; justify-content: center; text-decoration: none; width: 100%; padding: 12px; border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        Login
                    </a>
                </div>
                @endguest
            </section>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')

        <!-- ====================== BACKGROUND OPTIONS MODAL ====================== -->
        {{-- Modal bottom sheet untuk memilih opsi ubah background header --}}
        <div class="bg-modal-overlay" id="bgOptionsModal" onclick="closeBackgroundModal()">
            <div class="bg-sheet" onclick="event.stopPropagation()">
                <div class="bg-handle"></div>
                
                {{-- Tombol untuk memilih gambar dari galeri/kamera --}}
                <button class="bg-option" onclick="triggerUpload()">
                    <i class="fas fa-image"></i>
                    <div style="flex:1;">
                        <div style="font-weight:600;">Ubah Background</div>
                        <div style="font-size:12px; color:#888;">Ambil foto atau pilih dari galeri</div>
                    </div>
                </button>
                
                {{-- Form tersembunyi untuk upload file --}}
                <form id="bgForm" style="display:none;">
                    @csrf
                    <input type="file" id="bgInput" name="background" accept="image/*" onchange="uploadBackground(this)">
                </form>
            </div>
        </div>

        <!-- ====================== LOADING OVERLAY ====================== -->
        {{-- Overlay loading yang muncul saat upload background atau proses lainnya --}}
        <div id="loadingOverlay" class="loading-overlay">
            <div class="loading-spinner"></div>
            <div class="loading-text" id="loadingText">Memproses...</div>
            <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengupload background Anda.</div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memasukkan file JavaScript untuk fungsi profil dari partial --}}
    @include('buyer.profile.partials.profile-script')
    <script>
        /**
         * FUNGSI TOGGLE DEPOSIT
         * Menyembunyikan atau menampilkan saldo deposit (untuk privasi)
         */
        function toggleDeposit() {
            const amountEl = document.querySelector('.deposit-amount');
            const icon = document.getElementById('depositToggleIcon');
            const originalValue = amountEl.getAttribute('data-original');
            
            // Jika sedang disembunyikan (berisi titik-titik), tampilkan nominal asli
            if (amountEl.textContent.includes('•')) {
                amountEl.textContent = originalValue;
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                localStorage.setItem('depositVisible', 'true');
            } else {
                // Jika sedang ditampilkan, sembunyikan dengan titik-titik
                amountEl.textContent = 'Rp •••••••';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                localStorage.setItem('depositVisible', 'false');
            }
        }

        /**
         * INISIALISASI SAAT DOM READY
         * Mengatur default saldo deposit dalam keadaan tersembunyi
         */
        document.addEventListener('DOMContentLoaded', function() {
            const amountEl = document.querySelector('.deposit-amount');
            const icon = document.getElementById('depositToggleIcon');
            
            if(amountEl && icon) {
                amountEl.textContent = 'Rp •••••••';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        /**
         * FUNGSI DIRECT SWITCH TO LANDOWNER
         * Mengirim form POST untuk mengubah role dari buyer menjadi landowner
         * (tanpa perlu konfirmasi modal karena sudah memiliki izin)
         */
        function directSwitchToLandowner() {
            // Membuat form secara dinamis dan submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('switch.to.landowner') }}";
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }

        // ================= LOADING FUNCTIONS =================
        /**
         * Menampilkan overlay loading dengan pesan tertentu
         * @param {string} message - Pesan yang ditampilkan saat loading
         */
        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            if (overlay && text) {
                text.textContent = message;
                overlay.style.display = 'flex';
            }
        }

        /**
         * Menyembunyikan overlay loading
         */
        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }

        // ================= BACKGROUND MODAL FUNCTIONS =================
        /**
         * Membuka modal bottom sheet untuk opsi ubah background
         */
        function openBackgroundModal() {
            document.getElementById('bgOptionsModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        /**
         * Menutup modal bottom sheet background
         */
        function closeBackgroundModal() {
            document.getElementById('bgOptionsModal').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        /**
         * Memicu upload file dengan mengklik input file tersembunyi
         */
        function triggerUpload() {
            document.getElementById('bgInput').click();
        }
        
        /**
         * FUNGSI UPLOAD BACKGROUND
         * Mengirim file gambar ke server untuk diupdate sebagai background header profil
         * @param {HTMLInputElement} input - Elemen input file yang berisi gambar
         */
        function uploadBackground(input) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('background', input.files[0]);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Tutup modal dan tampilkan loading
                closeBackgroundModal();
                showLoading('Sedang mengupload background...');
                
                // Kirim request upload ke server
                fetch("{{ route('buyer.profile.background.update') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload halaman untuk menampilkan background baru
                        window.location.reload();
                    } else {
                        hideLoading();
                        showErrorPopup('Gagal mengupload background: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showErrorPopup('Gagal mengupload background');
                });
                
                // Reset input file
                input.value = '';
            }
        }
    </script>
@endpush
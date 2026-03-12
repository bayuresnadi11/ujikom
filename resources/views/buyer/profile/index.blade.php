@extends('layouts.main', ['title' => 'Profil - SewaLap'])

@push('styles')
    @include('buyer.profile.partials.profile-style')
    @include('buyer.profile.partials.visual-header-style')
    <style>
        /* Guest Overlay Styles */
        .guest-blur {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }
        
        .guest-overlay-container {
            position: relative;
            overflow: hidden;
        }

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
    <!-- Success Popup -->
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

    <!-- Error Popup -->
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

    @auth
    <!-- Modal untuk pengajuan baru (Only for Auth) -->
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
        @include('layouts.header', ['showSearch' => false])

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        showSuccessPopup("{{ session('success') }}");
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        showErrorPopup("{{ session('error') }}");
                    });
                </script>
            @endif

            <!-- Visual Header with Background -->
            <section class="visual-header" style="{{ auth()->check() && $user->background ? "background-image: url('" . asset('storage/' . $user->background) . "');" : 'background: linear-gradient(135deg, #0A5C36 0%, #14452F 100%);' }}">
                @auth
                <div class="visual-top-bar">
                    <a href="{{ route('buyer.profile.edit') }}" class="visual-btn">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>
                <!-- Camera Button -->
                <button class="camera-btn" onclick="openBackgroundModal()" title="Ubah Background">
                    <i class="fas fa-camera"></i>
                </button>
                @endauth
            </section>

            <!-- Profile Section -->
            <section class="visual-profile-section">
                <div class="visual-avatar-wrapper">
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

                <h1 class="visual-name">{{ auth()->check() ? ($user->name ?? 'User') : 'Guest' }}</h1>

                @auth
                <div style="display: flex; align-items: center; justify-content: center; gap: 6px; color: var(--success); font-size: 13px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                    <span>Akun Terverifikasi</span>
                </div>
                @endauth
            </section>

            <!-- Deposit Balance Card -->
            <section class="deposit-summary">
                <div class="deposit-card">
                    <div class="deposit-info">
                        <div class="deposit-label">Saldo Deposit</div>
                        <div class="deposit-amount" data-original="Rp {{ number_format($balance, 0, ',', '.') }}">
                             Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                    @auth
                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="deposit-action" onclick="toggleDeposit()" style="border: none; cursor: pointer; padding: 0.6rem 0.8rem; justify-content: center;">
                            <i class="fas fa-eye" id="depositToggleIcon"></i>
                        </button>
                        <a href="{{ route('buyer.deposit.index') }}" class="deposit-action">
                            <i class="fas fa-wallet"></i>
                            <span>Detail</span>
                        </a>
                    </div>
                    @else
                    <!-- Guest View for Deposit -->
                     <button type="button" onclick="window.location.href='{{ route('login') }}'" class="deposit-action" style="border:none; cursor:pointer;">
                        Login
                    </button>
                    @endauth
                </div>
            </section>


            <!-- Profile Details -->
            <section class="profile-details guest-overlay-container">
                @guest
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
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Telepon</div>
                            <div class="detail-value" id="detailPhone">{{ auth()->check() ? $user->phone : '-' }}</div>
                        </div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value" id="detailAddress">{{ auth()->check() ? ($user->address ?? 'belum di atur') : '-' }}</div>
                        </div>
                    </li>
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

            <!-- Menu Pengaturan, Bantuan & Logout -->
            <section class="settings-menu guest-overlay-container">
                @guest
                 <div class="guest-locked-overlay" style="background: rgba(255, 255, 255, 0.4);">
                    {{-- Transparent overlay to prevent clicks but show content vaguely --}}
                </div>
                @endguest

                <h2 class="menu-title {{ auth()->check() ? '' : 'guest-blur' }}">
                    <i class="fas fa-cog"></i>
                    Pengaturan & Bantuan
                </h2>
                <ul class="menu-list {{ auth()->check() ? '' : 'guest-blur' }}">
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
                            $isLandowner = $user->canSwitchToLandowner(); // Permission
                            $hasPending = $user->hasPendingLandownerRequest();
                        @endphp

                        @if($hasPending)
                            <!-- Tampilkan status pending -->
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
                            <!-- Sudah punya izin landowner - bisa switch -->
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
                            <!-- Belum punya izin - harus ajukan dulu -->
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

                        <form action="{{ route('logout') }}" method="POST" id="logoutForm" style="display: none;">
                            @csrf
                        </form>
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
                <!-- Guest Login Button - Positioned outside the blur/overlay list for clarity or below it -->
                <div style="margin-top: 20px; padding: 0 16px;">
                    <a href="{{ route('login') }}" class="btn-primary" style="display: flex; align-items: center; justify-content: center; text-decoration: none; width: 100%; padding: 12px; border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        Login
                    </a>
                </div>
                @endguest
            </section>
        </main>

        <!-- Bottom Nav - Modified: Dashboard changed to Menu -->
        @include('layouts.bottom-nav')

        <!-- Background Options Modal -->
        <div class="bg-modal-overlay" id="bgOptionsModal" onclick="closeBackgroundModal()">
            <div class="bg-sheet" onclick="event.stopPropagation()">
                <div class="bg-handle"></div>
                
                <button class="bg-option" onclick="triggerUpload()">
                    <i class="fas fa-image"></i>
                    <div style="flex:1;">
                        <div style="font-weight:600;">Ubah Background</div>
                        <div style="font-size:12px; color:#888;">Ambil foto atau pilih dari galeri</div>
                    </div>
                </button>
                
                <form id="bgForm" style="display:none;">
                    @csrf
                    <input type="file" id="bgInput" name="background" accept="image/*" onchange="uploadBackground(this)">
                </form>
            </div>
        </div>

        <!-- ================= LOADING OVERLAY ================= -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="loading-spinner"></div>
            <div class="loading-text" id="loadingText">Memproses...</div>
            <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengupload background Anda.</div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('buyer.profile.partials.profile-script')
    <script>
        function toggleDeposit() {
            const amountEl = document.querySelector('.deposit-amount');
            const icon = document.getElementById('depositToggleIcon');
            const originalValue = amountEl.getAttribute('data-original');
            
            if (amountEl.textContent.includes('•')) {
                amountEl.textContent = originalValue;
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                localStorage.setItem('depositVisible', 'true');
            } else {
                amountEl.textContent = 'Rp •••••••';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                localStorage.setItem('depositVisible', 'false');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const amountEl = document.querySelector('.deposit-amount');
            const icon = document.getElementById('depositToggleIcon');
            
            if(amountEl && icon) {
                amountEl.textContent = 'Rp •••••••';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        function directSwitchToLandowner() {
            // Create a form dynamically and submit
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
        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            if (overlay && text) {
                text.textContent = message;
                overlay.style.display = 'flex';
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }

        // ================= BACKGROUND MODAL FUNCTIONS =================
        function openBackgroundModal() {
            document.getElementById('bgOptionsModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeBackgroundModal() {
            document.getElementById('bgOptionsModal').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function triggerUpload() {
            document.getElementById('bgInput').click();
        }
        
        function uploadBackground(input) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('background', input.files[0]);
                formData.append('_token', '{{ csrf_token() }}');
                
                closeBackgroundModal();
                showLoading('Sedang mengupload background...');
                
                fetch("{{ route('buyer.profile.background.update') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
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
                
                input.value = '';
            }
        }
    </script>
@endpush

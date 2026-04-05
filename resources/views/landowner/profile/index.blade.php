@extends('layouts.main', ['title' => 'Profil - SewaLap'])

@push('styles')
    @include('landowner.profile.partials.profile-style')
    @include('landowner.profile.partials.visual-header-style')
    <style>
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

    <!-- Modal untuk pengajuan baru -->
    <div class="modal" id="switchModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ajukan sebagai Pemilik Lapangan</h3>
                <button class="modal-close" onclick="closeSwitchModal()">&times;</button>
            </div>

            <form id="switchForm" action="{{ route('switch.to.buyer') }}" method="POST">
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

    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        @include('layouts.header')

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
            <section class="visual-header" style="{{ $user->landowner_background ? "background-image: url('" . asset('storage/' . $user->landowner_background) . "');" : 'background: linear-gradient(135deg, #8B1538 0%, #6B0F2A 100%);' }}">
                <div class="visual-top-bar">
                    <a href="{{ route('landowner.profile.edit') }}" class="visual-btn">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>
                <!-- Camera Button -->
                <button class="camera-btn" onclick="openBackgroundModal()" title="Ubah Background">
                    <i class="fas fa-camera"></i>
                </button>
            </section>

            <!-- Profile Section -->
            <section class="visual-profile-section">
                <div class="visual-avatar-wrapper">
                    <a href="{{ route('landowner.profile.edit') }}" style="display: block; width: 100%; height: 100%;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="visual-avatar">
                        @else
                            <div class="visual-avatar" style="display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="font-size: 40px; color: #ccc;"></i>
                            </div>
                        @endif
                    </a>


                </div>

                <h1 class="visual-name">{{ $user->name ?? 'User' }}</h1>

                <div style="display: flex; align-items: center; justify-content: center; gap: 6px; color: var(--success); font-size: 13px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                    <span>Akun Landowner</span>
                </div>
            </section>

            <!-- Deposit Balance Card -->
            <section class="deposit-summary">
                <div class="deposit-card">
                    <div class="deposit-info">
                        <div class="deposit-label">Saldo Deposit</div>
                       <div class="deposit-amount" data-original="Rp {{ number_format(auth()->user()->deposit->balance ?? 0, 0, ',', '.') }}">
                             Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="deposit-action" onclick="toggleDeposit()" style="border: none; cursor: pointer; padding: 0.6rem 0.8rem; justify-content: center;">
                            <i class="fas fa-eye" id="depositToggleIcon"></i>
                        </button>
                        <a href="{{ route('landowner.deposit.index') }}" class="deposit-action">
                            <i class="fas fa-wallet"></i>
                            <span>Detail</span>
                        </a>
                    </div>
                </div>
            </section>


            <!-- Profile Details -->
            <section class="profile-details">
                <h2 class="details-title">
                    <i class="fas fa-user-circle"></i>
                    Detail Profil
                </h2>
                <ul class="details-list">
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Nama Bisnis</div>
                            <div class="detail-value" id="detailBusiness">{{ $user->business_name }}</div>
                        </div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Telepon</div>
                            <div class="detail-value" id="detailPhone">{{ $user->phone }}</div>
                        </div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value" id="detailAddress">{{ $user->address ?? 'belum di atur' }}</div>
                        </div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Jenis Kelamin</div>
                            <div class="detail-value" id="detailGender">
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
                            </div>
                        </div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Bergabung Sejak</div>
                            <div class="detail-value">{{ $user->created_at->format('d F Y') }}</div>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- Menu Pengaturan, Bantuan & Logout -->
            <section class="settings-menu">
                <h2 class="menu-title">
                    <i class="fas fa-cog"></i>
                    Pengaturan & Bantuan
                </h2>
                <ul class="menu-list">
                    <li class="menu-list-item" onclick="window.location.href='{{ route('landowner.venue.index') }}'">
                        <div class="menu-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="menu-content">
                            <div class="menu-item-title">Kelola Lapangan</div>
                            <div class="menu-item-desc">Atur lapangan dan fasilitas</div>
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
                        <!-- Switch to Buyer Button -->
                        <div class="menu-list-item" onclick="directSwitchToBuyer()" style="cursor: pointer;">
                            <div class="menu-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-item-title">Switch ke Penyewa</div>
                                <div class="menu-item-desc">Kembali ke mode penyewa app</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>

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
                </ul>
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
    @include('landowner.profile.partials.profile-script')
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
            const icon = document.getElementById('depositToggleIcon');
            if(icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

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

        function directSwitchToBuyer() {
            // Create a form dynamically and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('switch.to.buyer') }}";
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
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
                
                fetch("{{ route('landowner.profile.background.update') }}", {
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

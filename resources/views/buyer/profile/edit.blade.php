@extends('layouts.main', ['title' => 'Edit Profil'])

@push('styles')
    @include('buyer.profile.partials.edit-style')
@endpush

@section('content')
<div class="mobile-container" id="mobileContainer">
    <!-- Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <main class="main-content">
        {{-- Notifikasi success/error ditangani otomatis oleh komponen toast-alert di layout --}}

        <form method="POST" action="{{ route('buyer.profile.update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            <!-- Section 1: Avatar dan Informasi Dasar -->
            <div class="section-container" id="section-basic">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h2 class="section-title">Informasi Dasar</h2>
                </div>
                <div class="section-body">
                    <!-- Avatar Upload -->
                    <div class="avatar-section">
                        <div class="avatar-preview" id="avatarPreview" onclick="document.getElementById('avatar').click()">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" id="avatarImage">
                            @else
                                <i class="fas fa-user-tie"></i>
                            @endif
                        </div>
                        <div class="avatar-actions">
                            <button type="button" class="avatar-upload-btn" onclick="triggerAvatarUpload()">
                                <i class="fas fa-camera"></i>
                                <span>Ganti Foto Profil</span>
                            </button>
                            <input type="file" 
                                id="avatar" 
                                name="avatar" 
                                accept="image/*" 
                                class="avatar-input-hidden"
                                onchange="previewImage(event)">
                            <div class="avatar-hint">
                                Maks. 2MB • JPG, PNG, GIF
                            </div>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            <span class="label-text">Nama Lengkap</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="gender" class="form-label">
                            <i class="fas fa-venus-mars"></i>
                            <span class="label-text">Jenis Kelamin</span>
                        </label>
                        <select id="gender" 
                                name="gender" 
                                class="form-control @error('gender') is-invalid @enderror">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="label-text">Alamat</span>
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               class="form-control @error('address') is-invalid @enderror" 
                               value="{{ old('address', $user->address) ?? 'belum di atur' }}" 
                               placeholder="Masukkan alamat Anda">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BANK INFORMATION - DITAMBAHKAN DI BAWAH ALAMAT -->
                    <div class="form-group">
                        <label for="bank_name" class="form-label">
                            <i class="fas fa-university"></i>
                            <span class="label-text">Nama Bank</span>
                        </label>
                        <input type="text" 
                               id="bank_name" 
                               name="bank_name" 
                               class="form-control @error('bank_name') is-invalid @enderror" 
                               value="{{ old('bank_name', $user->bank_name) }}" 
                               placeholder="Contoh: BCA, Mandiri, BRI">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account_number" class="form-label">
                            <i class="fas fa-credit-card"></i>
                            <span class="label-text">Nomor Rekening</span>
                        </label>
                        <input type="text" 
                               id="account_number" 
                               name="account_number" 
                               class="form-control @error('account_number') is-invalid @enderror" 
                               value="{{ old('account_number', $user->account_number) }}" 
                               placeholder="Masukkan nomor rekening">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account_holder_name" class="form-label">
                            <i class="fas fa-user-circle"></i>
                            <span class="label-text">Nama Pemilik Rekening</span>
                        </label>
                        <input type="text" 
                               id="account_holder_name" 
                               name="account_holder_name" 
                               class="form-control @error('account_holder_name') is-invalid @enderror" 
                               value="{{ old('account_holder_name', $user->account_holder_name) }}" 
                               placeholder="Nama sesuai di rekening">
                        @error('account_holder_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- END OF BANK INFORMATION -->

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role" class="form-label">
                            <i class="fas fa-key"></i>
                            <span class="label-text">Role</span>
                        </label>
                        <div class="view-only-field">
                            <i class="fas fa-key"></i>
                            <span>{{ $user->role }}</span>
                        </div>
                        <small class="field-hint">Role tidak dapat diubah</small>
                    </div>
                </div>
            </div>

            <!-- Section 2: Informasi Kontak -->
            <div class="section-container" id="section-contact" style="position: relative;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h2 class="section-title">Informasi Kontak</h2>
                </div>
                <div class="section-body">
                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-mobile-alt"></i>
                            <span class="label-text">Nomor Telepon</span>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="Contoh: 081234567890"
                               required @if($pendingPhone) disabled @endif>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="field-hint">
                            <i class="fas fa-info-circle"></i>
                            Nomor baru akan diverifikasi via WhatsApp
                        </small>
                    </div>
                </div>
                @if($pendingPhone)
                <div class="phone-overlay">
                    <div class="spinner"></div>
                    <div class="overlay-message">
                        <h4>Menunggu Verifikasi</h4>
                        <p>Link verifikasi telah dikirim ke WhatsApp</p>
                        <small>Cek pesan WhatsApp Anda</small>
                    </div>
                    
                    <div class="overlay-actions">
                        <button type="button" class="btn-modern btn-warning" onclick="ResendPhone()">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Kirim Ulang
                        </button>
                        <button type="button" class="btn-modern btn-danger" onclick="showCustomAlert()">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Section 3: Ubah Kata Sandi -->
            <div class="section-container" id="section-password">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2 class="section-title">Ubah Kata Sandi</h2>
                </div>
                <div class="section-body">
                    <!-- Kata Sandi Saat Ini -->
                    <div class="form-group">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            <span class="label-text">Kata Sandi Saat Ini</span>
                        </label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   placeholder="Masukkan kata sandi saat ini">
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kata Sandi Baru -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            <span class="label-text">Kata Sandi Baru</span>
                        </label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 8 karakter"
                                   oninput="checkPasswordStrength(this.value)">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="passwordStrength"></div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i>
                            <span class="label-text">Konfirmasi Kata Sandi</span>
                        </label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ulangi kata sandi baru">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="form-group">
                        <small class="field-hint">
                            <i class="fas fa-info-circle"></i>
                            Kosongkan jika tidak ingin mengubah kata sandi
                        </small>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit" id="submitButton">
                <span id="submitText">Simpan Perubahan</span>
                <div class="loading" id="loading" style="display: none;"></div>
            </button>
        </form>
        <form id="resendPhoneForm" method="POST" action="{{ route('buyer.phone.resend') }}">
            @csrf
        </form>

        <form id="cancelPhoneForm" method="POST" action="{{ route('buyer.phone.cancel') }}">
            @csrf
        </form>
        
        <!-- Custom Alert Modal -->
        <div id="customAlert" class="custom-alert">
            <div class="alert-box">
                <div class="alert-icon">
                    <svg style="width: 30px; height: 30px; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h4>Batalkan Perubahan?</h4>
                <p>Apakah Anda yakin ingin membatalkan perubahan nomor telepon?</p>
                <div class="alert-buttons">
                    <button class="alert-btn cancel" onclick="hideCustomAlert()">Tidak</button>
                    <button class="alert-btn confirm" onclick="confirmCancel()">Ya, Batalkan</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Nav -->
    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
    @include('buyer.profile.partials.edit-script')
@endpush
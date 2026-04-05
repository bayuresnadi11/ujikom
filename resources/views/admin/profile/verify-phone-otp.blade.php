@extends('layouts.admin')

@push('styles')
    @include('admin.profile.partials.style')
@endpush
@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Verifikasi OTP
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('sukses'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('sukses') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('gagal'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ session('gagal') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-phone text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h4>Verifikasi Perubahan Nomor Telepon</h4>
                            <p class="text-muted">
                                Masukkan 6 digit kode OTP yang telah dikirim ke WhatsApp Anda
                            </p>
                        </div>

                        <!-- PERBAIKAN: Menggunakan route admin.profile.phone.verify-otp -->
                        <form action="{{ route('admin.profile.phone.verify-otp') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <div class="otp-input-wrapper">
                                    <input
                                        type="text"
                                        name="otp"
                                        maxlength="6"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        placeholder="------"
                                        required
                                        autofocus
                                        class="form-control otp-input text-center"
                                        id="otpInput"
                                        style="font-size: 1.5rem; letter-spacing: 10px;"
                                    >
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">Masukkan 6 digit kode OTP</small>
                                </div>
                            </div>

                            <div class="countdown-wrapper mb-4">
                                <div class="text-center">
                                    <div class="mb-2">
                                        <span id="countdown">01:00</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" id="progressBar" role="progressbar" 
                                             style="width: 100%; transition: width 1s linear;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Verifikasi
                                </button>

                                <!-- PERBAIKAN: Menggunakan route admin.profile.phone.resend-otp -->
                                <form action="{{ route('admin.profile.phone.resend-otp') }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary" id="resendBtn" disabled>
                                        <i class="bi bi-arrow-repeat me-2"></i>
                                        Kirim Ulang OTP (<span id="resendTimer">60</span>)
                                    </button>
                                </form>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <!-- PERBAIKAN: Menggunakan route admin.profile.phone -->
                            <a href="{{ route('admin.profile.phone') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke form perubahan nomor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @include('admin.profile.partials.script')
@endpush
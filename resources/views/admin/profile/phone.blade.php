@extends('layouts.admin')

@push('styles')
    @include('admin.profile.partials.style')
@endpush
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title mb-4">
                    <i class="bi bi-telephone me-2"></i>Ubah Nomor Telepon
                </h1>
            </div>
        </div>

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

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-telephone-plus me-2"></i>Form Perubahan Nomor Telepon
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong><br>
                            1. Masukkan nomor telepon baru yang valid<br>
                            2. System akan mengirimkan kode OTP ke nomor baru Anda via WhatsApp<br>
                            3. Verifikasi OTP untuk menyelesaikan proses perubahan
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                <span class="fw-bold">Nomor Telepon Saat Ini:</span>
                            </div>
                            <div class="ms-4">
                                <span class="badge bg-primary fs-6">{{ $user->phone }}</span>
                            </div>
                        </div>

                        <!-- PERBAIKAN: Menggunakan route admin.profile.phone.send-otp -->
                        <form action="{{ route('admin.profile.phone.send-otp') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor Telepon Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" 
                                           name="new_phone" 
                                           class="form-control" 
                                           placeholder="81234567890" 
                                           required
                                           minlength="10"
                                           pattern="[0-9]*"
                                           inputmode="numeric"
                                           value="{{ old('new_phone') }}">
                                </div>
                                <div class="form-text">Masukkan nomor tanpa kode negara (+62)</div>
                                @error('new_phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <!-- PERBAIKAN: Menggunakan route admin.profile.index -->
                                <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i>Kirim OTP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-shield-check me-2"></i>Keamanan Perubahan Nomor Telepon
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Verifikasi OTP via WhatsApp
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Cek ketersediaan nomor sebelum perubahan
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                OTP berlaku 1 menit
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Dapat mengirim ulang OTP jika diperlukan
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
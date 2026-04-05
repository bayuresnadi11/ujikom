@extends('layouts.main', ['title' => 'Edit Cashier'])

@push('styles')
    @include('landowner.cashier.partials.cashier-style')
@endpush

@section('content')
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('landowner.cashier.index') }}" class="logo">
                    <i class="fas fa-arrow-left logo-icon"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Edit Cashier</h1>
                <p class="page-subtitle">Perbarui informasi akun cashier</p>
            </section>

            <div class="content-card">
                <form action="{{ route('landowner.cashier.update', $cashier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $cashier->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Nomor Telepon
                        </label>
                        <input type="text" name="phone" id="phone_input" class="form-control" value="{{ old('phone', $cashier->phone) }}" required>
                        <div class="form-text">Format: 628xxx (Gunakan kode negara 62)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Password Baru (Opsional)
                        </label>
                        {{-- Logika Opsional: Jika input dibiarkan kosong, maka password lama akan tetap digunakan pada sistem --}}
                        <input type="password" name="password" class="form-control" placeholder="Isi jika ingin mengganti password">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Bottom Nav -->
                      @include('layouts.bottom-nav')

    </div>
@endsection

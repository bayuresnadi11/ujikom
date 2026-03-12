@extends('layouts.main', ['title' => 'Tambah Cashier'])

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
                <h1 class="page-title">Tambah Cashier</h1>
                <p class="page-subtitle">Tambahkan akun cashier baru untuk venue Anda</p>
            </section>

            <div class="content-card">
                <form action="{{ route('landowner.cashier.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nama Cashier">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Nomor Telepon
                        </label>
                        <input type="text" name="phone" id="phone_input" class="form-control" value="{{ old('phone') }}" required placeholder="628xxxx">
                        <div class="form-text">Format: 628xxx (Gunakan kode negara 62)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" name="password" class="form-control" required placeholder="Buat Password">
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Cashier
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    @include('layouts.bottom-nav')
@endsection

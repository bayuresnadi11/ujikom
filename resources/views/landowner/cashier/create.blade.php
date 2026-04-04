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
                <form action="{{ route('landowner.cashier.store') }}" method="POST" id="cashierForm" novalidate>
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama Cashier">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Nomor Telepon
                        </label>
                        <input type="text" name="phone" id="phone_input" class="form-control" value="{{ old('phone') }}" placeholder="628xxxx">
                        <div class="form-text">Format: 628xxx (Gunakan kode negara 62)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Buat Password">
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

    <script>
        document.getElementById('cashierForm').addEventListener('submit', function(e) {
            // Clear previous errors
            document.querySelectorAll('.js-error').forEach(el => el.remove());
            document.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
            
            let isValid = true;
            
            function addError(element, message) {
                element.style.borderColor = '#E74C3C';
                let formGroup = element.closest('.form-group');
                if (formGroup) {
                    let errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message js-error mt-1';
                    errorDiv.style.color = '#E74C3C';
                    errorDiv.style.fontSize = '12px';
                    errorDiv.style.display = 'flex';
                    errorDiv.style.alignItems = 'center';
                    errorDiv.style.gap = '4px';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }

            const nameInput = document.querySelector('input[name="name"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const passwordInput = document.querySelector('input[name="password"]');

            if (!nameInput.value.trim()) {
                addError(nameInput, 'Nama cashier belum diisi');
            }
            if (!phoneInput.value.trim()) {
                addError(phoneInput, 'Nomor telepon belum diisi');
            }
            if (!passwordInput.value.trim()) {
                addError(passwordInput, 'Password belum diisi');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection

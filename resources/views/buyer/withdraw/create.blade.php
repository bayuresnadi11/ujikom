@extends('layouts.main', ['title' => 'Tarik Dana - SewaLap'])

@push('styles')
    @include('buyer.home.partials.home-style')
    <style>
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin: 12px 16px;
            box-shadow: var(--shadow-sm);
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 13px;
        }

        .form-label .required {
            color: #dc3545;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            font-family: inherit;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(10, 92, 54, 0.1);
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230A5C36' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .balance-info {
            background: var(--gradient-primary);
            color: white;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .balance-info-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .balance-info-amount {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .min-amount {
            font-size: 11px;
            opacity: 0.85;
            margin-top: 8px;
        }

        .form-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin: 12px 16px;
            font-size: 13px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid var(--primary);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 12px;
            color: #084298;
            line-height: 1.5;
        }

        .info-box i {
            margin-right: 8px;
            color: var(--primary);
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 16px;
            margin-bottom: 80px;
        }

        .btn {
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: var(--text);
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .header-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            padding: 0 16px;
            margin: 16px 0 4px 0;
        }

        .header-subtitle {
            color: var(--text-light);
            font-size: 13px;
            padding: 0 16px;
            margin-bottom: 16px;
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container">
        @include('layouts.header')
        
        <main class="main-content">
            <h1 class="header-title">Tarik Dana</h1>
            <p class="header-subtitle">Tarik saldo Anda ke rekening bank</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('buyer.withdraw.store') }}" method="POST" id="withdrawForm">
                @csrf

                {{-- Saldo --}}
                <div class="form-card">
                    <div class="balance-info">
                        <div class="balance-info-label">Saldo Tersedia</div>
                        <div class="balance-info-amount">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                        <div class="min-amount">Minimum penarikan: Rp 50.000</div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        Penarikan akan diproses dalam 1-2 hari kerja setelah pengajuan.
                    </div>
                </div>

                {{-- Jumlah Penarikan --}}
                <div class="form-card">
                    <div class="section-title">Jumlah Penarikan</div>

                    <div class="form-group">
                        <label class="form-label">
                            Jumlah <span class="required">*</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--text-light); font-weight: 600;">Rp</span>
                            <input type="number" 
                                   name="amount" 
                                   class="form-input @error('amount') border-danger @enderror" 
                                   placeholder="Masukkan jumlah" 
                                   min="50000" 
                                   max="{{ $balance }}"
                                   value="{{ old('amount') }}"
                                   id="amountInput"
                                   style="padding-left: 30px;"
                                   required>
                        </div>
                        @error('amount')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Data Rekening --}}
                <div class="form-card">
                    <div class="section-title">Data Rekening Bank</div>

                    <div class="form-group">
                        <label class="form-label">
                            Nama Bank <span class="required">*</span>
                        </label>
                        <select name="bank_name" class="form-select @error('bank_name') border-danger @enderror" required>
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA" @selected(old('bank_name') == 'BCA')>BCA</option>
                            <option value="BNI" @selected(old('bank_name') == 'BNI')>BNI</option>
                            <option value="BRI" @selected(old('bank_name') == 'BRI')>BRI</option>
                            <option value="MANDIRI" @selected(old('bank_name') == 'MANDIRI')>Mandiri</option>
                            <option value="CIMB" @selected(old('bank_name') == 'CIMB')>CIMB Niaga</option>
                            <option value="OVO" @selected(old('bank_name') == 'OVO')>OVO</option>
                            <option value="DANA" @selected(old('bank_name') == 'DANA')>DANA</option>
                            <option value="GOPAY" @selected(old('bank_name') == 'GOPAY')>GoPay</option>
                        </select>
                        @error('bank_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Nomor Rekening <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="account_number" 
                               class="form-input @error('account_number') border-danger @enderror" 
                               placeholder="Contoh: 1234567890" 
                               value="{{ old('account_number') }}"
                               required>
                        @error('account_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Nama Pemilik Rekening <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="account_holder_name" 
                               class="form-input @error('account_holder_name') border-danger @enderror" 
                               placeholder="Nama sesuai rekening" 
                               value="{{ old('account_holder_name') }}"
                               required>
                        @error('account_holder_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info-box">
                        <i class="fas fa-shield-alt"></i>
                        Data rekening dijaga dengan aman dan hanya digunakan untuk penarikan.
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="button-group">
                    <a href="{{ route('buyer.withdraw.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-check"></i> Ajukan Penarikan
                    </button>
                </div>
            </form>
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    <script>
        const amountInput = document.getElementById('amountInput');
        const submitBtn = document.getElementById('submitBtn');

        amountInput.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            const maxBalance = {{ $balance }};
            
            if (amount < 50000 || amount > maxBalance) {
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        });

        document.getElementById('withdrawForm').addEventListener('submit', function(e) {
            const amount = parseFloat(amountInput.value) || 0;
            const maxBalance = {{ $balance }};

            if (amount < 50000) {
                e.preventDefault();
                alert('Jumlah minimum penarikan adalah Rp 50.000');
                return false;
            }

            if (amount > maxBalance) {
                e.preventDefault();
                alert('Jumlah penarikan tidak boleh melebihi saldo Anda');
                return false;
            }
        });
    </script>
@endpush

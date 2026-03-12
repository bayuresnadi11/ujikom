@extends('layouts.main', ['title' => 'Top Up Saldo - SewaLap'])

@push('styles')
    <style>
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
        }

        .amount-preset {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .preset-btn {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s;
        }

        .preset-btn:hover,
        .preset-btn.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
        }

        .payment-method {
            display: grid;
            gap: 12px;
            margin-bottom: 16px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .payment-option:hover {
            border-color: var(--primary);
            background: rgba(63, 81, 181, 0.05);
        }

        .payment-option input[type="radio"] {
            margin-right: 12px;
        }

        .payment-option.selected {
            border-color: var(--primary);
            background: rgba(63, 81, 181, 0.1);
        }

        .amount-info {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #666;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .error-message {
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
        }

        .info-box {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #2e7d32;
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container">
        @include('layouts.header')

        <main class="main-content">
            <!-- Page Header -->
            <div class="menu-header">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <button onclick="window.history.back()"
                        style="background:none; border:none; display:flex; align-items:center; gap:6px; font-size:14px; font-weight:600; color:var(--primary); cursor:pointer;">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
                <h1 class="menu-title">Top Up Saldo</h1>
                <p class="menu-subtitle">Tambahkan saldo untuk transaksi di SewaLap</p>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <form action="{{ route('buyer.deposit.store') }}" method="POST" id="depositForm">
                    @csrf

                    <!-- Amount Section -->
                    <div class="form-group">
                        <label class="form-label">Jumlah Top Up</label>

                        <div class="amount-preset">
                            <button type="button" class="preset-btn" data-amount="50000">
                                Rp 50.000
                            </button>
                            <button type="button" class="preset-btn" data-amount="100000">
                                Rp 100.000
                            </button>
                            <button type="button" class="preset-btn" data-amount="250000">
                                Rp 250.000
                            </button>
                            <button type="button" class="preset-btn" data-amount="500000">
                                Rp 500.000
                            </button>
                        </div>

                        <input type="hidden" name="amount" id="amountInput" value="">
                        <input type="text" class="form-input" id="amountDisplay" placeholder="Atau masukkan jumlah custom"
                            readonly style="background: #f5f5f5;">

                        <input type="text" class="form-input" id="amountCustom" placeholder="Masukkan jumlah (minimal Rp 10.000)"
                            style="display: none; margin-top: 8px;">

                        @error('amount')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Amount Info -->
                    <div class="amount-info" id="amountInfo">
                        Pilih atau masukkan jumlah top up yang ingin Anda lakukan
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran</label>

                        <div class="payment-method">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer" required>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">Transfer Bank</div>
                                    <div style="font-size: 12px; color: #999; margin-top: 4px;">BCA, Mandiri, BNI, dll</div>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="e_wallet" required>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">E-Wallet</div>
                                    <div style="font-size: 12px; color: #999; margin-top: 4px;">GCash, Dana, OVO, DANA</div>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="card" required>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">Kartu Kredit/Debit</div>
                                    <div style="font-size: 12px; color: #999; margin-top: 4px;">Visa, Mastercard, Amex</div>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        Saldo yang sudah ditambahkan dapat digunakan untuk berbagai keperluan di SewaLap.
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-check"></i>
                        Lanjutkan ke Pembayaran
                    </button>
                </form>
            </div>
        </main>

        @include('layouts.bottom-nav')
    </div>

    <script>
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const amount = parseInt(this.dataset.amount);

                document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                document.getElementById('amountInput').value = amount;
                document.getElementById('amountDisplay').value = 'Rp ' + amount.toLocaleString('id-ID');
                document.getElementById('amountCustom').style.display = 'none';
                updateAmountInfo(amount);
            });
        });

        document.getElementById('amountDisplay').addEventListener('click', function() {
            document.getElementById('amountCustom').style.display = 'block';
            document.getElementById('amountCustom').focus();
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
        });

        document.getElementById('amountCustom').addEventListener('input', function() {
            const value = this.value.replace(/\D/g, '');
            if (value) {
                const amount = parseInt(value);
                document.getElementById('amountInput').value = amount;
                document.getElementById('amountDisplay').value = 'Rp ' + amount.toLocaleString('id-ID');
                updateAmountInfo(amount);
            }
        });

        document.querySelectorAll('.payment-option input').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                this.closest('.payment-option').classList.add('selected');
            });
        });

        document.getElementById('depositForm').addEventListener('submit', function(e) {
            const amount = document.getElementById('amountInput').value;
            if (!amount || parseInt(amount) < 10000) {
                e.preventDefault();
                alert('Jumlah top up minimal Rp 10.000');
                return false;
            }

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                e.preventDefault();
                alert('Pilih metode pembayaran');
                return false;
            }
        });

        function updateAmountInfo(amount) {
            const info = document.getElementById('amountInfo');
            if (amount < 10000) {
                info.textContent = 'Jumlah minimal adalah Rp 10.000';
                info.style.background = '#ffebee';
                info.style.color = '#c62828';
            } else if (amount > 50000000) {
                info.textContent = 'Jumlah maksimal adalah Rp 50.000.000';
                info.style.background = '#ffebee';
                info.style.color = '#c62828';
            } else {
                info.textContent = 'Saldo akan langsung ditambahkan setelah pembayaran berhasil';
                info.style.background = '#e8f5e9';
                info.style.color = '#2e7d32';
            }
        }
    </script>
@endsection
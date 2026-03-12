@extends('layouts.main', ['title' => 'Konfirmasi Pembayaran - SewaLap'])

@push('styles')
    <style>
        .payment-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 16px;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.completed {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .amount-display {
            text-align: center;
            padding: 24px;
            margin-bottom: 16px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 12px;
        }

        .amount-display .amount {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .amount-display .label {
            font-size: 14px;
            opacity: 0.9;
        }

        .payment-detail {
            background: #f5f5f5;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            color: #666;
        }

        .detail-value {
            font-weight: 600;
            color: #333;
        }

        .instruction-box {
            background: #e3f2fd;
            border-left: 4px solid var(--primary);
            padding: 16px;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .instruction-title {
            font-weight: 600;
            color: #1976d2;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .instruction-content {
            font-size: 13px;
            color: #1565c0;
            line-height: 1.6;
        }

        .payment-code {
            background: white;
            border: 2px dashed var(--primary);
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin: 12px 0;
            font-family: monospace;
            font-weight: 600;
            font-size: 14px;
            color: var(--primary);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: rgba(63, 81, 181, 0.1);
        }

        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            text-align: center;
        }

        .success-icon {
            font-size: 32px;
            margin-bottom: 8px;
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
                <h1 class="menu-title">Detail Pembayaran</h1>
                <p class="menu-subtitle">Konfirmasi top up saldo Anda</p>
            </div>

            <!-- Status Badge -->
            <div class="payment-container">
                <span
                    class="status-badge {{ $deposit->status }}">
                    @if ($deposit->status === 'pending')
                        <i class="fas fa-hourglass-half"></i> Menunggu Pembayaran
                    @elseif ($deposit->status === 'completed')
                        <i class="fas fa-check-circle"></i> Pembayaran Berhasil
                    @else
                        <i class="fas fa-times-circle"></i> Dibatalkan
                    @endif
                </span>
            </div>

            <!-- Amount Display -->
            <div class="amount-display">
                <div class="amount">
                    Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                </div>
                <div class="label">Jumlah Top Up</div>
            </div>

            <!-- Payment Details -->
            <div class="payment-container">
                <h3 style="margin-bottom: 16px; font-size: 14px; font-weight: 700;">Detail Transaksi</h3>

                <div class="payment-detail">
                    <div class="detail-row">
                        <span class="detail-label">ID Transaksi</span>
                        <span class="detail-value">#{{ $deposit->id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value" style="text-transform: capitalize;">{{ $deposit->status }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Metode Pembayaran</span>
                        <span class="detail-value">
                            @if (str_contains($deposit->description, 'Transfer Bank'))
                                Transfer Bank
                            @elseif (str_contains($deposit->description, 'E-Wallet'))
                                E-Wallet
                            @else
                                Kartu Kredit/Debit
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal</span>
                        <span class="detail-value">{{ $deposit->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            @if ($deposit->status === 'pending')
                <div class="payment-container">
                    <div class="instruction-box">
                        <div class="instruction-title">
                            <i class="fas fa-info-circle"></i> Instruksi Pembayaran
                        </div>
                        <div class="instruction-content">
                            @if (str_contains($deposit->description, 'Transfer Bank'))
                                <p style="margin-bottom: 8px;">
                                    Silakan transfer ke rekening berikut:
                                </p>
                                <p>
                                    <strong>Bank BCA</strong><br>
                                    No. Rekening: 1234567890<br>
                                    Atas Nama: PT. SewaLap Indonesia
                                </p>
                                <p style="margin-top: 8px; font-size: 12px;">
                                    Gunakan kode unik pembayaran:
                                </p>
                                <div class="payment-code">{{ sprintf('%06d', $deposit->id) }}</div>
                                <p style="font-size: 12px; margin-top: 8px;">
                                    Tambahkan kode ini pada keterangan/berita transfer
                                </p>
                            @elseif (str_contains($deposit->description, 'E-Wallet'))
                                <p>
                                    Buka aplikasi e-wallet Anda dan lakukan pembayaran ke nomor:
                                </p>
                                <div class="payment-code">+62812345678</div>
                                <p style="font-size: 12px; margin-top: 8px;">
                                    Masukkan kode unik: <strong>{{ sprintf('%06d', $deposit->id) }}</strong>
                                </p>
                            @else
                                <p>
                                    Silakan gunakan kartu kredit/debit Anda untuk melanjutkan pembayaran.
                                </p>
                                <p style="font-size: 12px; margin-top: 8px;">
                                    Referensi: <strong>{{ sprintf('%06d', $deposit->id) }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="payment-container">
                    <form action="{{ route('landowner.deposit.confirm', $deposit->id) }}" method="POST" style="margin-bottom: 12px;">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i>
                            Saya Sudah Membayar
                        </button>
                    </form>

                    <form action="{{ route('landowner.deposit.cancel', $deposit->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-secondary"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')">
                            <i class="fas fa-times"></i>
                            Batalkan
                        </button>
                    </form>
                </div>
            @elseif ($deposit->status === 'completed')
                <div class="payment-container">
                    <div class="success-box">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 4px;">Pembayaran Berhasil!</div>
                        <div style="font-size: 13px;">
                            Saldo Anda telah ditambahkan sebesar Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <a href="{{ route('landowner.deposit.index') }}" class="btn btn-primary" style="display: block;">
                        <i class="fas fa-arrow-right"></i>
                        Kembali ke Saldo
                    </a>
                </div>
            @else
                <div class="payment-container">
                    <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 16px; border-radius: 8px; text-align: center;">
                        <div style="font-weight: 600; margin-bottom: 4px;">Transaksi Dibatalkan</div>
                        <div style="font-size: 13px;">
                            Deposit ini telah dibatalkan dan tidak dapat diproses lagi.
                        </div>
                    </div>

                    <a href="{{ route('landowner.deposit.index') }}" class="btn btn-primary" style="display: block; margin-top: 16px;">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Saldo
                    </a>
                </div>
            @endif
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

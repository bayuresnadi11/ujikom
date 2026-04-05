@extends('layouts.main', ['title' => 'Detail Penarikan - SewaLap'])

@push('styles')
    @include('buyer.home.partials.home-style')
    <style>
        .success-card {
            background: var(--gradient-primary);
            color: white;
            border-radius: 16px;
            padding: 24px;
            margin: 16px;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        .success-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .success-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .success-subtitle {
            font-size: 13px;
            opacity: 0.9;
        }

        .info-card {
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

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-light);
            font-weight: 500;
        }

        .detail-value {
            color: var(--text);
            font-weight: 600;
            text-align: right;
        }

        .detail-value.amount {
            font-size: 16px;
            color: var(--primary);
            font-weight: 800;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #cfe2ff;
            color: #084298;
        }

        .status-processed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-rejected {
            background: #f8d7da;
            color: #842029;
        }

        .timeline {
            position: relative;
            padding: 16px 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            width: 2px;
            height: 20px;
            background: #e9ecef;
        }

        .timeline-marker {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 12px;
            font-size: 16px;
            position: relative;
            z-index: 2;
        }

        .timeline-marker.pending {
            background: #ffc107;
            color: white;
        }

        .timeline-marker.rejected {
            background: #dc3545;
        }

        .timeline-content {
            flex: 1;
            padding-top: 8px;
        }

        .timeline-title {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
            font-size: 13px;
        }

        .timeline-date {
            font-size: 12px;
            color: var(--text-light);
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

        .btn-primary:hover {
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

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container">
        @include('layouts.header')
        
        <main class="main-content">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="success-card">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="success-title">Permintaan Diterima</div>
                    <div class="success-subtitle">Penarikan dana Anda sedang diproses</div>
                </div>
            @endif

            {{-- Detail Penarikan --}}
            <div class="info-card">
                <div class="section-title">Informasi Penarikan</div>

                <div class="detail-row">
                    <span class="detail-label">ID Penarikan</span>
                    <span class="detail-value">#{{ str_pad($withdrawal->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Jumlah</span>
                    <span class="detail-value amount">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $withdrawal->status }}">
                            {{ $withdrawal->getStatusLabel() }}
                        </span>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Tanggal Permintaan</span>
                    <span class="detail-value">{{ $withdrawal->created_at->format('d M Y') }}</span>
                </div>

                @if ($withdrawal->processed_at)
                    <div class="detail-row">
                        <span class="detail-label">Tanggal Diproses</span>
                        <span class="detail-value">{{ $withdrawal->processed_at->format('d M Y') }}</span>
                    </div>
                @endif
            </div>

            {{-- Detail Bank --}}
            <div class="info-card">
                <div class="section-title">Detail Rekening Bank</div>

                <div class="detail-row">
                    <span class="detail-label">Nama Bank</span>
                    <span class="detail-value">{{ $withdrawal->bank_name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Nomor Rekening</span>
                    <span class="detail-value" style="font-family: monospace;">{{ $withdrawal->account_number }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Nama Pemilik</span>
                    <span class="detail-value">{{ $withdrawal->account_holder_name }}</span>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="info-card">
                <div class="section-title">Alur Proses</div>

                <div class="timeline">
                    {{-- Step 1: Submitted --}}
                    <div class="timeline-item">
                        <div class="timeline-marker">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Permintaan Diterima</div>
                            <div class="timeline-date">{{ $withdrawal->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>

                    {{-- Step 2: Processing --}}
                    @if ($withdrawal->status === 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Sedang Diproses</div>
                                <div class="timeline-date">Estimasi 1-2 hari kerja</div>
                            </div>
                        </div>
                    @else
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Diverifikasi</div>
                                <div class="timeline-date">{{ $withdrawal->processed_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    @endif

                    {{-- Step 3: Completed/Rejected --}}
                    @if ($withdrawal->status === 'processed')
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Berhasil Ditransfer</div>
                                <div class="timeline-date">Dana masuk ke rekening Anda</div>
                            </div>
                        </div>
                    @elseif ($withdrawal->status === 'rejected')
                        <div class="timeline-item">
                            <div class="timeline-marker rejected">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Ditolak</div>
                                <div class="timeline-date">{{ $withdrawal->processed_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Box --}}
            @if ($withdrawal->status === 'pending')
                <div style="padding: 0 16px;">
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Penarikan sedang diproses.</strong> Kami akan memverifikasi data rekening dan mentransfer dana dalam 1-2 hari kerja.
                    </div>
                </div>
            @elseif ($withdrawal->status === 'processed')
                <div style="padding: 0 16px;">
                    <div class="info-box">
                        <i class="fas fa-check-circle"></i>
                        <strong>Penarikan berhasil!</strong> Dana telah ditransfer ke rekening Anda.
                    </div>
                </div>
            @elseif ($withdrawal->status === 'rejected')
                <div style="padding: 0 16px;">
                    <div class="info-box" style="background: #ffe5e5; color: #842029; border-left-color: #dc3545;">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Penarikan ditolak.</strong> {{ $withdrawal->rejection_reason ?? 'Hubungi customer support untuk informasi.' }}
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="button-group">
                <a href="{{ route('buyer.withdraw.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                </a>

                @if ($withdrawal->status === 'pending')
                    <form action="{{ route('buyer.withdraw.cancel', $withdrawal->id) }}" method="POST" style="width: 100%;" onsubmit="return confirm('Yakin ingin membatalkan? Saldo akan dikembalikan.');">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">
                            <i class="fas fa-times"></i> Batalkan Penarikan
                        </button>
                    </form>
                @endif
            </div>
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

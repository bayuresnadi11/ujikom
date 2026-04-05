@extends('layouts.main', ['title' => 'Penarikan Dana - SewaLap'])

@push('styles')
    @include('buyer.home.partials.home-style')
    <style>
        :root {
            --withdraw-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --withdraw-gradient-hover: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            --card-shadow-hover: 0 20px 40px -10px rgba(0, 0, 0, 0.12);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-container {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            min-height: 100vh;
        }

        /* Balance Card Modern */
        .balance-card {
            background: var(--withdraw-gradient);
            color: white;
            border-radius: 24px;
            padding: 28px 24px;
            margin: 20px 16px;
            box-shadow: var(--card-shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .balance-card:hover::before {
            opacity: 1;
        }

        .balance-label {
            font-size: 13px;
            opacity: 0.95;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .balance-amount {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 24px;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .btn-withdraw {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            color: var(--primary);
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition-smooth);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-withdraw::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        .btn-withdraw:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-withdraw:hover::before {
            left: 100%;
        }

        /* Section Title Modern */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
            padding: 0 20px;
            margin: 32px 0 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 20px;
            bottom: -8px;
            width: 40px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }

        .transaction-count {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-light);
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 20px;
        }

        /* Modern Withdrawal Card */
        .withdrawal-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin: 0 16px 16px 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
        }

        .withdrawal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--withdraw-gradient);
            border-radius: 4px 0 0 4px;
        }

        .withdrawal-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-4px);
            border-color: #e2e8f0;
        }

        .withdrawal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            position: relative;
        }

        .bank-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .bank-icon i {
            font-size: 20px;
            color: #64748b;
        }

        .withdrawal-info {
            flex: 1;
        }

        .withdrawal-info h3 {
            margin: 0;
            font-size: 16px;
            color: var(--text);
            font-weight: 700;
            line-height: 1.4;
        }

        .withdrawal-bank {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bank-chip {
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
        }

        .withdrawal-amount {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            text-align: right;
        }

        .withdrawal-status {
            font-size: 11px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        .status-icon {
            font-size: 10px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .status-approved {
            background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .status-processed {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .withdrawal-details {
            font-size: 13px;
            color: #64748b;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-label {
            font-weight: 500;
            color: #94a3b8;
        }

        .detail-value {
            font-weight: 600;
            color: #475569;
        }

        /* Empty State Modern */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
            background: white;
            border-radius: 20px;
            margin: 20px 16px;
            box-shadow: var(--card-shadow);
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-state-icon i {
            font-size: 36px;
            color: #94a3b8;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
            margin: 16px 0 8px;
        }

        .empty-state p {
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.5;
            color: #64748b;
        }

        /* CTA Buttons Modern */
        .cta-buttons {
            display: flex;
            gap: 16px;
            padding: 0 16px;
            margin-top: 32px;
        }

        .btn-primary {
            flex: 1;
            background: var(--withdraw-gradient);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-primary:hover {
            background: var(--withdraw-gradient-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover::after {
            left: 100%;
        }

        /* Animation for new items */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .withdrawal-card {
            animation: slideIn 0.3s ease-out;
        }

        /* Responsive adjustments */
        @media (max-width: 340px) {
            .withdrawal-details {
                grid-template-columns: 1fr;
            }
            
            .balance-amount {
                font-size: 30px;
            }
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container">
        @include('layouts.header')
        
        <main class="main-content">
            <!-- Modern Balance Card -->
            <div class="balance-card">
                <div class="balance-label">Saldo Tersedia</div>
                <div class="balance-amount">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                <a href="{{ route('buyer.withdraw.create') }}" class="btn-withdraw">
                    <i class="fas fa-arrow-up-from-bracket"></i> Tarik Dana
                </a>
            </div>

            <!-- Withdrawal History -->
            <h2 class="section-title">
                <span>Riwayat Penarikan</span>
                <span class="transaction-count">{{ $withdrawals->count() }} transaksi</span>
            </h2>

            @if ($withdrawals->count() > 0)
                @foreach ($withdrawals as $withdrawal)
                    <div class="withdrawal-card">
                        <div class="withdrawal-header">
                            <div class="bank-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="withdrawal-info">
                                <h3>{{ $withdrawal->bank_name }}</h3>
                                <div class="withdrawal-bank">
                                    <span class="bank-chip">{{ $withdrawal->bank_code ?? 'TRSF' }}</span>
                                    {{ $withdrawal->account_number }} • {{ $withdrawal->account_holder_name }}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div class="withdrawal-amount">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </div>
                                <div class="withdrawal-status status-{{ $withdrawal->status }}">
                                    <i class="fas fa-circle status-icon"></i>
                                    {{ $withdrawal->getStatusLabel() }}
                                </div>
                            </div>
                        </div>

                        <div class="withdrawal-details">
                            <div class="detail-row">
                                <span class="detail-label">ID Transaksi</span>
                                <span class="detail-value">#{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Tanggal</span>
                                <span class="detail-value">{{ $withdrawal->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Metode</span>
                                <span class="detail-value">Transfer Bank</span>
                            </div>
                            @if ($withdrawal->status === 'pending')
                                <div class="detail-row">
                                    <span class="detail-label">Estimasi</span>
                                    <span class="detail-value">1-2 hari kerja</span>
                                </div>
                            @elseif($withdrawal->processed_at)
                                <div class="detail-row">
                                    <span class="detail-label">Diproses</span>
                                    <span class="detail-value">{{ $withdrawal->processed_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Belum ada riwayat penarikan</h3>
                    <p>Saldo Anda siap ditarik kapan saja</p>
                    <p style="font-size: 13px;">Tarik dana pertama Anda sekarang</p>
                    <div class="cta-buttons">
                        <a href="{{ route('buyer.withdraw.create') }}" class="btn-primary">
                            <i class="fas fa-plus-circle"></i> Buat Penarikan Pertama
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Bottom Spacing -->
            <div style="height: 40px;"></div>
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
<script>
    // Add smooth hover effects
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.withdrawal-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });
        
        // Animate balance card on load
        const balanceCard = document.querySelector('.balance-card');
        if (balanceCard) {
            balanceCard.style.opacity = '0';
            balanceCard.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                balanceCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                balanceCard.style.opacity = '1';
                balanceCard.style.transform = 'translateY(0)';
            }, 300);
        }
    });
</script>
@endpush
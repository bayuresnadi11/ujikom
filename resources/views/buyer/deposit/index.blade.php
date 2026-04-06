@extends('layouts.main', ['title' => 'Deposit - SewaLap'])

@push('styles')
    @include('buyer.menu.partials.menu-style')
    <style>
        /* Base Variables */
        :root {
            --primary-green: #22c55e;
            --green-light: #bbf7d0;
            --green-dark: #16a34a;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        /* Balance Hero Section */
        .balance-hero {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-200);
        }

        .balance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .balance-title {
            font-size: 14px;
            color: var(--gray-700);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .balance-title i {
            color: var(--primary-green);
        }

        .balance-figure {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
            line-height: 1;
        }

        .balance-positive .balance-figure {
            color: var(--green-dark);
        }

        .balance-negative .balance-figure {
            color: #ef4444;
        }

        .balance-subtitle {
            font-size: 13px;
            color: var(--gray-700);
            margin-bottom: 20px;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 16px 12px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .action-btn:active {
            background: var(--gray-100);
            transform: translateY(1px);
        }

        .action-btn i {
            font-size: 20px;
            color: var(--primary-green);
        }

        .action-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-900);
        }

        /* Withdrawable Card */
        .withdrawable-card {
            background: linear-gradient(135deg, var(--green-light) 0%, #d1fae5 100%);
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            border: 1px solid #86efac;
        }

        .withdrawable-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .withdrawable-header i {
            color: var(--green-dark);
            font-size: 16px;
        }

        .withdrawable-label {
            font-size: 13px;
            color: var(--green-dark);
            font-weight: 600;
        }

        .withdrawable-amount {
            font-size: 20px;
            font-weight: 700;
            color: var(--green-dark);
        }

        /* Transaction Section */
        .transaction-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-200);
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-green);
        }

        .section-subtitle {
            font-size: 13px;
            color: var(--gray-700);
            margin-bottom: 16px;
        }

        /* Filter Section */
        .filter-section {
            margin-bottom: 24px;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .filter-header i {
            color: var(--primary-green);
            font-size: 16px;
        }

        .filter-title {
            font-size: 14px;
            color: var(--gray-700);
            font-weight: 500;
        }

        .filter-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 10px 20px;
            background: var(--gray-50);
            border: 1.5px solid var(--gray-200);
            border-radius: 24px;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-chip:active {
            background: var(--gray-100);
        }

        .filter-chip.active {
            background: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }

        /* Transaction List */
        .transaction-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Deposit Transaction Item */
        .transaction-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
            background: var(--gray-50);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }

        .transaction-item:active {
            background: var(--gray-100);
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 10px;
            border: 1px solid var(--gray-200);
            flex-shrink: 0;
        }

        .transaction-icon i {
            font-size: 18px;
            color: var(--primary-green);
        }

        .transaction-content {
            flex: 1;
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .transaction-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .transaction-amount {
            font-size: 16px;
            font-weight: 700;
        }

        .transaction-amount.positive {
            color: var(--green-dark);
        }

        .transaction-amount.negative {
            color: #ef4444;
        }

        .transaction-description {
            font-size: 13px;
            color: var(--gray-700);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .transaction-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .transaction-tag {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        .tag-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .tag-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .tag-success {
            background: #d1fae5;
            color: #065f46;
        }

        .transaction-time {
            font-size: 12px;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .transaction-time i {
            font-size: 11px;
            color: #9ca3af;
        }

        /* Withdrawal Card */
        .withdrawal-card {
            display: flex;
            flex-direction: column;
            padding: 20px;
            background: var(--gray-50);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }

        .withdrawal-card:active {
            background: var(--gray-100);
        }

        .withdrawal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .withdrawal-id {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .withdrawal-date {
            font-size: 12px;
            color: var(--gray-700);
            background: white;
            padding: 4px 10px;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        .amount-section {
            margin-bottom: 20px;
        }

        .amount-label {
            font-size: 12px;
            color: var(--gray-700);
            margin-bottom: 4px;
        }

        .withdrawal-amount-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-green);
        }

        .bank-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 11px;
            color: var(--gray-700);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #e0f2fe;
            color: #0369a1;
        }

        .status-processed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .info-box {
            padding: 16px;
            border-radius: 8px;
            margin-top: 12px;
        }

        .info-box.rejected {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
        }

        .info-box.processed {
            background: #f0fdf4;
            border-left: 4px solid var(--primary-green);
        }

        .info-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .info-header i {
            font-size: 14px;
        }

        .info-title {
            font-size: 13px;
            font-weight: 600;
        }

        .info-box.rejected .info-title {
            color: #991b1b;
        }

        .info-box.processed .info-title {
            color: #065f46;
        }

        .info-content {
            font-size: 13px;
            color: var(--gray-700);
            line-height: 1.4;
        }

        /* Proof Button */
        .proof-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 1.5px solid var(--primary-green);
            border-radius: 12px;
            color: var(--primary-green);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 12px;
            transition: all 0.2s ease;
        }

        .proof-button:active {
            background: var(--green-light);
            transform: translateY(1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            border-radius: 50%;
            border: 1px solid var(--gray-200);
        }

        .empty-icon i {
            font-size: 28px;
            color: var(--gray-700);
        }

        .empty-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .empty-description {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.5;
        }

        /* Loading State */
        .loading-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 20px;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-200);
            border-top-color: var(--primary-green);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 14px;
            color: var(--gray-700);
        }

        /* Error Message */
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px;
            margin: 16px 0;
            display: none;
        }

        .error-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .error-icon {
            color: #dc2626;
            font-size: 18px;
            flex-shrink: 0;
        }

        .error-text {
            font-size: 14px;
            color: #dc2626;
            line-height: 1.4;
        }

        /* Refresh Button */
        .refresh-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .refresh-btn:active {
            background: var(--gray-50);
        }

        .refresh-btn i {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .refresh-btn:active i {
            transform: rotate(180deg);
        }

        /* Modal Styles */
        .proof-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .proof-modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            max-width: 95%;
            max-height: 95%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 20px;
            background: var(--gray-50);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--gray-200);
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-title i {
            color: var(--primary-green);
        }

        .modal-close {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-700);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-close:active {
            background: var(--gray-100);
        }

        .modal-body {
            padding: 0;
        }

        .modal-img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 70vh;
            object-fit: contain;
        }

        /* Mobile Optimizations */
        @media (max-width: 480px) {
            .balance-hero,
            .transaction-section {
                padding: 20px;
                margin-left: -16px;
                margin-right: -16px;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
            
            .balance-figure {
                font-size: 32px;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .bank-details {
                grid-template-columns: 1fr;
            }
        }

        /* Ensure touch targets are large enough */
        button, 
        .action-btn, 
        .transaction-item,
        .withdrawal-card {
            min-height: 44px;
        }

        /* Remove tap highlight */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container" id="mobileContainer">
        @include('layouts.header')

        <main class="main-content">
            <!-- Balance Section -->
            <div class="menu-categories">
                <!-- Balance Card -->
                <div class="balance-hero">
                    <div class="balance-header">
                        <div class="balance-title">
                            <i class="fas fa-wallet"></i>
                            Total Saldo
                        </div>
                        <button onclick="refreshBalance()" class="refresh-btn">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    </div>
                    
                    <div class="{{ $totalBalance < 0 ? 'balance-negative' : 'balance-positive' }}">
                        <div class="balance-figure">
                            {{ $totalBalance < 0 ? '-' : '' }}Rp {{ number_format(abs($totalBalance), 0, ',', '.') }}
                        </div>
                        <div class="balance-subtitle">
                            Saldo terkini dari semua transaksi
                        </div>
                    </div>
                </div>

                <!-- Transaction History Section -->
                <div class="transaction-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-receipt"></i>
                            Riwayat Transaksi
                        </h2>
                    </div>
                    
                    <p class="section-subtitle">
                        Catatan semua transaksi deposit
                    </p>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-header">
                            <i class="fas fa-filter"></i>
                            <div class="filter-title">Filter Status</div>
                        </div>
                        <div class="filter-chips" id="filterButtons">
                            <button class="filter-chip active" data-filter="all">Semua Transaksi</button>
                            <button class="filter-chip" data-filter="deposit">Deposit</button>
                        </div>
                    </div>

                    <div id="errorMessage" class="error-message">
                        <div class="error-content">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                            <div class="error-text"></div>
                        </div>
                    </div>

                    <!-- Modal untuk menampilkan bukti foto -->
                    <div class="proof-modal" id="proofModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title">
                                    <i class="fas fa-receipt"></i> Bukti Transfer
                                </div>
                                <button class="modal-close" id="modalClose">&times;</button>
                            </div>
                            <div class="modal-body">
                                <img src="" alt="Bukti Transfer" class="modal-img" id="modalImage">
                            </div>
                        </div>
                    </div>

                    <!-- Combined Transaction and Withdrawal List -->
                    <div id="combinedList" class="transaction-list">
                        @php
                            $allTransactions = [];
                            
                            // Add deposits
                            foreach ($deposits as $deposit) {
                                $allTransactions[] = [
                                    'type' => 'deposit',
                                    'data' => $deposit,
                                    'created_at' => $deposit->created_at
                                ];
                            }
                            
                            // Add withdrawals
                            foreach ($withdrawals as $withdrawal) {
                                $allTransactions[] = [
                                    'type' => 'withdrawal',
                                    'data' => $withdrawal,
                                    'created_at' => $withdrawal->created_at
                                ];
                            }
                            
                            // Sort by date (newest first)
                            usort($allTransactions, function($a, $b) {
                                return $b['created_at'] <=> $a['created_at'];
                            });
                        @endphp

                        @if(count($allTransactions) > 0)
                            @foreach($allTransactions as $transaction)
                                @if($transaction['type'] === 'deposit')
                                    @php $deposit = $transaction['data']; @endphp
                                    <div class="transaction-item" data-type="deposit">
                                        <div class="transaction-icon">
                                            <i class="{{ $deposit->getSourceIcon() }}"></i>
                                        </div>
                                        
                                        <div class="transaction-content">
                                            <div class="transaction-header">
                                                <div class="transaction-title">
                                                    {{ $deposit->getSourceName() }}
                                                </div>
                                                <div class="transaction-amount {{ $deposit->amount > 0 ? 'positive' : 'negative' }}">
                                                    {{ $deposit->amount > 0 ? '+' : '' }}Rp {{ number_format(abs($deposit->amount), 0, ',', '.') }}
                                                </div>
                                            </div>
                                            
                                            @if($deposit->description)
                                                <div class="transaction-description">
                                                    {{ $deposit->description }}
                                                </div>
                                            @endif
                                            
                                            <div class="transaction-meta">
                                                @if($deposit->payment_info)
                                                    <span class="transaction-tag tag-info">
                                                        {{ $deposit->payment_info }}
                                                    </span>
                                                @endif
                                                
                                                @if(!$deposit->affects_balance)
                                                    <span class="transaction-tag tag-warning">
                                                        Tidak mempengaruhi saldo
                                                    </span>
                                                @endif
                                                
                                                <span class="transaction-tag tag-success">
                                                    Deposit
                                                </span>
                                            </div>
                                            
                                            <div class="transaction-time">
                                                <i class="fas fa-clock"></i>
                                                {{ $deposit->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @elseif($transaction['type'] === 'withdrawal')
                                    @php $withdrawal = $transaction['data']; @endphp
                                    <div class="withdrawal-card" data-type="withdrawal">
                                        <div class="withdrawal-header">
                                            <div class="withdrawal-id">Withdraw #{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}</div>
                                            <div class="withdrawal-date">{{ $withdrawal->created_at->format('d M Y H:i') }}</div>
                                        </div>
                                        
                                        <div class="amount-section">
                                            <div class="amount-label">Jumlah Penarikan</div>
                                            <div class="withdrawal-amount-value">
                                                - Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        <div class="bank-details">
                                            <div class="detail-item">
                                                <div class="detail-label">Bank</div>
                                                <div class="detail-value">{{ $withdrawal->bank_name }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">No. Rekening</div>
                                                <div class="detail-value">{{ $withdrawal->account_number }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Nama Pemilik</div>
                                                <div class="detail-value">{{ $withdrawal->account_holder_name }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Status</div>
                                                <div class="detail-value">
                                                    <span class="status-badge {{ $withdrawal->status_class }}">
                                                        {{ $withdrawal->status_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($withdrawal->status == 'rejected' && $withdrawal->rejection_reason)
                                            <div class="info-box rejected">
                                                <div class="info-header">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    <div class="info-title">Alasan Penolakan</div>
                                                </div>
                                                <div class="info-content">{{ $withdrawal->rejection_reason }}</div>
                                            </div>
                                        @endif
                                        
                                        @if($withdrawal->status == 'processed' && $withdrawal->processed_at)
                                            <div class="info-box processed">
                                                <div class="info-header">
                                                    <i class="fas fa-check-circle"></i>
                                                    <div class="info-title">Berhasil Diproses</div>
                                                </div>
                                                <div class="info-content">
                                                    Dana telah ditransfer pada {{ $withdrawal->processed_at->format('d M Y H:i') }}
                                                    @if($withdrawal->processedBy)
                                                        oleh {{ $withdrawal->processedBy->name }}
                                                    @endif
                                                </div>
                                                
                                                <!-- Tombol Lihat Bukti -->
                                                @if($withdrawal->photo)
                                                    <a href="{{ asset('storage/' . $withdrawal->photo) }}" 
                                                       target="_blank" 
                                                       class="proof-button">
                                                        <i class="fas fa-eye"></i> Lihat Bukti Transfer
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3 class="empty-title">Belum Ada Transaksi</h3>
                                <p class="empty-description">
                                    Transaksi deposit dan penarikan akan muncul di sini
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-chip');
            const transactionItems = document.querySelectorAll('.transaction-item');
            const withdrawalCards = document.querySelectorAll('.withdrawal-card');
            const allItems = document.querySelectorAll('.transaction-item, .withdrawal-card');
            
            // Filter functionality
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    // Filter items
                    allItems.forEach(item => {
                        if (filter === 'all') {
                            item.style.display = 'flex';
                        } else if (filter === 'deposit') {
                            if (item.classList.contains('transaction-item')) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        } else if (filter === 'withdrawal') {
                            if (item.classList.contains('withdrawal-card')) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        }
                    });
                    
                    // Show empty state if no items visible
                    const visibleItems = document.querySelectorAll('.transaction-item[style="display: flex;"], .withdrawal-card[style="display: flex;"]');
                    const emptyState = document.querySelector('.empty-state');
                    
                    if (visibleItems.length === 0 && emptyState) {
                        // If there's an empty state but it's hidden, show it
                        if (emptyState.style.display === 'none') {
                            emptyState.style.display = 'block';
                        }
                    } else if (visibleItems.length === 0 && !emptyState) {
                        // Create empty state if no items visible
                        const combinedList = document.getElementById('combinedList');
                        const emptyStateHtml = `
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-filter"></i>
                                </div>
                                <h3 class="empty-title">Tidak Ada Data</h3>
                                <p class="empty-description">
                                    Tidak ada transaksi dengan filter ini
                                </p>
                            </div>
                        `;
                        combinedList.innerHTML = emptyStateHtml;
                    } else if (visibleItems.length > 0 && emptyState) {
                        // Remove empty state if there are visible items
                        emptyState.remove();
                    }
                });
            });
            
            // Modal functionality
            const proofModal = document.getElementById('proofModal');
            const modalImage = document.getElementById('modalImage');
            const modalClose = document.getElementById('modalClose');
            
            // Function to show modal with image
            window.showProofModal = function(event, imageUrl) {
                event.preventDefault();
                modalImage.src = imageUrl;
                proofModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            };
            
            // Close modal when clicking close button
            modalClose.addEventListener('click', function() {
                proofModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
            
            // Close modal when clicking outside the image
            proofModal.addEventListener('click', function(event) {
                if (event.target === proofModal) {
                    proofModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && proofModal.classList.contains('active')) {
                    proofModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });
        
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
        }
        
        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return dateString;
            }
        }
        
        function refreshBalance() {
            window.location.reload();
        }
        
        function showErrorMessage(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 10000);
        }
    </script>
@endpush
@extends('layouts.main', ['title' => 'Riwayat Withdraw - SewaLap'])

@push('styles')
    @include('buyer.menu.partials.menu-style')
    <style>
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

        /* Navigation Header */
.nav-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    margin-bottom: 16px;
    gap: 12px;
}

.nav-back {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    color: var(--primary-green);
    font-size: 18px;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.nav-back:active {
    background: var(--gray-100);
    transform: scale(0.95);
}


        .nav-back i {
            color: var(--primary-green);
        }

.page-title {
    flex: 1;
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-align: center;
    padding: 0 8px;
}

.page-title i {
    color: var(--primary-green);
    font-size: 20px;
}


        /* Main Container */
        .withdraw-container {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-200);
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

        /* Withdrawal List */
        .withdrawal-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
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

        /* Amount Section */
        .amount-section {
            margin-bottom: 20px;
        }

        .amount-label {
            font-size: 12px;
            color: var(--gray-700);
            margin-bottom: 4px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-green);
        }

        /* Bank Details */
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

        /* Status Badge */
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

        /* Info Boxes */
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

        /* New Withdraw Button */
.new-withdraw-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    background: var(--primary-green);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 18px;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.2);
}

.new-withdraw-btn:active {
    background: var(--green-dark);
    transform: scale(0.95);
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
    .nav-back {
        position: relative;
    }
    
    .nav-back::after {
        content: 'Kembali';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: var(--gray-700);
        margin-top: 4px;
        white-space: nowrap;
    }
    
    .new-withdraw-btn::after {
        content: 'Withdraw';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: var(--gray-700);
        margin-top: 4px;
        white-space: nowrap;
    }
    
    .page-title {
        font-size: 18px;
        gap: 8px;
    }
    
    .page-title i {
        font-size: 18px;
    }
}

        /* Ensure touch targets are large enough */
        button, 
        .nav-back, 
        .filter-chip,
        .proof-button,
        .new-withdraw-btn,
        .modal-close {
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
            <div class="menu-categories">
                <!-- Header dengan layout yang lebih baik -->
                <div class="nav-header">
                    <!-- Kiri: Back Button -->
                    <a href="{{ route('buyer.deposit.index') }}" class="nav-back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    
                    <!-- Tengah: Title -->
                    <div class="page-title">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Withdraw</span>
                    </div>
                    
                    <!-- Kanan: New Withdraw Button -->
                    <a href="{{ route('buyer.deposit.withdraw.saldo') }}" class="new-withdraw-btn">
                        <i class="fas fa-hand-holding-usd"></i>
                    </a>
                </div>
                
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-header">
                        <i class="fas fa-filter"></i>
                        <div class="filter-title">Filter Status</div>
                    </div>
                    <div class="filter-chips" id="filterButtons">
                        <button class="filter-chip active" data-status="all">Semua</button>
                        <button class="filter-chip" data-status="pending">Pending</button>
                        <button class="filter-chip" data-status="approved">Disetujui</button>
                        <button class="filter-chip" data-status="processed">Diproses</button>
                        <button class="filter-chip" data-status="rejected">Ditolak</button>
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
                
                <div class="withdraw-container">
                    <div id="withdrawalList" class="withdrawal-list">
                        @forelse($withdrawals as $withdrawal)
                            <div class="withdrawal-card" data-status="{{ $withdrawal->status }}">
                                <div class="withdrawal-header">
                                    <div class="withdrawal-id">Withdraw #{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <div class="withdrawal-date">{{ $withdrawal->created_at->format('d M Y H:i') }}</div>
                                </div>
                                
                                <div class="amount-section">
                                    <div class="amount-label">Jumlah Penarikan</div>
                                    <div class="amount-value">
                                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
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
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3 class="empty-title">Belum Ada Riwayat Withdraw</h3>
                                <p class="empty-description">
                                    Riwayat penarikan Anda akan muncul di sini
                                </p>
                            </div>
                        @endforelse
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
            const withdrawalCards = document.querySelectorAll('.withdrawal-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const status = this.getAttribute('data-status');
                    
                    // Filter cards
                    withdrawalCards.forEach(card => {
                        if (status === 'all' || card.getAttribute('data-status') === status) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    // Show empty state if no cards visible
                    const visibleCards = document.querySelectorAll('.withdrawal-card[style="display: flex;"]');
                    const emptyState = document.querySelector('.empty-state');
                    
                    if (visibleCards.length === 0) {
                        if (!emptyState) {
                            const withdrawalList = document.getElementById('withdrawalList');
                            const emptyStateHtml = `
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-filter"></i>
                                    </div>
                                    <h3 class="empty-title">Tidak Ada Data</h3>
                                    <p class="empty-description">
                                        Tidak ada riwayat withdraw dengan status ini
                                    </p>
                                </div>
                            `;
                            withdrawalList.innerHTML = emptyStateHtml;
                        }
                    } else if (emptyState) {
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
    </script>
@endpush
@extends('layouts.main', ['title' => 'Deposit - SewaLap'])

@push('styles')
    @include('landowner.menu.partials.menu-style')
    <style>
        /* ====================
           LOADING & ERROR
        ==================== */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 14px 16px;
            border-radius: 10px;
            margin: 16px 0;
            display: none;
            box-shadow: 0 4px 12px rgba(238, 90, 82, 0.2);
            border-left: 4px solid #fff;
        }

        /* ====================
           BALANCE SECTION
        ==================== */
        .balance-container {
            background: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
            border-radius: 16px;
            padding: 28px 24px;
            color: white;
            margin-bottom: 28px;
            box-shadow: 0 10px 30px rgba(139, 21, 56, 0.2);
            position: relative;
            overflow: hidden;
        }

        .balance-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .balance-amount {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .balance-negative {
            color: #ffd93d !important;
            text-shadow: 0 2px 8px rgba(255, 217, 61, 0.3);
        }

        .balance-positive {
            color: white !important;
        }

        .balance-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .balance-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            flex: 1;
            justify-content: center;
        }

        .balance-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.15);
            text-decoration: none;
            color: white;
        }

        .balance-btn:active {
            transform: translateY(0);
        }

        .balance-btn:disabled,
        .balance-btn[style*="opacity: 0.5"] {
            opacity: 0.5 !important;
            cursor: not-allowed;
            pointer-events: none !important;
        }

        .balance-info {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ====================
           NO VENUE MESSAGE
        ==================== */
        .no-venue-message {
            text-align: center;
            padding: 48px 24px;
            background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
            border-radius: 16px;
            margin: 24px 0;
            box-shadow: 0 8px 25px rgba(139, 21, 56, 0.05);
            border: 1px solid rgba(139, 21, 56, 0.1);
        }

        .no-venue-message i {
            font-size: 56px;
            color: var(--primary);
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .no-venue-message h3 {
            color: var(--text);
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 22px;
        }

        .no-venue-message p {
            color: var(--text-light);
            font-size: 15px;
            margin-bottom: 24px;
            line-height: 1.5;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .create-venue-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #C7254E 0%, #A01B42 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(139, 21, 56, 0.3);
        }

        .create-venue-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 21, 56, 0.4);
            text-decoration: none;
            color: white;
        }

        /* ====================
           TRANSACTION HISTORY
        ==================== */
        .category-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #2c3e50;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f3f5;
        }

        .category-title i {
            color: var(--primary);
            background: rgba(139, 21, 56, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ====================
           FILTER SECTION
        ==================== */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #f1f3f5;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: #5d6d7e;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label i {
            color: var(--primary);
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            background: white;
            color: #5d6d7e;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            border-color: var(--primary);
            background: rgba(139, 21, 56, 0.05);
            color: var(--primary);
        }

        .filter-btn.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
        }

        .filter-btn .badge {
            background: rgba(0, 0, 0, 0.1);
            color: inherit;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        .filter-btn.active .badge {
            background: rgba(255, 255, 255, 0.3);
        }

        /* ====================
           CARD STYLES
        ==================== */
        .card-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f5;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-body {
            padding: 20px;
        }

        .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .card-row:last-child {
            margin-bottom: 0;
        }

        .card-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #5d6d7e;
            font-size: 14px;
        }

        .card-value {
            font-weight: 700;
            font-size: 16px;
        }

        .card-value-positive {
            color: #8B1538 !important;
        }

        .card-value-negative {
            color: #E74C3C !important;
        }

        /* ====================
           STATUS BADGES
        ==================== */
        .transaction-status {
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .status-pending {
            background: linear-gradient(135deg, #FFEAB6 0%, #FFF5D6 100%);
            color: #F39C12;
        }

        .status-completed {
            background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
            color: #8B1538;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #FADBD8 0%, #F5B7B1 100%);
            color: #C0392B;
        }

        .type-deposit {
            background: rgba(139, 21, 56, 0.1);
            color: #8B1538;
        }

        .rejection-label {
            font-size: 11px;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            text-transform: uppercase;
        }

        .rejection-text {
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            color: #991b1b;
        }

        .processed-label {
            font-size: 11px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            text-transform: uppercase;
        }

        .processed-detail {
            font-size: 11px;
            color: #1e3a8a;
        }

        .processed-detail strong {
            font-weight: 600;
            font-size: 12px;
        }

        /* ====================
           BOOKING INFO
        ==================== */
        .booking-info {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f3f5;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
        }

        .booking-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding: 6px 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .booking-detail:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .booking-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #7b8a8b;
            font-size: 11px;
            font-weight: 600;
        }

        .booking-value {
            font-weight: 700;
            color: #2c3e50;
            font-size: 12px;
        }

        /* ====================
           NO DATA STATE
        ==================== */
        .no-data {
            text-align: center;
            padding: 60px 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 2px dashed #e9ecef;
        }

        .no-data i {
            font-size: 56px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .no-data h3 {
            color: #7f8c8d;
            margin-bottom: 12px;
            font-weight: 600;
            font-size: 18px;
        }

        .no-data p {
            color: #95a5a6;
            font-size: 14px;
            line-height: 1.5;
            max-width: 300px;
            margin: 0 auto;
        }

        /* ====================
           RESPONSIVE
        ==================== */
        @media (max-width: 480px) {
            .balance-amount {
                font-size: 28px;
            }
            
            .balance-actions {
                flex-direction: column;
            }
            
            .balance-btn {
                padding: 14px 20px;
            }
            
            .category-title {
                font-size: 18px;
            }
            
            .card-body {
                padding: 16px;
            }
            
            .filter-buttons {
                gap: 6px;
            }
            
            .filter-btn {
                padding: 6px 10px;
                font-size: 11px;
            }
        }

        /* ====================
           ANIMATIONS
        ==================== */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
    </style>
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        @include('layouts.header')

        <main style="margin-left: 20px;margin-right: 20px; margin-top:20px;" class="main-content">
            <div class="menu-categories">
                <!-- Page Header -->
                <div style="margin-bottom: 24px;">
                    <h1 style="font-size: 24px; font-weight: 800; color: #2c3e50; margin-bottom: 8px;">
                        <i class="fas fa-wallet" style="color: var(--primary); margin-right: 10px;"></i>
                        Deposit
                    </h1>
                    <p style="color: #7b8a8b; font-size: 14px;">
                        Kelola saldo dari booking lapangan Anda
                    </p>
                </div>

                <!-- Balance Section -->
                <div class="balance-container fade-in">
                    <div id="balanceAmount" class="balance-amount">
                        <span class="loading-spinner"></span> 
                        <span class="pulse">Memuat saldo...</span>
                    </div>
                    
                    <div class="balance-actions">
                        <button onclick="refreshBalance()" class="balance-btn">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                    
                    <div class="balance-info">
                        <i class="fas fa-info-circle" style="font-size: 14px;"></i>
                        <span>Saldo berasal dari pembayaran booking di lapangan Anda</span>
                    </div>
                </div>

                <!-- Empty State: No Venue -->
                <div id="noVenueMessage" style="display: none;" class="no-venue-message fade-in">
                    <i class="fas fa-store-alt pulse"></i>
                    <h3>Belum Memiliki Lapangan</h3>
                    <p>Anda perlu membuat lapangan terlebih dahulu untuk dapat menerima booking dan deposit dari pelanggan.</p>
                    <a href="{{ route('landowner.lapangan.create') }}" class="create-venue-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Buat Lapangan Pertama</span>
                    </a>
                </div>

                <!-- Transaction History (SATU CONTENT SAJA) -->
                <div class="fade-in" style="margin-top: 32px;">
                    <h2 class="category-title">
                        <i class="fas fa-history"></i>
                        Riwayat Transaksi
                    </h2>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-label">
                            <i class="fas fa-filter"></i>
                            <span>Filter Status</span>
                        </div>
                        <div class="filter-buttons">
                            <button class="filter-btn active" data-type="all">
                                <i class="fas fa-list"></i>
                                <span>Semua</span>
                                <span class="badge" id="badge-all">0</span>
                            </button>
                            <button class="filter-btn" data-type="deposit">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Deposit</span>
                                <span class="badge" id="badge-deposit">0</span>
                            </button>
                        </div>
                    </div>

                    <div id="errorMessage" class="error-message" style="display: none;"></div>

                    <div id="transactionContainer" class="card-container">
                        <div class="no-data">
                            <i class="fas fa-history pulse"></i>
                            <h3>Memuat riwayat transaksi...</h3>
                            <p>Harap tunggu sebentar</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    <script>
        // ====================
        // UTILITY FUNCTIONS
        // ====================
        function formatCurrency(amount) {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
            return formatter.format(Math.round(amount));
        }
        
        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays === 0) {
                    return 'Hari ini ' + date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } else if (diffDays === 1) {
                    return 'Kemarin ' + date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } else {
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            } catch (e) {
                return dateString;
            }
        }
        
        function getStatusInfo(status) {
            const statusMap = {
                'completed': { 
                    class: 'status-completed', 
                    text: 'Selesai',
                    color: '#8B1538'
                },
                'pending': { 
                    class: 'status-pending', 
                    text: 'Pending',
                    color: '#F39C12'
                },
                'cancelled': { 
                    class: 'status-cancelled', 
                    text: 'Batal',
                    color: '#C0392B'
                }
            };
            return statusMap[status] || statusMap.pending;
        }
        
        // ====================
        // UI FUNCTIONS
        // ====================
        function refreshBalance() {
            const refreshBtn = document.querySelector('.balance-btn[onclick="refreshBalance()"]');
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i><span>Memperbarui...</span>';
            loadTransactionHistory();
        }
        
        function showErrorMessage(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.innerHTML = `
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
                    <div>
                        <strong style="display: block; margin-bottom: 4px;">Terjadi Kesalahan</strong>
                        <span>${message}</span>
                    </div>
                </div>
            `;
            errorMessage.style.display = 'block';
            
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 8000);
        }
        
        function showLoading() {
            const container = document.getElementById('transactionContainer');
            const balanceAmount = document.getElementById('balanceAmount');
            const noVenueMessage = document.getElementById('noVenueMessage');
            
            balanceAmount.innerHTML = `
                <span class="loading-spinner"></span> 
                <span class="pulse">Memuat saldo...</span>
            `;
            
            container.innerHTML = `
                <div class="no-data">
                    <i class="fas fa-history pulse"></i>
                    <h3>Memuat riwayat transaksi...</h3>
                    <p>Harap tunggu sebentar</p>
                </div>
            `;
            
            noVenueMessage.style.display = 'none';
        }
        
        function showNoDataMessage() {
            const container = document.getElementById('transactionContainer');
            container.innerHTML = `
                <div class="no-data fade-in">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum Ada Transaksi</h3>
                    <p>Riwayat transaksi akan muncul di sini</p>
                </div>
            `;
        }
        
        // ====================
        // FILTER FUNCTIONS
        // ====================
        function filterTransactions(type) {
            const cards = document.querySelectorAll('.transaction-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const cardType = card.getAttribute('data-type');
                
                if (type === 'all' || cardType === type) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update active filter button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.filter-btn[data-type="${type}"]`).classList.add('active');
            
            // Show no data message if no cards visible
            const container = document.getElementById('transactionContainer');
            const noDataElement = container.querySelector('.no-data');
            
            if (visibleCount === 0 && cards.length > 0) {
                if (!noDataElement) {
                    const noData = document.createElement('div');
                    noData.className = 'no-data fade-in';
                    noData.innerHTML = `
                        <i class="fas fa-filter"></i>
                        <h3>Tidak Ada Data</h3>
                        <p>Tidak ada transaksi dengan tipe ${type === 'deposit' ? 'deposit' : 'penarikan'}.</p>
                    `;
                    container.appendChild(noData);
                }
            } else if (noDataElement && visibleCount > 0) {
                noDataElement.remove();
            }
        }
        
        // ====================
        // MAIN DATA LOADER
        // ====================
        function loadTransactionHistory() {
            const container = document.getElementById('transactionContainer');
            const errorMessage = document.getElementById('errorMessage');
            const balanceAmount = document.getElementById('balanceAmount');
            const noVenueMessage = document.getElementById('noVenueMessage');
            const refreshBtn = document.querySelector('.balance-btn[onclick="refreshBalance()"]');

            // Show loading state
            showLoading();
            errorMessage.style.display = 'none';

            fetch('{{ route('landowner.deposit.history') }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data);
                    
                    if (!data.success) {
                        throw new Error(data.message || 'Terjadi kesalahan pada server');
                    }
                    
                    // Update balance display
                    const balance = data.balance || 0;
                    const isNegative = balance < 0;
                    
                    balanceAmount.innerHTML = `
                        ${isNegative ? 
                            '<span class="balance-negative" style="display: flex; align-items: center; gap: 8px;">' + 
                            '<i class="fas fa-exclamation-circle"></i>' + 
                            formatCurrency(balance) + '</span>' : 
                            '<span class="balance-positive">' + formatCurrency(balance) + '</span>'
                        }
                    `;

                    let allTransactions = [];
                    let counts = {
                        all: 0,
                        deposit: 0
                    };

                    // Add deposits
                    if (data.deposits && data.deposits.length > 0) {
                        data.deposits.forEach(deposit => {
                            allTransactions.push({
                                ...deposit,
                                type: 'deposit',
                                date: new Date(deposit.created_at)
                            });
                            counts.deposit++;
                        });
                    }

                    counts.all = allTransactions.length;

                    // Sort by date (newest first)
                    allTransactions.sort((a, b) => b.date - a.date);

                    // Update transaction history
                    if (allTransactions.length > 0) {
                        let html = '';
                        allTransactions.forEach((transaction, index) => {
                            const delay = index * 50;
                            
                            if (transaction.type === 'deposit') {
                                // DEPOSIT CARD
                                const isPositive = transaction.is_positive;
                                const statusInfo = getStatusInfo(transaction.status);
                                
                                html += `
                                    <div class="card fade-in transaction-card" style="animation-delay: ${delay}ms;" data-type="deposit">
                                        <div class="card-body">
                                            <span class="transaction-type type-deposit">Deposit</span>
                                            <div class="card-row" style="margin-bottom: 12px;">
                                                <div class="card-label" style="display: flex; align-items: center; gap: 12px;">
                                                    <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, rgba(139, 21, 56, 0.1) 0%, rgba(160, 27, 66, 0.1) 100%); display: flex; align-items: center; justify-content: center;">
                                                        <i class="${transaction.source_type_icon}" style="color: var(--primary); font-size: 16px;"></i>
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 700; color: #2c3e50; font-size: 15px;">${transaction.source_type_name}</div>
                                                        <div style="font-size: 12px; color: #95a5a6; margin-top: 2px;">${transaction.description}</div>
                                                    </div>
                                                </div>
                                                <div class="card-value ${isPositive ? 'card-value-positive' : 'card-value-negative'}" style="font-size: 18px; font-weight: 800;">
                                                    ${isPositive ? '+' : ''}${formatCurrency(transaction.amount)}
                                                </div>
                                            </div>
                                            
                                            ${transaction.booking_info ? `
                                                <div class="booking-info">
                                                    <div class="booking-detail">
                                                        <span class="booking-label">
                                                            <i class="fas fa-ticket-alt"></i>
                                                            <span>Tiket Booking</span>
                                                        </span>
                                                        <span class="booking-value">${transaction.booking_info.ticket_code}</span>
                                                    </div>
                                                    <div class="booking-detail">
                                                        <span class="booking-label">
                                                            <i class="fas fa-store"></i>
                                                            <span>Lapangan</span>
                                                        </span>
                                                        <span class="booking-value">${transaction.booking_info.venue_name}</span>
                                                    </div>
                                                    <div class="booking-detail">
                                                        <span class="booking-label">
                                                            <i class="fas fa-user"></i>
                                                            <span>Penyewa</span>
                                                        </span>
                                                        <span class="booking-value">${transaction.booking_info.user_name}</span>
                                                    </div>
                                                    <div class="booking-detail">
                                                        <span class="booking-label">
                                                            <i class="fas fa-credit-card"></i>
                                                            <span>Status</span>
                                                        </span>
                                                        <span class="booking-value" style="color: ${statusInfo.color};">${transaction.booking_info.booking_payment}</span>
                                                    </div>
                                                </div>
                                            ` : ''}
                                            
                                            <div class="card-row" style="margin-top: 16px;">
                                                <div class="card-label" style="color: #7b8a8b; font-size: 13px;">
                                                    <i class="far fa-clock"></i>
                                                    <span>${formatDate(transaction.created_at)}</span>
                                                </div>
                                                <div class="card-value">
                                                    <span class="transaction-status ${statusInfo.class}">
                                                        <i class="fas ${transaction.status === 'pending' ? 'fa-clock' : transaction.status === 'completed' ? 'fa-check-circle' : 'fa-times-circle'}" 
                                                           style="margin-right: 4px; font-size: 10px;"></i>
                                                        ${statusInfo.text}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                        container.innerHTML = html;
                        noVenueMessage.style.display = 'none';
                        
                        // Update badge counts
                        document.getElementById('badge-all').textContent = counts.all;
                        document.getElementById('badge-deposit').textContent = counts.deposit;
                        
                        // Set active filter to "all"
                        filterTransactions('all');
                    } else {
                        // Check if no data due to no venues
                        if (balance === 0) {
                            fetch('/api/landowner/has-venues')
                                .then(res => res.json())
                                .then(venueData => {
                                    if (!venueData.hasVenues) {
                                        noVenueMessage.style.display = 'block';
                                        container.innerHTML = '';
                                    } else {
                                        showNoDataMessage();
                                        noVenueMessage.style.display = 'none';
                                    }
                                })
                                .catch(() => {
                                    showNoDataMessage();
                                    noVenueMessage.style.display = 'none';
                                });
                        } else {
                            showNoDataMessage();
                            noVenueMessage.style.display = 'none';
                        }
                    }

                    // Reset refresh button
                    if (refreshBtn) {
                        refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i><span>Refresh</span>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Gagal memuat data. Silakan refresh halaman atau coba lagi nanti.');
                    showNoDataMessage();
                    
                    // Reset refresh button
                    if (refreshBtn) {
                        refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i><span>Refresh</span>';
                    }
                });
        }

        // ====================
        // EVENT LISTENERS
        // ====================
        document.addEventListener('DOMContentLoaded', function() {
            // Initial load
            loadTransactionHistory();
            
            // Auto-refresh every 60 seconds
            setInterval(() => {
                loadTransactionHistory();
            }, 60000);
            
            // Filter button event listeners
            document.addEventListener('click', function(e) {
                if (e.target.closest('.filter-btn')) {
                    const filterBtn = e.target.closest('.filter-btn');
                    const type = filterBtn.getAttribute('data-type');
                    filterTransactions(type);
                }
            });
        });

        // ====================
        // GLOBAL FUNCTIONS
        // ====================
        window.refreshBalance = refreshBalance;
        window.formatCurrency = formatCurrency;
    </script>
@endpush
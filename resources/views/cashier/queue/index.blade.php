@extends('layouts.main')

@section('title', 'Kasir & Booking')

@push('styles')
    @include('cashier.queue.partials.style')
@endpush

@section('content')
<!-- Header Tetap (Fixed Header) - Menampilkan judul halaman dan informasi logout -->
<div class="fixed-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="title">Kasir & Booking</div>
            <div class="subtitle">Kelola pemesanan lapangan dan proses pembayaran dengan mudah.</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
            </button>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="logout-icon mb-3">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </div>
                    <h5 class="modal-title mb-2">Keluar dari Sistem</h5>
                    <p class="text-muted small">Apakah Anda yakin ingin logout?</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-50" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="/logout" class="btn btn-warning w-50">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="content-wrapper">
        <div class="row g-3">
            <!-- Bagian Kiri: Pemilihan Lapangan (Field Selection) -->
            <div class="col-lg-7">
                <div class="card section-card">
                    <div class="card-header">
                        <h6 class="mb-0">Pilih Lapangan</h6>
                    </div>
                    <div class="card-body">
                        <!-- Filter berdasarkan kategori dan pencarian nama lapangan -->
                        <div class="filter-section">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <select id="category" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="search" class="form-control" placeholder="Cari lapangan...">
                                </div>
                            </div>
                        </div>

                        <!-- Grid untuk menampilkan daftar venue/lapangan yang tersedia -->
                        <div class="venue-grid" id="venue-list">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted mt-2">Memuat data lapangan...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan: Keranjang Pesanan & Ringkasan Pembayaran -->
            <div class="col-lg-5">
                <div class="card section-card">
                    <div class="card-header">
                        <h6 class="mb-0">Keranjang & Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <!-- Input untuk mencari pelanggan terdaftar (buyer) -->
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold">Nama Penyewa</label>
                            <input type="text" id="buyerSearch" class="form-control" placeholder="Cari nama penyewa..." autocomplete="off">
                            <input type="hidden" id="buyerId">
                            <div id="buyerDropdown" class="autocomplete-dropdown" style="display: none;"></div>
                            <div id="selectedBuyer" class="selected-buyer" style="display: none;"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Komunitas <span class="text-muted small">(Opsional)</span></label>
                            <input type="text" id="community" class="form-control" placeholder="Nama komunitas">
                        </div>

                        <!-- Daftar item yang dimasukkan ke keranjang belanja -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0">Daftar Pesanan</h6>
                                <small class="text-muted" id="cartCount">0 item</small>
                            </div>
                            <div id="cartItems" class="cart-items">
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-cart-shopping fa-2x mb-2"></i>
                                    <p class="small mb-0">Belum ada pesanan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan total biaya dan pemilihan metode pembayaran -->
                        <div class="payment-summary">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Subtotal</span>
                                <span id="subtotal">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong class="text-success" id="total">Rp 0</strong>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Metode Pembayaran</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="cash">
                                    <label class="form-check-label" for="cash">
                                        <i class="fa-solid fa-money-bill-wave me-2"></i>Cash
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="midtrans">
                                    <label class="form-check-label" for="midtrans">
                                        <i class="fa-solid fa-credit-card me-2"></i>Midtrans
                                    </label>
                                </div>
                            </div>

                            <!-- Input nominal uang tunai jika metode Cash dipilih -->
                            <div class="mb-3" id="cashSection">
                                <label class="form-label fw-semibold">Uang Diterima</label>
                                <input type="number" id="cashInput" class="form-control" placeholder="Masukkan nominal">
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Kembalian</small>
                                    <small id="changeAmount">Rp 0</small>
                                </div>
                            </div>
                            <!-- Tombol Finalisasi Pembayaran -->
                            <button id="payButton" class="btn btn-success w-100 btn-pay" disabled>
                                <i class="fa-solid fa-credit-card me-2"></i>Bayar & Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Fixed Navbar - Aksi Cepat -->
    <div class="bottom-navbar">
        <div class="nav-buttons">
            <!-- Tombol Dashboard -->
            <a href="/cashier/dashboard" class="btn btn-outline-info btn-nav">
                <i class="fa-solid fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <!-- Dropdown Display -->
            <div class="dropdown display-dropdown">
                <button class="btn btn-outline-danger btn-nav dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-display"></i>
                    <span>Display</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3">
                    <li>
                        <a class="dropdown-item rounded-3 py-3 mb-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#modalAllVenues">
                            <i class="fa-solid fa-layer-group me-3 text-danger"></i>
                            Semua Venue
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 py-3" href="#" data-bs-toggle="modal"
                            data-bs-target="#modalSelectSection">
                            <i class="fa-solid fa-table-columns me-3 text-danger"></i>
                            Pilih Lapangan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tombol lainnya -->
            <a href="/cashier/ticket" class="btn btn-outline-primary btn-nav">
                <i class="fa-solid fa-ticket"></i>
                <span>Riwayat Tiket</span>
            </a>
            <a href="/cashier/scan" class="btn btn-outline-success btn-nav">
                <i class="fa-solid fa-qrcode"></i>
                <span>Scan QR</span>
            </a>
            <a href="/cashier/queue" class="btn btn-warning btn-nav">
                <i class="fa-solid fa-cash-register"></i>
                <span>Kasir</span>
            </a>
        </div>
    </div>

<!-- Modal Pilih Section -->
<div class="modal fade" id="modalSelectSection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="fa-solid fa-table-columns me-2"></i>Pilih Lapangan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    @foreach ($sections as $section)
                        <div class="col-md-4">
                            <a href="{{ route('cashier.display.sections.show', $section->id) }}" target="_blank"
                                class="btn btn-outline-success w-100 py-4">
                                <strong>{{ $section->venue->venue_name }}</strong><br>
                                <small class="text-muted">
                                    {{ $section->section_name }}
                                </small>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Semua Venue -->
<div class="modal fade" id="modalAllVenues" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="fa-solid fa-layer-group me-2"></i> Semua Venue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @foreach ($venues as $venue)
                        <div class="col-md-4">
                            <a href="{{ route('cashier.display.venues.sections', $venue->id) }}" target="_blank"
                                class="btn btn-outline-success w-100 py-4">
                                <strong>{{ $venue->venue_name }}</strong><br>
                                <small class="text-muted">
                                    {{ $venue->location ?? '-' }}
                                </small>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Sheet Overlay -->
<div class="bottom-sheet-overlay" id="bottomSheetOverlay" onclick="closeBottomSheet()"></div>

<!-- Bottom Sheet -->
<div class="bottom-sheet" id="bottomSheet">
    <div class="bottom-sheet-header">
        <div>
            <h5 class="mb-0" id="sheetVenueName">Loading...</h5>
            <small id="sheetVenueMeta">Field Rental Management</small>
        </div>
        <button type="button" class="btn btn-link text-white" onclick="closeBottomSheet()">
            <i class="fa-solid fa-times fa-lg"></i>
        </button>
    </div>
    
    <div class="bottom-sheet-body">
        <!-- Sections Tabs -->
        <div class="mb-4" id="sectionTabs">
            <div class="d-flex flex-wrap gap-2">
                <!-- Section tabs akan muncul di sini -->
            </div>
        </div>

        <!-- Date Filter -->
        <div class="mb-4" id="dateFilter" style="display: none;">
            <h6 class="mb-2 fw-semibold text-muted small">Pilih Tanggal</h6>
            <div class="d-flex flex-wrap gap-2" id="dateButtons">
                <!-- Date buttons akan muncul di sini -->
            </div>
        </div>

        <!-- Field Info -->
        <div class="field-header" id="fieldHeader" style="display: none;">
            <div>
                <div class="field-name" id="currentSectionName"></div>
                <div class="field-meta" id="currentSectionDesc"></div>
            </div>
            <div class="text-end">
                <div class="field-price" id="currentSectionPrice"></div>
                <div class="field-meta" id="currentSectionPlayers"></div>
            </div>
        </div>

        <!-- Schedules -->
        <div id="scheduleList">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Memuat jadwal...</p>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Detail Pesanan -->
                        <div class="order-details mb-4">
                            <h6 class="mb-3 fw-semibold">Detail Pesanan</h6>
                            <div id="modalOrderDetails">
                                <!-- Detail pesanan akan dimasukkan di sini secara dinamis -->
                            </div>
                        </div>
                        
                        <!-- Informasi Penyewa -->
                        <div class="buyer-info mb-4">
                            <h6 class="mb-3 fw-semibold">Informasi Penyewa</h6>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Nama</small>
                                            <span id="modalBuyerName" class="fw-semibold">-</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Telepon</small>
                                            <span id="modalBuyerPhone" class="fw-semibold">-</span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted d-block">Komunitas</small>
                                            <span id="modalCommunity" class="fw-semibold">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Ringkasan Pembayaran -->
                        <div class="payment-summary sticky-top" style="top: 20px;">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-3 fw-semibold">Ringkasan Pembayaran</h6>
                                    
                                    <div class="summary-item mb-2">
                                        <small class="text-muted">Total Pesanan</small>
                                        <small class="text-muted" id="modalItemCount">0 item</small>
                                    </div>
                                    
                                    <div class="summary-total mb-3">
                                        <span class="d-block text-muted mb-1">Total</span>
                                        <h4 class="text-success mb-0" id="modalTotal">Rp 0</h4>
                                    </div>
                                    
                                    <div class="summary-cash mb-3">
                                        <small class="text-muted d-block mb-1">Uang Diterima</small>
                                        <h5 id="modalCash">Rp 0</h5>
                                    </div>
                                    
                                    <div class="summary-change">
                                        <small class="text-muted d-block mb-1">Kembalian</small>
                                        <h5 class="text-success" id="modalChange">Rp 0</h5>
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-top">
                                        <p class="small text-muted mb-3">Dengan mengklik Bayar, Anda menyetujui transaksi ini</p>
                                        
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                <i class="fa-solid fa-times me-2"></i>Batal
                                            </button>
                                            <button type="button" class="btn btn-success" onclick="confirmPayment()">
                                                <i class="fa-solid fa-credit-card me-2"></i>Bayar Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="success-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h5 class="modal-title mb-2">Pembayaran Berhasil!</h5>
                <p class="text-muted small mb-4" id="successMessage"></p>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="error-icon mb-3">
                    <i class="fa-solid fa-circle-exclamation text-danger fa-3x"></i>
                </div>
                <h5 class="modal-title mb-2" id="errorTitle">Terjadi Kesalahan</h5>
                <p class="text-muted small mb-4" id="errorMessage">-</p>
                <button type="button" class="btn btn-outline-danger w-50" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Venue Card Template -->
<template id="venueCardTemplate">
    <div class="venue-card-item" onclick="selectVenue({venueId}, '{venueName}', '{categoryName}')">
        <div class="venue-card">
            <div class="venue-image">
                <img src="{imageUrl}" class="card-img-top" alt="{venueName}" 
                     onerror="this.src='https://via.placeholder.com/300x200/41a67e/ffffff?text=No+Image'">
            </div>
            <div class="card-body">
                <h6 class="card-title mb-1">{venueName}</h6>
                <div class="venue-info">
                    <small class="text-muted d-block mb-1">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        {location}
                    </small>
                    <small class="text-muted d-block mb-2">
                        <i class="fa-solid fa-tags me-1"></i>
                        {categoryName}
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
    {{-- MIDTRANS SNAP JS --}}
    @php
        $snapJsUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @include('cashier.queue.partials.script')
@endpush
{{--
=============================================================================
VIEW: SCAN QR TIKET (KASIR - HALAMAN SCAN QR)
Halaman untuk kasir melakukan scan QR code tiket booking
Validasi otomatis untuk masuk venue dan lapangan
=============================================================================
--}}

{{-- Extend layout utama --}}
@extends('layouts.main')

{{-- Set judul halaman --}}
@section('title', 'Scan QR Tiket')

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling scan dari partial --}}
    @include('cashier.scan.partials.style')
@endpush

{{-- Section konten utama --}}
@section('content')
<!-- ====================== HEADER TETAP (FIXED HEADER) ====================== -->
<div class="fixed-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="title">Scan QR Tiket</div>
            <div class="subtitle">Validasi otomatis masuk venue & lapangan</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            {{-- Status online --}}
            <span class="badge bg-light text-success fw-semibold px-3 py-2">
                <i class="fas fa-circle me-1"></i> Online
            </span>
            {{-- Tombol logout --}}
            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
            </button>
        </div>
    </div>
</div>

<!-- ====================== KONTAINER UTAMA SCAN ====================== -->
{{-- Tata letak tanpa scroll: panel kiri scanner (75%), panel kanan hasil (25%) --}}
<div class="scan-container">
    <!-- ====================== PANEL KIRI - AREA SCANNER KAMERA ====================== -->
    <div class="scanner-panel">
        {{-- Kontrol scanner --}}
        <div class="scanner-controls">
            {{-- Mode scan otomatis --}}
            <div class="alert alert-success border-0 bg-transparent text-success fw-bold py-1 px-2 mb-0 d-flex align-items-center small">
                <i class="fas fa-robot me-2"></i> Mode: Smart Validation
            </div>
            
            {{-- Pilihan kamera yang tersedia --}}
            <select id="camera-select" class="form-select">
                <option value="">Loading kamera...</option>
            </select>
        </div>

        {{-- Area video kamera scanner --}}
        <div class="scanner-wrapper">
            <div id="qr-reader"></div>
            {{-- Status inisialisasi scanner --}}
            <div class="scanner-status">
                <i class="fas fa-circle-notch fa-spin me-2"></i> Menginisialisasi scanner...
            </div>
        </div>
    </div>

    <!-- ====================== PANEL KANAN - RINGKASAN HASIL SCAN ====================== -->
    <div class="results-panel">
        <div class="results-header">
            <i class="fas fa-clipboard-check me-2"></i>Hasil Scan
        </div>
        <div class="results-panel-inner">
            <div id="scan-log">
                {{-- Placeholder ketika belum ada scan --}}
                <div id="scan-placeholder" class="text-center text-muted py-5">
                    <i class="fas fa-qrcode fa-4x mb-3 opacity-50"></i>
                    <p class="small">Arahkan QR code ke scanner untuk mulai scan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ====================== BOTTOM NAVBAR - FIXED ====================== -->
{{-- Navigasi bawah untuk kasir --}}
<div class="bottom-navbar">
    <div class="nav-buttons">
        {{-- Tombol Dashboard --}}
        <a href="/cashier/dashboard" class="btn btn-outline-info btn-nav">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        {{-- Dropdown Display --}}
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

        {{-- Tombol lainnya --}}
        <a href="/cashier/ticket" class="btn btn-outline-primary btn-nav">
            <i class="fa-solid fa-ticket"></i>
            <span>Riwayat Tiket</span>
        </a>
        {{-- Tombol Scan QR (aktif) --}}
        <a href="/cashier/scan" class="btn btn-success btn-nav">
            <i class="fa-solid fa-qrcode"></i>
            <span>Scan QR</span>
        </a>
        <a href="/cashier/queue" class="btn btn-outline-warning btn-nav">
            <i class="fa-solid fa-cash-register"></i>
            <span>Kasir</span>
        </a>
    </div>
</div>

<!-- ====================== NOTIFICATION CONTAINER ====================== -->
{{-- Container untuk notifikasi toast --}}
<div class="notification-container" id="notification-container"></div>

<!-- ====================== MODALS ====================== -->

{{-- Logout Modal --}}
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
                    <button type="button" class="btn btn-outline-secondary w-50" data-bs-dismiss="modal">Batal</button>
                    <a href="/logout" class="btn btn-warning w-50">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pilih Lapangan (Select Section) --}}
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
                    {{-- Looping semua section (lapangan) --}}
                    @foreach ($sections as $section)
                        <div class="col-md-4">
                            <a href="{{ route('cashier.display.sections.show', $section->id) }}" target="_blank"
                                class="btn btn-outline-success w-100 py-4">
                                <strong>{{ $section->venue->venue_name }}</strong><br>
                                <small class="text-muted">{{ $section->section_name }}</small>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Semua Venue --}}
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
                    {{-- Looping semua venue --}}
                    @foreach ($venues as $venue)
                        <div class="col-md-4">
                            <a href="{{ route('cashier.display.venues.sections', $venue->id) }}" target="_blank"
                                class="btn btn-outline-success w-100 py-4">
                                <strong>{{ $venue->venue_name }}</strong><br>
                                <small class="text-muted">{{ $venue->location ?? '-' }}</small>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Section untuk menambahkan JavaScript khusus halaman ini --}}
@push('scripts')
    {{-- Library html5-qrcode untuk scan QR code --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    {{-- Memasukkan file JavaScript untuk fungsi scan dari partial --}}
    @include('cashier.scan.partials.script')
@endpush
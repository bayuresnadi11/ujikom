@extends('layouts.main')

@section('title', 'Scan QR Tiket')

@push('styles')
    @include('cashier.scan.partials.style')
@endpush

@section('content')
<!-- Fixed Header -->
<div class="fixed-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="title">Scan QR Tiket</div>
            <div class="subtitle">Validasi masuk lapangan dengan QR code</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-success fw-semibold px-3 py-2">
                <i class="fas fa-circle me-1"></i> Online
            </span>
            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
            </button>
        </div>
    </div>
</div>

<!-- Main Container - NO SCROLL -->
<div class="scan-container">
    <!-- Left Panel - Scanner (75%) -->
    <div class="scanner-panel">
        <div class="scanner-controls">
            <div class="mode-toggle btn-group" role="group">
                <button type="button" class="btn btn-outline-success active">
                    <i class="fas fa-door-open"></i> Masuk Venue
                </button>
                <button type="button" class="btn btn-outline-success">
                    <i class="fas fa-futbol"></i> Masuk Lapangan
                </button>
            </div>
            
            <select id="camera-select" class="form-select">
                <option value="">Loading kamera...</option>
            </select>
        </div>

        <!-- Scanner Wrapper -->
        <div class="scanner-wrapper">
            <div id="qr-reader"></div>
            <div class="scanner-status">
                <i class="fas fa-circle-notch fa-spin me-2"></i> Menginisialisasi scanner...
            </div>
        </div>
    </div>

    <!-- Right Panel - Results (25%) -->
    <div class="results-panel">
        <div class="results-header">
            <i class="fas fa-clipboard-check me-2"></i>Hasil Scan
        </div>
        <div class="results-panel-inner">
            <div id="scan-log">
                <div id="scan-placeholder" class="text-center text-muted py-5">
                    <i class="fas fa-qrcode fa-4x mb-3 opacity-50"></i>
                    <p class="small">Arahkan QR code ke scanner untuk memulai scan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Navbar - Fixed -->
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

<!-- Notification Container -->
<div class="notification-container" id="notification-container"></div>

<!-- Modals -->
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    @include('cashier.scan.partials.script')
@endpush
@extends('layouts.main')

@section('title', 'Riwayat Tiket')

@push('styles')
    @include('cashier.ticket.partials.style')
@endpush

@section('content')
    <!-- Fixed Header -->
    <div class="fixed-header">
        <div>
            <div class="title">Riwayat Tiket</div>
            <div class="subtitle">Pantau semua tiket penyewa dengan mudah.</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-success fw-semibold px-3 py-2">
                Total: {{ $bookings->count() }} tiket
            </span>
            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
            </button>
        </div>
    </div>

    <!-- Ticket Container -->
    <div class="ticket-container">
        <div class="card section-card h-100">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Tiket</th>
                                <th>Penyewa</th>
                                <th>Lapangan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Komunitas</th>
                                <th>Status Bayar</th>
                                <th>Status Scan</th>
                                <th>QR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    {{-- KODE TIKET --}}
                                    <td class="fw-semibold">
                                        {{ $booking->ticket_code }}
                                    </td>

                                    {{-- PENYEWA --}}
                                    <td>
                                        {{ $booking->user->name ?? '-' }}
                                    </td>

                                    {{-- LAPANGAN / SECTION --}}
                                    <td class="fw-semibold">
                                        {{ $booking->venue->venue_name ?? '-' }}
                                        -
                                        {{ optional($booking->schedule->section)->section_name ?? '-' }}
                                    </td>

                                    {{-- TANGGAL --}}
                                    <td>
                                        {{ optional($booking->schedule)->date ? \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') : '-' }}
                                    </td>

                                    {{-- JAM --}}
                                    <td>
                                        {{ $booking->schedule->start_time ?? '-' }}
                                        -
                                        {{ $booking->schedule->end_time ?? '-' }}
                                    </td>

                                    {{-- KOMUNITAS --}}
                                    <td>
                                        {{ $booking->playTogether->community_name ?? '-' }}
                                    </td>

                                    {{-- STATUS BAYAR --}}
                                    <td>
                                        @if ($booking->isPaid())
                                            <span class="badge bg-success">Berhasil</span>
                                        @elseif ($booking->isPendingPayment())
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($booking->isExpiredOrCancelled())
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Dibayar</span>
                                        @endif
                                    </td>

                                    {{-- STATUS SCAN --}}
                                    <td>
                                        @switch($booking->scan_status)
                                            @case('belum_scan')
                                                <span class="badge bg-warning text-dark">
                                                    BELUM SCAN
                                                </span>
                                                @break

                                            @case('masuk_venue')
                                                <span class="badge bg-info">
                                                    MASUK VENUE
                                                </span>
                                                @break

                                            @case('masuk_lapang')
                                                <span class="badge bg-success">
                                                    MASUK LAPANG
                                                </span>
                                                @break

                                            @default
                                                <span class="badge bg-secondary">
                                                    UNKNOWN
                                                </span>
                                        @endswitch
                                    </td>
                                    {{-- QR --}}
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="showQr('{{ $booking->ticket_code }}')">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center empty-state">
                                        Belum ada booking masuk
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navbar -->
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
            <a href="/cashier/ticket" class="btn btn-primary btn-nav">
                <i class="fa-solid fa-ticket"></i>
                <span>Riwayat Tiket</span>
            </a>
            <a href="/cashier/scan" class="btn btn-outline-success btn-nav">
                <i class="fa-solid fa-qrcode"></i>
                <span>Scan QR</span>
            </a>
            <a href="/cashier/queue" class="btn btn-outline-warning btn-nav">
                <i class="fa-solid fa-cash-register"></i>
                <span>Kasir</span>
            </a>
        </div>
    </div>
    <!-- Modals -->

    <!-- Logout Modal -->
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
                        <a href="/logout" class="btn btn-warning w-50 text-white">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Selecting Section -->
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

    <!-- MODAL QR -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">QR Tiket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <div id="qrContainer"></div>

                    <div class="mt-3 fw-semibold" id="ticketCode"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    @include('cashier.ticket.partials.script')
@endpush



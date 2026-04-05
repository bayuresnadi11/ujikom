@extends('layouts.main')

@section('title', 'Cashier')

@push('styles')
    @include('cashier.dashboard.partials.style')
@endpush

@section('content')
    <!-- Fixed Header -->
    <div class="fixed-header">
        <div>
            <div class="title">Daftar Penyewa</div>
            <div class="subtitle">Kelola data penyewa dengan cepat dan rapi.</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="row-count-badge">
                <i class="fa-solid fa-users me-1"></i>
                {{ $totalCustomers ?? 0 }} Penyewa
            </span>
            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="dashboard-container">
        <div class="container-fluid p-0">
            <div class="row g-3">

                <!-- Form Tambah Penyewa -->
                <div class="col-lg-4">
                    <form action="{{ route('cashier.customers.store') }}" method="POST" id="addCustomerForm">
                        @csrf

                        <div class="card section-card card-fixed-height">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fa-solid fa-user-plus me-2"></i>Tambah Penyewa Baru
                                </div>
                            </div>

                            <div class="card-body d-grid gap-3">
                                <div>
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="name"
                                        class="form-control"
                                        required
                                        placeholder="Masukkan Nama Penyewa..."
                                        maxlength="100">
                                </div>

                                <div>
                                    <label class="form-label fw-semibold">No. Telepon/WhatsApp</label>
                                    <input type="tel" name="phone"
                                        class="form-control"
                                        required
                                        placeholder="Masukkan Nomor Telepon..."
                                        pattern="[0-9]{10,13}"
                                        title="Masukkan 10-13 digit nomor telepon">
                                </div>

                                <button type="submit" class="btn btn-success fw-semibold mt-1" id="submitBtn">
                                    <i class="fa-solid fa-user-plus me-2"></i>Tambah Penyewa
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Penyewa -->
                <div class="col-lg-8">
                    <div class="card section-card card-fixed-height">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-list me-2"></i>Daftar Penyewa
                            </div>
                            
                            <!-- Search Form -->
                            <form action="{{ route('cashier.dashboard.index') }}" method="GET" class="search-form-wrapper">
                                <div class="search-container">
                                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control search-box" 
                                           placeholder="Cari nama atau telepon..."
                                           value="{{ request('search') }}"
                                           autocomplete="off">
                                    @if(request('search'))
                                        <button type="button" class="search-clear" id="clearSearch">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                                @if(request('search'))
                                    <a href="{{ route('cashier.dashboard.index') }}" class="btn btn-outline-secondary btn-sm btn-reset">
                                        <i class="fa-solid fa-rotate-left me-1"></i>Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <div class="card-body-scrollable">
                            @if($customers->count() > 0)
                                <!-- Scrollable Table -->
                                <div class="scrollable-table-container">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4" style="width: 60px;">#</th>
                                                <th>Nama Penyewa</th>
                                                <th style="width: 160px;">No. Telepon</th>
                                                <th style="width: 100px;" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $index => $customer)
                                            <tr>
                                                <td class="fw-semibold ps-4">
                                                    {{ $customers->firstItem() + $index }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title bg-light text-primary rounded-circle" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fa-solid fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $customer->name }}</div>
                                                            <small class="text-muted">
                                                                Ditambahkan: {{ $customer->created_at->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-phone text-success me-2"></i>
                                                        <span class="text-monospace">{{ $customer->phone }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('cashier.customers.destroy', $customer->id) }}"
                                                        method="POST"
                                                        class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3" title="Hapus penyewa">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="pagination-container d-flex justify-content-between align-items-center">
                                    <div class="pagination-info">
                                        Menampilkan {{ $customers->count() }} dari {{ $customers->total() }} hasil
                                        @if(request('search'))
                                            <span class="text-muted">(Total keseluruhan: {{ $totalCustomers ?? 0 }} penyewa)</span>
                                        @endif
                                    </div>
                                    
                                    @if($customers->hasPages())
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination custom-pagination mb-0">
                                            {{-- Previous Page Link --}}
                                            @if ($customers->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">
                                                        <i class="fa-solid fa-chevron-left"></i>
                                                    </span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $customers->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                                                        <i class="fa-solid fa-chevron-left"></i>
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                                                @if ($page == $customers->currentPage())
                                                    <li class="page-item active" aria-current="page">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $customers->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($customers->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $customers->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                                                        <i class="fa-solid fa-chevron-right"></i>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">
                                                        <i class="fa-solid fa-chevron-right"></i>
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                    @endif
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-users-slash"></i>
                                    </div>
                                    <h5 class="empty-state-title">
                                        @if(request('search'))
                                            Hasil pencarian tidak ditemukan
                                        @else
                                            Belum ada penyewa terdaftar
                                        @endif
                                    </h5>
                                    <p class="empty-state-subtitle">
                                        @if(request('search'))
                                            Coba gunakan kata kunci lain atau tambahkan penyewa baru.
                                        @else
                                            Tambahkan penyewa pertama Anda dengan mengisi form di samping.
                                        @endif
                                    </p>
                                    @if(request('search'))
                                        <a href="{{ route('cashier.dashboard.index') }}" class="btn btn-primary mt-3">
                                            <i class="fa-solid fa-rotate-left me-2"></i>Tampilkan Semua
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bottom Navbar -->
    <div class="bottom-navbar">
        <div class="nav-buttons">
            <!-- Tombol Dashboard -->
            <a href="/cashier/dashboard" class="btn btn-info btn-nav">
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
                        <div class="logout-icon mb-3" style="width: 70px; height: 70px; background: #fff8e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fa-solid fa-right-from-bracket" style="font-size: 28px; color: #ff9800;"></i>
                        </div>
                        <h5 class="modal-title mb-2">Keluar dari Sistem</h5>
                        <p class="text-muted small">Apakah Anda yakin ingin logout?</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50" data-bs-dismiss="modal">Batal</button>
                        <a href="/logout" class="btn btn-warning w-50 text-white">Logout</a>
                    </div>
                </div>
            </div>
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
    @include('cashier.dashboard.partials.script')
@endpush
@extends('layouts.main', ['title' => 'Main Bareng Saya - SewaLap'])

@push('styles')
    @include('buyer.main_bareng_saya.partials.style')
    <style>
        .btn-pay {
            background: #2E7D32;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-pay:hover {
            background: #1B5E20;
            transform: translateY(-1px);
        }
        .badge-paid {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #C8E6C9;
        }
        .badge-pending-pay {
            background: #FFF3E0;
            color: #E65100;
            border: 1px solid #FFE0B2;
        }
        /* Status Badge Styles */
        .badge-status-pending { background: #E3F2FD; color: #1976D2; border: 1px solid #BBDEFB; }
        .badge-status-active { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
        .badge-status-completed { background: #F3E5F5; color: #7B1FA2; border: 1px solid #E1BEE7; }
        .badge-status-canceled { background: #FFEBEE; color: #D32F2F; border: 1px solid #FFCDD2; }

        /* QR Modal */
        .qr-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .qr-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            position: relative;
            max-width: 300px;
            width: 90%;
        }

        .qr-close {
            position: absolute;
            top: 8px;
            right: 12px;
            cursor: pointer;
            font-size: 22px;
        }

        .btn-show-qr {
            margin-left: 10px;
            padding: 6px 12px;
            border-radius: 20px;
            border: none;
            background: rgba(52, 152, 219, .15);
            color: #3498db;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <!-- Main App Container -->
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <a href="{{ route('buyer.home') }}" class="logo">
                    <i class="fas fa-futbol logo-icon"></i>
                    Sewa<span>Lap</span>
                </a>
                <div class="header-actions">
                    <button class="header-icon" onclick="window.location.href='{{ route('buyer.notifications.index') }}'">
                        <i class="far fa-bell"></i>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="notification-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </button>
                </div>
            </div>
         
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Main Bareng Saya</h1>
                <p class="page-subtitle">
                    Kelola main bareng yang Anda buat dan ikuti
                </p>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <a href="{{ route('buyer.main_bareng_saya.index', ['filter' => 'created']) }}" 
                   class="tab-btn {{ $filter === 'created' ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i> Saya Buat
                </a>
                <a href="{{ route('buyer.main_bareng_saya.index', ['filter' => 'joined']) }}" 
                   class="tab-btn {{ $filter === 'joined' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> Disetujui
                </a>
                <a href="{{ route('buyer.main_bareng_saya.index', ['filter' => 'pending']) }}" 
                   class="tab-btn {{ $filter === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Pending
                </a>
            </div>

            <!-- Filter Info -->
            <div class="filter-info">
                <i class="fas fa-filter"></i>
                Menampilkan: 
                <span class="filter-badge">
                    @if($filter === 'created')
                        Main Bareng yang Saya Buat
                    @elseif($filter === 'joined')
                        Main Bareng yang Saya Ikuti (Disetujui)
                    @else
                        Main Bareng yang Saya Ikuti (Pending)
                    @endif
                </span>
            </div>

            <!-- Actions Bar -->
            <div class="actions-bar">
                <form method="GET" action="{{ route('buyer.main_bareng_saya.index') }}" class="search-box">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    
                    <input type="text" 
                           class="search-input" 
                           name="search" 
                           placeholder="Cari berdasarkan lokasi atau venue..." 
                           value="{{ $search }}"
                           id="searchInput">
                </form>
                <button class="btn-add" onclick="window.location.href='{{ route('buyer.booking.index') }}'">
                    <i class="fas fa-plus"></i> Buat Baru
                </button>
            </div>

            <!-- Card Container -->
            <div class="card-container" id="cardContainer">
                @forelse($playTogethers as $playTogether)
                    @php
                        $booking = $playTogether->booking;
                        $venue = $booking->venue ?? null;
                        $category = $venue->category ?? null;
                        $creator = $playTogether->creator;
                        
                        // Check if user is host
                        $isHost = $playTogether->created_by == auth()->id();
                        
                        // Get user participant info
                        $userParticipant = $playTogether->participants()
                            ->where('user_id', auth()->id())
                            ->first();
                        
                        // Hitung peserta yang sudah approved
                        $approvedParticipantsCount = $playTogether->participants()
                            ->where('approval_status', 'approved')
                            ->count();
                            
                        // Cek apakah tanggal hari ini atau besok
                        $eventDate = \Carbon\Carbon::parse($playTogether->date);
                        $isToday = $eventDate->isToday();
                        $isTomorrow = $eventDate->isTomorrow();
                        $isCriticalTime = $isToday || $isTomorrow;

                        // Trigger sesuaikan biaya (Khusus Host, Tipe Berbayar, Belum Penuh, H-1/Hari ini, Belum Pernah Adjusted)
                        $canAdjustPrice =
                            $isHost &&
                            in_array($playTogether->payment_scheme, ['participant', null]) &&
                            $approvedParticipantsCount < $playTogether->max_participants &&
                            $isCriticalTime &&
                            in_array($playTogether->status, ['pending', 'active']);
                    @endphp
                    
                    @if($venue && $category)
                        <div class="card" data-id="{{ $playTogether->id }}">
                            <!-- Card Image -->
                            <div class="card-image">
                                @if($venue->photo)
                                    <img src="{{ asset('storage/' . $venue->photo) }}" 
                                         alt="{{ $venue->venue_name }}" class="venue-image" />
                                @else
                                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                         alt="{{ $venue->venue_name }}" class="venue-image" />
                                @endif
                            </div>

                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-futbol"></i>
                                    {{ $venue->venue_name }}
                                </div>
                                <div class="card-badges">
                                    {{-- LABEL STATUS PLAY TOGETHER --}}
                                    <span class="badge badge-status-{{ $playTogether->status }}">
                                        {{ strtoupper($playTogether->status) }}
                                    </span>

                                    @if($isHost)
                                        <span class="badge badge-host-yes">
                                            <i class="fas fa-crown"></i> HOST
                                        </span>
                                    @endif
                                    
                                    @if($userParticipant && !$isHost)
                                        @if($userParticipant->approval_status === 'pending')
                                            <span class="badge" style="background: #FFF3E0; color: #E65100; border: 1px solid #FFE0B2;">
                                                <i class="fas fa-clock"></i> PENDING
                                            </span>
                                        @elseif($userParticipant->approval_status === 'approved')
                                            <span class="badge badge-active">
                                                <i class="fas fa-check"></i> APPROVED
                                            </span>
                                        @elseif($userParticipant->approval_status === 'rejected')
                                            <span class="badge badge-canceled">
                                                <i class="fas fa-times"></i> REJECTED
                                            </span>
                                        @endif
                                    @endif

                                    <span class="badge badge-{{ $playTogether->privacy === 'public' ? 'public' : 'private' }}">
                                        <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i> 
                                        {{ strtoupper($playTogether->privacy) }}
                                    </span>
                                    <span class="badge badge-{{ $playTogether->type === 'paid' ? 'paid' : 'free' }}">
                                        <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i> 
                                        {{ strtoupper($playTogether->type) }}
                                    </span>
                                    
                                    @if($isToday)
                                        <span class="badge badge-today">
                                            <i class="fas fa-calendar-day"></i> HARI INI
                                        </span>
                                    @elseif($isTomorrow)
                                        <span class="badge badge-upcoming">
                                            <i class="fas fa-calendar-alt"></i> BESOK
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Tanggal
                                    </div>
                                    <div class="card-value">
                                        {{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-tag"></i>
                                        Kategori
                                    </div>
                                    <div class="card-value">
                                        {{ $category->name ?? 'Tidak ada kategori' }}
                                    </div>
                                </div>

                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-users"></i>
                                        Peserta
                                    </div>
                                    <div class="card-value">
                                        {{ $approvedParticipantsCount }} / {{ $playTogether->max_participants }} orang
                                    </div>
                                </div>

                                <div class="card-row">
    <div class="card-label">
        <i class="fas fa-money-bill-wave"></i>
        Biaya
    </div>
    <div class="card-value">
        @php
            $lapangPrice = 0;
            $joinPrice = 0;

            // ====================== LAPANG ======================
            if($playTogether->payment_scheme === 'participant') {
                $lapangPrice = $booking->total_price ?? 0;
                if($playTogether->max_participants > 0){
                    $lapangPrice = ceil($lapangPrice / $playTogether->max_participants);
                }
            }

            // ====================== JOIN ======================
            if($playTogether->type === 'paid') {
                $joinPrice = $playTogether->price_per_person ?? 0;
            }
        @endphp

        @if($lapangPrice > 0 && $joinPrice > 0)
            {{-- Gabung Lapang + Join --}}
            <span class="badge badge-cost">
                Lapang + Join: Rp {{ number_format($lapangPrice + $joinPrice,0,',','.') }}
            </span>
        @elseif($lapangPrice > 0)
            <span class="badge badge-cost">Lapang: Rp {{ number_format($lapangPrice,0,',','.') }}/orang</span>
        @elseif($joinPrice > 0)
            <span class="badge badge-cost">Join: Rp {{ number_format($joinPrice,0,',','.') }}</span>
        @else
            <span class="badge badge-free">GRATIS</span>
        @endif
    </div>
</div>


                                @if(!$isHost)
                                <div class="card-row">
                                    <div class="card-label">
                                        <i class="fas fa-user"></i>
                                        Host
                                    </div>
                                    <div class="card-value">
                                        {{ $creator->name ?? 'Tidak diketahui' }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="window.location.href='{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}'">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>

                                    {{-- TOMBOL SESUAIKAN BIAYA (HOST ONLY) --}}
                                    @if($canAdjustPrice && $playTogether->payment_type !== 'adjusted')
                                        <button class="btn-action" style="background:#FF9800;color:white;"
                                            onclick="recalculatePriceAlert({{ $playTogether->id }}, {{ (int) ($booking->amount ?? $booking->schedule->rental_price) }}, {{ (int) $playTogether->price_per_person }}, {{ $approvedParticipantsCount }}, {{ $playTogether->max_participants }})">
                                            <i class="fas fa-calculator"></i> Sesuaikan Biaya
                                        </button>
                                    @elseif($playTogether->payment_type === 'adjusted')
                                        <button class="btn-action" style="background:#BDBDBD;color:white;" disabled>
                                            <i class="fas fa-calculator"></i> Biaya Sudah Disesuaikan
                                        </button>
                                    @endif

                                    @if($userParticipant && $userParticipant->approval_status === 'approved')
                                        @php
                                            // ✅ MENGGUNAKAN MODEL METHODS (STANDARDIZED)
                                            $showPayButton = $booking->shouldShowPayButtonFor(auth()->id());
                                            $remainingToPay = $booking->getParticipantPaymentAmount(auth()->id());
                                            
                                            // Label tombol berdasarkan biaya
                                            $lapangFee = $booking->getLapangPerPerson();
                                            $joinFee = $booking->getJoinFee();
                                            
                                            $payLabel = 'Bayar Bagian Saya';
                                            if($lapangFee > 0 && $joinFee > 0) {
                                                $payLabel = 'Bayar Lapang + Join';
                                            } elseif($lapangFee > 0) {
                                                $payLabel = 'Bayar Lapang';
                                            } elseif($joinFee > 0) {
                                                $payLabel = 'Bayar Join';
                                            }
                                        @endphp

                                        @if($showPayButton && $remainingToPay > 0)
                                            <button class="btn-pay" onclick="payParticipant({{ $playTogether->id }})">
                                                <i class="fas fa-wallet"></i> {{ $payLabel }}
                                                <small style="display: block; font-size: 10px; margin-top: 2px;">
                                                    Rp {{ number_format($remainingToPay, 0, ',', '.') }}
                                                </small>
                                            </button>
                                        @endif
                                    @endif

                                    @php
                                        // Total bayar dari peserta yang sudah lunas
                                        $paidParticipantsCount = $playTogether->participants()
                                            ->where('approval_status', 'approved')
                                            ->where('payment_status', 'paid')
                                            ->count();

                                        $totalPaid = $paidParticipantsCount * $playTogether->price_per_person;

                                        // Total biaya yang harus dipenuhi untuk lapangan
                                        $bookingCost = $booking->total_price ?? ($playTogether->price_per_person * $playTogether->max_participants);

                                        $canShowQr = $booking->booking_payment === 'full';
                                    @endphp

                                    @if($canShowQr)
                                        <a href="{{ route('buyer.main_bareng_saya.qr', $playTogether->id) }}" 
                                            class="btn-show-qr">
                                            <i class="fas fa-qrcode"></i> Lihat QR
                                        </a>
                                    @endif

                                    @php
                                        $isHost = $playTogether->created_by == auth()->id();

                                        // Ambil semua peserta approved selain host dari query DB
                                        $participants = $playTogether->participants()
                                            ->where('approval_status', 'approved')
                                            ->where('user_id', '!=', $playTogether->created_by)
                                            ->get();

                                        if($participants->isEmpty()) {
                                            $allPaid = false; // belum ada yang bayar, jangan disable tombol
                                        } else {
                                            $pendingParticipants = $participants->filter(function($p){
                                                return $p->payment_status !== 'paid';
                                            })->count();

                                            $allPaid = $pendingParticipants === 0; // true kalau semua peserta sudah lunas
                                        }
                                    @endphp


                                    @if($isHost)
                                        @if(!$allPaid)
                                            <button class="btn-action btn-edit" onclick="window.location.href='{{ route('buyer.main_bareng_saya.edit', $playTogether->id) }}'">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn-action btn-delete" onclick="confirmDelete({{ $playTogether->id }}, '{{ $venue->venue_name }}')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        @else
                                            <button class="btn-action btn-edit" disabled title="Tidak bisa diedit, semua peserta sudah bayar">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn-action btn-delete" disabled title="Tidak bisa dihapus, semua peserta sudah bayar">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="no-data">
                        <i class="fas fa-futbol"></i>
                        <h3>Tidak ada Main Bareng</h3>
                        <p>
                            @if($filter === 'created')
                                Anda belum membuat main bareng apapun
                            @elseif($filter === 'joined')
                                Anda belum mengikuti main bareng yang disetujui
                            @else
                                Anda tidak memiliki pengajuan main bareng yang pending
                            @endif
                        </p>
                        @if($filter === 'created')
                            <button class="btn-add" onclick="window.location.href='{{ route('buyer.booking.index') }}'">
                                <i class="fas fa-plus"></i> Buat Main Bareng
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($playTogethers->hasPages())
                <div class="pagination">
                    @if($playTogethers->onFirstPage())
                        <span class="pagination-link disabled">‹</span>
                    @else
                        <a href="{{ $playTogethers->appends(['filter' => $filter, 'search' => $search])->previousPageUrl() }}" class="pagination-link">‹</a>
                    @endif

                    @foreach(range(1, $playTogethers->lastPage()) as $page)
                        @if($page == $playTogethers->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $playTogethers->appends(['filter' => $filter, 'search' => $search])->url($page) }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($playTogethers->hasMorePages())
                        <a href="{{ $playTogethers->appends(['filter' => $filter, 'search' => $search])->nextPageUrl() }}" class="pagination-link">›</a>
                    @else
                        <span class="pagination-link disabled">›</span>
                    @endif
                </div>
            @endif

            <!-- QR Modal -->
            <div class="qr-modal" id="qrModal">
                <div class="qr-content">
                    <div id="qrCodeContainer" style="margin-top: 10px;"></div>
                    <p id="qrText" style="margin-top:15px;font-weight:600"></p>
                </div>
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Confirm Delete Modal -->
        <div class="modal-overlay confirm-modal" id="confirmModal">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="confirm-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="confirm-text">
                        <h3 id="confirmTitle">Hapus Main Bareng</h3>
                        <p id="confirmMessage">Apakah Anda yakin ingin menghapus main bareng ini?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel" onclick="closeConfirmModal()">Batal</button>
                    <button class="btn-submit" id="confirmDeleteBtn" style="background: var(--danger);">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('buyer.main_bareng_saya.partials.script')
    
    {{-- SWEETALERT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- MIDTRANS SNAP JS --}}
    @php
        $snapJsUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>

    <script>
        /**
         * Recalculate Alert for Host
         */
        function recalculatePriceAlert(
            id,
            lapangPrice,
            joinPrice,
            currentParticipants,
            maxParticipants
        ) {
            // 🔥 TOTAL = LAPANG + (JOIN × PESERTA)
            const totalTarget = lapangPrice;

            // 🔥 BIAYA BARU PER ORANG
            const newPrice =
                Math.ceil(totalTarget / currentParticipants);
       
            Swal.fire({
                title: 'Sesuaikan Biaya Patungan?',
                html: `
                    <div style="text-align:left;font-size:0.9rem">
                        <p>Peserta saat ini <b>${currentParticipants}/${maxParticipants}</b></p>

                        <div style="background:#f8f9fa;padding:10px;border-radius:8px;margin:10px 0">
                            <div>Lapang: <b>Rp ${lapangPrice.toLocaleString('id-ID')}</b></div>
                            <div>Join: <b>Rp ${joinPrice.toLocaleString('id-ID')}</b> × ${currentParticipants}</div>
                            <hr>
                            <div>Total: <b>Rp ${totalTarget.toLocaleString('id-ID')}</b></div>
                        </div>

                        <div>
                            Biaya baru per orang:
                            <h3 style="color:#2E7D32">
                                Rp ${newPrice.toLocaleString('id-ID')}
                            </h3>
                        </div>

                        <p style="color:#d32f2f;font-weight:600">
                            * Semua pembayaran peserta akan di-reset ke PENDING.
                            Peserta yang sudah bayar hanya membayar selisih.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2E7D32',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sesuaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(`/buyer/main-bareng-saya/${id}/recalculate-price`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', `Biaya baru: Rp ${data.new_price.toLocaleString('id-ID')}`, 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                    });
                }
            });
        }

        /**
         * Participant Payment Logic
         */
        function payParticipant(playTogetherId) {
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            fetch(`/buyer/main-bareng-saya/${playTogetherId}/get-snap-token`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                updatePaymentStatus(playTogetherId, result);
                            },
                            onPending: function(result) {
                                updatePaymentStatus(playTogetherId, result);
                            },
                            onError: function(result) {
                                console.error(result);
                                showToast('Pembayaran gagal. Silakan coba lagi.', 'error');
                                btn.disabled = false;
                                btn.innerHTML = originalContent;
                            },
                            onClose: function() {
                                showToast('Pembayaran dibatalkan.', 'warning');
                                btn.disabled = false;
                                btn.innerHTML = originalContent;
                            }
                        });
                    } else {
                        showToast(data.message || 'Gagal memproses pembayaran.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan sistem.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                });
        }

        function updatePaymentStatus(playTogetherId, result) {
            fetch(`/buyer/main-bareng-saya/${playTogetherId}/update-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    status: result.transaction_status,
                    gross_amount: result.gross_amount || 0 // ambil jumlah yg dibayarkan
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Status pembayaran diperbarui.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message || 'Gagal memperbarui pembayaran.', 'error');
                }
            })
            .catch(err => console.error('Status update failed:', err));
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 
                         (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
            
            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <script>
    function showQr(code) {
        const qrModal = document.getElementById('qrModal');
        const qrContainer = document.getElementById('qrCodeContainer');
        const qrText = document.getElementById('qrText');

        qrModal.style.display = 'flex';
        qrContainer.innerHTML = ''; // Kosongkan sebelum generate QR baru
        qrText.innerText = code;

        new QRCode(qrContainer, {
            text: code,
            width: 200,
            height: 200
        });
    }

    // Tutup modal saat klik di area gelap
    const qrModal = document.getElementById('qrModal');
    qrModal.addEventListener('click', function(e) {
        if (e.target === this) { // klik hanya di overlay
            qrModal.style.display = 'none';
            document.getElementById('qrCodeContainer').innerHTML = '';
        }
    });

    // Klik konten QR → jangan tutup modal
    const qrContent = document.querySelector('.qr-content');
    qrContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    </script>

@endpush
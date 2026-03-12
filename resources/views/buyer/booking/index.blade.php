@extends('layouts.main', ['title' => 'My Bookings'])

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/booking-style.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<style>
.section-content,
.section-description {
    max-height: none !important;
    height: auto !important;
    overflow: visible !important;
}
</style>

<div class="mobile-container">
    @include('layouts.header')
    
    {{-- Loading Overlay --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Sedang Memproses...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, kami sedang memproses data pembayaran Anda.</div>
    </div>

    <main class="main-content" style="padding: 60px 0 100px 0;">
        <section class="page-header" style="padding: 100px 20px 30px 40px;">
            <h1 class="page-title">Bookingan Saya</h1>
            <p class="page-subtitle">Kelola semua booking lapangan Anda</p>
        </section>

        <div class="single-column-container">
            @forelse($bookings as $booking)
                <div class="section-card booking-card">
                    <div class="section-header">
                        <div>
                            <h3 class="section-title">{{ $booking->venue->venue_name }}</h3>
                            <p class="section-subtitle">{{ $booking->schedule->section->section_name }}</p>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            @if($booking->booking_payment === 'pending')
                                <a href="{{ route('buyer.booking.edit', $booking->id) }}" class="card-action-btn btn-edit-card">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('buyer.booking.destroy', $booking->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="card-action-btn btn-delete-card" onclick="return confirm('Yakin ingin menghapus booking ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="section-content">
                        <div class="detail-item">
                            <span class="detail-label">Tanggal</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Waktu</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}</span>
                        </div>

                        @php
                            $totalHargaTampil = $booking->amount;
                            $lapangParticipant = 0;
                            $joinFee = 0;

                            if (
                                in_array($booking->type, ['play_together', 'sparring']) &&
                                $booking->pay_by === 'participant' &&
                                $booking->playTogether
                            ) {
                                // lapang dibagi participant
                                $lapangParticipant = $booking->playTogether->price_per_person ?? 0;

                                // join fee kalau paid
                                if ($booking->playTogether->type === 'paid') {
                                    $joinFee = $booking->playTogether->price_per_person_input ?? 0;
                                    // kalau join fee lu simpan di field lain, ganti di sini
                                }

                                $totalHargaTampil = $lapangParticipant + $joinFee;
                            }
                        @endphp

                        <div class="detail-item">
                            <span class="detail-label">Total Harga</span>
                            <span class="detail-value">
                                Rp {{ number_format($booking->getDisplayTotalAmount(), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe Booking</span>
                            <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $booking->type)) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status Pembayaran</span>
                            <span class="status-badge 
                                @if($booking->booking_payment === 'full') badge-paid
                                @elseif($booking->booking_payment === 'partial') badge-confirmed
                                @else badge-pending
                                @endif">
                                <i class="fas 
                                    @if($booking->booking_payment === 'full') fa-check-circle
                                    @elseif($booking->booking_payment === 'partial') fa-clock
                                    @else fa-exclamation-circle
                                    @endif"></i>
                                {{ ucfirst($booking->booking_payment) }}
                            </span>
                        </div>
                        @if($booking->booking_payment === 'full')
                            <div class="detail-item">
                                <span class="detail-label">Ticket Code</span>
                                <span class="detail-value">{{ $booking->ticket_code }}
                                    <button class="btn-show-qr" onclick="showQr('{{ $booking->ticket_code }}')">
                                        <i class="fas fa-qrcode"></i> Show QR
                                    </button>
                                </span>
                            </div>
                        @endif
                    </div>
                    @php
                        $showBookingPayButton = false;
                        $showParticipantPayButton = false;

                        // REGULAR
                        if ($booking->type === 'regular' && $booking->booking_payment === 'pending') {
                            $showBookingPayButton = true;
                        }

                        // HOST BAYAR LAPANG
                        if (
                            in_array($booking->type, ['play_together', 'sparring']) &&
                            $booking->pay_by === 'host' &&
                            $booking->booking_payment === 'pending'
                        ) {
                            $showBookingPayButton = true;
                        }

                        // PARTICIPANT BAYAR
                        if (
                            in_array($booking->type, ['play_together', 'sparring']) &&
                            $booking->pay_by === 'participant' &&
                            $booking->booking_payment !== 'full'
                        ) {
                            $showParticipantPayButton = true;
                        }
                        @endphp

                    @if($showBookingPayButton)
                        <div class="booking-actions">
                            <button class="btn-pay" onclick="payNow({{ $booking->id }})">
                                <i class="fas fa-credit-card"></i>
                                Bayar Lapangan
                            </button>
                        </div>
                    @endif

                    @php
    $userParticipant = $booking->playTogether
        ? $booking->playTogether->participants
            ->where('user_id', auth()->id())
            ->first()
        : null;

    $showParticipantPayButton = false;

    if (
        in_array($booking->type, ['play_together', 'sparring']) &&
        $booking->pay_by === 'participant' &&
        $userParticipant &&
        $userParticipant->payment_status !== 'paid'
    ) {
        $showParticipantPayButton = true;
    }
@endphp

@if($showParticipantPayButton)
    <div class="booking-actions">
        <button class="btn-pay" onclick="payNow({{ $booking->id }})">
            <i class="fas fa-credit-card"></i>
            Bayar Bagian Saya
        </button>
    </div>
@endif


                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>Belum Ada Booking</h3>
                    <p>Anda belum memiliki booking apapun</p>
                    <a href="{{ route('buyer.venue.index') }}" class="btn-pay">
                        <i class="fas fa-plus-circle"></i>
                        Buat Booking Baru
                    </a>
                </div>
            @endforelse
        </div>

        <div class="qr-modal" id="qrModal" style="display:none">
            <div class="qr-content" id="qrContent">
                <div id="qrContainer"></div>
                <p id="qrText" style="margin-top:15px;font-weight:600"></p>
            </div>
        </div>

    </main>

    @include('layouts.bottom-nav')
</div>

<style>
.single-column-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 100px;
}

.section-card {
    width: 100%;
    margin-bottom: 0;
}

.booking-card {
    width: 100%;
    max-width: 100%;
}

.booking-actions {
    padding: 15px 20px;
    border-top: 1px solid var(--border);
}

.btn-pay {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
    text-decoration: none;
}

.btn-pay:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.empty-state i {
    font-size: 64px;
    color: var(--text-light);
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: var(--text);
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 25px;
}

.card-action-btn {
    background: transparent;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
    font-size: 16px;
}

.card-action-btn:hover {
    background: var(--background);
    color: var(--text);
}

.btn-edit-card:hover {
    color: var(--primary);
}

.btn-delete-card:hover {
    color: var(--danger);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-left: 8px;
}

.badge-paid {
    background: rgba(39, 174, 96, 0.1);
    color: var(--success);
}

.badge-pending {
    background: rgba(241, 196, 15, 0.1);
    color: var(--warning);
}

.badge-confirmed {
    background: rgba(52, 152, 219, 0.1);
    color: #3498db;
}

.badge-canceled {
    background: rgba(231, 76, 60, 0.1);
    color: var(--danger);
}

.qr-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    display: flex;
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

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .section-actions {
        margin-top: 10px;
    }
}

/* Loading Overlay Styles */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    color: white;
    text-align: center;
}
.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(255, 255, 255, 0.3);
    border-top: 5px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}
.loading-text {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
}
.loading-subtext {
    font-size: 12px;
    opacity: 0.8;
    max-width: 250px;
    line-height: 1.5;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

<script>
function showQr(code) {
    document.getElementById('qrModal').style.display = 'flex';
    document.getElementById('qrContainer').innerHTML = '';
    document.getElementById('qrText').innerText = code;

    new QRCode(document.getElementById("qrContainer"), {
        text: code,
        width: 200,
        height: 200
    });
}

qrModal.addEventListener('click', function () {
    qrModal.style.display = 'none';
});

qrContent.addEventListener('click', function (e) {
    e.stopPropagation();
});

// Loading Overlay Functions
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const bookingCards = document.querySelectorAll('.booking-card');
            
            bookingCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
</script>

@php
    $isProduction = $setting->midtrans_is_production ?? false;
    $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $clientKey = $setting->midtrans_client_key ?? '';
@endphp
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

<script>
function payNow(bookingId) {
    showLoading();
    fetch(`/buyer/booking/${bookingId}/generate-snap-token`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            const snapToken = data.snap_token;

            snap.pay(snapToken, {
                onSuccess: function(result){
                    showLoading();
                    // Kirim ke server untuk update status booking
                    fetch(`/buyer/booking/${bookingId}/update-payment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(resp => {
                        hideLoading();
                        if(resp.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran berhasil',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'Gagal update status booking', 'error');
                        }
                    });
                },
                onPending: function(result){
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran masih pending',
                        text: 'Selesaikan pembayaran di Midtrans',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                onError: function(result){
                    Swal.fire('Error', 'Terjadi kesalahan saat pembayaran', 'error');
                },
                onClose: function(){
                    console.log('Popup ditutup tanpa transaksi');
                }
            });

        } else {
            Swal.fire('Error', data.message || 'Gagal mendapatkan token Midtrans', 'error');
        }
    })
    .catch(err => {
        hideLoading();
        console.error(err);
        Swal.fire('Error', 'Terjadi kesalahan', 'error');
    });
}
</script>

@endpush
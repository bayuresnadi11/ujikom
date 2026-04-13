{{--
=============================================================================
VIEW: QR MAIN BARENG SAYA
Halaman untuk menampilkan QR Code dari main bareng yang dibuat (sebagai host)
QR Code digunakan untuk absensi peserta atau verifikasi kehadiran
=============================================================================
--}}

{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'QR Main Bareng Saya'])

{{-- Section konten utama --}}
@section('content')
<div class="container mt-5">
    {{-- Judul halaman menampilkan nama main bareng --}}
    <h2>QR Code untuk Main Bareng: {{ $playTogether->name }}</h2>

    {{-- Menampilkan kode tiket booking --}}
    <div class="mb-2">
        <strong>Ticket Code:</strong> 
        <span class="detail-value">{{ $booking->ticket_code }}</span>
    </div>

    {{-- Container untuk menampilkan QR Code (akan diisi oleh library QRCode.js) --}}
    <div id="qrcode"></div>

    {{-- Tombol kembali ke halaman daftar main bareng --}}
    <a href="{{ route('buyer.main_bareng_saya.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

{{-- Library QRCode.js untuk generate QR Code --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    /**
     * PEMBUATAN QR CODE
     * Menggabungkan URL halaman detail main bareng dengan parameter ticket code
     * Sehingga ketika di-scan, akan mengarah ke halaman verifikasi dengan kode tiket
     */
    
    // URL tujuan ketika QR Code di-scan
    // Format: halaman detail main bareng + parameter ticket kode
    var qrText = "{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}?ticket={{ $booking->ticket_code }}";

    // Generate QR Code menggunakan library QRCode.js
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: qrText,           // Teks/URL yang akan di-encode ke QR Code
        width: 200,             // Lebar QR Code dalam pixel
        height: 200,            // Tinggi QR Code dalam pixel
    });
</script>
@endsection
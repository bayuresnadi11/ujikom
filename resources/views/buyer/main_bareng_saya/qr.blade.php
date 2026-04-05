@extends('layouts.main', ['title' => 'QR Main Bareng Saya'])

@section('content')
<div class="container mt-5">
    <h2>QR Code untuk Main Bareng: {{ $playTogether->name }}</h2>

    <div class="mb-2">
        <strong>Ticket Code:</strong> 
        <span class="detail-value">{{ $booking->ticket_code }}</span>
    </div>

    <div id="qrcode"></div>

    <a href="{{ route('buyer.main_bareng_saya.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // gabung URL + ticket code atau pakai ticket code saja
    var qrText = "{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}?ticket={{ $booking->ticket_code }}";

    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: qrText,
        width: 200,
        height: 200,
    });
</script>
@endsection

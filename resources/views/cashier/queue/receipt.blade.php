<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $appSettings->company_name ?? config('app.name', 'Field Rental') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace, sans-serif;
            font-size: 11px;
            line-height: 1.2;
            color: #000;
            background: #fff;
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }
        
        /* Header */
        .receipt-header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }
        
        .store-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        
        .store-tagline {
            font-size: 9px;
            color: #555;
            margin-bottom: 5px;
        }
        
        .store-address {
            font-size: 9px;
            margin-bottom: 2px;
        }
        
        .store-contact {
            font-size: 9px;
            margin-bottom: 5px;
        }
        
        /* Transaction Info */
        .transaction-info {
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 80px;
        }
        
        .info-value {
            text-align: right;
            flex: 1;
        }
        
        /* Customer Info */
        .customer-info {
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }
        
        .customer-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
            text-decoration: underline;
        }
        
        /* QR Code Section */
        .qr-section {
            text-align: center;
            margin: 10px 0;
            padding: 10px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        
        .qr-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .qr-valid {
            font-size: 8px;
            color: #555;
            margin-top: 5px;
        }
        
        .qr-code {
            margin: 8px auto;
            width: 120px;
            height: 120px;
        }
        
        /* Booking Details */
        .booking-details {
            margin-bottom: 10px;
        }
        
        .booking-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
            text-align: center;
            text-decoration: underline;
        }
        
        .booking-item {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #ccc;
        }
        
        .venue-name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .field-info {
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .schedule-info {
            font-size: 10px;
            color: #333;
        }
        
        /* Payment Summary */
        .payment-summary {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #000;
            border-radius: 3px;
        }
        
        .summary-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            text-decoration: underline;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .total-row {
            font-weight: bold;
            font-size: 12px;
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px dashed #000;
        }
        
        .payment-method {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px dashed #ccc;
            font-size: 10px;
        }
        
        /* Footer */
        .receipt-footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px dashed #000;
            font-size: 9px;
        }
        
        .thank-you {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .app-info {
            margin-bottom: 3px;
        }
        
        .terms {
            font-size: 8px;
            color: #666;
            margin-top: 8px;
            line-height: 1.1;
        }
        
        /* Utilities */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-small { font-size: 9px; }
        .text-muted { color: #555; }
        
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        
        /* Print Styles */
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
    <!-- Include QR Code CSS jika diperlukan -->
</head>
<body>
    <!-- Print Button (only visible in browser) -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #198754; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Tutup
        </button>
    </div>

    <!-- Receipt Header -->
    <div class="receipt-header">
        <div class="store-name">{{ $appSettings->company_name ?? config('app.name', 'Field Rental') }}</div>
        <div class="store-tagline">{{ $appSettings->tagline ?? 'Penyewaan Lapangan Olahraga Profesional' }}</div>
        <div class="store-address">{{ $appSettings->address ?? 'Jl. Contoh No. 123, Kota Contoh' }}</div>
        <div class="store-contact">
            <i class="fas fa-phone"></i> {{ $appSettings->phone ?? '0812-3456-7890' }} | 
            <i class="fas fa-envelope"></i> {{ $appSettings->email ?? 'info@fieldrental.com' }}
        </div>
    </div>

    <!-- Transaction Info -->
    <div class="transaction-info">
        <div class="info-row">
            <span class="info-label">No. Tiket:</span>
            <span class="info-value">{{ $transaction->transaction_code }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kasir:</span>
            <span class="info-value">{{ $transaction->cashier_name ?? 'Kasir' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value text-bold">
                @if($transaction->bookings->first()->booking_payment === 'full')
                    LUNAS
                @elseif($transaction->bookings->first()->booking_payment === 'partial')
                    SEBAGIAN
                @else
                    PENDING
                @endif
            </span>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="customer-info">
        <div class="customer-title">INFORMASI PENYEWA</div>
        <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value">{{ $transaction->customer->name ?? 'Tidak diketahui' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Telepon:</span>
            <span class="info-value">{{ $transaction->customer->phone ?? 'Tidak diketahui' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Komunitas:</span>
            <span class="info-value">{{ $transaction->community ?? '-' }}</span>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-title">TIKET QR CODE</div>
        <div class="qr-code">
            <!-- Generate QR Code -->
            @php
                // Gunakan ticket code untuk QR
                $qrContent = $transaction->transaction_code;
            @endphp
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($qrContent) }}" 
                 alt="QR Code" 
                 style="width: 120px; height: 120px;">
        </div>
    </div>

    <!-- Booking Details -->
    <div class="booking-details">
        <div class="booking-title">DETAIL BOOKING</div>
        
        @foreach($transaction->bookings as $booking)
            @php
                $scheduleDate = $booking->booking_date ?? $booking->created_at->format('Y-m-d');
                $venueName = $booking->venue_name ?? ($booking->schedule->section->venue->venue_name ?? 'Venue');
                $sectionName = $booking->section_name ?? ($booking->schedule->section->section_name ?? 'Section');
            @endphp
            <div class="booking-item">
                <div class="venue-name">{{ $venueName }}</div>
                <div class="field-info">
                    <i class="fas fa-map-marker-alt"></i> Lapangan: {{ $sectionName }}
                    @if($booking->section && $booking->section->venue && $booking->section->venue->category)
                    ({{ $booking->section->venue->category->category_name ?? '' }})
                    @endif
                </div>
                <div class="schedule-info">
                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($scheduleDate)->format('d/m/Y') }} 
                    {{ $booking->start_time }} - {{ $booking->end_time }}
                    ({{ $booking->duration }} jam)
                </div>
            </div>
        @endforeach
    </div>

    <!-- Payment Summary -->
    <div class="payment-summary">
        <div class="summary-title">RINGKASAN PEMBAYARAN</div>
        
        @foreach($transaction->bookings as $booking)
        <div class="summary-row">
            <span>{{ $booking->section_name ?? 'Lapangan' }} {{ $loop->iteration }}</span>
            <span>Rp {{ number_format($booking->price, 0, ',', '.') }}</span>
        </div>
        @endforeach
        
        <div class="dashed-line"></div>
        
        <div class="summary-row total-row">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        
        <div class="payment-method">
            <div class="summary-row">
                <span>Metode Pembayaran:</span>
                <span class="text-bold">{{ strtoupper($transaction->payment_method) }}</span>
            </div>
            @if($transaction->payment_method == 'cash')
            <div class="summary-row">
                <span>Uang Diterima:</span>
                <span>Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="receipt-footer">
        <div class="thank-you">TERIMA KASIH ATAS KEPERCAYAAN ANDA</div>
        <div class="app-info">
            📱 {{ $appSettings->app_name ?? 'Sewalap' }}
        </div>
        <div class="terms">
            * Tiket berlaku hanya untuk tanggal dan jam yang tertera<br>
            * Show QR Code saat check-in di venue<br>
            * Hubungi admin untuk bantuan: {{ $appSettings->support_phone ?? '0812-3456-7890' }}
        </div>
    </div>

    <script>
        // Auto print ketika halaman dibuka
        window.onload = function() {
            @if($autoPrint)
            setTimeout(function() {
                window.print();
            }, 500);
            @endif
        };
        
        // Close window setelah print (jika diinginkan)
        window.onafterprint = function() {
            @if($autoClose)
            setTimeout(function() {
                window.close();
            }, 1000);
            @endif
        };
    </script>
</body>
</html>
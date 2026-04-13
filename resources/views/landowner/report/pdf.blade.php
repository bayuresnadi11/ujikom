<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Booking</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #064e3b;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 24px;
        }

        /* HEADER */
        .header {
            background: #0f766e;
            color: #ffffff;
            padding: 16px 20px;
            border-radius: 8px 8px 0 0;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        /* BOX */
        .box {
            border: 2px solid #0f766e;
            border-top: none;
            padding: 16px 20px;
            border-radius: 0 0 8px 8px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px 6px;
            vertical-align: top;
        }

        .label {
            width: 160px;
            font-weight: bold;
            color: #065f46;
            white-space: nowrap;
        }

        .colon {
            width: 10px;
            text-align: center;
            font-weight: bold;
            color: #065f46;
        }

        .value {
            color: #064e3b;
        }

        /* GARIS HR NYAMBUNG */
        .row-divider td {
            padding-bottom: 10px;
            border-bottom: 1px solid #d1d5db; /* abu tipis */
        }

        /* STATUS */
        .status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status.confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #065f46;
            text-align: right;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h2>Laporan Booking {{ $booking->venue->venue_name }}</h2>
    </div>

    <!-- BOX -->
    <div class="box">
        <table>
            <tr class="row-divider">
                <td class="label">Nama Penyewa</td>
                <td class="colon">:</td>
                <td class="value">{{ $booking->user->name }}</td>
            </tr>

            <tr class="row-divider">
                <td class="label">No Telepon</td>
                <td class="colon">:</td>
                <td class="value">{{ $booking->user->phone ?? '-' }}</td>
            </tr>
            
            <tr class="row-divider">
                <td class="label">Lapangan</td>
                <td class="colon">:</td>
                <td class="value">
                    {{ $booking->venue->venue_name }}
                    -
                    {{ $booking->schedule->venueSection->section_name ?? '-' }}
                </td>
            </tr>

            <tr class="row-divider">
                <td class="label">Tanggal</td>
                <td class="colon">:</td>
                <td class="value">
                    {{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('d M Y') }}
                </td>
            </tr>

            <tr class="row-divider">
                <td class="label">Jam</td>
                <td class="colon">:</td>
                <td class="value">
                    {{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }}
                    –
                    {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}
                </td>
            </tr>

            <tr class="row-divider">
                <td class="label">Harga</td>
                <td class="colon">:</td>
                <td class="value">
                    Rp {{ number_format($booking->schedule->rental_price,0,',','.') }}
                </td>
            </tr>

            <tr>
                <td class="label">Tiket Kode</td>
                <td class="colon">:</td>
                <td class="value">
                    <span class="ticket-code">
                        {{ $booking->ticket_code ?? '-' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }}
    </div>

</div>
</body>
</html>

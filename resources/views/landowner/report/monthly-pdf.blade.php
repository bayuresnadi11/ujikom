<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>

    <style>
        /* Mengatur font dan ukuran teks */
        body { font-family: DejaVu Sans; font-size:12px; }

        /* Mengatur tampilan tabel */
        table { width:100%; border-collapse: collapse; margin-top:10px; }

        /* Mengatur border dan padding pada tabel */
        th, td { border:1px solid #ccc; padding:6px; text-align:left; }

        /* Mengatur header tabel */
        th { background:#0f766e; color:white; }

        /* Mengatur isi baris tabel agar rata tengah */
        tr {text-align: center;}
    </style>
</head>
<body>

<!-- Judul laporan bulanan -->
<h2>Laporan Bulanan - {{ $monthName }}</h2>

<!-- Menampilkan total booking -->
<p>Total Booking: <strong>{{ $totalBooking }}</strong></p>

<!-- Menampilkan total pendapatan dengan format rupiah -->
<p>Total Pendapatan: 
    <strong>Rp {{ number_format($totalPendapatan,0,',','.') }}</strong>
</p>

<!-- Tabel data booking -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Lapangan</th>
            <th>Tanggal</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        <!-- Perulangan data booking -->
        @foreach($bookings as $b)
        <tr>

            <!-- Nomor urut otomatis -->
            <td>{{ $loop->iteration }}</td>

            <!-- Nama user yang booking -->
            <td>{{ $b->user->name }}</td>                  

            <!-- Nama lapangan dan section -->
            <td>
                {{ $b->venue->venue_name }} - 
                {{ $b->schedule->section->section_name ?? '-' }}
            </td>

            <!-- Format tanggal -->
            <td>
                {{ \Carbon\Carbon::parse($b->schedule->date)->format('d M Y') }}
            </td>

            <!-- Total pembayaran -->
            <td>
                Rp {{ number_format($b->total_paid ?? $b->amount ?? 0,0,',','.') }}
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
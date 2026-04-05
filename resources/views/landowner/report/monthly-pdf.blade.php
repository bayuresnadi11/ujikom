<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>
    <style>
        body { font-family: DejaVu Sans; font-size:12px; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #ccc; padding:6px; text-align:left; }
        th { background:#0f766e; color:white; }
        tr {text-align: center;}
    </style>
</head>
<body>

<h2>Laporan Bulanan - {{ $monthName }}</h2>

<p>Total Booking: <strong>{{ $totalBooking }}</strong></p>
<p>Total Pendapatan: 
    <strong>Rp {{ number_format($totalPendapatan,0,',','.') }}</strong>
</p>

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
        @foreach($bookings as $b)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $b->user->name }}</td>                  
            <td>{{ $b->venue->venue_name }} - {{ $b->schedule->section->section_name ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($b->schedule->date)->format('d M Y') }}</td>
            <td>Rp {{ number_format($b->total_paid ?? $b->amount ?? 0,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
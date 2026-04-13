@extends('layouts.main', ['title' => 'Laporan Lapangan'])

@push('styles')
    @include('landowner.report.partials.report-style')
    <style>
        /* ===============================
        MONTHLY DOWNLOAD SECTION
        =================================*/

        .monthly-download {
            display: flex;
            align-items: end;
            gap: 12px;
            margin: 18px 0 20px 0;
            flex-wrap: wrap;
        }

        .monthly-field {
            display: flex;
            flex-direction: column;
        }

        .monthly-field label {
            font-size: 13px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #065f46;
        }

        .month-select {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            min-width: 160px;
            transition: 0.2s ease;
        }

        .month-select:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 2px rgba(15,118,110,0.15);
        }

        /* CARD CONTAINER */
        .history-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            margin-bottom: 14px;
            transition: 0.2s ease;
        }

        .history-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        /* LEFT (AVATAR) */
        .card-left .avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }

        /* MIDDLE */
        .card-middle {
            flex: 1;
            min-width: 0;
        }

        /* NAME */
        .user-name {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 2px;
        }

        /* PHONE */
        .user-phone {
            font-size: 12.5px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        /* VENUE */
        .venue-name {
            font-size: 13px;
            font-weight: 500;
            color: #0f766e;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* DATE */
        .booking-date {
            font-size: 12px;
            color: #9ca3af;
        }

        /* RIGHT */
        .card-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        /* PRICE */
        .price {
            font-size: 14px;
            font-weight: 700;
            color: #16a34a;
        }

        /* BUTTON */
        .btn-download-month {
            background: #0f766e;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-download-month:hover {
            background: #0d5f59;
            transform: translateY(-1px);
        }

        .btn-download-month:active {
            transform: scale(0.98);
        }
    </style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="mobile-container">

    <!-- ================= HEADER ================= -->
    @include('layouts.header')

    <!-- ================= MAIN ================= -->
    <main class="main-content"> 

        <!-- TITLE -->
        <section class="page-header">
            <h1 class="page-title">Laporan Lapangan</h1>
            <p class="page-subtitle">Riwayat & analitik booking lapangan</p>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('landowner.report-lapangan.index') }}" class="search-form" style="margin-top: 16px;">
                <div class="search-input-wrapper" style="position: relative; display: flex; align-items: center;">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Cari booking..." 
                        class="search-input"
                        style="width: 100%; padding: 10px 40px 10px 16px; border-radius: 8px; border: 1px solid #ddd; font-size: 14px;"
                    >
                    <button type="submit" style="position: absolute; right: 8px; background: none; border: none; cursor: pointer; color: #666;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </section>

        <!-- ================= TAB BUTTON ================= -->
        <div class="report-tabs">
            <button class="tab-btn active" data-tab="history">
                <i class="fas fa-list"></i> Laporan
            </button>
            <button class="tab-btn" data-tab="analytics">
                <i class="fas fa-chart-bar"></i> Analitik
            </button>
        </div>

        <!-- ================= TAB LAPORAN ================= -->
        <section id="history" class="tab-content active history-list">
            {{-- Loop untuk menampilkan semua data riwayat transaksi/booking untuk laporan --}}
            @forelse($bookings as $booking)
                <div class="history-card">

                    <div class="card-left">
                        <img
                            src="{{ $booking->user->avatar ?? asset('images/default-avatar.png') }}"
                            class="avatar"
                            alt="avatar"
                        >
                    </div>

                    <div class="card-middle">
                        <h3 class="user-name">{{ $booking->user->name }}</h3>

                        <p class="user-phone">
                            {{ $booking->user->phone ?? '-' }}
                        </p>

                        {{-- Menampilkan nama venue beserta bagian lapangannya --}}
                        <p class="venue-name">
                            {{ $booking->venue->venue_name }} -
                            {{ $booking->schedule->venueSection->section_name ?? 'N/A' }}
                        </p>

                        {{-- Memformat tanggal dan jam mulai-selesai menggunakan Carbon --}}
                        <p class="booking-date">
                            {{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('d M Y') }}
                            |
                            {{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}
                        </p>
                    </div>


                    <div class="card-right">
                        <a href="{{ route('landowner.report-lapangan.pdf', $booking->id) }}" class="btn-download-pdf" title="Unduh laporan">
                            <i class="fas fa-download"></i>
                        </a>

                        <p class="price">
                            Rp {{ number_format($booking->total_paid ?? $booking->amount ?? 0, 0, ',', '.') }}
                        </p>

                    </div>

                </div>
            @empty
                <p class="no-data">Laporan tidak tersedia.</p>
            @endforelse

            {{-- Pagination --}}
            <div class="pagination flex justify-center mt-4">
                {{ $bookings->links('pagination::simple-default') }}
            </div>

            <p class="no-data" style="display:none">Data tidak ditemukan</p>
        </section>

        <!-- ================= TAB ANALYTICS ================= -->
        <section id="analytics" class="tab-content">
            <div class="analytics-box">
                <h3 class="analytics-title">Ringkasan Analitik</h3>
               
                <div class="monthly-download">
                    <div class="monthly-field">
                        <label for="bulan">Pilih Bulan</label>
                        <select id="bulan" class="month-select">
                            @foreach(range(1,12) as $m)
                                <option value="{{ $m }}">
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button onclick="downloadBulanan()" class="btn-download-month">
                        <i class="fas fa-download"></i>
                    </button>
                </div>

                <script>
                function downloadBulanan(){
                    let month = document.getElementById('bulan').value;
                    window.location.href = 
                        "{{ url('landowner/report-lapangan/bulanan') }}/" + month;
                }
                </script>
                <ul class="analytics-list">
                    <li>
                        <span>Total Booking</span>
                        <strong>{{ $allBookings->count() }}</strong>
                    </li>

                    <li>
                        <span>Total Pendapatan</span>
                        <strong>
                            Rp {{ number_format($allBookings->sum(fn($b) => $b->total_paid ?? $b->amount ?? 0), 0, ',', '.') }}
                        </strong>
                    </li>

                    <li>
                        <span>Rata-rata per Booking</span>
                        <strong>
                            Rp {{ number_format($allBookings->count() > 0 ? $allBookings->sum(fn($b) => $b->total_paid ?? $b->amount ?? 0) / $allBookings->count() : 0, 0, ',', '.') }}
                        </strong>
                    </li>
                </ul>

                <!-- BUTTON -->
                <button id="toggleChart" class="btn-chart">
                    Lihat Grafik Bulanan
                </button>

                <!-- CHART -->
                <div id="chartWrapper" style="display:none; margin-top:16px;">
                    <canvas id="monthlyChart" height="120"></canvas>
                </div>
            </div>
        </section>


        
    </main>

    <!-- ================= BOTTOM NAV ================= -->
    @include('layouts.bottom-nav')

</div>
@endsection

@push('scripts')
    @include('landowner.report.partials.report-script')
@endpush
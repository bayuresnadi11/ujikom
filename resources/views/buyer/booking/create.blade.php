{{--
=============================================================================
VIEW: CREATE BOOKING
Tampilan untuk membuat booking baru (Regular / Main Bareng / Sparring)
=============================================================================
--}}

{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'Create Booking'])

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling booking dari partial --}}
    @include('buyer.booking.partials.booking-style')
    <style>
        /* ===== MAIN CONTENT ===== */
        /* Area konten utama dengan padding untuk menghindari fixed header dan bottom nav */
        .main-content {
            padding-top: 70px;           /* tinggi header fixed */
            padding-bottom: 90px;        /* tinggi bottom nav */
            min-height: calc(100vh - 70px - 90px);
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            padding: 20px 16px 0;
            margin-bottom: 20px;
        }

        /* Judul halaman dengan gradien teks */
        .page-title {
            font-size: 28px;
            font-weight: 900;
            color: #0A5C36;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #0A5C36, #2ECC71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6C757D; /* text-light */
            line-height: 1.4;
            margin: 0;
            font-weight: 500;
        }

        /* ===================== BOOKING INFO CARDS ===================== */
        /* Container untuk kartu informasi venue dan lapangan */
        .booking-info-cards {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            margin-bottom: 15px; /* jarak bawah */
            flex-wrap: wrap;
        }

        /* ===================== CARD ITEM ===================== */
        /* Kartu informasi individual */
        .info-card-item {
            flex: 1;
            min-width: 160px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        /* Efek hover pada kartu informasi */
        .info-card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
            border-color: #d1d5db;
        }

        /* ===================== LABEL ===================== */
        .info-card-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* Icon di label */
        .info-card-label i {
            color: #3b82f6;
            font-size: 14px;
        }

        /* ===================== VALUE ===================== */
        .info-card-value {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            word-break: break-word;
        }
    </style>
    
    {{-- CSS tambahan untuk halaman booking dan SweetAlert2 --}}
    <link rel="stylesheet" href="{{ asset('/css/booking-style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="mobile-container">
        {{-- Header - include dari layout terpisah --}}
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            {{-- ====================== HEADER HALAMAN ====================== --}}
            <section class="page-header">
                <h1 class="page-title">Buat Booking Baru</h1>
                <p class="page-subtitle">Isi form untuk membuat booking lapangan</p>
            </section>

            <div style="padding: 0 20px;">
                {{-- ====================== MENAMPILKAN ERROR VALIDASI ====================== --}}
                {{-- Jika ada error validasi dari Laravel, tampilkan di sini --}}
                @if ($errors->any())
                    <div style="background: #fee; border: 1px solid #fcc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li style="color: #c00;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="booking-form-container">
                    <!-- ====================== FORM FILTER TANGGAL ====================== -->
                    {{-- Form untuk memfilter jadwal berdasarkan tanggal --}}
                    <form method="GET" action="{{ route('buyer.booking.create') }}" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
                        <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                        <input type="hidden" name="section_id" value="{{ $section->id }}">
                        <input type="date" name="booking_date" value="{{ request('booking_date', date('Y-m-d')) }}" class="form-control" style="max-width: 200px;">
                        <button type="submit" class="btn-form btn-form-primary" style="padding: 8px 16px;">Filter</button>
                    </form>

                    {{-- ====================== FORM UTAMA BOOKING ====================== --}}
                    <form action="{{ route('buyer.booking.store') }}" method="POST" id="bookingForm">
                        @csrf

                        {{-- Hidden fields untuk venue dan section (dari parameter URL) --}}
                        <input type="hidden" name="venue_id" value="{{ $venue->id ?? '' }}" id="venue_id">
                        <input type="hidden" name="section_id" value="{{ $section->id ?? '' }}" id="section_id">

                        {{-- ====================== KARTU INFORMASI VENUE & SECTION ====================== --}}
                        <div class="booking-info-cards">
                            <div class="info-card-item">
                                <span class="info-card-label"><i class="fas fa-building"></i> Venue</span>
                                <span class="info-card-value">{{ $venue->venue_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-card-item">
                                <span class="info-card-label"><i class="fas fa-running"></i> Lapangan</span>
                                <span class="info-card-value">{{ $section->section_name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        {{-- ====================== PEMILIHAN JADWAL ====================== --}}
                        <div class="form-group">
                            <label class="form-label">Pilih Jadwal <span style="color: red;">*</span></label>
                            
                            <div class="schedule-cards-container" id="scheduleCardsContainer">
                                {{-- Looping menampilkan semua jadwal yang tersedia --}}
                                @forelse($schedules as $schedule)
                                    <div class="schedule-card" 
                                        data-schedule-id="{{ $schedule->id }}" 
                                        data-price="{{ $schedule->rental_price }}"
                                        data-date="{{ $schedule->date }}"
                                        data-start="{{ $schedule->start_time }}"
                                        data-end="{{ $schedule->end_time }}"
                                        data-duration="{{ $schedule->rental_duration }}"
                                        onclick="selectSchedule(this)">
                                        <div class="schedule-date">
                                            <div class="date-day">{{ \Carbon\Carbon::parse($schedule->date)->format('d') }}</div>
                                            <div class="date-month">{{ \Carbon\Carbon::parse($schedule->date)->format('M') }}</div>
                                        </div>
                                        <div class="schedule-details">
                                            <div class="schedule-time">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                                            <div class="schedule-price">Rp {{ number_format($schedule->rental_price, 0, ',', '.') }}</div>
                                            <span class="schedule-status available">Tersedia</span>
                                        </div>
                                    </div>
                                @empty
                                    <p style="text-align: center; color: var(--text-light);">Tidak ada jadwal tersedia</p>
                                @endforelse
                            </div>
                            {{-- Hidden input untuk menyimpan ID jadwal yang dipilih --}}
                            <input type="hidden" name="schedule_id" id="schedule_id">
                            @error('schedule_id')
                                <span style="color: red; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- ====================== KARTU INFO JADWAL TERPILIH ====================== --}}
                        {{-- Kartu yang muncul setelah user memilih jadwal --}}
                        <div id="scheduleInfoCard" style="display: none;" class="schedule-info-card">
                            <h4><i class="fas fa-info-circle"></i> Detail Jadwal Terpilih</h4>
                            <div class="schedule-details-grid">
                                <div class="detail-item">
                                    <div class="detail-label">Tanggal</div>
                                    <div class="detail-value" id="selectedDate">-</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Waktu</div>
                                    <div class="detail-value" id="selectedTime">-</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Durasi</div>
                                    <div class="detail-value" id="selectedDuration">-</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Harga</div>
                                    <div class="detail-value" id="selectedPrice">-</div>
                                </div>
                            </div>
                        </div>

                        {{-- ====================== TIPE BOOKING ====================== --}}
                        <div class="form-group">
                            <label class="form-label">Tipe Booking <span style="color: red;">*</span></label>
                            <select class="form-control" name="type" id="bookingType" onchange="toggleBookingType()">
                                <option value="">Pilih Tipe</option>
                                <option value="regular">Regular</option>
                                <option value="play_together">Main Bareng</option>
                            </select>
                            @error('type')
                                <span style="color: red; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- ====================== PEMBAYARAN OLEH (HOST/PARTICIPANT) ====================== --}}
                        {{-- Section ini muncul untuk tipe play_together atau sparring --}}
                        <div id="payBySection" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Lapangan Dibayar Oleh <span style="color: red;">*</span></label>
                                <select class="form-control" name="pay_by" id="payBy" onchange="updateCalculator()">
                                    <option value="host">Host (Saya bayar semua)</option>
                                    <option value="participant">Participant (Dibagi participant)</option>
                                </select>
                            </div>
                        </div>

                        {{-- ====================== SECTION PLAY TOGETHER ====================== --}}
                        {{-- Form khusus untuk tipe Main Bareng --}}
                        <div id="playTogetherSection" class="form-section" style="display: none;">
                            <div class="form-section-header">
                                <i class="fas fa-users"></i> Informasi Main Bareng
                            </div>

                            {{-- Nama Main Bareng --}}
                            <div class="form-group">
                                <label class="form-label">Nama Main Bareng <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="pt_name" id="pt_name" placeholder="Masukkan nama play together" value="{{ old('pt_name') }}">
                                @error('pt_name')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Privasi (Public/Private/Community) --}}
                            <div class="form-group">
                                <label class="form-label">Privasi <span style="color: red;">*</span></label>
                                <select class="form-control" name="pt_privacy" id="pt_privacy" onchange="toggleCommunitySelect()">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="community">Community</option>
                                </select>
                            </div>

                            {{-- Dropdown komunitas (hanya muncul jika privacy = community) --}}
                            <div class="form-group" id="pt_community_group" style="display: none;">
                                <label class="form-label">Pilih Komunitas <span style="color: red;">*</span></label>
                                <select class="form-control" name="pt_community_id" id="pt_community_id">
                                    <option value="">Pilih Komunitas</option>
                                    @foreach($myCommunities as $community)
                                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                                    @endforeach
                                </select>
                                @error('pt_community_id')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Maksimal Participant --}}
                            <div class="form-group">
                                <label class="form-label">Maksimal Participant <span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="pt_max_participants" id="pt_max_participants" min="2" placeholder="Contoh: 10" value="{{ old('pt_max_participants') }}" onchange="updateCalculator()">
                                @error('pt_max_participants')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Metode Pembayaran Main Bareng (Free/Paid) --}}
                            <div class="form-group">
                                <label class="form-label">Metode Pembayaran Main Bareng <span style="color: red;">*</span></label>
                                <select class="form-control" name="pt_type" id="pt_type" onchange="togglePricePerPerson()">
                                    <option value="free">Gratis</option>
                                    <option value="paid">Berbayar</option>
                                </select>
                            </div>

                            {{-- Harga per person (hanya jika paid) --}}
                            <div class="form-group" id="pt_price_group" style="display: none;">
                                <label class="form-label">Biaya per Orang<span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="pt_price_per_person" id="pt_price_per_person" min="0" placeholder="Contoh: 50000" value="{{ old('pt_price_per_person') }}" onchange="updateCalculator()">
                                @error('pt_price_per_person')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- ====================== KALKULATOR BIAYA ====================== --}}
                            {{-- Kalkulator untuk menghitung biaya per participant --}}
                            <div id="calculatorHelper" class="schedule-info-card" style="display: none;">
                                <h4><i class="fas fa-calculator"></i> Kalkulator Biaya</h4>
                                <div class="schedule-details-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Biaya Booking</div>
                                        <div class="detail-value" id="calc_booking_cost">Rp 0</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Jumlah Participant</div>
                                        <div class="detail-value" id="calc_participant_count">0</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Lapang per Person</div>
                                        <div class="detail-value" id="calc_booking_per_person">Rp 0</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Join per Person</div>
                                        <div class="detail-value" id="calc_price_per_person">Rp 0</div>
                                    </div>
                                    <div class="detail-item" style="grid-column: 1 / -1; background: rgba(39, 174, 96, 0.1); border: 2px solid var(--primary);">
                                        <div class="detail-label">Total per Person</div>
                                        <div class="detail-value" style="color: var(--primary); font-size: 18px;" id="calc_total_per_person">Rp 0</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Batas Pembayaran --}}
                            <div class="form-group">
                                <label class="form-label">Batas Pembayaran</label>
                                <input type="datetime-local" class="form-control" name="pt_payment_deadline" value="{{ old('pt_payment_deadline') }}">
                            </div>

                            {{-- Jenis Kelamin yang diizinkan --}}
                            <div class="form-group">
                                <label class="form-label">
                                    Jenis Kelamin <span style="color: red;">*</span>
                                </label>
                                <select class="form-control" name="pt_gender" id="pt_gender" required>
                                    <option value="Campur" {{ old('pt_gender') == 'Campur' ? 'selected' : '' }}>Campur</option>
                                    <option value="Laki-laki" {{ old('pt_gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('pt_gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="pt_description" rows="3" placeholder="Deskripsi play together">{{ old('pt_description') }}</textarea>
                            </div>

                            {{-- Perlu persetujuan host --}}
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="pt_host_approval" value="1" {{ old('pt_host_approval') ? 'checked' : '' }}>
                                    Memerlukan persetujuan host untuk bergabung
                                </label>
                            </div>

                            {{-- Sertakan host sebagai participant --}}
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="pt_include_host" value="1" id="pt_include_host" {{ old('pt_include_host') ? 'checked' : '' }}>
                                    Sertakan saya sebagai participant
                                </label>
                            </div>
                        </div>

                        {{-- ====================== SECTION SPARRING ====================== --}}
                        {{-- Form khusus untuk tipe Sparring (dinonaktifkan/di-comment pada tampilan ini) --}}
                        <div id="sparringSection" class="form-section" style="display: none;">
                            <div class="form-section-header">
                                <i class="fas fa-trophy"></i> Informasi Sparring
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Sparring <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="sp_name" id="sp_name" placeholder="Masukkan nama sparring" value="{{ old('sp_name') }}">
                                @error('sp_name')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Privasi <span style="color: red;">*</span></label>
                                <select class="form-control" name="sp_privacy" id="sp_privacy">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="community">Community</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Metode Pembayaran Main Bareng <span style="color: red;">*</span></label>
                                <select class="form-control" name="sp_type" id="sp_type" onchange="toggleSparringCost()">
                                    <option value="free">Gratis</option>
                                    <option value="paid">Berbayar</option>
                                </select>
                            </div>

                            {{-- Biaya per participant (hanya jika paid) --}}
                            <div class="form-group" id="sp_cost_group" style="display: none;">
                                <label class="form-label">Biaya Per Participant <span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="sp_cost_per_participant" id="sp_cost_per_participant" min="0" placeholder="Contoh: 50000" value="{{ old('sp_cost_per_participant') }}">
                                @error('sp_cost_per_participant')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="sp_description" rows="3" placeholder="Deskripsi sparring">{{ old('sp_description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="sp_host_approval" value="1" {{ old('sp_host_approval') ? 'checked' : '' }}>
                                    Memerlukan persetujuan host untuk bergabung
                                </label>
                            </div>
                        </div>

                        {{-- ====================== TOMBOL AKSI ====================== --}}
                        <div class="form-actions">
                            <button type="button" class="btn-form btn-form-secondary" onclick="window.history.back()">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn-form btn-form-primary" id="submitBtn">
                                <i class="fas fa-check"></i>
                                Buat Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        {{-- Bottom Navigation Bar --}}
        @include('layouts.bottom-nav')
    </div>

    {{-- ====================== STYLE TAMBAHAN ====================== --}}
    <style>
        /* Container form booking utama */
        .booking-form-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 100px;
            border: 1px solid var(--border);
        }

        /* Group form field */
        .form-group {
            margin-bottom: 25px;
        }

        /* Label form */
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        /* Input form control */
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--text);
            background: var(--card-bg);
            transition: all 0.3s ease;
        }

        /* Focus state untuk input */
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        /* State error untuk input */
        .form-control.error {
            border-color: #E74C3C;
        }

        /* Pesan error */
        .error-message {
            color: #E74C3C;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Container kartu jadwal */
        .schedule-cards-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 10px;
        }

        /* Kartu jadwal individual */
        .schedule-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Hover effect kartu jadwal */
        .schedule-card:hover {
            transform: translateX(5px);
            border-color: var(--primary);
        }

        /* State selected untuk kartu jadwal */
        .schedule-card.selected {
            border-color: var(--primary);
            background: rgba(39, 174, 96, 0.05);
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.2);
        }

        /* Bagian tanggal pada kartu jadwal */
        .schedule-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .date-day {
            font-size: 20px;
            font-weight: 700;
        }

        .date-month {
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Detail jadwal */
        .schedule-details {
            flex: 1;
        }

        .schedule-time {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 5px;
        }

        .schedule-price {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 5px;
        }

        /* Status ketersediaan */
        .schedule-status {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
        }

        .schedule-status.available {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .schedule-status.unavailable {
            background: rgba(231, 76, 60, 0.1);
            color: #E74C3C;
        }

        /* Kartu jadwal tidak tersedia */
        .schedule-card.unavailable {
            opacity: 0.7;
            background: #f8f9fa;
            border-color: #dee2e6;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .schedule-date.unavailable-date {
            background: #95a5a6;
        }

        /* Kartu informasi jadwal terpilih */
        .schedule-info-card {
            background: rgba(39, 174, 96, 0.05);
            border: 1px solid rgba(39, 174, 96, 0.2);
            border-radius: var(--radius);
            padding: 20px;
            margin: 20px 0;
        }

        .schedule-info-card h4 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--primary);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Grid detail jadwal */
        .schedule-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .detail-item {
            background: white;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .detail-label {
            font-size: 11px;
            color: var(--text-light);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        /* Section form untuk Play Together / Sparring */
        .form-section {
            background: rgba(39, 174, 96, 0.03);
            border-radius: var(--radius);
            padding: 20px;
            margin: 20px 0;
            border: 1px solid rgba(39, 174, 96, 0.1);
        }

        .form-section-header {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Tombol aksi form */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn-form {
            flex: 1;
            padding: 16px;
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
        }

        .btn-form-secondary {
            background: var(--background);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-form-secondary:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }

        .btn-form-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-form-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        /* Checkbox styling */
        .form-group label input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .schedule-details-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-form {
                width: 100%;
            }
        }
    </style>
@endsection

{{-- ====================== JAVASCRIPT ====================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ====================== VARIABEL GLOBAL ======================
let selectedScheduleData = null;    // Menyimpan data jadwal yang dipilih
let selectedSchedulePrice = 0;      // Menyimpan harga jadwal yang dipilih

// ====================== FUNGSI PILIH JADWAL ======================
// Fungsi ini dipanggil ketika user mengklik kartu jadwal
function selectSchedule(element) {
    // Hapus class 'selected' dari semua kartu jadwal
    document.querySelectorAll('.schedule-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Tambahkan class 'selected' pada kartu yang diklik
    element.classList.add('selected');
    
    // Ambil data dari atribut data-* pada elemen
    const scheduleId = element.getAttribute('data-schedule-id');
    const price = parseFloat(element.getAttribute('data-price'));
    const date = element.getAttribute('data-date');
    const startTime = element.getAttribute('data-start');
    const endTime = element.getAttribute('data-end');
    const duration = element.getAttribute('data-duration');
    
    // Simpan ke hidden input dan variabel global
    document.getElementById('schedule_id').value = scheduleId;
    selectedSchedulePrice = price;
    
    // Update tampilan kartu info jadwal
    const dateObj = new Date(date);
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('selectedDate').textContent = dateObj.toLocaleDateString('id-ID', options);
    document.getElementById('selectedTime').textContent = startTime.substring(0, 5) + ' - ' + endTime.substring(0, 5);
    document.getElementById('selectedDuration').textContent = duration + ' jam';
    document.getElementById('selectedPrice').textContent = 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('scheduleInfoCard').style.display = 'block';
    
    // Update kalkulator biaya
    updateCalculator();
    
    console.log('Schedule selected:', scheduleId, price);
}

// ====================== FUNGSI TOGGLE TIPE BOOKING ======================
// Menampilkan/menyembunyikan section berdasarkan tipe booking yang dipilih
function toggleBookingType() {
    const type = document.getElementById('bookingType').value;
    const payBySection = document.getElementById('payBySection');
    const playTogetherSection = document.getElementById('playTogetherSection');
    const sparringSection = document.getElementById('sparringSection');
    
    // Sembunyikan semua section terlebih dahulu
    payBySection.style.display = 'none';
    playTogetherSection.style.display = 'none';
    sparringSection.style.display = 'none';
    
    // Tampilkan section sesuai tipe yang dipilih
    if (type === 'play_together') {
        payBySection.style.display = 'block';
        playTogetherSection.style.display = 'block';
    } else if (type === 'sparring') {
        payBySection.style.display = 'block';
        sparringSection.style.display = 'block';
    }
    
    updateCalculator();
}

// ====================== FUNGSI TOGGLE KOMUNITAS ======================
// Menampilkan dropdown komunitas hanya jika privacy dipilih 'community'
function toggleCommunitySelect() {
    const privacy = document.getElementById('pt_privacy').value;
    const communityGroup = document.getElementById('pt_community_group');
    
    if (privacy === 'community') {
        communityGroup.style.display = 'block';
    } else {
        communityGroup.style.display = 'none';
    }
}

// ====================== FUNGSI TOGGLE HARGA PER PERSON ======================
// Menampilkan input harga per person hanya jika metode pembayaran 'paid'
function togglePricePerPerson() {
    const type = document.getElementById('pt_type').value;
    const priceGroup = document.getElementById('pt_price_group');
    
    if (type === 'paid') {
        priceGroup.style.display = 'block';
    } else {
        priceGroup.style.display = 'none';
    }
    
    updateCalculator();
}

// ====================== FUNGSI TOGGLE BIAYA SPARRING ======================
// Menampilkan input biaya per participant sparring hanya jika 'paid'
function toggleSparringCost() {
    const type = document.getElementById('sp_type').value;
    const costGroup = document.getElementById('sp_cost_group');
    
    if (type === 'paid') {
        costGroup.style.display = 'block';
    } else {
        costGroup.style.display = 'none';
    }
}

// ====================== FUNGSI UPDATE KALKULATOR ======================
// Menghitung dan menampilkan rincian biaya per participant untuk PlayTogether
function updateCalculator() {
    const bookingType = document.getElementById('bookingType').value;
    const payBy = document.getElementById('payBy')?.value;
    const ptType = document.getElementById('pt_type')?.value;

    const maxParticipants =
        parseInt(document.getElementById('pt_max_participants')?.value) || 0;

    const pricePerPerson =
        parseInt(document.getElementById('pt_price_per_person')?.value) || 0;

    const calculatorHelper = document.getElementById('calculatorHelper');

    // Validasi: hanya tampilkan kalkulator untuk PlayTogether dengan data lengkap
    if (
        bookingType !== 'play_together' ||
        maxParticipants <= 0 ||
        selectedSchedulePrice <= 0
    ) {
        calculatorHelper.style.display = 'none';
        return;
    }

    calculatorHelper.style.display = 'block';

    const bookingCost = selectedSchedulePrice;

    // Hitung biaya booking per person (hanya jika participant yang bayar)
    const bookingPerPerson =
        payBy === 'participant'
            ? Math.ceil(bookingCost / maxParticipants)
            : 0;

    // Biaya join fee (jika metode pembayaran paid)
    const joinCost =
        ptType === 'paid' ? pricePerPerson : 0;

    const totalPerPerson = bookingPerPerson + joinCost;

    // Update tampilan kalkulator
    document.getElementById('calc_booking_cost').textContent =
        'Rp ' + bookingCost.toLocaleString('id-ID');

    document.getElementById('calc_participant_count').textContent =
        maxParticipants;

    document.getElementById('calc_booking_per_person').textContent =
        bookingPerPerson > 0
            ? 'Rp ' + bookingPerPerson.toLocaleString('id-ID')
            : 'Ditanggung Host';

    document.getElementById('calc_price_per_person').textContent =
        joinCost > 0
            ? 'Rp ' + joinCost.toLocaleString('id-ID')
            : 'Rp 0';

    document.getElementById('calc_total_per_person').textContent =
        'Rp ' + totalPerPerson.toLocaleString('id-ID');
}

// ====================== VALIDASI FORM SEBELUM SUBMIT ======================
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const scheduleId = document.getElementById('schedule_id').value;
    const bookingType = document.getElementById('bookingType').value;
    
    // Hapus pesan error sebelumnya
    document.querySelectorAll('.js-error').forEach(el => el.remove());
    document.querySelectorAll('.form-control.error').forEach(el => el.classList.remove('error'));
    
    let isValid = true;
    
    // Fungsi helper untuk menambahkan pesan error
    function addError(elementId, message) {
        let element = document.getElementById(elementId);
        if (!element) return;
        if (element.classList.contains('form-control')) {
            element.classList.add('error');
        }
        let formGroup = element.closest('.form-group');
        if (formGroup) {
            let errorDiv = document.createElement('div');
            errorDiv.className = 'error-message js-error mt-1';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            formGroup.appendChild(errorDiv);
        }
        isValid = false;
    }

    // Validasi jadwal
    if (!scheduleId) {
        addError('schedule_id', 'Jadwal belum dipilih');
    }
    
    // Validasi tipe booking
    if (!bookingType) {
        addError('bookingType', 'Tipe booking belum dipilih');
    }

    // Validasi khusus untuk Play Together
    if (bookingType === 'play_together') {
        if (!document.getElementById('pt_name').value.trim()) {
            addError('pt_name', 'Nama Main Bareng belum diisi');
        }
        
        const ptPrivacy = document.getElementById('pt_privacy').value;
        if (ptPrivacy === 'community' && !document.getElementById('pt_community_id').value) {
            addError('pt_community_id', 'Komunitas belum dipilih');
        }
        
        if (!document.getElementById('pt_max_participants').value) {
            addError('pt_max_participants', 'Maksimal participant belum diisi');
        }

        if (!document.getElementById('pt_gender').value.trim()) {
            addError('pt_gender', 'Jenis kelamin belum diisi');
        }

        const ptType = document.getElementById('pt_type').value;
        if (ptType === 'paid' && !document.getElementById('pt_price_per_person').value) {
            addError('pt_price_per_person', 'Biaya per orang belum diisi');
        }
    }
    
    // Validasi khusus untuk Sparring
    if (bookingType === 'sparring') {
        if (!document.getElementById('sp_name').value.trim()) {
            addError('sp_name', 'Nama Sparring belum diisi');
        }

        const spType = document.getElementById('sp_type').value;
        if (spType === 'paid' && !document.getElementById('sp_cost_per_participant').value) {
            addError('sp_cost_per_participant', 'Biaya per participant belum diisi');
        }
    }

    // Jika tidak valid, hentikan submit
    if (!isValid) {
        e.preventDefault();
        return false;
    }
    
    // Disable tombol submit untuk mencegah double submission
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    
    console.log('Form submitted with data:', {
        venue_id: document.getElementById('venue_id').value,
        section_id: document.getElementById('section_id').value,
        schedule_id: scheduleId,
        type: bookingType
    });
    
    return true;
});

// ====================== DEBUG: LOG SAAT LOAD ======================
console.log('Booking create page loaded');
console.log('Venue ID:', document.getElementById('venue_id')?.value);
console.log('Section ID:', document.getElementById('section_id')?.value);
</script>

<script>
/**
 * FUNGSI LOAD SCHEDULES
 * Memuat ulang jadwal berdasarkan tanggal yang dipilih (AJAX)
 * Catatan: Fungsi ini tidak digunakan langsung pada halaman ini,
 * tetapi disediakan untuk keperluan filter dinamis jika diperlukan.
 */
function loadSchedules() {
    const date = document.getElementById('dateFilter').value;
    const sectionId = document.getElementById('section_id').value;

    fetch(`/buyer/schedules?section_id=${sectionId}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('scheduleCardsContainer');
            container.innerHTML = ''; // Kosongkan jadwal lama
            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;">Tidak ada jadwal tersedia</p>';
                return;
            }
            data.forEach(schedule => {
                const card = document.createElement('div');
                card.className = 'schedule-card';
                card.setAttribute('data-schedule-id', schedule.id);
                card.setAttribute('data-price', schedule.rental_price);
                card.setAttribute('data-date', schedule.date);
                card.setAttribute('data-start', schedule.start_time);
                card.setAttribute('data-end', schedule.end_time);
                card.setAttribute('data-duration', schedule.rental_duration);
                card.onclick = () => selectSchedule(card);

                card.innerHTML = `
                    <div class="schedule-date">
                        <div class="date-day">${new Date(schedule.date).getDate()}</div>
                        <div class="date-month">${new Date(schedule.date).toLocaleString('id-ID',{month:'short'})}</div>
                    </div>
                    <div class="schedule-details">
                        <div class="schedule-time">${schedule.start_time} - ${schedule.end_time}</div>
                        <div class="schedule-price">Rp ${schedule.rental_price.toLocaleString('id-ID')}</div>
                        <span class="schedule-status available">Tersedia</span>
                    </div>
                `;
                container.appendChild(card);
            });
        });
}
</script>
@endpush
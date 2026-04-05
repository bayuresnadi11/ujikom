@extends('layouts.main', ['title' => 'Display Jadwal - ' . $venue->venue_name])

@push('styles')
    @include('cashier.schedule.partials.style-venue')
@endpush

@section('content')

@php
    use Carbon\Carbon;

    // Inisialisasi waktu sekarang dan koleksi jadwal
    $currentTime = Carbon::now();
    $allSchedules = collect();

    // Loop melalui setiap bagian lapangan (section) dalam venue ini
    foreach ($venue->venueSections as $section) {
        foreach ($section->schedules as $schedule) {

            $startTime = Carbon::parse($schedule->start_time);
            $endTime   = Carbon::parse($schedule->end_time);

            $booking = $schedule->booking; // 🔥 LANGSUNG

            $isCurrent = $currentTime->between($startTime, $endTime);
            $isPassed  = $endTime->lt($currentTime);

            // Check if booking is paid
            $isPaid = $booking && $booking->payment_status === 'paid';

            // PERBAIKAN: Mengutamakan nama dari User ID
            // Jika ada booking, ambil nama user dari relasi user_id
            // Jika tidak ada user (atau booking komunitas), ambil nama komunitas
            $customerName = 
                $booking?->user?->name 
                ?? $booking?->playTogether?->community?->name 
                ?? 'LAPANGAN KOSONG';

            $allSchedules->push([
                'id' => $schedule->id,
                'section_name' => $section->section_name,

                // Ini menampung nama yang sudah diambil dari user_id
                'customer_name' => $customerName,

                'start_time' => $startTime->format('H:i'),
                'end_time'   => $endTime->format('H:i'),

                'is_booked' => $booking !== null,
                'is_paid' => $isPaid,
                'scan_status' => ($booking && !empty($booking->scan_status)) ? $booking->scan_status : ($booking ? 'belum_scan' : null),
                'is_current' => $isCurrent,
                'is_passed'  => $isPassed,

                'start_datetime' => $startTime,
            ]);
        }
    }

    // ===============================
    // URUTKAN & FILTER JADWAL
    // ===============================
    // Urutkan berdasarkan waktu mulai
    $allSchedules = $allSchedules->sortBy('start_datetime')->values();

    // Ambil jadwal yang belum lewat (mendatang)
    $upcomingSchedules = $allSchedules->where('is_passed', false)->values();

    // Identifikasi jadwal yang sedang berlangsung (sekarang)
    $currentRunningSchedules = $upcomingSchedules->where('is_current', true)->values();

    // Jadwal berikutnya yang akan tampil di grid (maksimal 9 item)
    $gridSchedules = $upcomingSchedules->where('is_current', false)->take(9)->values();

    $displaySchedules = $currentRunningSchedules;

    if ($displaySchedules->isEmpty() && $gridSchedules->isNotEmpty()) {
        $displaySchedules = $gridSchedules->take(2);
        $gridSchedules = $gridSchedules->slice(2)->values();
    }

    $displayTitle = $venue->venue_name;
@endphp


<!-- ===============================
HEADER
================================ -->
<header class="display-header">
    <div class="display-brand">
        <img 
            src="{{ $venue->owner && $venue->owner->avatar
                ? asset('storage/' . $venue->owner->avatar)
                : asset('images/default-avatar.png') }}"
            onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';"
            alt="Foto Landowner"
            class="landowner-avatar"
        />
        <div>
            <div>{{ $displayTitle }}</div>
        </div>
    </div>
    <div class="display-clock">
        <div class="time" id="clockTime">00:00:00</div>
        <div class="date" id="clockDate">{{ Carbon::today()->format('d M Y') }}</div>
    </div>
</header>

<!-- ===============================
MAIN CONTENT
================================ -->
<div class="display-content">

    <!-- ===============================
    LEFT : ACTIVE SCHEDULE
    ================================ -->
    <div class="active-schedule-panel">
        <div class="active-schedule-card">

            <div class="card-header ">
    @if($sectionsSlides->isNotEmpty())
        <div id="sectionCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner text-center">

                @foreach($sectionsSlides as $index => $slide)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <h5 class="m-0 py-2 fs-2 fw-bold ">
                            {{ $slide['section_name'] }}
                        </h5>
                    </div>
                @endforeach

            </div>
        </div>
    @elseif($gridSchedules->isNotEmpty())
        JADWAL BERIKUTNYA
    @else
        TIDAK ADA JADWAL
    @endif
</div>


            <div class="card-body" id="activeScheduleBody">
                @if($displaySchedules->isNotEmpty())
                    <div class="slide-container">
                        <div class="slide-wrapper" id="slideWrapper">

                            @foreach($displaySchedules as $index => $schedule)
                                @php
                                    // Determine display mode
                                    $isPaid = $schedule['is_paid'] ?? false;
                                    $scanStatus = $schedule['scan_status'] ?? null;
                                    $isMasukLapang = $scanStatus === 'masuk_lapang';
                                    $isBelumScan = $scanStatus === 'belum_scan';
                                    $isMasukVenue = $scanStatus === 'masuk_venue';
                                    
                                    // Determine time class
                                    $timeClass = '';
                                    if ($isBelumScan) {
                                        $timeClass = 'time-belum-scan';
                                    } elseif ($isMasukVenue) {
                                        $timeClass = 'time-masuk-venue';
                                    }
                                    
                                    // Determine status for active display
                                    if ($schedule['is_booked'] && $scanStatus) {
                                        $scanStatusMap = [
                                            'belum_scan' => ['text' => 'BELUM SCAN', 'class' => 'status-belum-scan'],
                                            'masuk_venue' => ['text' => 'MASUK VENUE', 'class' => 'status-masuk-venue'],
                                        ];
                                        $statusInfo = $scanStatusMap[$scanStatus] ?? ['text' => 'TERISI', 'class' => 'status-booked'];
                                        $activeStatusText = $statusInfo['text'];
                                        $activeStatusClass = $statusInfo['class'];
                                    } elseif ($schedule['is_booked']) {
                                        $activeStatusText = 'TERISI';
                                        $activeStatusClass = 'status-booked';
                                    } else {
                                        $activeStatusText = 'KOSONG';
                                        $activeStatusClass = 'status-available';
                                    }
                                @endphp
                                <div class="schedule-slide"
                                     data-index="{{ $index }}"
                                     data-schedule-id="{{ $schedule['id'] }}"
                                     data-scan-status="{{ $scanStatus }}"
                                     data-is-paid="{{ $isPaid ? 'true' : 'false' }}"
                                     data-end-time="{{ $schedule['end_time'] }}">

                                    @if($isMasukLapang)
                                        {{-- Countdown timer display for masuk_lapang --}}
                                        <div class="countdown-container">
                                            <div class="countdown-label">SISA WAKTU MAIN</div>
                                            <div class="countdown-timer" 
                                                 id="countdownTimer-{{ $index }}"
                                                 data-end-time="{{ $schedule['end_time'] }}">
                                                --:--:--
                                            </div>
                                            <div class="countdown-schedule">
                                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                            </div>
                                           
                                        </div>
                                    @else
                                        {{-- Normal display with status badge --}}
                                        <div class="current-schedule-time {{ $timeClass }}">
                                            {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                        </div>

                                        <div class="current-schedule-label">WAKTU MAIN</div>

                                        <div class="current-schedule-details">
                                        </div>

                                        <div class="schedule-status {{ $activeStatusClass }}">
                                            {{ $activeStatusText }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>

                    @if($displaySchedules->count() > 1)
                        <div class="slide-indicators" id="slideIndicators">
                            @foreach($displaySchedules as $index => $schedule)
                                <div class="slide-indicator {{ $index === 0 ? 'active' : '' }}"
                                     data-index="{{ $index }}"></div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="empty-title">TIDAK ADA JADWAL</div>
                        <div class="empty-subtitle">
                            Tidak ada jadwal hari ini
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ===============================
    RIGHT : GRID JADWAL
    ================================ -->
    <div class="schedules-grid-panel">
        <div class="schedules-header">
            <i class="fas fa-list-alt"></i>
            <span>JADWAL HARI INI</span>
            <span class="ms-auto" style="font-size: 0.9rem; opacity: 0.9;">
                <span id="upcomingCount">{{ $gridSchedules->count() }}</span> Jadwal
            </span>
        </div>

        <div class="schedules-container">
            @if($gridSchedules->isNotEmpty())
                <div class="schedules-grid" id="schedulesGrid">

                    @foreach($gridSchedules as $index => $schedule)
                        @php
                            $customerName = $schedule['customer_name'] ?? 'KOSONG';

                            // Determine status for grid display
                            if (($schedule['is_booked'] ?? false) && ($schedule['scan_status'] ?? null)) {
                                $scanStatusMap = [
                                    'belum_scan' => ['text' => 'BELUM SCAN', 'class' => 'badge-belum-scan'],
                                    'masuk_venue' => ['text' => 'MASUK VENUE', 'class' => 'badge-masuk-venue'],
                                    'masuk_lapang' => ['text' => 'MASUK LAPANG', 'class' => 'badge-masuk-lapang'],
                                ];
                                $statusInfo = $scanStatusMap[$schedule['scan_status']] ?? ['text' => 'TERISI', 'class' => 'badge-booked'];
                                $statusClass = $statusInfo['class'];
                                $badgeText = $statusInfo['text'];
                            } elseif ($schedule['is_booked']) {
                                $statusClass = 'badge-booked';
                                $badgeText = 'TERISI';
                            } else {
                                $statusClass = 'badge-available';
                                $badgeText = 'KOSONG';
                            }
                        @endphp

                        <div class="schedule-card color-{{ $index % 6 }}"
                             data-schedule-id="{{ $schedule['id'] }}"
                             data-start-time="{{ $schedule['start_time'] }}"
                             data-end-time="{{ $schedule['end_time'] }}"
                             data-section="{{ $schedule['section_name'] }}"
                             data-is-booked="{{ $schedule['is_booked'] ? 'true' : 'false' }}"
                             data-is-current="{{ $schedule['is_current'] ? 'true' : 'false' }}"
                             data-index="{{ $index }}">

                            <div class="schedule-card-body">
                                <div class="schedule-header">
                                    <div class="schedule-time">
                                        {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                    </div>
                                    <div class="schedule-badge {{ $statusClass }}">
                                        {{ $badgeText }}
                                    </div>
                                </div>

                                <div class="schedule-details">
                                    <div class="schedule-section-name">
                                        {{ $schedule['section_name'] }}
                                    </div>
                                    <div class="schedule-customer-name">
                                        {{ $customerName }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-4x mb-3"
                       style="opacity: .5;"></i>

                    <div class="empty-title">
                        @if($currentRunningSchedules->isNotEmpty())
                            SEMUA JADWAL SEDANG BERLANGSUNG
                        @else
                            TIDAK ADA JADWAL
                        @endif
                    </div>

                    <div class="empty-subtitle">
                        @if($currentRunningSchedules->isNotEmpty())
                            Semua jadwal sedang berlangsung ditampilkan di sebelah kiri
                        @else
                            Tidak ada jadwal di venue ini hari ini
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('cashier.schedule.partials.script-venue')
@endpush
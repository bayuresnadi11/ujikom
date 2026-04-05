@extends('layouts.main', ['title' => 'Display Jadwal - ' . $section->section_name])

@push('styles')
    @include('cashier.schedule.partials.style-section')
@endpush

@section('content')

@php
    use Carbon\Carbon;
    
    // Inisialisasi waktu sekarang dan koleksi jadwal mendatang
    $currentTime = Carbon::now();
    $upcomingSchedules = collect();

    // Loop melalui jadwal lapangan ini (Section)
    foreach ($section->schedules as $schedule) {
        $startTime = Carbon::parse($schedule->start_time);
        $endTime   = Carbon::parse($schedule->end_time);

        // 🔥 LOAD BOOKING dengan relasi user
        $booking = $schedule->booking()->with(['user', 'playTogether.community'])->first();

        $isCurrent = $currentTime->between($startTime, $endTime);
        $isPassed  = $endTime->lt($currentTime);

        // Check if booking is paid
        $isPaid = $booking && $booking->booking_payment === 'full';

        // PERBAIKAN: Mengutamakan nama dari User ID
        $customerName = 
            $booking?->user?->name 
            ?? $booking?->playTogether?->community?->name 
            ?? 'LAPANGAN KOSONG';

        // Skip jadwal yang sudah lewat
        if ($isPassed) {
            continue;
        }

        $upcomingSchedules->push([
            'id' => $schedule->id,
            'customer_name' => $customerName,
            'start_time' => $startTime->format('H:i'),
            'end_time'   => $endTime->format('H:i'),
            'is_booked' => $booking !== null,
            'is_paid' => $isPaid,
            'scan_status' => ($booking && !empty($booking->scan_status)) ? $booking->scan_status : ($booking ? 'belum_scan' : null),
            'is_current' => $isCurrent,
            'is_passed'  => $isPassed,
            'start_datetime' => $startTime,
            'date' => $schedule->date,
        ]);
    }

    // Urutkan jadwal berdasarkan waktu mulai
    $upcomingSchedules = $upcomingSchedules->sortBy('start_datetime')->values();

    // Filter jadwal agar hanya menampilkan jadwal hari ini
    $today = Carbon::today();
    $upcomingSchedules = $upcomingSchedules->filter(function($schedule) use ($today) {
        $scheduleDate = Carbon::parse($schedule['date']);
        return $scheduleDate->isSameDay($today);
    })->values();

    // Batasi maksimal 9 jadwal untuk tampilan grid
    $upcomingSchedules = $upcomingSchedules->take(9);
    
    // Cari slot waktu yang sedang berlangsung saat ini
    $currentSlot = $upcomingSchedules->where('is_current', true)->first();
    $currentIndex = $upcomingSchedules->search(fn($s) => ($s['id'] ?? null) === ($currentSlot['id'] ?? null));
    
    // Jika tidak ada jadwal sedang berlangsung, ambil jadwal terdekat berikutnya sebagai default display
    if (!$currentSlot && $upcomingSchedules->isNotEmpty()) {
        $currentSlot = $upcomingSchedules->first();
        $currentIndex = 0;
    }
    
    // Judul tampilan (Venue - Lapangan)
    $displayTitle = ($section->venue->venue_name ?? 'Venue') . ' - ' . ($section->section_name ?? 'Section');
    $today = Carbon::today()->format('d M Y');
@endphp

<!-- Header -->
<header class="display-header">
    <div class="display-brand">
        <img 
            src="{{ $section->venue->owner && $section->venue->owner->avatar
                ? asset('storage/' . $section->venue->owner->avatar)
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
        <div class="date" id="clockDate">{{ $today }}</div>
    </div>
</header>

<!-- Main Layout -->
<div class="display-content">
    <!-- Left: Current Time Slot -->
    <div class="current-slot-panel">
        <div class="current-slot-card">
            <div class="card-header text-center fw-bold fs-2">
                {{ $section->section_name }}
            </div>

            <div class="card-body" id="currentSlotBody">
                @if($currentSlot)
                    @php
                        // Determine display mode for current slot
                        $isPaid = $currentSlot['is_paid'] ?? false;
                        $scanStatus = isset($currentSlot['scan_status']) ? trim($currentSlot['scan_status']) : null;
                        
                        // Default to 'belum_scan' if booked but no status
                        if (empty($scanStatus) && ($currentSlot['is_booked'] ?? false)) {
                            $scanStatus = 'belum_scan';
                        }

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
                        
                        // Determine status text and class
                        if ($currentSlot['is_booked'] && $scanStatus) {
                            $scanStatusMap = [
                                'belum_scan' => ['text' => 'BELUM SCAN', 'class' => 'slot-status-belum-scan'],
                                'masuk_venue' => ['text' => 'MASUK VENUE', 'class' => 'slot-status-masuk-venue'],
                                'masuk_lapang' => ['text' => 'MASUK LAPANG', 'class' => 'slot-status-masuk-lapang'],
                            ];
                            $statusInfo = $scanStatusMap[$scanStatus] ?? ['text' => 'TERISI', 'class' => 'slot-status-booked'];
                            $slotStatusText = $statusInfo['text'];
                            $slotStatusClass = $statusInfo['class'];
                        } elseif ($currentSlot['is_booked']) {
                            $slotStatusText = 'TERISI';
                            $slotStatusClass = 'slot-status-booked';
                        } else {
                            $slotStatusText = 'KOSONG';
                            $slotStatusClass = 'slot-status-available';
                        }
                    @endphp
                    
                    @if($isMasukLapang)
                        {{-- Display countdown timer for masuk_lapang --}}
                        <div class="countdown-container">
                            <div class="countdown-label">SISA WAKTU MAIN</div>
                            <div class="countdown-timer" 
                                 id="countdownTimer"
                                 data-end-time="{{ $currentSlot['end_time'] }}">
                                --:--:--
                            </div>
                            <div class="countdown-schedule">
                                {{ $currentSlot['start_time'] }} - {{ $currentSlot['end_time'] }}
                            </div>
                        </div>
                    @else
                        {{-- Normal display for other statuses --}}
                        <div class="current-slot-time {{ $timeClass }}">{{ $currentSlot['start_time'] }} - {{ $currentSlot['end_time'] }}</div>
                        <div class="current-slot-label">WAKTU MAIN</div>
                        <div class="current-slot-name">
                            {{ $currentSlot['customer_name'] ?? 'LAPANGAN KOSONG' }}
                        </div>
                        <div class="slot-status {{ $slotStatusClass }}">
                            {{ $slotStatusText }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="empty-title">TIDAK ADA JADWAL</div>
                        <div class="empty-subtitle">Tidak ada jadwal untuk hari ini</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right: All Schedules Grid -->
    <div class="schedule-grid-panel">
        <div class="schedule-header">
            <i class="fas fa-list-alt"></i>
            <span>JADWAL MENDATANG</span>
        </div>
        <div class="schedule-container">
            @if($upcomingSchedules->count() > 0)
                <div class="schedule-grid" id="scheduleGrid">
                    @foreach($upcomingSchedules as $index => $schedule)
                        @php
                            // Skip current slot dari grid (jangan ditampilkan duplikat)
                            if ($index === $currentIndex) continue;
                            
                            $customerName = $schedule['customer_name'] ?? 'KOSONG';
                            
                            // Determine status text and class
                            $scanStatus = $schedule['scan_status'] ?? null;

                            if (($schedule['is_booked'] ?? false) && $scanStatus) {
                                $scanStatusMap = [
                                    'belum_scan' => ['text' => 'BELUM SCAN', 'class' => 'status-belum-scan'],
                                    'masuk_venue' => ['text' => 'MASUK VENUE', 'class' => 'status-masuk-venue'],
                                    'masuk_lapang' => ['text' => 'MASUK LAPANG', 'class' => 'status-masuk-lapang'],
                                ];
                                $statusInfo = $scanStatusMap[$scanStatus] ?? ['text' => 'TERISI', 'class' => 'status-booked'];
                                $status = $statusInfo['text'];
                                $statusClass = $statusInfo['class'];
                            } elseif ($schedule['is_booked']) {
                                $status = 'TERISI';
                                $statusClass = 'status-booked';
                            } else {
                                $status = 'KOSONG';
                                $statusClass = 'status-available';
                            }
                        @endphp
                        <div class="schedule-card color-{{ $index % 6 }}" 
                             data-start="{{ $schedule['start_time'] }}"
                             data-end="{{ $schedule['end_time'] }}"
                             data-id="{{ $schedule['id'] ?? $index }}">
                            <div class="schedule-card-body">
                                <div class="schedule-time">{{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}</div>
                                <div class="schedule-customer">{{ $customerName }}</div>
                                <div class="schedule-status {{ $statusClass }}">{{ $status }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-4x mb-3" style="color: var(--text-light); opacity: 0.5;"></i>
                    <div class="empty-title">TIDAK ADA JADWAL</div>
                    <div class="empty-subtitle">Tidak ada jadwal untuk lapangan ini hari ini</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('cashier.schedule.partials.script-section')
@endpush
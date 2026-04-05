@extends('layouts.main', ['title' => 'Edit Booking'])

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/booking-style.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')

<div class="mobile-container">
    @include('layouts.header')

    <main class="main-content">
        <section class="page-header">
            <h1 class="page-title">Edit Booking</h1>
            <p class="page-subtitle">Update informasi booking Anda</p>
        </section>

        <div class="booking-form-container">
            <form action="{{ route('buyer.booking.update', $booking->id) }}" method="POST" id="bookingForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="venue_id" value="{{ $booking->venue_id }}">
                <input type="hidden" name="section_id" value="{{ $booking->schedule->section_id }}">

                <div class="form-group">
                    <label class="form-label">Venue</label>
                    <input type="text" class="form-control" value="{{ $booking->venue->venue_name }}" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Section</label>
                    <input type="text" class="form-control" value="{{ $booking->schedule->section->section_name }}" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Jadwal <span style="color: red;">*</span></label>
                    <div class="schedule-cards-container">
                        @foreach($schedules as $schedule)
                            <div class="schedule-card {{ $schedule->id == $booking->schedule_id ? 'selected' : '' }}" 
                                 data-schedule-id="{{ $schedule->id }}" 
                                 onclick="selectSchedule({{ $schedule->id }}, {{ $schedule->rental_price }})">
                                <div class="schedule-date">
                                    <div class="date-day">{{ \Carbon\Carbon::parse($schedule->date)->format('d') }}</div>
                                    <div class="date-month">{{ \Carbon\Carbon::parse($schedule->date)->format('M') }}</div>
                                </div>
                                <div class="schedule-details">
                                    <div class="schedule-time">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                                    <div class="schedule-price">Rp {{ number_format($schedule->rental_price, 0, ',', '.') }}</div>
                                    <span class="schedule-status available">{{ $schedule->id == $booking->schedule_id ? 'Terpilih' : 'Tersedia' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $booking->schedule_id }}" required>
                    @error('schedule_id')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div id="scheduleInfoCard" class="schedule-info-card" style="display: {{ $booking->schedule_id ? 'block' : 'none' }};">
                    <h4><i class="fas fa-info-circle"></i> Detail Jadwal Terpilih</h4>
                    <div class="schedule-details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-value" id="selectedDate">{{ \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Waktu</div>
                            <div class="detail-value" id="selectedTime">{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Durasi</div>
                            <div class="detail-value" id="selectedDuration">{{ $booking->schedule->rental_duration }} jam</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Harga</div>
                            <div class="detail-value" id="selectedPrice">Rp {{ number_format($booking->schedule->rental_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Booking <span style="color: red;">*</span></label>
                    <select class="form-control" name="type" id="bookingType" required onchange="toggleBookingType()">
                        <option value="regular" {{ $booking->type == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="play_together" {{ $booking->type == 'play_together' ? 'selected' : '' }}>Main Bareng</option>
                        <option value="sparring" {{ $booking->type == 'sparring' ? 'selected' : '' }}>Sparring</option>
                    </select>
                    @error('type')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div id="payBySection" style="display: {{ in_array($booking->type, ['play_together', 'sparring']) ? 'block' : 'none' }};">
                    <div class="form-group">
                        <label class="form-label">Lapangan Dibayar Oleh <span style="color: red;">*</span></label>
                        <select class="form-control" name="pay_by" id="payBy" onchange="updateCalculator()">
                            <option value="host" {{ $booking->pay_by == 'host' ? 'selected' : '' }}>Host (Saya bayar semua)</option>
                            <option value="participant" {{ $booking->pay_by == 'participant' ? 'selected' : '' }}>Participant (Dibagi participant)</option>
                        </select>
                    </div>
                </div>

                @php
                    $playTogether = $booking->playTogether;
                @endphp

                <div id="playTogetherSection" class="form-section" style="display: {{ $booking->type == 'play_together' ? 'block' : 'none' }};">
                    <div class="form-section-header">
                        <i class="fas fa-users"></i> Informasi Main Bareng
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Main Bareng <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="pt_name" value="{{ $playTogether->name ?? '' }}" placeholder="Masukkan nama play together">
                        @error('pt_name')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Privasi <span style="color: red;">*</span></label>
                        <select class="form-control" name="pt_privacy" id="pt_privacy" onchange="toggleCommunitySelect()">
                            <option value="public" {{ ($playTogether->privacy ?? '') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ ($playTogether->privacy ?? '') == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="community" {{ ($playTogether->privacy ?? '') == 'community' ? 'selected' : '' }}>Community</option>
                        </select>
                    </div>

                    <div class="form-group" id="pt_community_group" style="display: {{ ($playTogether->privacy ?? '') == 'community' ? 'block' : 'none' }};">
                        <label class="form-label">Pilih Komunitas <span style="color: red;">*</span></label>
                        <select class="form-control" name="pt_community_id">
                            <option value="">Pilih Komunitas</option>
                            @foreach($myCommunities as $community)
                                <option value="{{ $community->id }}" {{ ($playTogether->community_id ?? '') == $community->id ? 'selected' : '' }}>{{ $community->name }}</option>
                            @endforeach
                        </select>
                        @error('pt_community_id')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Maksimal Participant <span style="color: red;">*</span></label>
                        <input type="number" class="form-control" name="pt_max_participants" id="pt_max_participants" min="2" value="{{ $playTogether->max_participants ?? '' }}" placeholder="Contoh: 10" onchange="updateCalculator()">
                        @error('pt_max_participants')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran Main Bareng <span style="color: red;">*</span></label>
                        <select class="form-control" name="pt_type" id="pt_type" onchange="togglePricePerPerson()">
                            <option value="free" {{ ($playTogether->type ?? '') == 'free' ? 'selected' : '' }}>Gratis</option>
                            <option value="paid" {{ ($playTogether->type ?? '') == 'paid' ? 'selected' : '' }}>Berbayar</option>
                        </select>
                    </div>

                    <div class="form-group" id="pt_price_group" style="display: {{ ($playTogether->type ?? '') == 'paid' ? 'block' : 'none' }};">
                        <label class="form-label">Harga Per Person <span style="color: red;">*</span></label>
                        <input type="number" class="form-control" name="pt_price_per_person" id="pt_price_per_person" min="0" value="{{ $playTogether->price_per_person ?? '' }}" placeholder="Contoh: 50000" onchange="updateCalculator()">
                        @error('pt_price_per_person')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

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
                                <div class="detail-label">Booking per Person</div>
                                <div class="detail-value" id="calc_booking_per_person">Rp 0</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Harga per Person</div>
                                <div class="detail-value" id="calc_price_per_person">Rp 0</div>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1; background: rgba(39, 174, 96, 0.1); border: 2px solid var(--primary);">
                                <div class="detail-label">Total per Person</div>
                                <div class="detail-value" style="color: var(--primary); font-size: 18px;" id="calc_total_per_person">Rp 0</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <input type="text" class="form-control" name="pt_gender" value="{{ $playTogether->gender ?? '' }}" placeholder="Contoh: Laki-laki / Perempuan / Campur">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="pt_description" rows="3" placeholder="Deskripsi play together">{{ $playTogether->description ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Deadline</label>
                        <input type="datetime-local" class="form-control" name="pt_payment_deadline" value="{{ $playTogether ? \Carbon\Carbon::parse($playTogether->payment_deadline)->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="pt_host_approval" value="1" {{ ($playTogether->host_approval ?? false) ? 'checked' : '' }}>
                            Memerlukan persetujuan host untuk bergabung
                        </label>
                    </div>
                </div>

                @php
                    $sparring = $booking->sparring;
                @endphp

                <div id="sparringSection" class="form-section" style="display: {{ $booking->type == 'sparring' ? 'block' : 'none' }};">
                    <div class="form-section-header">
                        <i class="fas fa-trophy"></i> Informasi Sparring
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Sparring <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="sp_name" value="{{ $sparring->name ?? '' }}" placeholder="Masukkan nama sparring">
                        @error('sp_name')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Privacy <span style="color: red;">*</span></label>
                        <select class="form-control" name="sp_privacy">
                            <option value="public" {{ ($sparring->privacy ?? '') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ ($sparring->privacy ?? '') == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="community" {{ ($sparring->privacy ?? '') == 'community' ? 'selected' : '' }}>Community</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran Main Bareng <span style="color: red;">*</span></label>
                        <select class="form-control" name="sp_type" id="sp_type" onchange="toggleSparringCost()">
                            <option value="free" {{ ($sparring->type ?? '') == 'free' ? 'selected' : '' }}>Gratis</option>
                            <option value="paid" {{ ($sparring->type ?? '') == 'paid' ? 'selected' : '' }}>Berbayar</option>
                        </select>
                    </div>

                    <div class="form-group" id="sp_cost_group" style="display: {{ ($sparring->type ?? '') == 'paid' ? 'block' : 'none' }};">
                        <label class="form-label">Biaya Per Participant <span style="color: red;">*</span></label>
                        <input type="number" class="form-control" name="sp_cost_per_participant" min="0" value="{{ $sparring->cost_per_participant ?? '' }}" placeholder="Contoh: 50000">
                        @error('sp_cost_per_participant')
                            <span style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="sp_description" rows="3" placeholder="Deskripsi sparring">{{ $sparring->description ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="sp_host_approval" value="1" {{ ($sparring->host_approval ?? false) ? 'checked' : '' }}>
                            Memerlukan persetujuan host untuk bergabung
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-form btn-form-secondary" onclick="window.history.back()">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn-form btn-form-primary">
                        <i class="fas fa-save"></i>
                        Update Booking
                    </button>
                </div>
            </form>
        </div>
    </main>

    <nav class="bottom-nav">
        <a href="{{ route('buyer.home') }}" class="nav-item">
            <i class="fas fa-home nav-icon"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('buyer.venue.index') }}" class="nav-item">
            <i class="fas fa-compass nav-icon"></i>
            <span>Venue</span>
        </a>
        <a href="{{ route('buyer.booking.index') }}" class="nav-item active">
            <i class="fas fa-calendar-alt nav-icon"></i>
            <span>Booking</span>
        </a>
        <a href="{{ route('buyer.chat') }}" class="nav-item">
            <i class="fas fa-comments nav-icon"></i>
            <span>Chat</span>
        </a>
        <a href="{{ route('buyer.profile') }}" class="nav-item">
            <i class="fas fa-user-circle nav-icon"></i>
            <span>Profil</span>
        </a>
    </nav>
</div>

<style>
.booking-form-container {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 25px;
    box-shadow: var(--shadow);
    margin-bottom: 100px;
    border: 1px solid var(--border);
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: var(--text);
    font-size: 14px;
}

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

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
}

.schedule-cards-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 10px;
}

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

.schedule-card:hover {
    transform: translateX(5px);
    border-color: var(--primary);
}

.schedule-card.selected {
    border-color: var(--primary);
    background: rgba(39, 174, 96, 0.05);
}

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

.form-group label input[type="checkbox"] {
    margin-right: 10px;
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
}

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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let selectedScheduleData = null;
let selectedSchedulePrice = {{ $booking->schedule->rental_price }};

function selectSchedule(scheduleId, price) {
    document.querySelectorAll('.schedule-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    event.currentTarget.classList.add('selected');
    document.getElementById('schedule_id').value = scheduleId;
    selectedSchedulePrice = price;
    
    const schedules = @json($schedules);
    const schedule = schedules.find(s => s.id === scheduleId);
    
    if (schedule) {
        selectedScheduleData = schedule;
        document.getElementById('selectedDate').textContent = new Date(schedule.date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
        document.getElementById('selectedTime').textContent = schedule.start_time.substring(0, 5) + ' - ' + schedule.end_time.substring(0, 5);
        document.getElementById('selectedDuration').textContent = schedule.rental_duration + ' jam';
        document.getElementById('selectedPrice').textContent = 'Rp ' + parseInt(schedule.rental_price).toLocaleString('id-ID');
        document.getElementById('scheduleInfoCard').style.display = 'block';
        
        updateCalculator();
    }
}

function toggleBookingType() {
    const type = document.getElementById('bookingType').value;
    const payBySection = document.getElementById('payBySection');
    const playTogetherSection = document.getElementById('playTogetherSection');
    const sparringSection = document.getElementById('sparringSection');
    
    payBySection.style.display = 'none';
    playTogetherSection.style.display = 'none';
    sparringSection.style.display = 'none';
    
    if (type === 'play_together') {
        payBySection.style.display = 'block';
        playTogetherSection.style.display = 'block';
    } else if (type === 'sparring') {
        payBySection.style.display = 'block';
        sparringSection.style.display = 'block';
    }
    
    updateCalculator();
}

function toggleCommunitySelect() {
    const privacy = document.getElementById('pt_privacy').value;
    const communityGroup = document.getElementById('pt_community_group');
    
    if (privacy === 'community') {
        communityGroup.style.display = 'block';
    } else {
        communityGroup.style.display = 'none';
    }
}

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

function toggleSparringCost() {
    const type = document.getElementById('sp_type').value;
    const costGroup = document.getElementById('sp_cost_group');
    
    if (type === 'paid') {
        costGroup.style.display = 'block';
    } else {
        costGroup.style.display = 'none';
    }
}

function updateCalculator() {
    const bookingType = document.getElementById('bookingType').value;
    const payBy = document.getElementById('payBy')?.value;
    const maxParticipants = parseInt(document.getElementById('pt_max_participants')?.value) || 0;
    const pricePerPerson = parseInt(document.getElementById('pt_price_per_person')?.value) || 0;
    const ptType = document.getElementById('pt_type')?.value;
    const calculatorHelper = document.getElementById('calculatorHelper');
    
    if (bookingType === 'play_together' && payBy === 'participant' && ptType === 'paid' && maxParticipants > 0) {
        calculatorHelper.style.display = 'block';
        
        const bookingCost = selectedSchedulePrice;
        const bookingPerPerson = bookingCost / maxParticipants;
        const totalPerPerson = bookingPerPerson + pricePerPerson;
        
        document.getElementById('calc_booking_cost').textContent = 'Rp ' + bookingCost.toLocaleString('id-ID');
        document.getElementById('calc_participant_count').textContent = maxParticipants;
        document.getElementById('calc_booking_per_person').textContent = 'Rp ' + Math.ceil(bookingPerPerson).toLocaleString('id-ID');
        document.getElementById('calc_price_per_person').textContent = 'Rp ' + pricePerPerson.toLocaleString('id-ID');
        document.getElementById('calc_total_per_person').textContent = 'Rp ' + Math.ceil(totalPerPerson).toLocaleString('id-ID');
    } else {
        calculatorHelper.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleBookingType();
    updateCalculator();
});

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const scheduleId = document.getElementById('schedule_id').value;
    if (!scheduleId) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Silakan pilih jadwal terlebih dahulu!',
        });
    }
});
</script>
@endpush
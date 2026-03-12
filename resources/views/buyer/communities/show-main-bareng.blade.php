@extends('layouts.main', ['title' => 'Detail Main Bareng - ' . $community->name])

@push('styles')
    <style>        
        /* ================= VARIABLES ================= */
        :root {
            --primary: #0A5C36;
            --primary-dark: #08482B;
            --secondary: #27AE60;
            --accent: #2ECC71;
            --light: #F8F9FA;
            --light-gray: #E9ECEF;
            --text: #212529;
            --text-light: #6C757D;
            --danger: #E74C3C;
            --gold: #FFD700;
            
            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            --gradient-light: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
            --gradient-dark: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 3px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 6px 24px rgba(0,0,0,0.12);
            --shadow-xl: 0 9px 36px rgba(0,0,0,0.15);
        }

        /* ================= GLOBAL ================= */
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: var(--text);
        }

        /* ================= HEADER ================= */
        .mobile-container {
            width: 100%;
            min-height: 100vh;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            overflow-x: hidden;
        }
        
        /* ================= SIMPLE HEADER ================= */
        .simple-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: white;
            z-index: 1100;
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid var(--light-gray);
        }

        .header-content {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            gap: 16px;
        }

        .back-button-simple {
            background: none;
            border: none;
            font-size: 18px;
            color: var(--primary);
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .back-button-simple:hover {
            background: var(--light);
        }

        .header-title-simple {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }

        /* ================= MAIN ================= */
        .main-content {
            padding: 80px 16px 140px;
            min-height: 100vh;
        }

        /* ================= BOTTOM NAV - Community Style ================= */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 8px 0 10px;
            box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
            z-index: 1000;
            border-top: 1px solid var(--light-gray);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            padding: 4px 8px;
            transition: all 0.2s ease;
            border-radius: 8px;
            position: relative;
            cursor: pointer;
            background: none;
            border: none;
            min-width: 48px;
            color: #999;
        }

        .nav-item.active {
            color: var(--primary);
        }

        .nav-item.active .nav-icon {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
            transform: scale(1.05);
        }

        .nav-item.active::after {
            content: "";
            position: absolute;
            top: -4px;
            width: 24px;
            height: 3px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .nav-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
            background: var(--light);
            color: var(--text-light);
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
        }

        /* ================ DETAIL PAGE STYLING ================ */
        .detail-header {
            padding: 16px;
            background: var(--gradient-light);
            border-bottom: 1px solid var(--light-gray);
        }

        .detail-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 16px;
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
            width: fit-content;
            transition: all 0.3s ease;
        }

        .detail-back-btn:hover {
            background: var(--light);
            transform: translateX(-2px);
        }

        .detail-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        /* Badges Style */
        .detail-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-public { background: #E8F5E9; color: #0A5C36; }
        .badge-private { background: #FFF3E0; color: #E65100; }
        .badge-paid { background: #E3F2FD; color: #1565C0; }
        .badge-free { background: #F3E5F5; color: #7B1FA2; }
        .badge-active { background: #E8F5E9; color: #0A5C36; }
        .badge-pending { background: #FFF3E0; color: #E65100; }
        .badge-gender { background: var(--light); color: var(--primary); }
        .badge-host-yes { background: #E3F2FD; color: #1565C0; }
        .badge-host-no { background: #FFEBEE; color: #C62828; }
        .badge-approved { background: #E8F5E9; color: #0A5C36; font-size: 10px; }
        .badge-pending-sm { background: #FFF3E0; color: #E65100; font-size: 10px; }
        .badge-partial-sm {
            background: #FFF3E0;
            color: #E65100;
            font-size: 10px;
        }
        .badge-host { background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%); color: #856404; font-size: 10px; }

        /* Payment Status Badges */
        .badge-lunas {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #C8E6C9;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-belum-lunas {
            background: #FFEBEE;
            color: #D32F2F;
            border: 1px solid #FFCDD2;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        /* ================ DETAIL CONTENT ================ */
        .detail-content { padding: 16px; }
        .detail-section {
            margin-bottom: 24px;
            padding: 16px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--light-gray);
        }

        .detail-section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--light);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media (min-width: 400px) {
            .detail-grid { grid-template-columns: repeat(2, 1fr); }
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .detail-item:last-child { border-bottom: none; }

        .detail-label {
            color: var(--text-light);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text);
            font-size: 13px;
            text-align: right;
        }

        .detail-description {
            padding: 12px;
            background: var(--light);
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text);
            margin-top: 12px;
        }

        /* Participants Section */
        .participants-container { margin-top: 12px; }
        .participant-count { font-size: 13px; color: var(--text-light); margin-bottom: 12px; }
        .participant-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            padding: 4px;
        }

        .participant-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid var(--light-gray);
        }

        .participant-item:last-child { border-bottom: none; }

        .participant-info { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
        }
        .participant-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--primary);
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
            box-shadow: var(--shadow-sm);
            text-transform: uppercase;
        }

        .participant-name { 
            font-weight: 700; 
            font-size: 14px; 
            color: var(--text); 
        }

        /* Actions */
        .action-buttons {
            position: fixed;
            bottom: 75px;
            left: 0;
            width: 100%;
            padding: 16px;
            background: linear-gradient(to top, white 50%, transparent);
            z-index: 900;
        }

        .btn-container { display: flex; gap: 12px; }
        .btn-action {
            flex: 1;
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-join { background: var(--gradient-primary); color: white; }
        .btn-apply { background: var(--gradient-accent); color: white; }
        .btn-host-join { background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%); color: #856404; }
        .btn-disabled { background: var(--light-gray); color: var(--text-light); cursor: not-allowed; }

        /* ================ FULL QUOTA ALERT ================ */
        .quota-alert {
            margin: 16px;
            padding: 16px;
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            border-left: 4px solid #F57C00;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-sm);
        }

        .quota-alert-icon {
            width: 40px;
            height: 40px;
            background: #F57C00;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .quota-alert-content {
            flex: 1;
        }

        .quota-alert-title {
            font-size: 14px;
            font-weight: 700;
            color: #E65100;
            margin-bottom: 4px;
        }

        .quota-alert-message {
            font-size: 12px;
            color: #6C757D;
            line-height: 1.4;
        }


        /* Payment Modal & Toast */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .toast {
            padding: 12px 16px;
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 16px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 480px;
        }

        .modal-header { padding: 20px; border-bottom: 1px solid var(--light-gray); display: flex; justify-content: space-between; align-items: center; }
        .modal-title { font-size: 18px; font-weight: 700; color: var(--primary); }
        .modal-close { background: none; border: none; cursor: pointer; }
        .modal-body { padding: 20px; }
        .payment-amount { text-align: center; }
        .payment-amount .amount { font-size: 24px; font-weight: 700; color: var(--primary); }

        @media (min-width: 480px) {
            .mobile-container {
                max-width: 480px;
                margin: 0 auto;
            }

            .simple-header, .bottom-nav, .action-buttons {
                max-width: 480px;
                left: 50% !important;
                transform: translateX(-50%) !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        <header class="simple-header">
            <div class="header-content">
                <a href="{{ route('buyer.communities.show', $community->id) }}" class="back-button-simple">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="header-title-simple">Detail Aktivitas</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Detail Header -->
            <div class="detail-header" style="background: none; border: none; padding-top: 0; margin-bottom: 20px;">
                <h1 class="detail-title">{{ $playTogether->booking->venue->venue_name ?? 'Main Bareng' }}</h1>
                
                <div class="detail-badges">
                    <span class="badge badge-{{ $playTogether->privacy === 'public' ? 'public' : 'private' }}">
                        <i class="fas fa-{{ $playTogether->privacy === 'public' ? 'globe' : 'lock' }}"></i> 
                        {{ strtoupper($playTogether->privacy) }}
                    </span>
                    <span class="badge badge-{{ $playTogether->type === 'paid' ? 'paid' : 'free' }}">
                        <i class="fas fa-{{ $playTogether->type === 'paid' ? 'money-bill-wave' : 'gift' }}"></i> 
                        {{ strtoupper($playTogether->type) }}
                    </span>
                    <span class="badge badge-{{ $playTogether->status }}">
                        {{ strtoupper($playTogether->status) }}
                    </span>
                    <span class="badge badge-{{ $playTogether->host_approval ? 'host-yes' : 'host-no' }}">
                        @if($playTogether->host_approval)
                            <i class="fas fa-user-check"></i> Perlu Persetujuan
                        @else
                            <i class="fas fa-bolt"></i> Auto Join
                        @endif
                    </span>
                </div>
            </div>

            <!-- Detail Content -->
            <div class="detail-content">
                <!-- Booking Details Section -->
                <div class="detail-section">
                    <h3 class="detail-section-title">
                        <i class="fas fa-calendar-alt"></i> Detail Booking
                    </h3>
                    
                    @if($playTogether->booking)
                        @php
                            $booking = $playTogether->booking;
                            $venue = $booking->venue;
                            $schedule = $booking->schedule;
                        @endphp
                        
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-ticket-alt"></i> Kode Tiket</div>
                                <div class="detail-value">{{ $booking->ticket_code }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-map-marker-alt"></i> Lokasi</div>
                                <div class="detail-value">{{ $venue->location }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-calendar-day"></i> Tanggal Booking</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y') }}</div>
                            </div>
                            @if($schedule)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-clock"></i> Waktu</div>
                                    <div class="detail-value">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-hourglass-half"></i> Durasi</div>
                                    <div class="detail-value">{{ $schedule->rental_duration }} jam</div>
                                </div>
                            @endif
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-dollar-sign"></i> Status Pembayaran
                                </div>
                                <div class="detail-value">
                                    @switch($booking->booking_payment)
                                        @case('full')
                                            <span class="badge badge-approved">LUNAS</span>
                                            @break

                                        @case('partial')
                                            <span class="badge badge-partial-sm">PARTIAL</span>
                                            @break

                                        @default
                                            <span class="badge badge-pending-sm">PENDING</span>
                                    @endswitch
                                </div>
                            </div>
                            @if($booking->isPaid())
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-check-circle"></i> Dibayar pada</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->paid_at)->translatedFormat('d M Y H:i') }}</div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p style="color: var(--text-light); text-align: center; padding: 20px;">Detail booking tidak tersedia</p>
                    @endif
                </div>

                <!-- Main Bareng Info Section -->
                <div class="detail-section">
                    <h3 class="detail-section-title"><i class="fas fa-info-circle"></i> Informasi Main Bareng</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-tag"></i> Tipe Lapangan</div>
                            <div class="detail-value">{{ $playTogether->booking->venue->category->category_name ?? 'Olahraga' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-calendar-alt"></i> Tanggal Main</div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-users"></i> Kuota Peserta</div>
                            <div class="detail-value">{{ $approvedParticipantsCount }} / {{ $playTogether->max_participants }} orang</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-venus-mars"></i> Jenis Kelamin</div>
                            <div class="detail-value">
                                <span class="badge badge-gender">
                                    @if(in_array(strtolower($playTogether->gender), ['male', 'laki-laki'])) Laki-laki @elseif(in_array(strtolower($playTogether->gender), ['female', 'perempuan'])) Perempuan @else Campur @endif
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-money-bill-wave"></i> Biaya</div>
                            <div class="detail-value">
                                @if($playTogether->type === 'paid') <strong>Rp {{ number_format($playTogether->price_per_person, 0, ',', '.') }}</strong> @else <span class="badge badge-free">GRATIS</span> @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-user"></i> Host</div>
                            <div class="detail-value">{{ $playTogether->creator->name }} @if($isCreator) <span class="badge badge-host">Anda</span> @endif</div>
                        </div>
                    </div>
                </div>

                <!-- Participants Section -->
                <div class="detail-section">
                    <h3 class="detail-section-title"><i class="fas fa-users"></i> Daftar Peserta</h3>
                    <div class="participant-count">Total: {{ $approvedParticipantsCount }} / {{ $playTogether->max_participants }}</div>
                    <div class="participants-container">
                        @foreach($approvedParticipants as $participant)
                            @php
                                $user = $participant->user;
                                $memberName = $user->name;
                                $avatar = $user->avatar;
                                
                                if ($avatar) {
                                    $avatarUrl = str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar);
                                } else {
                                    $avatarUrl = null;
                                    $initials = strtoupper(substr($memberName, 0, 2));
                                    $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
                                    $colorIndex = crc32($memberName) % count($colors);
                                    $avatarColor = $colors[$colorIndex];
                                }
                            @endphp

                            <div class="participant-item">
                                <div class="participant-info">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" class="participant-avatar" alt="{{ $memberName }}">
                                    @else
                                        <div class="participant-avatar" style="background-color: {{ $avatarColor }};">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <div class="participant-name">{{ $memberName }}</div>
                                </div>
                                <span class="badge {{ $participant->user_id == $playTogether->created_by ? 'badge-host' : 'badge-approved' }}">
                                    {{ $participant->user_id == $playTogether->created_by ? 'Host' : 'Diterima' }}
                                </span>

                                {{-- LABEL STATUS PEMBAYARAN (Lunas / Belum Lunas) --}}
                                @if($playTogether->type === 'paid')
                                    @if($participant->payment_status === 'paid')
                                        <span class="badge-lunas">LUNAS</span>
                                    @else
                                        <span class="badge-belum-lunas">BELUM LUNAS</span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Full Quota Alert -->
            @if($isFull && !$hasJoined && !$isCreator)
                <div class="quota-alert">
                    <div class="quota-alert-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="quota-alert-content">
                        <div class="quota-alert-title">Kuota Penuh</div>
                        <div class="quota-alert-message">
                            Maaf, kuota peserta untuk Main Bareng ini sudah penuh ({{ $approvedParticipantsCount }}/{{ $playTogether->max_participants }}). Silakan cari Main Bareng lainnya.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            @if(!$hasJoined)
                <div class="action-buttons">
                    <div class="btn-container">
                        @if($isFull)
                            <button class="btn-action btn-disabled">
                                <i class="fas fa-users-slash"></i> Kuota Penuh
                            </button>
                        @else
                            @if($showHostJoinButton) <button class="btn-action btn-host-join" onclick="joinMainBareng({{ $playTogether->id }}, false, true)">Bergabung Host</button> @endif
                            @if($showJoinButton) <button class="btn-action btn-join" onclick="joinMainBareng({{ $playTogether->id }}, false, false)">Bergabung</button> @endif
                            @if($showApplyButton) <button class="btn-action btn-apply" onclick="joinMainBareng({{ $playTogether->id }}, true, false)">Ajukan</button> @endif
                        @endif
                    </div>
                </div>
            @else
                <div class="action-buttons">
                    <div class="btn-container">
                        <button class="btn-action btn-disabled"><i class="fas fa-check"></i> Sudah Bergabung</button>
                    </div>
                </div>
            @endif
        </main>

        <!-- Bottom Nav -->
        <nav class="bottom-nav">
            <a href="{{ route('buyer.communities.show', $community->id) }}" class="nav-item">
                <div class="nav-icon"><i class="fas fa-user-circle"></i></div>
                <span class="nav-label">Profil</span>
            </a>
            <a href="{{ route('buyer.communities.aktivitas', $community->id) }}" class="nav-item active">
                <div class="nav-icon"><i class="fas fa-calendar-alt"></i></div>
                <span class="nav-label">Aktivitas</span>
            </a>
            <a href="{{ route('buyer.communities.members.index', $community->id) }}" class="nav-item">
                <div class="nav-icon"><i class="fas fa-users"></i></div>
                <span class="nav-label">Anggota</span>
            </a>
            <a href="#" class="nav-item">
                <div class="nav-icon"><i class="fas fa-trophy"></i></div>
                <span class="nav-label">Kompetisi</span>
            </a>
            <a href="{{ route('buyer.communities.galeri', $community->id) }}" class="nav-item">
                <div class="nav-icon"><i class="fas fa-images"></i></div>
                <span class="nav-label">Galeri</span>
            </a>
        </nav>

        <!-- Payment Modal -->
        <div class="modal-overlay" id="paymentModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Pembayaran</h2>
                    <button class="modal-close" onclick="closePaymentModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="payment-amount">
                        <h3>Lanjutkan Pembayaran</h3>
                        <div class="amount" id="paymentAmount">Rp 0</div>
                    </div>
                    <div id="snap-container"></div>
                </div>
            </div>
        </div>

        <!-- Custom Join Confirmation Modal -->
        <div class="confirm-overlay" id="joinConfirmModal">
            <div class="confirm-modal">
                <div class="confirm-icon">
                    <i class="fas fa-question"></i>
                </div>
                <h3 class="confirm-title">Konfirmasi</h3>
                <p class="confirm-message" id="joinModalText">Apakah Anda yakin ingin bergabung?</p>
                <div class="confirm-actions">
                    <button class="btn-confirm btn-confirm-cancel" onclick="closeJoinModal()">Batal</button>
                    <button class="btn-confirm btn-confirm-ok" onclick="confirmJoin()">Oke</button>
                </div>
            </div>
        </div>

        <!-- Custom Success Modal -->
        <div class="confirm-overlay" id="successModal">
            <div class="confirm-modal">
                <div class="confirm-icon" style="background: #E8F5E9; color: #2ECC71;">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="confirm-title">Berhasil</h3>
                <p class="confirm-message" id="successModalText">Permintaan bergabung berhasil dikirim.</p>
                <div class="confirm-actions" style="justify-content: center;">
                    <button class="btn-confirm btn-confirm-ok" onclick="closeSuccessModalAndReload()" style="flex: none; min-width: 120px;">OK</button>
                </div>
            </div>
        </div>

        <div class="toast-container" id="toastContainer"></div>
    </div>
@endsection

@push('styles')
    <style>
        /* ================= CUSTOM CONFIRM MODAL ================= */
        .confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(4px);
            padding: 20px;
        }

        .confirm-overlay.active {
            display: flex;
        }

        .confirm-modal {
            background: white;
            width: 100%;
            max-width: 320px;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .confirm-overlay.active .confirm-modal {
            transform: scale(1);
            opacity: 1;
        }

        .confirm-icon {
            width: 60px;
            height: 60px;
            background: #f0faf5;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px;
        }

        .confirm-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 8px;
        }

        .confirm-message {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .confirm-actions {
            display: flex;
            gap: 12px;
        }

        .btn-confirm {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-confirm-cancel {
            background: var(--light);
            color: var(--text-light);
        }

        .btn-confirm-ok {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-confirm-ok:active {
            transform: scale(0.98);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $setting->midtrans_client_key ?? '' }}"></script>
    <script>
        let paymentCheckInterval;
        let currentPaymentAmount = 0;
        let currentOrderId = null;
        let isHostJoin = false;
        let selectedActivityId = null;

        function showNotification() {
            // Dummy function to prevent error
            console.log('Notification clicked');
        }

        // --- Custom Open Modal Function ---
        function joinMainBareng(playTogetherId, needsApproval = false, isHost = false) {
            isHostJoin = isHost;
            selectedActivityId = playTogetherId;
            
            const message = isHost ? 'Apakah Anda yakin ingin bergabung sebagai Host?' : 'Apakah Anda yakin ingin bergabung?';
            document.getElementById('joinModalText').innerText = message;
            
            document.getElementById('joinConfirmModal').classList.add('active');
        }

        // --- Close Modal ---
        function closeJoinModal() {
            document.getElementById('joinConfirmModal').classList.remove('active');
            selectedActivityId = null;
        }

        // --- Confirm Join ---
        function confirmJoin() {
            if (!selectedActivityId) return;
            const id = selectedActivityId;
            closeJoinModal();
            processJoin(id);
        }

        // --- Success Modal Functions ---
        function showSuccessModal(message) {
            document.getElementById('successModalText').innerText = message;
            document.getElementById('successModal').classList.add('active');
        }

        function closeSuccessModalAndReload() {
            document.getElementById('successModal').classList.remove('active');
            window.location.reload();
        }

        function processJoin(id) {
            showLoading();
            fetch(`/buyer/main-bareng/${id}/join`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({
                    is_host: isHostJoin,
                    // Tambahkan parameter lain jika diperlukan
                })
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if(data.success) {
                    if(data.needs_payment) {
                        // Payment flow
                        currentPaymentAmount = data.amount;
                        document.getElementById('paymentAmount').innerText = 'Rp ' + parseInt(data.amount).toLocaleString('id-ID');
                        document.getElementById('paymentModal').classList.add('active');
                        
                        if (data.snap_token) {
                            startPayment(id, data.snap_token);
                        } else {
                            Swal.fire('Error', 'Token pembayaran tidak ditemukan', 'error');
                        }
                    } else {
                        // Free / Auto-join flow
                        showSuccessModal(data.message);
                    }
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => {
                hideLoading();
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            });
        }

        function startPayment(id, token) {
            snap.pay(token, {
                onSuccess: function(result) { 
                    showLoading('Menyelesaikan pembayaran...');
                    finishPayment(result); 
                },
                onPending: function(result) { 
                    Swal.fire('Pending', 'Pembayaran sedang diproses', 'info'); 
                },
                onError: function(result) {
                    Swal.fire('Gagal', 'Pembayaran gagal dilakukan', 'error');
                },
                onClose: function() { 
                    document.getElementById('paymentModal').classList.remove('active'); 
                }
            });
        }

        function finishPayment(result) {
            fetch('/buyer/main-bareng/create-participant', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ 
                    order_id: result.order_id, 
                    transaction_status: result.transaction_status,
                    is_host_join: isHostJoin
                })
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    // REPLACED SWAL WITH CUSTOM MODAL
                    showSuccessModal(data.message);
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => {
                hideLoading();
                console.error(err);
                location.reload();
            });
        }

        function closePaymentModal() { 
            if (paymentCheckInterval) {
                clearInterval(paymentCheckInterval);
            }
            document.getElementById('paymentModal').classList.remove('active'); 
        }

        // Toast notification
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <div>${message}</div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    if(toast.parentNode) container.removeChild(toast);
                }, 300);
            }, duration);
        }

        function showLoading(message = 'Memproses...') {
            const container = document.getElementById('toastContainer');
            const loading = document.createElement('div');
            loading.className = 'toast info';
            loading.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <div style="margin-left: 10px">${message}</div>
            `;
            loading.id = 'loadingToast';
            container.appendChild(loading);
        }

        function hideLoading() {
            const loading = document.getElementById('loadingToast');
            if (loading && loading.parentNode) {
                loading.parentNode.removeChild(loading);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Close modals on outside click
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                    }
                });
            });

            // Escape key to close modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                        modal.classList.remove('active');
                    });
                }
            });
        });
    </script>
@endpush

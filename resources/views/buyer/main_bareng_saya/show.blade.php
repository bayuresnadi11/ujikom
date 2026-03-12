@extends('layouts.main', ['title' => 'Detail Main Bareng - SewaLap'])

@push('styles')
    @include('buyer.main_bareng_saya.partials.style')
    <style>
        /* Additional styles for show page */
        .detail-hero {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: var(--gradient-light);
        }

        .detail-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            padding: 16px;
            color: white;
        }

        .participant-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .participant-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .participant-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary);
            font-size: 18px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .participant-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .participant-info {
            flex: 1;
            min-width: 0;
        }

        .participant-name {
            font-weight: 700;
            color: var(--text);
            font-size: 14px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .participant-phone {
            font-size: 11px;
            color: var(--text-light);
        }

        .participant-actions {
            display: flex;
            gap: 6px;
        }

        .btn-approve {
            background: #E8F5E9;
            color: #0A5C36;
            border: 1px solid #C8E6C9;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-approve:hover {
            background: #0A5C36;
            color: white;
        }

        .btn-reject {
            background: #FFEBEE;
            color: var(--danger);
            border: 1px solid #FFCDD2;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reject:hover {
            background: var(--danger);
            color: white;
        }

        .invitation-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 12px;
            margin-bottom: 8px;
        }

        .section-empty {
            text-align: center;
            padding: 30px 16px;
            color: var(--text-lighter);
            background: var(--light);
            border-radius: var(--radius-md);
        }

        .section-empty i {
            font-size: 32px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

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
        /* ================= LOADING OVERLAY ================= */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            color: white;
            text-align: center;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .loading-text {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .loading-subtext {
            font-size: 12px;
            opacity: 0.8;
            max-width: 250px;
            line-height: 1.5;
            padding: 0 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-top">
                <button class="header-icon" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="logo">
                    <i class="fas fa-futbol logo-icon"></i>
                    Detail Main Bareng
                </div>
                <div class="header-actions">
                    @if($isHost)
                        <button class="header-icon" onclick="window.location.href='{{ route('buyer.main_bareng_saya.edit', $playTogether->id) }}'">
                            <i class="fas fa-edit"></i>
                        </button>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content" style="padding-top: 60px;">
            <!-- Hero Image -->
            <div class="detail-hero">
                @if($playTogether->booking->venue->photo)
                    <img src="{{ asset('storage/' . $playTogether->booking->venue->photo) }}" alt="Venue">
                @else
                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Venue">
                @endif
                <div class="detail-hero-overlay">
                    <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 4px;">
                        {{ $playTogether->booking->venue->venue_name }}
                    </h2>
                </div>
            </div>

            <!-- Info Section -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Utama
                </h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt"></i>
                            Tanggal
                        </div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Lokasi
                        </div>
                        <div class="detail-value">{{ $playTogether->location }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-users"></i>
                            Peserta
                        </div>
                        <div class="detail-value">
                            {{ $approvedParticipants->count() }} / {{ $playTogether->max_participants }} orang
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Biaya
                        </div>
                        <div class="detail-value">
                            @if($playTogether->type === 'paid')
                                <span class="badge badge-cost">
                                    Rp {{ number_format($playTogether->price_per_person, 0, ',', '.') }}/orang
                                </span>
                            @else
                                <span class="badge badge-free">GRATIS</span>
                            @endif
                        </div>
                    </div>
                    @if($playTogether->description)
                    <div class="detail-item" style="flex-direction: column; align-items: flex-start;">
                        <div class="detail-label" style="margin-bottom: 8px;">
                            <i class="fas fa-align-left"></i>
                            Deskripsi
                        </div>
                        <div class="detail-value" style="text-align: left; font-weight: normal;">
                            {{ $playTogether->description }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Participants Section -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Peserta Disetujui ({{ $approvedParticipants->count() }})
                </h3>
                @if($approvedParticipants->count() > 0)
                    @foreach($approvedParticipants as $participant)
                        <div class="participant-card">
                            <div class="participant-avatar">
                                @if($participant->user->avatar)
                                    <img src="{{ asset('storage/' . $participant->user->avatar) }}" alt="{{ $participant->user->name }}">
                                @else
                                    {{ substr($participant->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="participant-info">
                                <div class="participant-name">
                                    {{ $participant->user->name }}
                                    @if($participant->user_id == $playTogether->created_by)
                                        <span class="badge badge-host-yes" style="font-size: 9px; padding: 2px 6px;">
                                            <i class="fas fa-crown"></i> HOST
                                        </span>
                                    @endif

                                    {{-- LABEL STATUS PEMBAYARAN (Lunas / Belum Lunas) --}}
                                    @if($playTogether->type === 'paid')
                                        @if($participant->payment_status === 'paid')
                                            <span class="badge-lunas">LUNAS</span>
                                        @else
                                            <span class="badge-belum-lunas">BELUM LUNAS</span>
                                        @endif
                                    @endif
                                </div>
                                <div class="participant-phone">{{ $participant->user->phone }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="section-empty">
                        <i class="fas fa-user-slash"></i>
                        <p>Belum ada peserta yang disetujui</p>
                    </div>
                @endif
            </div>

            <!-- Host Features -->
            @if($isHost)
                <!-- Pending Approval Section -->
                @if($pendingParticipants->count() > 0)
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-clock"></i>
                        Menunggu Persetujuan ({{ $pendingParticipants->count() }})
                    </h3>
                    @foreach($pendingParticipants as $participant)
                        <div class="participant-card">
                            <div class="participant-avatar">
                                @if($participant->user->avatar)
                                    <img src="{{ asset('storage/' . $participant->user->avatar) }}" alt="{{ $participant->user->name }}">
                                @else
                                    {{ substr($participant->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="participant-info">
                                <div class="participant-name">{{ $participant->user->name }}</div>
                                <div class="participant-phone">{{ $participant->user->phone }}</div>
                            </div>
                            <div class="participant-actions">
                                <button class="btn-approve" onclick="approveParticipant({{ $participant->id }})">
                                    <i class="fas fa-check"></i> Setuju
                                </button>
                                <button class="btn-reject" onclick="rejectParticipant({{ $participant->id }})">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Add Participant Section -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-plus"></i>
                        Tambah Peserta
                    </h3>
                    <div class="search-box" style="margin-bottom: 16px;">
                        <input type="text" 
                               class="search-input" 
                               placeholder="Cari berdasarkan nama atau nomor telepon..." 
                               id="userSearchInput"
                               onkeyup="searchUsers()">
                    </div>
                    <div id="userSearchResults"></div>
                </div>

                <!-- Invitations Section -->
                @if($invitations->count() > 0)
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-envelope"></i>
                        Undangan Terkirim ({{ $invitations->count() }})
                    </h3>
                    @foreach($invitations as $invitation)
                        <div class="invitation-card">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <div style="font-weight: 700; color: var(--text); font-size: 13px;">
                                    {{ $invitation->invitedUser->name }}
                                </div>
                                <span class="badge badge-{{ $invitation->status === 'pending' ? 'warning' : ($invitation->status === 'accepted' ? 'active' : 'canceled') }}">
                                    {{ strtoupper($invitation->status) }}
                                </span>
                            </div>
                            <div style="font-size: 11px; color: var(--text-light);">
                                {{ $invitation->invitedUser->phone }}
                            </div>
                            <div style="font-size: 10px; color: var(--text-lighter); margin-top: 4px;">
                                <i class="fas fa-clock"></i> {{ $invitation->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            @endif

            <!-- User Status (Non-Host) -->
            @if(!$isHost && $userParticipant)
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-check"></i>
                        Status Partisipasi Anda
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-flag"></i>
                                Status
                            </div>
                            <div class="detail-value">
                                @if($userParticipant->approval_status === 'pending')
                                    <span class="badge" style="background: #FFF3E0; color: #E65100;">
                                        <i class="fas fa-clock"></i> MENUNGGU PERSETUJUAN
                                    </span>
                                @elseif($userParticipant->approval_status === 'approved')
                                    <span class="badge badge-active">
                                        <i class="fas fa-check"></i> DISETUJUI
                                    </span>
                                @else
                                    <span class="badge badge-canceled">
                                        <i class="fas fa-times"></i> DITOLAK
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL BAYAR JIKA DISETUJUI TAPI BELUM LUNAS --}}
                    @if($userParticipant->approval_status === 'approved' && $userParticipant->payment_status === 'pending' && $playTogether->type === 'paid')
                        <div style="margin-top: 16px;">
                            <button id="payButton" class="btn-approve" style="width: 100%; padding: 12px; font-size: 14px; background: var(--primary); color: white; border: none;">
                                <i class="fas fa-money-bill-wave"></i> Bayar Sekarang
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </main>

        <!-- Bottom Nav -->
        <nav class="bottom-nav">
            <a href="{{ route('buyer.home') }}" class="nav-item">
                <i class="fas fa-home nav-icon"></i>
                Beranda
            </a>
            <a href="{{ route('buyer.main_bareng.index') }}" class="nav-item active">
                <i class="fas fa-futbol nav-icon"></i>
                Main Bareng
            </a>
            <a href="{{ route('buyer.explore') }}" class="nav-item">
                <i class="fas fa-search nav-icon"></i>
                Explore
            </a>
            <a href="{{ route('buyer.chat') }}" class="nav-item">
                <i class="fas fa-comments nav-icon"></i>
                Chat
            </a>
            <a href="{{ route('buyer.profile') }}" class="nav-item">
                <i class="fas fa-user nav-icon"></i>
                Profil
            </a>
        </nav>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="loading-spinner"></div>
            <div class="loading-text" id="loadingText">Memproses...</div>
            <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $setting->midtrans_client_key ?? '' }}"></script>
    <script>
        // Pay Button Handler
        if(document.getElementById('payButton')) {
            document.getElementById('payButton').addEventListener('click', function() {
                payNow();
            });
        }

        async function payNow() {
            showLoading('Mendapatkan data pembayaran...');

            try {
                const response = await fetch(`{{ route('buyer.main_bareng_saya.getSnapToken', $playTogether->id) }}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                hideLoading();

                if (result.success && result.snap_token) {
                    snap.pay(result.snap_token, {
                        onSuccess: function(result) {
                            showLoading('Sinkronisasi status pembayaran...');

                            // Call updatePaymentStatus on backend
                            fetch(`{{ route('buyer.main_bareng_saya.updatePaymentStatus', $playTogether->id) }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    status: result.transaction_status,
                                    order_id: result.order_id,
                                    gross_amount: result.gross_amount
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showToast('Pembayaran berhasil!', 'success');
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    showToast(data.message || 'Gagal sinkronisasi pembayaran.', 'error');
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error('Update Error:', err);
                                showToast('Pembayaran berhasil, tapi gagal sinkronisasi.', 'warning');
                                setTimeout(() => window.location.reload(), 2000);
                            });
                        },
                        onPending: function(result) {
                            showToast('Menunggu pembayaran...', 'info');
                            setTimeout(() => window.location.reload(), 1500);
                        },
                        onError: function(result) {
                            showToast('Pembayaran gagal.', 'error');
                        },
                        onClose: function() {
                            showToast('Pembayaran dibatalkan.', 'info');
                        }
                    });
                } else {
                    showToast(result.message || 'Gagal memulai pembayaran', 'error');
                }
            } catch (error) {
                hideLoading();
                console.error('Error paying:', error);
                showToast('Terjadi kesalahan sistem', 'error');
            }
        }

        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            if (overlay && text) {
                text.textContent = message;
                overlay.style.display = 'flex';
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }

        let searchTimeout;

        // Search users for invitation
        function searchUsers() {
            const input = document.getElementById('userSearchInput');
            const search = input.value.trim();
            const resultsDiv = document.getElementById('userSearchResults');

            clearTimeout(searchTimeout);

            if (search.length < 2) {
                resultsDiv.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`{{ route('buyer.main_bareng_saya.searchUsers', $playTogether->id) }}?search=${encodeURIComponent(search)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const result = await response.json();

                    if (result.success && result.data.length > 0) {
                        let html = '<div style="background: white; border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; max-height: 300px; overflow-y: auto;">';
                        
                        result.data.forEach(user => {
                            html += `
                                <div class="user-search-result" onclick="inviteUser(${user.id}, '${user.name}')">
                                    <div class="participant-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                                        ${user.avatar ? `<img src="/storage/${user.avatar}" alt="${user.name}">` : user.name.charAt(0)}
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 700; font-size: 13px; color: var(--text);">${user.name}</div>
                                        <div style="font-size: 11px; color: var(--text-light);">${user.phone}</div>
                                    </div>
                                    <i class="fas fa-plus-circle" style="color: var(--primary); font-size: 20px;"></i>
                                </div>
                            `;
                        });
                        
                        html += '</div>';
                        resultsDiv.innerHTML = html;
                    } else {
                        resultsDiv.innerHTML = '<div class="section-empty"><i class="fas fa-user-slash"></i><p>Tidak ada pengguna ditemukan</p></div>';
                    }
                } catch (error) {
                    console.error('Error searching users:', error);
                }
            }, 500);
        }

        // Invite user
        async function inviteUser(userId, userName) {
            if (!confirm(`Kirim undangan ke ${userName}?`)) return;

            showLoading(`Mengirim undangan ke ${userName}...`);

            try {
                const response = await fetch(`{{ route('buyer.main_bareng_saya.inviteUser', $playTogether->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                const result = await response.json();
                hideLoading();

                if (result.success) {
                    showToast(result.message, 'success');
                    document.getElementById('userSearchInput').value = '';
                    document.getElementById('userSearchResults').innerHTML = '';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(result.message || 'Gagal mengirim undangan', 'error');
                }
            } catch (error) {
                hideLoading();
                console.error('Error inviting user:', error);
                showToast('Terjadi kesalahan jaringan', 'error');
            }
        }

        // Approve participant
        async function approveParticipant(participantId) {
            if (!confirm('Setujui peserta ini?')) return;

            showLoading('Menyetujui peserta...');

            try {
                const response = await fetch(`{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}/approve/${participantId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                hideLoading();

                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(result.message || 'Gagal menyetujui peserta', 'error');
                }
            } catch (error) {
                hideLoading();
                console.error('Error approving participant:', error);
                showToast('Terjadi kesalahan jaringan', 'error');
            }
        }

        // Reject participant
        async function rejectParticipant(participantId) {
            if (!confirm('Tolak peserta ini?')) return;

            showLoading('Menolak peserta...');

            try {
                const response = await fetch(`{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}/reject/${participantId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                hideLoading();

                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(result.message || 'Gagal menolak peserta', 'error');
                }
            } catch (error) {
                hideLoading();
                console.error('Error rejecting participant:', error);
                showToast('Terjadi kesalahan jaringan', 'error');
            }
        }

        // Show Toast
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle'}"></i>
                </div>
                <div class="toast-content">
                    <h4>${type === 'success' ? 'Sukses!' : type === 'error' ? 'Error!' : 'Info'}</h4>
                    <p>${message}</p>
                </div>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
@endpush

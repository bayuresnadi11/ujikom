@extends('layouts.admin', ['title' => 'Permintaan Role'])

@push('styles')
@include('buyer.home.partials.home-style')
@include('buyer.profile.partials.role-request-style')
@endpush

@section('content')
<div class="mobile-container">
    <!-- ================= HEADER ================= -->
    @include('layouts.header')

    <main class="main-content">
        <!-- ================= PAGE HEADER ================= -->
        <section class="page-header">
            <div class="page-header-content">
                <a href="{{ route('admin.dashboard.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="page-title">Permintaan Role</h1>
                    <p class="page-subtitle">Kelola permintaan pengguna untuk menjadi pemilik lapangan</p>
                </div>
            </div>
        </section>

        <!-- ================= STATS ================= -->
        <div class="summary-cards">
            <div class="summary-card total">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-number" id="totalCount">{{ $roleRequests->total() }}</div>
                <div class="stat-label">Total Request</div>
            </div>
            
            <div class="summary-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="pendingCount">
                    {{ $roleRequests->where('status', 'pending')->count() }}
                </div>
                <div class="stat-label">Pending</div>
            </div>
            
            <div class="summary-card approved">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number" id="approvedCount">
                    {{ $roleRequests->where('status', 'approved')->count() }}
                </div>
                <div class="stat-label">Disetujui</div>
            </div>
            
            <div class="summary-card rejected">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number" id="rejectedCount">
                    {{ $roleRequests->where('status', 'rejected')->count() }}
                </div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>

        <!-- ================= FILTER TABS ================= -->
        <div class="filter-tabs">
            <div class="tab-list">
                <button class="tab-button active" data-filter="all">
                    <span>Semua</span>
                    <span class="tab-count">{{ $roleRequests->total() }}</span>
                </button>
                <button class="tab-button" data-filter="pending">
                    <i class="fas fa-clock"></i>
                    <span>Pending</span>
                    <span class="tab-count">{{ $roleRequests->where('status', 'pending')->count() }}</span>
                </button>
                <button class="tab-button" data-filter="approved">
                    <i class="fas fa-check"></i>
                    <span>Disetujui</span>
                    <span class="tab-count">{{ $roleRequests->where('status', 'approved')->count() }}</span>
                </button>
                <button class="tab-button" data-filter="rejected">
                    <i class="fas fa-times"></i>
                    <span>Ditolak</span>
                    <span class="tab-count">{{ $roleRequests->where('status', 'rejected')->count() }}</span>
                </button>
            </div>
        </div>

        <!-- ================= REQUEST LIST ================= -->
        <div id="requestsContainer" class="requests-container">
            @foreach ($roleRequests as $request)
                <div class="request-card" data-status="{{ $request->status }}" data-id="{{ $request->id }}">
                    <!-- Header -->
                    <div class="request-header">
                        <div class="user-info">
                            <img 
                                src="{{ $request->user->profile_photo ? asset('storage/' . $request->user->profile_photo) : asset('images/default-avatar.png') }}"
                                class="user-avatar"
                                alt="{{ $request->user->name }}"
                            >
                            <div class="user-details">
                                <div class="user-name">{{ $request->user->name }}</div>
                                <div class="user-email">{{ $request->user->email }}</div>
                                @if($request->user->phone)
                                    <div class="user-phone">
                                        <i class="fas fa-phone"></i>
                                        {{ $request->user->phone }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="request-meta">
                            <span class="meta-item">
                                <i class="fas fa-user-tag"></i>
                                Saat ini: {{ ucfirst($request->user->role) }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $request->created_at->format('d M Y') }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                {{ $request->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="request-body">
                        <div class="request-title">
                            <i class="fas fa-exchange-alt"></i>
                            Permintaan Perubahan Role
                        </div>
                        
                        <div class="request-details">
                            <div class="detail-item">
                                <div class="detail-label">Role Saat Ini</div>
                                <div class="detail-value">{{ ucfirst($request->user->role) }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Role Diminta</div>
                                <div class="detail-value">
                                    <span class="badge-pending" style="font-size: 10px; padding: 4px 8px;">
                                        {{ ucfirst($request->requested_role) }}
                                    </span>
                                </div>
                            </div>
                            @if($request->processed_at)
                                <div class="detail-item">
                                    <div class="detail-label">Diproses</div>
                                    <div class="detail-value">{{ $request->processed_at->format('d M Y H:i') }}</div>
                                </div>
                                @if($request->admin)
                                    <div class="detail-item">
                                        <div class="detail-label">Diproses Oleh</div>
                                        <div class="detail-value">{{ $request->admin->name }}</div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        @if($request->reason)
                            <div class="reason-box">
                                <div class="reason-label">Alasan Permintaan</div>
                                <div class="reason-text">{{ $request->reason }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="request-footer">
                        <!-- Status Badge -->
                        @if($request->status === 'pending')
                            <span class="status-badge badge-pending">
                                <i class="fas fa-clock"></i> Menunggu
                            </span>
                        @elseif($request->status === 'approved')
                            <span class="status-badge badge-approved">
                                <i class="fas fa-check"></i> Disetujui
                            </span>
                        @elseif($request->status === 'rejected')
                            <span class="status-badge badge-rejected">
                                <i class="fas fa-times"></i> Ditolak
                            </span>
                        @endif

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            @if($request->status === 'pending')
                                <form method="POST" action="{{ route('admin.rolerequest.approve') }}" class="approve-form" data-id="{{ $request->id }}">
                                    @csrf
                                    <input type="hidden" name="request_id" value="{{ $request->id }}">
                                    <button type="submit" class="btn-action btn-approve">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.rolerequest.reject') }}" class="reject-form" data-id="{{ $request->id }}">
                                    @csrf
                                    <input type="hidden" name="request_id" value="{{ $request->id }}">
                                    <button type="submit" class="btn-action btn-reject">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            @else
                                <button class="btn-action btn-approve" disabled>
                                    <i class="fas fa-check"></i> {{ $request->status === 'approved' ? 'Disetujui' : 'Selesai' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($roleRequests->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox empty-state-icon"></i>
                    <h3 class="empty-state-title">Belum ada permintaan</h3>
                    <p class="empty-state-desc">
                        Belum ada pengguna yang mengajukan permintaan perubahan role
                    </p>
                </div>
            @endif
        </div>
    </main>

    <!-- ================= TOAST ================= -->
    <div class="toast" id="toast"></div>

    <!-- ================= LOADING ================= -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memproses...</div>
    </div>

    <!-- ================= BOTTOM NAV ================= -->
    @include('layouts.admin-bottom-nav')
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // ================= TAB FILTER =================
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                filterRequests(filter);
            });
        });
        
        // ================= FILTER REQUESTS =================
        function filterRequests(status) {
            const cards = document.querySelectorAll('.request-card');
            
            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // ================= FORM SUBMISSION =================
        document.addEventListener('submit', async function(e) {
            if (!e.target.classList.contains('approve-form') && !e.target.classList.contains('reject-form')) return;
            e.preventDefault();
            
            const form = e.target;
            const requestId = form.dataset.id;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const isApprove = form.classList.contains('approve-form');
            
            // Disable button & show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            showLoading(true);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        request_id: requestId
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    updateRequestCard(requestId, isApprove);
                    showToast(data.message || 'Berhasil diproses', 'success');
                } else {
                    showToast(data.message || 'Gagal memproses', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('AJAX error:', error);
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            } finally {
                showLoading(false);
            }
        });
        
        // ================= UPDATE CARD AFTER ACTION =================
        function updateRequestCard(requestId, isApprove) {
            const card = document.querySelector(`.request-card[data-id="${requestId}"]`);
            if (!card) return;
            
            const status = isApprove ? 'approved' : 'rejected';
            card.dataset.status = status;
            
            // Update status badge
            const statusBadge = card.querySelector('.status-badge');
            if (statusBadge) {
                if (isApprove) {
                    statusBadge.className = 'status-badge badge-approved';
                    statusBadge.innerHTML = '<i class="fas fa-check"></i> Disetujui';
                } else {
                    statusBadge.className = 'status-badge badge-rejected';
                    statusBadge.innerHTML = '<i class="fas fa-times"></i> Ditolak';
                }
            }
            
            // Update action buttons
            const actionButtons = card.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.innerHTML = `
                    <button class="btn-action btn-approve" disabled>
                        <i class="fas fa-check"></i> ${isApprove ? 'Disetujui' : 'Ditolak'}
                    </button>
                `;
            }
            
            // Update processed info
            const detailsContainer = card.querySelector('.request-details');
            if (detailsContainer) {
                const now = new Date();
                const formattedDate = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
                const formattedTime = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                detailsContainer.innerHTML += `
                    <div class="detail-item">
                        <div class="detail-label">Diproses</div>
                        <div class="detail-value">${formattedDate} ${formattedTime}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Diproses Oleh</div>
                        <div class="detail-value">Anda</div>
                    </div>
                `;
            }
            
            // Update counters
            updateCounters();
        }
        
        // ================= UPDATE COUNTERS =================
        function updateCounters() {
            const cards = document.querySelectorAll('.request-card');
            let pending = 0, approved = 0, rejected = 0;
            
            cards.forEach(card => {
                if (card.style.display !== 'none') {
                    const status = card.dataset.status;
                    if (status === 'pending') pending++;
                    else if (status === 'approved') approved++;
                    else if (status === 'rejected') rejected++;
                }
            });
            
            document.getElementById('pendingCount').textContent = pending;
            document.getElementById('approvedCount').textContent = approved;
            document.getElementById('rejectedCount').textContent = rejected;
            document.getElementById('totalCount').textContent = pending + approved + rejected;
            
            // Update tab counts
            document.querySelectorAll('.tab-count').forEach(count => {
                const tab = count.closest('.tab-button');
                if (tab.dataset.filter === 'pending') count.textContent = pending;
                else if (tab.dataset.filter === 'approved') count.textContent = approved;
                else if (tab.dataset.filter === 'rejected') count.textContent = rejected;
                else count.textContent = pending + approved + rejected;
            });
        }
        
        // ================= TOAST =================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.className = `toast ${type} show`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            `;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // ================= LOADING OVERLAY =================
        function showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            overlay.style.display = show ? 'flex' : 'none';
        }
        
        // ================= REFRESH BUTTON =================
        document.getElementById('refreshBtn').addEventListener('click', function() {
            window.location.reload();
        });
    });
</script>
@endpush
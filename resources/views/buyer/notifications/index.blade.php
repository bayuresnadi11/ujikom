@extends('layouts.main', ['title' => 'Notifikasi - SewaLap'])

@push('styles')
@include('buyer.notifications.partials.notifications-style')
@endpush

@section('content')
    <div class="mobile-container" id="mobileContainer">
        <!-- New Header -->
        <header class="fixed-header">
            <a href="{{ route('buyer.home') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="header-title">Notifikasi</h1>
            <button class="menu-dots-btn" id="openMenuBtn">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </header>

    <main class="main-content">
            <!-- Filter Tipe -->
            <div class="filter-tabs">
                <button class="filter-btn active" id="filterAll" onclick="filterByType('all')">
                    Semua
                </button>
                <button class="filter-btn" id="filterCommunity" onclick="filterByType('community')">
                    Komunitas
                </button>
            </div>

            <!-- Community Profile Header (Visible only when filtering by Community) -->
            @if(count($userCommunities) > 0)
            <div class="community-context-header" id="communityHeader">
                @foreach($userCommunities as $community)
                <div class="community-context-item {{ $loop->first ? 'active' : '' }}" 
                     onclick="filterByCommunity('{{ $community->id }}', this)"
                     data-community-id="{{ $community->id }}">
                    <div class="community-context-logo-container">
                        <img src="{{ $community->logo ? asset('storage/' . $community->logo) : asset('images/default-community.png') }}" 
                             alt="{{ $community->name }}" class="community-context-logo-img">
                    </div>
                    <div class="community-context-info">
                        <h2 class="community-context-name">{{ $community->name }}</h2>
                        <p class="community-context-category">{{ $community->category->category_name ?? '-' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        <!-- Notification Tabs (Pill Style) -->
        <div class="notification-tabs-pill">
            <button class="pill-btn active" onclick="filterNotifications('unread')" id="tabUnread">
                Belum Dibaca
                @if($unreadCount > 0)
                    <span class="pill-badge">{{ $unreadCount }}</span>
                @endif
            </button>
            <button class="pill-btn" onclick="filterNotifications('read')" id="tabRead">
                Sudah Dibaca
                @php
                    $readCount = count(array_filter($allNotifications, function($n) { return $n['is_read']; }));
                @endphp
                @if($readCount > 0)
                    <span class="pill-badge">{{ $readCount }}</span>
                @endif
            </button>
        </div>

        <!-- Notifications List -->
        <div class="notifications-list" id="notificationsList">
            @forelse($allNotifications as $notification)
                @php
                    // Determine notification type class
                    $typeClass = $notification['type'] ?? 'update';
                    $typeIcon = $notification['icon'] ?? 'fas fa-bell';
                    $typeColor = $notification['color'] ?? 'primary';
                    
                    // Handle different date formats
                    if ($notification['created_at'] instanceof \Carbon\Carbon) {
                        $timeAgo = $notification['created_at']->diffForHumans();
                        $fullDate = $notification['created_at']->format('d M Y H:i');
                    } else {
                        $timeAgo = \Carbon\Carbon::parse($notification['created_at'])->diffForHumans();
                        $fullDate = \Carbon\Carbon::parse($notification['created_at'])->format('d M Y H:i');
                    }
                    
                    // Get notification data
                    $notificationData = $notification['data'] ?? [];
                    $communityName = $notificationData['community_name'] ?? null;
                    $message = $notification['message'] ?? '';
                    $title = $notification['title'] ?? 'Notifikasi';
                    
                    // Check if it's a community notification
                    $isCommunity = stripos($notification['type'] ?? '', 'community') !== false;
                    $isCommunityMessage = ($notification['type'] ?? '') === 'community_message';
                    $isCommunityRemoved = ($notification['type'] ?? '') === 'community_removed';
                    $isCommunityDisbanded = ($notification['type'] ?? '') === 'community_disbanded';
                    $isCommunityRoleChanged = ($notification['type'] ?? '') === 'community_role_changed';
                    $isCommunityInvitation = ($notification['type'] ?? '') === 'community_invitation';
                    $isMainBarengInvitation = ($notification['type'] ?? '') === 'main_bareng_invitation';
                    $isMainBarengJoinRequest = ($notification['type'] ?? '') === 'main_bareng_join_request';
                    $isMainBarengJoinRequestPaid = ($notification['type'] ?? '') === 'main_bareng_join_request_paid';
                    $isPaymentSuccess = ($notification['type'] ?? '') === 'payment_success';
                    $isPaymentReceived = ($notification['type'] ?? '') === 'payment_received';
                    
                    // Specific status for join requests
                    $notificationType = $notificationData['notification_type'] ?? null;
                    $isRequestApproved = $notificationType === 'community_request_approved';
                    $isRequestRejected = $notificationType === 'community_request_rejected';
                    $isMemberLeft = ($notification['type'] ?? '') === 'community_member_left';
                    
                    // Determine action URL
                    $actionUrl = $notification['action_url'] ?? null;
                    if (!$actionUrl && $isCommunity && isset($notificationData['community_id'])) {
                        $actionUrl = route('buyer.communities.show', $notificationData['community_id']);
                    }
                @endphp
                
                <div class="notification-item {{ $notification['is_read'] ? 'read' : 'unread' }}"
                     data-type="{{ $notification['type'] ?? 'update' }}"
                     data-id="{{ $notification['id'] }}"
                     data-community-id="{{ $notificationData['community_id'] ?? '' }}"
                     data-source="{{ $notification['source'] ?? 'laravel' }}"
                     onclick="handleNotificationClick('{{ $notification['id'] }}', '{{ $notification['source'] ?? 'laravel' }}', '{{ $actionUrl }}')">

                    <!-- Notification Icon -->
                    @if(!empty($notification['avatar_url']))
                        <img src="{{ $notification['avatar_url'] }}" alt="Avatar" class="notification-avatar">
                    @elseif(!empty($notification['initials']))
                        <div class="notification-avatar" style="background-color: {{ $notification['avatar_color'] ?? 'var(--primary)' }}; color: white; font-weight: 700;">
                            {{ $notification['initials'] }}
                        </div>
                    @else
                        <div class="notification-icon {{ $notification['type'] }}" style="background: var(--color-{{ $notification['color'] ?? 'primary' }})">
                            <i class="{{ $notification['icon'] ?? 'fas fa-bell' }}"></i>
                        </div>
                    @endif

                    <!-- Notification Content -->
                    <div class="notification-content">
                        <div class="notification-header">
                            <h4 class="notification-title">{{ $title }}</h4>
                            <span class="notification-time" title="{{ $fullDate }}">
                                    {{ $timeAgo }}
                            </span>
                        </div>
                        
                        <p class="notification-message">
                            @if($isCommunityRemoved)
                                <i class="fas fa-user-slash text-danger"></i> 
                                Anda telah dikeluarkan dari komunitas <strong>{{ $communityName ?? 'Komunitas' }}</strong>
                            @elseif($isCommunityDisbanded)
                                <i class="fas fa-trash-alt text-danger"></i> 
                                Komunitas <strong>{{ $communityName ?? 'Komunitas' }}</strong> telah dibubarkan oleh pembuatnya
                            @elseif($isMemberLeft)
                                <i class="fas fa-sign-out-alt text-warning"></i> 
                                {{ $message }}
                            @elseif($isCommunityRoleChanged)
                                <i class="fas fa-user-shield text-warning"></i> 
                                Role Anda di komunitas <strong>{{ $communityName ?? 'Komunitas' }}</strong> telah diubah
                            @elseif($isCommunityMessage)
                                <i class="fas fa-comment-dots text-info"></i> 
                                Pesan dari komunitas <strong>{{ $communityName ?? 'Komunitas' }}</strong>
                            @elseif($isCommunityInvitation || $notificationType === 'community_reinvitation')
                                <i class="fas fa-envelope text-success"></i> 
                                {{ $message }}
                            @elseif($isMainBarengInvitation)
                                <i class="fas fa-futbol text-success"></i> 
                                {{ $message }}
                            @elseif($isMainBarengJoinRequest)
                                <i class="fas fa-user-plus text-info"></i> 
                                {{ $message }}
                            @elseif($isMainBarengJoinRequestPaid)
                                <i class="fas fa-money-check-alt text-success"></i> 
                                {{ $message }}
                            @elseif($notificationType === 'community_request_pending')
                                <i class="fas fa-users text-info"></i> 
                                {{ $message }}
                            @elseif($isRequestApproved)
                                <i class="fas fa-check-circle text-success"></i> 
                                {{ $message }}
                            @elseif($isRequestRejected)
                                <i class="fas fa-times-circle text-danger"></i> 
                                {{ $message }}
                            @elseif($notificationType === 'community_invitation_accepted')
                                <i class="fas fa-check-circle text-success"></i> 
                                {{ $message }}
                            @elseif($notificationType === 'community_invitation_rejected')
                                <i class="fas fa-times-circle text-danger"></i> 
                                {{ $message }}
                            @elseif($isPaymentReceived)
                                <i class="fas fa-money-bill-wave text-success"></i> 
                                {{ $message }}
                            @elseif($notificationType === 'role_request_approved')
                                <i class="fas fa-check-circle text-success"></i> 
                                {{ $message }}
                            @elseif($notificationType === 'role_request_rejected')
                                <i class="fas fa-times-circle text-danger"></i> 
                                {{ $message }}
                            @else
                                {{ $message }}
                            @endif
                        </p>

                        <!-- Action Buttons -->
                        <div class="notification-actions">
                            @if($isCommunity)
                                @if($isCommunityInvitation || $notificationType === 'community_reinvitation')
                                    @if(isset($notificationData['action_taken']) && $notificationData['action_taken'])
                                        <div class="action-status">
                                            @if(($notificationData['status'] ?? '') === 'accepted')
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Undangan Diterima</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fas fa-times"></i> Undangan Ditolak</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="membership-actions">
                                            <button class="btn-approve" onclick="event.stopPropagation(); acceptInvitation('{{ $notification['id'] }}', '{{ $notificationData['community_id'] ?? '' }}')">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                            <button class="btn-reject" onclick="event.stopPropagation(); rejectInvitation('{{ $notification['id'] }}', '{{ $notificationData['community_id'] ?? '' }}')">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </div>
                                    @endif
                                @elseif($actionUrl)
                                    <button class="btn-notification-action" onclick="event.stopPropagation(); window.location.href='{{ $actionUrl }}'">
                                        @if($isCommunityRemoved || $isCommunityDisbanded)
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        @else
                                            <i class="fas fa-external-link-alt"></i> Lihat Komunitas
                                        @endif
                                    </button>
                                @endif
                            @elseif($isMainBarengJoinRequest)
                                @php
                                    $participantId = $notificationData['participant_id'] ?? null;
                                    $playTogetherId = $notificationData['play_together_id'] ?? null;
                                    $status = $notificationData['status'] ?? 'pending';
                                    $actionTaken = isset($notificationData['action_taken']) ? $notificationData['action_taken'] : false;
                                @endphp

                                @if($actionTaken)
                                    <div class="action-status">
                                        @if($status === 'approved')
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Peserta Diterima</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times"></i> Peserta Ditolak</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="membership-actions">
                                        <button class="btn-approve" onclick="event.stopPropagation(); approveMainBarengJoinRequest('{{ $notification['id'] }}', '{{ $playTogetherId }}', '{{ $participantId }}')">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                        <button class="btn-reject" onclick="event.stopPropagation(); rejectMainBarengJoinRequest('{{ $notification['id'] }}', '{{ $playTogetherId }}', '{{ $participantId }}')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                @endif
                            @elseif($isMainBarengInvitation)
                                @php
                                    $invitationId = $notificationData['invitation_id'] ?? null;
                                    $status = $notificationData['status'] ?? 'pending';
                                    $actionTaken = isset($notificationData['action_taken']) ? $notificationData['action_taken'] : false;
                                @endphp

                                @if($actionTaken)
                                    <div class="action-status">
                                        @if($status === 'accepted')
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Undangan Diterima</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times"></i> Undangan Ditolak</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="membership-actions">
                                        <button class="btn-approve" onclick="event.stopPropagation(); acceptMainBarengInvitation('{{ $notification['id'] }}', '{{ $invitationId }}')">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                        <button class="btn-reject" onclick="event.stopPropagation(); rejectMainBarengInvitation('{{ $notification['id'] }}', '{{ $invitationId }}')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                @endif
                            @elseif(($notification['type'] ?? '') === 'main_bareng_join_request_paid')
                                @php
                                    $participantId = $notificationData['participant_id'] ?? null;
                                    $playTogetherId = $notificationData['play_together_id'] ?? null;
                                    $status = $notificationData['status'] ?? 'pending';
                                    $actionTaken = isset($notificationData['action_taken']) ? $notificationData['action_taken'] : false;
                                @endphp

                                @if($actionTaken)
                                    <div class="action-status">
                                        @if($status === 'approved')
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Peserta Diterima</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times"></i> Peserta Ditolak</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="membership-actions">
                                        {{-- HANYA TOMBOL TERIMA KARENA SUDAH BAYAR --}}
                                        <button class="btn-approve" style="width: 100%;" onclick="event.stopPropagation(); approveMainBarengJoinRequest('{{ $notification['id'] }}', '{{ $playTogetherId }}', '{{ $participantId }}')">
                                            <i class="fas fa-check"></i> Terima (Sudah Bayar)
                                        </button>
                                    </div>
                                @endif
                             @elseif($actionUrl)
                                <button class="btn-notification-action" onclick="event.stopPropagation(); window.location.href='{{ $actionUrl }}'">
                                    <i class="fas fa-external-link-alt"></i> {{ $notification['action_text'] ?? 'Lihat Detail' }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Delete button -->
                    <button class="btn-delete-notification" onclick="event.stopPropagation(); deleteNotification('{{ $notification['id'] }}', '{{ $notification['source'] ?? 'laravel' }}')" title="Hapus notifikasi">
                        <i class="fas fa-trash-alt"></i>
                    </button>

                    <!-- Unread Indicator -->
                    @if(!$notification['is_read'])
                        <div class="unread-indicator"></div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-bell-slash empty-state-icon"></i>
                    <h3 class="empty-state-title">Belum ada notifikasi</h3>
                    <p class="empty-state-desc">Notifikasi akan muncul di sini ketika ada update penting</p>
                </div>
            @endforelse
        </div>
        
        <!-- Load More Button -->
        @if(count($allNotifications) > 0)
        <div class="load-more-section">
            <!-- <button class="btn-load-more" onclick="loadMoreNotifications()" id="loadMoreBtn">
                <i class="fas fa-sync-alt"></i> Muat Lebih Banyak
            </button> -->
        </div>
        @endif
    </main>
    
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses...</div>
        <div class="loading-subtext">Mohon tunggu sebentar, kami sedang mengirim notifikasi pemberitahuan.</div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>
    
    <!-- Bottom Sheet Menu -->
    <div class="bottom-sheet-overlay" id="bottomSheetOverlay"></div>
    <div class="bottom-sheet" id="bottomSheet">
        <div class="bottom-sheet-handle"></div>
        <div class="bottom-sheet-content">
            <div class="bottom-sheet-item" onclick="handleMarkAllAsRead()">
                <div class="bottom-sheet-icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="bottom-sheet-text">Tandai semua sudah dibaca</div>
            </div>
            <div class="bottom-sheet-item" onclick="showDeleteConfirm()">
                <div class="bottom-sheet-icon delete">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div class="bottom-sheet-text text-danger">Hapus semua notifikasi</div>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div class="custom-modal-overlay" id="confirmModalOverlay">
        <div class="custom-modal">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="modal-title">Hapus Notifikasi?</h3>
            <p class="modal-message">Apakah Anda yakin ingin menghapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button class="modal-btn btn-cancel" onclick="closeDeleteConfirm()">Batal</button>
                <button class="modal-btn btn-confirm" onclick="confirmDeleteAll()">Hapus Semua</button>
            </div>
        </div>
    </div>
    <!-- Bottom Nav -->
    @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
@include('buyer.notifications.partials.notifications-script')
@endpush
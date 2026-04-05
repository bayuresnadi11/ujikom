@extends('layouts.main', ['title' => 'Notifikasi - SewaLap'])

@push('styles')
@include('landowner.notifications.partials.notifications-style')
@endpush

@section('content')
@include('layouts.header')
<div class="mobile-container" id="mobileContainer">

    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Notifikasi</h1>
            <p class="page-subtitle">Update terbaru dan informasi penting</p>
        </div>

        <!-- Quick Stats & Actions -->
        <div class="actions-bar">
            <div class="notification-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $unreadCount }}</span>
                    <span class="stat-label">Belum Dibaca</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ count($allNotifications) }}</span>
                    <span class="stat-label">Total</span>
                </div>
            </div>
            @if($unreadCount > 0)
                <button class="btn-action" onclick="markAllAsRead()" id="markAllBtn">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <!-- Notification Tabs -->
        <div class="notification-tabs">
            <button class="tab-btn active" onclick="filterNotifications('all')" id="tabAll">
                <i class="fas fa-bell"></i> Semua
            </button>
            <button class="tab-btn" onclick="filterNotifications('unread')" id="tabUnread">
                <i class="fas fa-circle"></i> Belum Dibaca
                @if($unreadCount > 0)
                    <span class="badge-count">{{ $unreadCount }}</span>
                @endif
            </button>
        </div>

        <!-- Type Filters -->
        <div class="filter-tabs">
            <button class="filter-btn active" onclick="filterByType('all')" id="filterAll">
                Semua Tipe
            </button>
            <button class="filter-btn" onclick="filterByType('venue')" id="filterVenue">
                <i class="fas fa-map-marker-alt"></i> Venue
            </button>
        </div>

        <!-- Notifications List -->
        <div class="notifications-list" id="notificationsList">
            {{-- Melakukan loop koleksi data allNotifications yang bisa saja berasal dari module/tabel yang berbeda --}}
            @forelse($allNotifications as $notification)
                @php
                    // Logika Mapping Visual: Menentukan icon dan style list berdasarkan Tipe Notifikasinya
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
                    $venueName = $notificationData['venue_name'] ?? null;
                    $message = $notification['message'] ?? '';
                    $title = $notification['title'] ?? 'Notifikasi';

                    // Check notification types for landowners
                    $isVenueBooking = ($notification['type'] ?? '') === 'venue_booking';
                    $isVenuePayment = ($notification['type'] ?? '') === 'venue_payment';
                    $isScheduleUpdate = ($notification['type'] ?? '') === 'schedule_update';
                    $isCommunity = stripos($notification['type'] ?? '', 'community') !== false;
                    $isSystem = ($notification['type'] ?? '') === 'system';

                    // Determine action URL
                    // URL Generator Dinamis: Action button akan mengarahkan user langsung ke objek spesifik (misal ke modul Schedule atau Profil)
                    $actionUrl = $notification['action_url'] ?? null;
                    if (!$actionUrl && $isVenueBooking && isset($notificationData['venue_id'])) {
                        $actionUrl = route('landowner.venue.show', $notificationData['venue_id']);
                    }
                    if (!$actionUrl && $isScheduleUpdate && isset($notificationData['section_id'])) {
                        $actionUrl = route('landowner.schedule.index', ['section' => $notificationData['section_id']]);
                    }
                @endphp

                <div class="notification-item {{ $notification['is_read'] ? 'read' : 'unread' }}"
                     data-type="{{ $notification['type'] ?? 'update' }}"
                     data-id="{{ $notification['id'] }}"
                     data-source="{{ $notification['source'] ?? 'laravel' }}"
                     onclick="handleNotificationClick('{{ $notification['id'] }}', '{{ $notification['source'] ?? 'laravel' }}', '{{ $actionUrl }}')">

                    <!-- Notification Icon -->
                    <div class="notification-icon {{ $typeClass }}" style="background: var(--color-{{ $typeColor }})">
                        <i class="{{ $typeIcon }}"></i>
                    </div>

                    <!-- Notification Content -->
                    <div class="notification-content">
                        <div class="notification-header">
                            <h4 class="notification-title">{{ $title }}</h4>
                            <span class="notification-time" title="{{ $fullDate }}">
                                {{ $timeAgo }}
                            </span>
                        </div>

                        <p class="notification-message">
                            @if($isVenueBooking)
                                <i class="fas fa-calendar-plus text-primary"></i>
                                Booking baru untuk venue <strong>{{ $venueName ?? 'Venue' }}</strong>: {{ $message }}
                            @elseif($isVenuePayment)
                                <i class="fas fa-credit-card text-success"></i>
                                Pembayaran diterima untuk venue <strong>{{ $venueName ?? 'Venue' }}</strong>: {{ $message }}
                            @elseif($isScheduleUpdate)
                                <i class="fas fa-calendar-alt text-info"></i>
                                Update jadwal: {{ $message }}
                            @else
                                {{ $message }}
                            @endif
                        </p>

                        <!-- Action Buttons -->
                        <div class="notification-actions">
                            @if($actionUrl)
                                <button class="btn-notification-action" onclick="event.stopPropagation(); window.location.href='{{ $actionUrl }}'">
                                    <i class="fas fa-external-link-alt"></i> {{ $notification['action_text'] ?? 'Lihat Detail' }}
                                </button>
                            @endif

                            <!-- Delete button -->
                            <button class="btn-delete-notification" onclick="event.stopPropagation(); deleteNotification('{{ $notification['id'] }}', '{{ $notification['source'] ?? 'laravel' }}')" title="Hapus notifikasi">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

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
            <button class="btn-load-more" onclick="loadMoreNotifications()" id="loadMoreBtn">
                <i class="fas fa-sync-alt"></i> Muat Lebih Banyak
            </button>
        </div>
        @endif
    </main>

    @include('layouts.bottom-nav')

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>
</div>
@endsection

@push('scripts')
@include('landowner.notifications.partials.notifications-script')
@endpush
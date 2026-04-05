@extends('layouts.main', ['title' => 'Komunitas'])

@push('styles')
@include('buyer.communities.partials.communities-style')
<style>
    /* Override FAB container to stay within mobile container width */
    .fab-container {
        position: fixed !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 100% !important;
        max-width: 500px !important;
        bottom: 90px !important; /* Raised to avoid bottom nav */
        right: auto !important;
        pointer-events: none !important; /* Allow clicking through empty space */
        z-index: 1000;
        padding-right: 20px; /* Right margin effectively */
    }

    .fab-main {
        position: absolute !important;
        right: 20px !important;
        bottom: 0 !important;
        pointer-events: auto !important; /* Re-enable clicks */
    }

    .fab-menu {
        pointer-events: auto !important;
        right: 20px !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 480px) {
        .fab-container {
            bottom: 110px !important;
        }
    }


    



    


    /* Push Page Header down to avoid overlap with fixed navbar */
    .page-header {
        padding-top: 85px !important;
    }
</style>
@endpush

@section('content')
<div class="mobile-container" id="mobileContainer">
    @include('layouts.header', ['showSearch' => false])

    <main class="main-content">
        <!-- ================= PAGE HEADER ================= -->
        <section class="page-header">
            <h1 class="page-title">Komunitas</h1>
            <p class="page-subtitle">Temukan dan kelola komunitas olahraga Anda</p>
        </section>

        <!-- ================= COMMUNITY GRID ================= -->
        <div class="tab-content active" style="padding: 0 16px;">
            <div class="section-header" style="margin: 10px 0 15px; display: flex; align-items: center; gap: 10px;">
                <h2 style="font-size: 16px; font-weight: 800; color: #0A5C36; margin: 0;">Komunitas Saya</h2>
                <div style="flex: 1; height: 1px; background: #E1F0E7;"></div>
            </div>

            <div id="communityList" class="community-grid">
                @foreach ($allCommunities as $community)
                    <a href="{{ route('buyer.communities.show', $community->id) }}" class="community-item">
                        <div class="community-logo-wrapper">
                            <div class="community-logo-container">
                                <img src="{{ $community->logo && $community->logo != 'default.png' ? asset('storage/' . $community->logo) : asset('images/default-community.png') }}" 
                                     alt="{{ $community->name }}" class="community-logo-img">
                            </div>
                            @if ($community->is_manager)
                                <div class="community-badge-admin"><i class="fas fa-crown"></i></div>
                                @if (($community->pending_count ?? 0) > 0)
                                    <div class="community-badge-pending">
                                        {{ $community->pending_count }}
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="community-info">
                            <div class="community-name">{{ $community->name }}</div>
                            <div class="community-category">{{ $community->category->category_name ?? '-' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- ================= ACTION CARDS ================= -->
            <div class="action-card-section">
                <!-- CARI & GABUNG -->
                <a href="{{ route('communities.join') }}" class="menu-action-card">
                    <div class="action-card-icon icon-join">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="action-card-content">
                        <span class="action-card-title">Cari & Gabung</span>
                        <span class="action-card-desc">Temukan komunitas baru yang seru</span>
                    </div>
                    <div class="action-card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <!-- BUAT KOMUNITAS -->
                <a href="{{ route('buyer.communities.create') }}" class="menu-action-card">
                    <div class="action-card-icon icon-create">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="action-card-content">
                        <span class="action-card-title">Buat Komunitas</span>
                        <span class="action-card-desc">Bangun komunitas olahraga anda sendiri</span>
                    </div>
                    <div class="action-card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>

            @if ($allCommunities->isEmpty())
                <div class="empty-state" style="margin-top: 20px;">
                    <i class="fas fa-users-slash empty-state-icon"></i>
                    <h3 class="empty-state-title">Belum ada komunitas</h3>
                    <p class="empty-state-desc">Mulai petualangan olahraga Anda dengan bergabung atau membuat komunitas!</p>
                </div>
            @endif
        </div>



        <!-- ================= INVITE POPUP ================= -->
        <div class="invite-popup-overlay" id="invitePopup" onclick="closeInvitePopup()">
            <div class="invite-popup-box" onclick="event.stopPropagation()">
                

                @forelse($invitedCommunities as $invite)
                    <div class="invite-item">
                        <div class="invite-left">
                            <img
                                src="{{ $invite->community->logo
                                    ? asset('storage/'.$invite->community->logo)
                                    : asset('images/default-community.png') }}"
                                class="invite-logo"
                                alt="{{ $invite->community->name }}"
                            >
                            <div class="invite-info">
                                <strong class="invite-name">
                                    {{ $invite->community->name }}
                                </strong>
                                <small class="invite-category">
                                    {{ $invite->community->category->category_name ?? '-' }}
                                </small>
                            </div>
                        </div>
                        <div class="invite-actions">
                            <form action="{{ route('buyer.communities.joinInvite', $invite->community->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-accept">Terima</button>
                            </form>
                            <form action="{{ route('buyer.communities.rejectInvite', $invite->community->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-reject">Tolak</button>
                            </form>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>

    </main>

   <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
</div>
@endsection

@push('scripts')
@include('buyer.communities.partials.communities-script')
@endpush

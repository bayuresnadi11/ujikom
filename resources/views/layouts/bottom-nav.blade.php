@php
    // ===============================
    // DETECT STATE
    // ===============================
    $isAuth = auth()->check();
    $role = null;

    if ($isAuth) {
        // kalau ada switch role
        $role = session('temp_role') ?? auth()->user()->role;
    }

    // ===============================
    // BASE ROUTE
    // ===============================
    if (! $isAuth) {
        // GUEST
        $base = '';
    } elseif ($role === 'landowner') {
        $base = 'landowner';
    } else {
        // buyer
        $base = 'buyer';
    }

    // ===============================
    // CEK TIPE USER
    // ===============================
    $isGuest = ! $isAuth;
    $isBuyer = $base === 'buyer';
    $isLandowner = $base === 'landowner';
@endphp

@if($isGuest || $isBuyer)
    {{-- ================= NAVIGASI UNTUK GUEST DAN BUYER ================= --}}
    {{-- Format: Beranda - Komunitas - Main Bareng - Sparring - Booking - Profil/Login --}}
    <nav class="bottom-nav">
        {{-- ================= BERANDA ================= --}}
        @php
            if ($isGuest) {
                $isHomeActive = request()->routeIs(['welcome', 'welcome.*']);
                $homeUrl = route('welcome');
            } else {
                $isHomeActive = request()->routeIs(['buyer.home', 'buyer.home.*', 'buyer.notifications.*']);
                $homeUrl = route('buyer.home');
            }
        @endphp
        <a href="{{ $homeUrl }}"
           class="nav-item {{ $isHomeActive ? 'active' : '' }}">
            <i class="fas fa-home nav-icon"></i>
            <span>Beranda</span>
        </a>
        
        {{-- ================= KOMUNITAS ================= --}}
        @php
            if ($isGuest) {
                $communitiesUrl = route('communities.join');
                $isCommunitiesActive = request()->routeIs(['communities.join']);
            } else {
                // BUYER: default ke index, tapi juga bisa akses join via link lain
                $communitiesUrl = route('buyer.communities.index');
                $isCommunitiesActive = request()->routeIs([
                    'buyer.communities.*', 
                    'buyer.community.*',
                    'communities.join' // Juga aktif jika di halaman join
                ]);
            }
        @endphp
        <a href="{{ $communitiesUrl }}"
        class="nav-item {{ $isCommunitiesActive ? 'active' : '' }}">
            <i class="fas fa-users nav-icon"></i>
            <span>Komunitas</span>
        </a>

        {{-- ================= MAIN BARENG ================= --}}
        @php
            if ($isGuest) {
                $mainBarengUrl = route('guest.main_bareng.index') ?? '#';
                $isMainBarengActive = request()->routeIs(['main_bareng.*', 'main-bareng.*']);
            } else {
                $mainBarengUrl = route('buyer.main_bareng.index');
                $isMainBarengActive = request()->routeIs(['buyer.main_bareng.*', 'buyer.main-bareng.*']);
            }
        @endphp
        <a href="{{ $mainBarengUrl }}"
        class="nav-item {{ $isMainBarengActive ? 'active' : '' }}">
            <i class="fas fa-handshake nav-icon"></i>
            <span>Main Bareng</span>
        </a>

        <!-- {{-- ================= SPARRING ================= --}}
        @php
            if ($isGuest) {
                $sparringUrl = route('guest.sparring.index') ?? '#';
                $isSparringActive = request()->routeIs(['sparring.*']);
            } else {
                $sparringUrl = route('buyer.sparring.index') ?? '#';
                $isSparringActive = request()->routeIs(['buyer.sparring.*']);
            }
        @endphp
        <a href="{{ $sparringUrl }}"
           class="nav-item {{ $isSparringActive ? 'active' : '' }}">
            <i class="fas fa-user-friends nav-icon"></i>
            <span>Sparring</span>
        </a> -->

        {{-- ================= BOOKING ================= --}}
        @php
            if ($isGuest) {
                $bookingUrl = route('guest.venue.index') ?? '#';
                $isBookingActive = request()->routeIs(['venue.*', 'venues.*', 'booking.*']);
            } else {
                $bookingUrl = route('buyer.venue.index') ?? '#';
                $isBookingActive = request()->routeIs(['buyer.venue.*', 'buyer.venues.*', 'buyer.booking.*']);
            }
        @endphp
        <a href="{{ $bookingUrl }}"
           class="nav-item {{ $isBookingActive ? 'active' : '' }}">
            <i class="fas fa-calendar-alt nav-icon"></i>
            <span>Booking</span>
        </a>

        {{-- ================= PROFIL / LOGIN ================= --}}
        @php
            if ($isGuest) {
                $profileUrl = route('guest.profile');
                $isProfileActive = request()->routeIs(['guest.profile']);
            } else {
                $profileUrl = route('buyer.profile');
                $isProfileActive = request()->routeIs(['buyer.profile', 'buyer.profile.*']);
            }
        @endphp
        <a href="{{ $profileUrl }}"
           class="nav-item {{ $isProfileActive ? 'active' : '' }}">
            <i class="fas fa-user-circle nav-icon"></i>
            <span>Profil</span>
        </a>
    </nav>
@else
    {{-- ================= NAVIGASI UNTUK LANDOWNER ================= --}}
    {{-- Format: Beranda - Kelola Venue - Jadwal - Kelola Kasir - Profil --}}
    <nav class="bottom-nav">
        {{-- ================= BERANDA ================= --}}
        @php
            $isHomeActive = request()->routeIs(['landowner.home', 'landowner.home.*']);
        @endphp
        <a href="{{ route('landowner.home') }}"
           class="nav-item {{ $isHomeActive ? 'active' : '' }}">
            <i class="fas fa-home nav-icon"></i>
            <span>Beranda</span>
        </a>

        {{-- ================= KELOLA VENUE ================= --}}
        @php
            $venueActiveRoutes = [
                'landowner.venue.*',
                'landowner.section-lapangan.*'
            ];
            $isVenueActive = request()->routeIs($venueActiveRoutes);
            $venueUrl = route('landowner.venue.index');
        @endphp
        <a href="{{ $venueUrl }}"
           class="nav-item {{ $isVenueActive ? 'active' : '' }}">
            <i class="fas fa-map-marker-alt nav-icon"></i>
            <span>Kelola Venue</span>
        </a>

        {{-- ================= JADWAL ================= --}}
        @php
            $scheduleActiveRoutes = ['landowner.schedule.*'];
            $isScheduleActive = request()->routeIs($scheduleActiveRoutes);
            $scheduleUrl = route('landowner.schedule.index');
        @endphp
        <a href="{{ $scheduleUrl }}"
           class="nav-item {{ $isScheduleActive ? 'active' : '' }}">
            <i class="fas fa-calendar-alt nav-icon"></i>
            <span>Jadwal</span>
        </a>

        {{-- ================= KELOLA KASIR ================= --}}
        @php
            $cashierActiveRoutes = ['landowner.cashier.*'];
            $isCashierActive = request()->routeIs($cashierActiveRoutes);
            $cashierUrl = route('landowner.cashier.index');
        @endphp
        <a href="{{ $cashierUrl }}"
           class="nav-item {{ $isCashierActive ? 'active' : '' }}">
            <i class="fas fa-cash-register nav-icon"></i>
            <span>Kelola Kasir</span>
        </a>

        {{-- ================= PROFIL ================= --}}
        @php
            $isProfileActive = request()->routeIs(['landowner.profile', 'landowner.profile.*']);
        @endphp
        <a href="{{ route('landowner.profile') }}"
           class="nav-item {{ $isProfileActive ? 'active' : '' }}">
            <i class="fas fa-user-circle nav-icon"></i>
            <span>Profil</span>
        </a>
    </nav>
@endif

@php
    // Determine colors based on role
    if ($isLandowner) {
        // AYO Burgundy for landowner
        $navPrimary = '#8B1538';
        $navSecondary = '#A01B42';
        $navAccent = '#C7254E';
    } else {
        // Green for buyer/guest
        $navPrimary = '#0A5C36';
        $navSecondary = '#27AE60';
        $navAccent = '#2ECC71';
    }
@endphp

<style>
    :root {
        --primary: {{ $navPrimary }};
        --secondary: {{ $navSecondary }};
        --accent: {{ $navAccent }};
        --light: #F8F9FA;
        --text-light: #6C757D;
        --shadow-md: 0 3px 12px rgba(0,0,0,0.08);
        --gradient-accent: linear-gradient(135deg, {{ $navSecondary }} 0%, {{ $navAccent }} 100%);
        --gradient-primary: linear-gradient(135deg, {{ $navPrimary }} 0%, {{ $navSecondary }} 100%);
    }

    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        display: flex;
        justify-content: space-around;
        padding: 10px 4px 14px; /* Balanced padding */
        box-shadow: 0 -5px 20px rgba(0,0,0,0.06);
        border-radius: 24px 24px 0 0;
        z-index: 1000;
        border-top: 1px solid rgba(0,0,0,0.02);
    }


    /* Helper for mobile container positioning if used within one */
    @media (min-width: 480px) {
        .bottom-nav {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
        }
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: var(--text-light);
        font-size: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        flex: 1;
        min-width: 0; /* Allow shrinking if needed */
        white-space: nowrap; /* Prevent text wrapping */
    }


    .nav-item.active {
        color: var(--primary);
        font-weight: 700;
    }

    .nav-item.active .nav-icon {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
        transform: translateY(-5px);
    }
    
    .nav-item.active::after {
        content: "";
        position: absolute;
        top: -12px;
        width: 24px;
        height: 3px;
        background: var(--gradient-accent);
        border-radius: 2px;
    }

    .nav-item:hover {
        color: var(--primary);
    }

    .nav-icon {
        font-size: 18px;
        margin-bottom: 4px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: transparent;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach((item) => {
        item.addEventListener('click', e => {
            // Allow default link behavior (navigation)
            // Just updated active class for visual feedback before page load
            if (item.classList.contains('active')) return;
            
            navItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        });
    });
});
</script>
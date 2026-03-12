@props(['theme' => 'dark', 'showSearch' => true, 'backUrl' => null, 'title' => null])

@php
    $isDark = $theme === 'dark';
    
    // Tentukan role dan warna berdasarkan role
    $role = null;
    if (auth()->check()) {
        $role = session('temp_role') ?? auth()->user()->role;
    }
    
    // Get app settings from database
    try {
        $appSettings = \App\Models\Setting::first();
        $appName = $appSettings->app_name ?? 'SewaLap';
        $appLogo = $appSettings->app_logo ?? null;
    } catch (\Exception $e) {
        $appName = 'SewaLap';
        $appLogo = null;
    }
    
    // Warna berdasarkan role - LANDOWNER AYO BURGUNDY
    if ($role === 'landowner') {
        // Warna AYO burgundy untuk landowner
        $primaryColor = '#8B1538';      // AYO Burgundy - Main
        $primaryDark = '#6B0F2A';       // Darker burgundy
        $accentColor = '#C7254E';       // Light burgundy
        $secondaryColor = '#A01B42';    // Medium burgundy
        $successColor = '#8B1538';      // Burgundy untuk chat badge
        $dangerColor = '#EF4444';       // Red untuk notification
        
        // Gradients burgundy
        $gradientLight = 'linear-gradient(135deg, #A01B42 0%, #8B1538 100%)';
        $gradientDark = 'linear-gradient(135deg, #6B0F2A 0%, #8B1538 100%)';
        $gradientAccent = 'linear-gradient(135deg, #C7254E 0%, #A01B42 100%)';
        $gradientBright = 'linear-gradient(135deg, #C7254E 0%, #A01B42 100%)';
        
        $logoGradient = 'linear-gradient(135deg, #A01B42, #C7254E)';
        $logoAccent = '#C7254E';
    } else {
        // Warna hijau untuk buyer/guest - DIUBAH MENJADI HIJAU PADA FILE CSS
        $primaryColor = '#0A5C36';      // Hijau tua (#0A5C36)
        $primaryDark = '#08482B';       // Hijau lebih gelap (#08482B)
        $accentColor = '#2ECC71';       // Hijau cerah (#2ECC71)
        $secondaryColor = '#27AE60';    // Hijau sedang (#27AE60)
        $successColor = 'var(--success)'; // Hijau untuk chat
        $dangerColor = 'var(--danger)';   // Merah untuk notifikasi
        
        $gradientLight = 'linear-gradient(135deg, #0A5C36 0%, #27AE60 100%)';
        $gradientDark = 'linear-gradient(135deg, #08482B 0%, #0A5C36 100%)';
        $gradientAccent = 'linear-gradient(135deg, #27AE60 0%, #2ECC71 100%)';
        $gradientBright = 'linear-gradient(135deg, #2ECC71 0%, #0A5C36 100%)';
        
        $logoGradient = 'linear-gradient(135deg, #27AE60, #2ECC71)';
        $logoAccent = 'var(--accent)';
    }
    
    // Tentukan gradient berdasarkan theme dan role
    $roleGradient = $isDark ? $gradientDark : $gradientLight;
    $roleAccentGradient = $isDark ? $gradientDark : $gradientAccent;
    
    // Warna untuk header
    $headerBg = $isDark ? ($role === 'landowner' ? $gradientDark : $primaryDark) : 'white';
    $logoColor = $isDark ? 'white' : $primaryColor;
    
    // Warna untuk span logo (accent)
    $logoSpanColor = $isDark 
        ? ($role === 'landowner' ? '#C7254E' : '#2ECC71') 
        : ($role === 'landowner' ? '#8B1538' : 'var(--accent)');
    
    $logoSpanGradient = $isDark 
        ? ($role === 'landowner' 
            ? 'linear-gradient(135deg, #C7254E, #A01B42)' 
            : 'linear-gradient(135deg, #27AE60, #2ECC71)')
        : ($role === 'landowner' 
            ? 'linear-gradient(135deg, #A01B42, #C7254E)' 
            : 'none');
    
    // Warna untuk icon
    $iconBg = $isDark ? 'rgba(255, 255, 255, 0.15)' : 'var(--light)';
    $iconColor = $isDark ? 'white' : 'var(--text)';
    $iconBorder = $isDark ? 'none' : '2px solid var(--light-gray)';
    $borderBottom = $isDark 
        ? ($role === 'landowner' ? 'rgba(139, 21, 56, 0.3)' : 'rgba(255, 255, 255, 0.15)') 
        : 'var(--light-gray)';

    // Count unread notifications
    $unreadCount = 0;
    if (auth()->check()) {
        try {
            $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', auth()->id())
                ->whereNull('read_at')
                ->count();
        } catch (\Exception $e) {
            $unreadCount = 0;
        }
    }

    // Count unread chat messages
    $unreadChatCount = 0;
    if (auth()->check()) {
        try {
            $unreadChatCount = auth()->user()->unreadChatCount();
        } catch (\Exception $e) {
            $unreadChatCount = 0;
        }
    }

    // Tentukan URL chat berdasarkan role
    $chatUrl = '#';
    $homeUrl = '#';
    if (auth()->check()) {
        if ($role === 'buyer') {
            $chatUrl = route('buyer.chat');
            $homeUrl = route('buyer.home');
        } elseif ($role === 'landowner') {
            $chatUrl = route('landowner.chat');
            $homeUrl = route('landowner.home');
        } elseif ($role === 'admin') {
            $chatUrl = route('admin.chat');
            $homeUrl = route('admin.home');
        }
    } else {
        $chatUrl = route('login');
        $homeUrl = route('welcome');
    }
@endphp

<style>
    .mobile-header-{{ $theme }} {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: {{ $headerBg }};
        z-index: 1100;
        box-shadow: {{ $isDark ? '0 4px 12px rgba(0, 0, 0, 0.4)' : 'var(--shadow-sm)' }};
        {{ $isDark ? 'backdrop-filter: blur(10px);' : 'border-bottom: 1px solid var(--light-gray);' }}
    }

    .mobile-header-{{ $theme }} .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid {{ $borderBottom }};
    }

    @if($showSearch)
        .mobile-header-{{ $theme }} .search-bar {
            padding: 12px 16px;
            background: {{ $isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(255, 255, 255, 0.8)' }};
            border-top: 1px solid {{ $borderBottom }};
        }

        .mobile-header-{{ $theme }} .search-container {
            display: flex;
            align-items: center;
            background: {{ $isDark ? 'rgba(255, 255, 255, 0.1)' : 'white' }};
            border: {{ $isDark ? 'none' : '1px solid var(--light-gray)' }};
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .mobile-header-{{ $theme }} .search-container:focus-within {
            border-color: {{ $accentColor }};
            box-shadow: 0 0 0 2px {{ $role === 'landowner' ? 'rgba(139, 21, 56, 0.3)' : 'rgba(46, 204, 113, 0.2)' }};
            background: {{ $isDark ? 'rgba(255, 255, 255, 0.15)' : 'white' }};
        }

        .mobile-header-{{ $theme }} .search-input {
            flex: 1;
            padding: 8px 12px;
            border: none;
            background: transparent;
            font-size: 14px;
            outline: none;
            color: {{ $isDark ? 'white' : 'var(--text)' }};
            font-weight: 500;
        }

        .mobile-header-{{ $theme }} .search-input::placeholder {
            color: {{ $isDark ? 'rgba(255, 255, 255, 0.6)' : 'var(--text-light)' }};
        }

        .mobile-header-{{ $theme }} .search-btn {
            background: {{ $role === 'landowner' ? $gradientBright : $gradientAccent }};
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 20px 20px 0;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .mobile-header-{{ $theme }} .search-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
        }

        .mobile-header-{{ $theme }} .search-btn:hover::before {
            left: 100%;
        }

        .mobile-header-{{ $theme }} .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: {{ $isDark ? '0 4px 12px rgba(0, 0, 0, 0.4)' : '0 4px 12px rgba(0, 0, 0, 0.15)' }};
        }
    @endif

    .mobile-header-{{ $theme }} .logo {
        font-size: 18px;
        font-weight: 800;
        color: {{ $logoColor }};
        text-decoration: none;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .mobile-header-{{ $theme }} .logo:hover {
        transform: translateY(-1px);
    }

    .mobile-header-{{ $theme }} .logo-img {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: {{ $isDark ? '0 4px 12px rgba(0, 0, 0, 0.4)' : 'var(--shadow-sm)' }};
        transition: all 0.3s ease;
        border: {{ $role === 'landowner' ? '2px solid rgba(139, 21, 56, 0.3)' : 'none' }};
    }

    .mobile-header-{{ $theme }} .logo-icon {
        background: {{ $roleGradient }};
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        box-shadow: {{ $isDark ? '0 4px 12px rgba(0, 0, 0, 0.4)' : 'var(--shadow-sm)' }};
        transition: all 0.3s ease;
        border: {{ $role === 'landowner' ? '2px solid rgba(199, 37, 78, 0.2)' : 'none' }};
    }

    .mobile-header-{{ $theme }} .logo:hover .logo-img,
    .mobile-header-{{ $theme }} .logo:hover .logo-icon {
        transform: scale(1.05) rotate(5deg);
        box-shadow: {{ $isDark ? '0 6px 20px rgba(0, 0, 0, 0.5)' : 'var(--shadow-md)' }};
    }

    .mobile-header-{{ $theme }} .logo-text {
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .mobile-header-{{ $theme }} .logo-text span {
        {{ $logoSpanGradient !== 'none' 
            ? "background: $logoSpanGradient; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" 
            : "color: $logoSpanColor; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);" }}
        font-weight: 700;
        transition: all 0.3s ease;
        position: relative;
    }

    .mobile-header-{{ $theme }} .logo-text span::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: {{ $role === 'landowner' ? '#C7254E' : '#2ECC71' }};
        transition: width 0.3s ease;
    }

    .mobile-header-{{ $theme }} .logo:hover .logo-text span::after {
        width: 100%;
    }

    .mobile-header-{{ $theme }} .logo:hover .logo-text span {
        {{ $role === 'landowner' 
            ? ($isDark 
                ? "color: #C7254E; background: none; -webkit-text-fill-color: #C7254E; text-shadow: 0 0 10px rgba(199, 37, 78, 0.5);" 
                : "color: #8B1538; background: none; -webkit-text-fill-color: #8B1538; text-shadow: 0 2px 4px rgba(139, 21, 56, 0.2);") 
            : "" }}
    }

    .mobile-header-{{ $theme }} .header-actions {
        display: flex;
        gap: 8px;
        position: relative;
    }

    .mobile-header-{{ $theme }} .header-icon {
        background: {{ $iconBg }};
        border: {{ $iconBorder }};
        font-size: 18px;
        cursor: pointer;
        color: {{ $iconColor }};
        padding: 6px;
        border-radius: 10px;

        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        text-decoration: none;
        overflow: visible; /* Diubah dari hidden ke visible agar badge tidak terpotong */
    }

    /* Badge Styles dengan efek yang lebih menarik dan TIDAK TERPOTONG */
    .mobile-header-{{ $theme }} .notification-badge,
    .mobile-header-{{ $theme }} .chat-badge {
        position: absolute;
        top: -6px; /* Dinaikkan sedikit agar tidak terpotong */
        right: -6px; /* Digeser sedikit ke luar */
        color: white;
        font-size: 10px;
        font-weight: 800;
        min-width: 20px; /* Sedikit lebih besar */
        height: 20px; /* Sedikit lebih besar */
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        border: 2px solid {{ $isDark ? ($role === 'landowner' ? '#6B0F2A' : 'var(--primary-dark)') : 'white' }};
        z-index: 20; /* Z-index lebih tinggi */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        overflow: visible;
    }

    /* Warna badge untuk kedua role - Kontras dengan header & Blink */
    .mobile-header-{{ $theme }} .notification-badge,
    .mobile-header-{{ $theme }} .chat-badge {
        /* Landowner (Burgundy Header) -> ORANGE Badge */
        /* Buyer (Green Header) -> RED Badge */
        background: {{ $role === 'landowner' ? 'linear-gradient(135deg, #FF9800, #F39C12)' : 'linear-gradient(135deg, #EF4444, #DC2626)' }};
        animation: blink-badge 2s ease-in-out infinite;
    }

    /* Simple blink animation untuk badge */
    @keyframes blink-badge {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.4;
        }
    }

    /* Animasi pulse untuk buyer - DIUBAH KE HIJAU SESUAI CSS */
    @keyframes pulse-notification {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 0 8px rgba(231, 76, 60, 0);
        }
    }

    @keyframes pulse-chat {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 0 8px rgba(46, 204, 113, 0);
        }
    }

    /* Styling untuk landowner */
    .mobile-header-{{ $theme }} .landowner-accent {
        color: #FFE4E1 !important; /* Misty Rose - White with a hint of red, keeping it in the family but distinct */
        -webkit-text-fill-color: #FFE4E1 !important;
        background: none !important;
        text-shadow: 0 0 10px rgba(255, 228, 225, 0.4); /* Soft glow */
    }

    .mobile-header-{{ $theme }} .landowner-gradient {
        background: {{ $role === 'landowner' ? $gradientBright : 'inherit' }} !important;
    }

    /* Efek glow untuk landowner pada dark mode */
    {{ $role === 'landowner' && $isDark ? '
    .mobile-header-' . $theme . ' .logo-icon {
        box-shadow: 0 4px 20px rgba(139, 21, 56, 0.4);
    }
    
    .mobile-header-' . $theme . ' .header-icon:hover {
        box-shadow: 0 4px 20px rgba(139, 21, 56, 0.3);
    }
    ' : '' }}

    /* Mobile responsiveness */
    @media (min-width: 600px) {
        .mobile-header-{{ $theme }} {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0;
        }
        
        /* Untuk desktop, atur agar badge tetap terlihat */
        .mobile-header-{{ $theme }} .notification-badge,
        .mobile-header-{{ $theme }} .chat-badge {
            top: -5px;
            right: -5px;
        }
    }
</style>

<header class="mobile-header mobile-header-{{ $theme }}">
    <div class="header-top">
        @if($backUrl)
            <a href="{{ $backUrl }}" class="header-icon" style="margin-right: 12px; text-decoration: none;">
                <i class="fas fa-arrow-left"></i>
            </a>
            
            @if($title)
                <div class="logo-text" style="font-size: 18px; font-weight: 800; color: {{ $isDark ? 'white' : 'var(--text)' }};">
                    {{ $title }}
                </div>
            @else
                <a href="{{ $homeUrl }}" class="logo">
                    @if($appLogo)
                        <img src="{{ Storage::url('logo/' . $appLogo) }}" alt="{{ $appName }}" class="logo-img">
                    @else
                        <i class="fas fa-futbol logo-icon {{ $role === 'landowner' ? 'landowner-gradient' : '' }}"></i>
                    @endif
                    
                    <div class="logo-text">
                        @php
                            $appNameParts = explode(' ', $appName, 2);
                            $firstPart = $appNameParts[0] ?? '';
                            $secondPart = $appNameParts[1] ?? '';
                        @endphp
                        
                        {{ $firstPart }}
                        @if($secondPart)
                            <span class="{{ $role === 'landowner' ? 'landowner-accent' : '' }}">{{ $secondPart }}</span>
                        @endif
                    </div>
                </a>
            @endif
        @else
            <a href="{{ $homeUrl }}" class="logo">
                @if($appLogo)
                    <img src="{{ Storage::url('logo/' . $appLogo) }}" alt="{{ $appName }}" class="logo-img">
                @else
                    <i class="fas fa-futbol logo-icon {{ $role === 'landowner' ? 'landowner-gradient' : '' }}"></i>
                @endif
                
                <div class="logo-text">
                    @php
                        $appNameParts = explode(' ', $appName, 2);
                        $firstPart = $appNameParts[0] ?? '';
                        $secondPart = $appNameParts[1] ?? '';
                    @endphp
                    
                    {{ $firstPart }}
                    @if($secondPart)
                        <span class="{{ $role === 'landowner' ? 'landowner-accent' : '' }}">{{ $secondPart }}</span>
                    @endif
                </div>
            </a>
        @endif
        
        <div class="header-actions">
            @auth
                <!-- Chat Icon with Badge -->
                <a href="{{ $chatUrl }}" class="header-icon" title="Chat">
                    <i class="far fa-comments"></i>
                    @if ($unreadChatCount > 0)
                        <span class="chat-badge">
                            {{ $unreadChatCount > 99 ? '99+' : $unreadChatCount }}
                        </span>
                    @endif
                </a>

                <!-- Notification Icon with Badge -->
                <a href="{{ $role === 'buyer' 
                    ? route('buyer.notifications.index')
                    : route('landowner.notifications.index') }}" 
                   class="header-icon" 
                   title="Notifikasi">
                    <i class="far fa-bell"></i>
                    @if ($unreadCount > 0)
                        <span class="notification-badge">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            @endauth

            @guest
                <!-- Untuk guest, tampilkan icon chat dan notifikasi (tanpa badge) -->
                <a href="{{ route('login') }}" class="header-icon" title="Chat">
                    <i class="far fa-comments"></i>
                </a>
                <a href="{{ route('login') }}" class="header-icon" title="Notifikasi">
                    <i class="far fa-bell"></i>
                </a>
            @endguest
        </div>
    </div>

    @if($showSearch)
        <!-- Search Bar -->
        {{-- <div class="search-bar">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Cari lapangan, olahraga, lokasi..." id="headerSearch">
                <button class="search-btn" onclick="handleHeaderSearch()" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div> --}}
    @endif
</header>

@if($showSearch)
    <script>
        function handleHeaderSearch() {
            const searchInput = document.getElementById('headerSearch');
            const searchTerm = searchInput.value.trim();

            if (searchTerm) {
                // Redirect to explore page with search query
                const searchUrl = `{{ url('/explore') }}?search=${encodeURIComponent(searchTerm)}`;
                window.location.href = searchUrl;
            } else {
                // If no search term, just go to explore page
                window.location.href = '{{ url('/explore') }}';
            }
        }

        // Add enter key support for header search
        document.addEventListener('DOMContentLoaded', function () {
            const headerSearchInput = document.getElementById('headerSearch');
            if (headerSearchInput) {
                headerSearchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        handleHeaderSearch();
                    }
                });
            }
        });
    </script>
@endif
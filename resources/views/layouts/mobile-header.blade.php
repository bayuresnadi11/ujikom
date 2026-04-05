@props(['theme' => 'dark', 'showSearch' => true])

@php
    $isDark = $theme === 'dark';
    $headerBg = $isDark ? 'var(--gradient-dark)' : 'white';
    $logoColor = $isDark ? 'white' : 'var(--primary)';
    $logoSpanColor = $isDark ? '#a3e9c0' : 'var(--accent)';
    $logoSpanGradient = $isDark ? 'linear-gradient(135deg, #a3e9c0, #7fdbad)' : 'none';
    $logoSpanTextFill = $isDark ? '-webkit-text-fill-color: transparent; -webkit-background-clip: text;' : '';
    $iconBg = $isDark ? 'rgba(255, 255, 255, 0.15)' : 'var(--light)';
    $iconColor = $isDark ? 'white' : 'var(--text)';
    $iconBorder = $isDark ? 'none' : '2px solid var(--light-gray)';
    $borderBottom = $isDark ? 'rgba(255, 255, 255, 0.15)' : 'var(--light-gray)';
    
    // Count unread notifications - SIMPLE VERSION
    $unreadCount = 0;
    if (auth()->check()) {
        try {
            // Query langsung tanpa eloquent untuk menghindari soft deletes issue
            $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', auth()->id())
                ->whereNull('read_at')
                ->count();
        } catch (\Exception $e) {
            $unreadCount = 0;
        }
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
        box-shadow: {{ $isDark ? 'var(--shadow-md)' : 'var(--shadow-sm)' }};
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
        border-color: {{ $isDark ? '#a3e9c0' : 'var(--accent)' }};
        box-shadow: {{ $isDark ? '0 0 0 2px rgba(163, 233, 192, 0.2)' : '0 0 0 2px rgba(46, 204, 113, 0.2)' }};
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
        background: {{ $isDark ? 'rgba(255, 255, 255, 0.2)' : 'var(--gradient-accent)' }};
        color: {{ $isDark ? 'white' : 'white' }};
        border: none;
        padding: 6px 12px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 20px 20px 0;
    }

    .mobile-header-{{ $theme }} .search-btn:hover {
        background: {{ $isDark ? 'rgba(255, 255, 255, 0.3)' : 'rgba(46, 204, 113, 0.9)' }};
        transform: translateY(-1px);
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
    }

    .mobile-header-{{ $theme }} .logo-icon {
        background: {{ $isDark ? 'rgba(255, 255, 255, 0.2)' : 'var(--gradient-primary)' }};
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        {{ $isDark ? 'box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);' : 'color: white; box-shadow: var(--shadow-sm);' }}
    }

    .mobile-header-{{ $theme }} .logo span {
        {{ $logoSpanGradient !== 'none' ? "background: $logoSpanGradient; -webkit-background-clip: text; -webkit-text-fill-color: transparent;" : "color: $logoSpanColor;" }}
        font-weight: 700;
    }

    .mobile-header-{{ $theme }} .header-actions {
        display: flex;
        gap: 8px;
    }

    .mobile-header-{{ $theme }} .header-icon {
        background: {{ $iconBg }};
        border: {{ $iconBorder }};
        font-size: 18px;
        cursor: pointer;
        color: {{ $iconColor }};
        padding: 6px;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        text-decoration: none;
    }

    /* Badge Styles */
    .mobile-header-{{ $theme }} .notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: var(--danger);
        color: white;
        font-size: 10px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid {{ $isDark ? 'var(--primary-dark)' : 'white' }};
        z-index: 10;
        animation: pulse 2s infinite;
    }

    /* Pulse animation for attention */
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    .mobile-header-{{ $theme }} .header-icon:hover {
        background: {{ $isDark ? 'rgba(255, 255, 255, 0.25)' : 'rgba(255, 255, 255, 0.8)' }};
        transform: translateY(-1px);
    }

    /* Mobile responsiveness */
    @media (min-width: 600px) {
        .mobile-header-{{ $theme }} {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0;
        }
    }
</style>

<header class="mobile-header mobile-header-{{ $theme }}">
    <div class="header-top">
        <a href="{{ url('/buyer/home') }}" class="logo">
            <i class="fas fa-futbol logo-icon"></i>
            Sewa<span>Lap</span>
        </a>
        <div class="header-actions">
            @auth
                <!-- Notification Icon with Badge -->
                <a href="{{ route('buyer.notifications.index') }}" class="header-icon" title="Notifikasi">
                    <i class="far fa-bell"></i>
                    @if($unreadCount > 0)
                        <span class="notification-badge">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            @endauth
        </div>
    </div>

    @if($showSearch)
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Cari lapangan, olahraga, lokasi..." id="headerSearch">
            <button class="search-btn" onclick="handleHeaderSearch()" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const headerSearchInput = document.getElementById('headerSearch');
    if (headerSearchInput) {
        headerSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleHeaderSearch();
            }
        });
    }
});
</script>
@endif

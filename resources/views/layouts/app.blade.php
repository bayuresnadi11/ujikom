<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app_setting('app_name', 'SewaLap') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles untuk notification badge -->
    <style>
        /* Styles untuk notification badge global */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #dc3545;
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
            border: 2px solid #fff;
            z-index: 10;
            animation: pulse 2s infinite;
        }

        .notification-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        /* Pulse animation untuk notifikasi */
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

        /* Blink animation untuk pesan */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Styles untuk navbar */
        .navbar-nav .nav-link {
            position: relative;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .notification-badge {
                top: -2px;
                right: -2px;
                font-size: 9px;
                min-width: 16px;
                height: 16px;
            }
        }
    </style>

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-futbol me-2"></i>
                    {{ app_setting('app_name', 'SewaLap') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Menu tambahan bisa ditambah di sini -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <!-- Notifications Icon -->
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('buyer.notifications.index') }}" title="Notifikasi">
                                    <i class="far fa-bell"></i>
                                    @php
                                        $unreadCount = auth()->user()->unreadNotifications()->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="notification-badge">
                                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>

                            <!-- Messages Icon (Opsional) -->
                            {{--
                            @php
                                $unreadMessages = \App\Models\Chat::where('receiver_id', auth()->id())
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('messages.index') }}" title="Pesan">
                                    <i class="far fa-envelope"></i>
                                    @if($unreadMessages > 0)
                                        <span class="notification-badge">{{ $unreadMessages > 99 ? '99+' : $unreadMessages }}</span>
                                    @endif
                                </a>
                            </li>
                            --}}

                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user me-2"></i> Profil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('buyer.notifications.index') }}">
                                        <i class="fas fa-bell me-2"></i> Notifikasi
                                        @if($unreadCount > 0)
                                            <span class="badge bg-danger float-end">{{ $unreadCount }}</span>
                                        @endif
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Script untuk real-time notification updates -->
    @auth
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let updateInProgress = false;
        
        // Function untuk update notification count
        async function updateNotificationCount() {
            if (updateInProgress) return;
            
            updateInProgress = true;
            
            try {
                const response = await fetch('{{ route("notifications.unread-count") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    },
                    cache: 'no-store'
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                updateNotificationBadge(data.count);
                
            } catch (error) {
                console.error('Error fetching notification count:', error);
            } finally {
                updateInProgress = false;
            }
        }
        
        // Function untuk update badge UI
        function updateNotificationBadge(count) {
            const notificationLinks = document.querySelectorAll('a[href*="notifications"]');
            const bellIcon = document.querySelector('.fa-bell');
            const dropdownBadge = document.querySelector('.dropdown-item[href*="notifications"] .badge');
            
            notificationLinks.forEach(link => {
                let badge = link.querySelector('.notification-badge');
                
                if (count > 0) {
                    // Update atau create badge
                    if (badge) {
                        badge.textContent = count > 99 ? '99+' : count;
                    } else {
                        badge = document.createElement('span');
                        badge.className = 'notification-badge';
                        badge.textContent = count > 99 ? '99+' : count;
                        link.appendChild(badge);
                    }
                    
                    // Change to solid bell icon
                    if (bellIcon) {
                        bellIcon.classList.remove('far');
                        bellIcon.classList.add('fas');
                    }
                } else {
                    // Remove badge if exists
                    if (badge) {
                        badge.remove();
                    }
                    
                    // Change to regular bell icon
                    if (bellIcon) {
                        bellIcon.classList.remove('fas');
                        bellIcon.classList.add('far');
                    }
                }
            });
            
            // Update badge in dropdown
            if (dropdownBadge) {
                if (count > 0) {
                    dropdownBadge.textContent = count;
                    dropdownBadge.style.display = 'inline-block';
                } else {
                    dropdownBadge.style.display = 'none';
                }
            }
        }
        
        // Event listener untuk page visibility
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateNotificationCount();
            }
        });
        
        // Update ketika user kembali ke tab
        window.addEventListener('focus', updateNotificationCount);
        
        // Update setiap 30 detik
        const updateInterval = setInterval(updateNotificationCount, 30000);
        
        // Update segera setelah page load
        setTimeout(updateNotificationCount, 1000);
        
        // Cleanup interval saat page unload
        window.addEventListener('beforeunload', function() {
            clearInterval(updateInterval);
        });
        
        // Listen untuk events dari Laravel Echo (jika menggunakan websockets)
        @if(config('broadcasting.default') !== 'log')
        try {
            // Contoh dengan Pusher/Laravel Echo
            // window.Echo.private('App.Models.User.{{ auth()->id() }}')
            //     .notification((notification) => {
            //         updateNotificationCount();
            //     });
        } catch (e) {
            console.log('Websocket not configured');
        }
        @endif
    });
    </script>
    @endauth

    <!-- Script untuk auto logout jika tab tidak aktif (opsional) -->
    <script>
    @auth
    let idleTime = 0;
    const idleInterval = setInterval(timerIncrement, 60000); // 1 minute
    
    function timerIncrement() {
        idleTime++;
        // Auto logout setelah 30 menit tidak aktif
        if (idleTime > 30) {
            document.getElementById('logout-form').submit();
        }
    }
    
    // Reset idle time on user activity
    ['mousemove', 'keypress', 'click', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, () => {
            idleTime = 0;
        });
    });
    @endauth
    </script>
</body>
</html>
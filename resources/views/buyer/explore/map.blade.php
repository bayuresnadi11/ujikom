<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#0A5C36">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Peta Lapangan - SewaLap</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('guest-partials.explore-style')
    <style>
        /* ================= FULL MAP STYLES ================= */
        
        /* Reset default margins */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #f5f5f5;
        }

        /* Mobile container khusus untuk map */
        .mobile-container {
            width: 100%;
            height: 100vh;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            box-shadow: 0 0 20px rgba(10, 92, 54, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-width: 100%;
        }

        /* Main content untuk map */
        .main-content {
            flex: 1;
            position: relative;
            padding: 0;
            margin: 0;
            min-height: 0;
            overflow: hidden;
        }

        /* Map container */
        .full-map-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }

        .map-full-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Map wrapper dengan overlay controls */
        .map-wrapper-full {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #e9ecef;
        }

        .map-frame-full {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        /* Map Controls Overlay */
        .map-controls {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 0 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-button {
            background: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .map-actions {
            display: flex;
            gap: 8px;
        }

        .map-action-btn {
            background: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .map-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .map-action-btn.active {
            background: var(--primary);
            color: white;
        }

        /* Map Bottom Sheet */
        .map-bottom-sheet {
            position: absolute;
            bottom: 70px;
            left: 0;
            right: 0;
            z-index: 90;
            background: white;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(100%);
            transition: transform 0.3s ease;
            max-height: 70vh;
            overflow-y: auto;
        }

        .map-bottom-sheet.active {
            transform: translateY(0);
        }

        .sheet-handle {
            width: 40px;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 12px auto;
        }

        .sheet-content {
            padding: 0 16px 20px;
        }

        .sheet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .sheet-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sheet-title i {
            color: var(--accent);
            font-size: 14px;
        }

        .sheet-close {
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .sheet-close:hover {
            background: #f8fafc;
            color: var(--text);
        }

        /* Venue List in Bottom Sheet */
        .venue-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .venue-list-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .venue-list-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: var(--accent);
        }

        .venue-list-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--gradient-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            flex-shrink: 0;
        }

        .venue-list-content {
            flex: 1;
            min-width: 0;
        }

        .venue-list-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .venue-list-details {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .venue-list-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #fbbf24;
        }

        .venue-list-price {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
            margin-top: 4px;
        }

        /* Map Markers Info */
        .map-marker-info {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            max-width: 300px;
            width: 90%;
        }

        .map-marker-info.active {
            display: block;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* Search Bar untuk Map */
        .map-search-bar {
            position: absolute;
            top: 80px;
            left: 16px;
            right: 16px;
            z-index: 90;
        }

        .map-search-container {
            background: white;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .map-search-input {
            flex: 1;
            border: none;
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            color: var(--text);
            outline: none;
        }

        .map-search-input::placeholder {
            color: #94a3b8;
        }

        .map-search-btn {
            background: var(--gradient-primary);
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .map-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(10, 92, 54, 0.2);
        }

        /* Responsive */
        @media (max-width: 360px) {
            .map-controls {
                padding: 0 12px;
                top: 16px;
            }
            
            .back-button,
            .map-action-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .map-search-bar {
                top: 70px;
                left: 12px;
                right: 12px;
            }
            
            .map-search-container {
                padding: 10px;
            }
            
            .map-search-input {
                padding: 10px 14px;
                font-size: 13px;
            }
            
            .map-search-btn {
                width: 40px;
                height: 40px;
            }
            
            .sheet-content {
                padding: 0 12px 16px;
            }
            
            .sheet-title {
                font-size: 15px;
            }
            
            .venue-list-item {
                padding: 10px;
                gap: 10px;
            }
            
            .venue-list-icon {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
            
            .venue-list-name {
                font-size: 13px;
            }
            
            .venue-list-details {
                font-size: 11px;
                gap: 8px;
            }
            
            .venue-list-price {
                font-size: 13px;
            }
        }

        @media (min-width: 600px) {
            .mobile-container {
                max-width: 480px;
                height: 90vh;
                margin: 5vh auto;
                box-shadow: 0 0 40px rgba(10, 92, 54, 0.2);
                border-radius: 20px;
                overflow: hidden;
            }
            
            .map-controls {
                top: 16px;
            }
            
            .map-search-bar {
                top: 70px;
            }
            
            .map-bottom-sheet {
                bottom: 80px;
            }
        }

        /* Animation for page transition */
        .page-transition {
            opacity: 0;
            transform: translateY(4px);
            animation: pageIn 0.3s ease-out forwards;
        }

        @keyframes pageIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Main App Container --> 
    <div class="mobile-container" id="mobileContainer">
        <!-- Header -->
        @include('layouts.header')
        
        <!-- Main Content -->
        <main class="main-content page-transition">
            <!-- Map Full Container -->
            <section class="full-map-section">
                <div class="map-full-container">
                    <!-- Map Controls -->
                    <div class="map-controls">
                        <button class="back-button" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div class="map-actions">
                            <button class="map-action-btn" id="btnMyLocation" title="Lokasi Saya">
                                <i class="fas fa-location-crosshairs"></i>
                            </button>
                            <button class="map-action-btn active" id="btnLapangan" title="Lapangan">
                                <i class="fas fa-futbol"></i>
                            </button>
                            <button class="map-action-btn" id="btnFilter" title="Filter">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="map-search-bar">
                        <div class="map-search-container">
                            <input type="text" class="map-search-input" placeholder="Cari lapangan di sekitar..." id="mapSearch">
                            <button class="map-search-btn" id="btnMapSearch">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Map Iframe -->
                    <div class="map-wrapper-full">
                        <iframe 
                            class="map-frame-full"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=106.8190,-6.2296,106.8700,-6.1880&layer=mapnik&marker=-6.2088,106.8456"
                            title="Peta Lapangan Olahraga"
                            loading="lazy"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Marker Info (Hidden by default) -->
                    <div class="map-marker-info" id="markerInfo">
                        <h3>Lapangan Futsal Cimahi</h3>
                        <div class="venue-list-details">
                            <span class="venue-list-rating">
                                <i class="fas fa-star"></i> 5.0
                            </span>
                            <span><i class="fas fa-location-dot"></i> Cimahi</span>
                        </div>
                        <p class="venue-list-price">Rp 150.000/jam</p>
                        <button class="btn-explore-all" onclick="window.location.href='/venue/detail'">
                            Lihat Detail
                        </button>
                    </div>

                    <!-- Bottom Sheet -->
                    <div class="map-bottom-sheet" id="bottomSheet">
                        <div class="sheet-handle"></div>
                        <div class="sheet-content">
                            <div class="sheet-header">
                                <h3 class="sheet-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Lapangan Terdekat
                                </h3>
                                <button class="sheet-close" id="btnCloseSheet">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="venue-list">
                                <!-- Kampung Bali -->
                                <div class="venue-list-item" onclick="showMarkerInfo(1)">
                                    <div class="venue-list-icon">
                                        <i class="fas fa-futbol"></i>
                                    </div>
                                    <div class="venue-list-content">
                                        <h4 class="venue-list-name">Kampung Bali</h4>
                                        <div class="venue-list-details">
                                            <span class="venue-list-rating">
                                                <i class="fas fa-star"></i> 4.8
                                            </span>
                                            <span><i class="fas fa-location-dot"></i> Jakarta Pusat</span>
                                        </div>
                                        <p class="venue-list-price">Rp 120.000 - 200.000/jam</p>
                                    </div>
                                </div>

                                <!-- Jakarta Pusat -->
                                <div class="venue-list-item" onclick="showMarkerInfo(2)">
                                    <div class="venue-list-icon">
                                        <i class="fas fa-basketball"></i>
                                    </div>
                                    <div class="venue-list-content">
                                        <h4 class="venue-list-name">Jakarta Pusat</h4>
                                        <div class="venue-list-details">
                                            <span class="venue-list-rating">
                                                <i class="fas fa-star"></i> 4.5
                                            </span>
                                            <span><i class="fas fa-location-dot"></i> 12 lapangan tersedia</span>
                                        </div>
                                        <p class="venue-list-price">Rp 100.000 - 180.000/jam</p>
                                    </div>
                                </div>

                                <!-- Rawasan Utan Kayu -->
                                <div class="venue-list-item" onclick="showMarkerInfo(3)">
                                    <div class="venue-list-icon">
                                        <i class="fas fa-volleyball"></i>
                                    </div>
                                    <div class="venue-list-content">
                                        <h4 class="venue-list-name">Rawasan Utan Kayu</h4>
                                        <div class="venue-list-details">
                                            <span class="venue-list-rating">
                                                <i class="fas fa-star"></i> 4.7
                                            </span>
                                            <span><i class="fas fa-location-dot"></i> Jakarta Timur</span>
                                        </div>
                                        <p class="venue-list-price">Rp 130.000 - 220.000/jam</p>
                                    </div>
                                </div>
                            </div>

                            <button class="btn-explore-all" onclick="window.location.href='/buyer/explore'">
                                <i class="fas fa-compass"></i>
                                Jelajahi Semua Lapangan
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Bottom Nav -->
        <nav class="bottom-nav">
            <button class="nav-item" onclick="window.location.href='{{ url('/buyer/home') }}'">
                <i class="fas fa-home nav-icon"></i>
                <span>Beranda</span>
            </button>
            <button class="nav-item" onclick="window.location.href='{{ url('buyer/menu') }}'">
                <i class="fas fa-layer-group nav-icon"></i>
                <span>Menu</span>
            </button>
            <button class="nav-item active" onclick="window.location.href='{{ url('buyer/explore') }}'">
                <i class="fas fa-compass nav-icon"></i>
                <span>Jelajah</span>
            </button>
            <button class="nav-item" onclick="window.location.href='{{ url('buyer/chat') }}'">
                <i class="fas fa-comment-dots nav-icon"></i>
                <span>Chat</span>
            </button>
            <button class="nav-item" onclick="window.location.href='{{ url('/buyer/profile') }}'">
                <i class="fas fa-user-circle nav-icon"></i>
                <span>Profil</span>
            </button>
        </nav>    
    </div>

    <script>
        // JavaScript untuk kontrol map
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle bottom sheet
            const bottomSheet = document.getElementById('bottomSheet');
            const btnCloseSheet = document.getElementById('btnCloseSheet');
            
            // Auto show bottom sheet setelah page load
            setTimeout(() => {
                bottomSheet.classList.add('active');
            }, 500);
            
            btnCloseSheet.addEventListener('click', () => {
                bottomSheet.classList.remove('active');
            });
            
            // Re-open bottom sheet ketika klik handle
            document.querySelector('.sheet-handle').addEventListener('click', () => {
                bottomSheet.classList.add('active');
            });
            
            // Map action buttons
            const btnMyLocation = document.getElementById('btnMyLocation');
            const btnLapangan = document.getElementById('btnLapangan');
            const btnFilter = document.getElementById('btnFilter');
            const btnMapSearch = document.getElementById('btnMapSearch');
            const mapSearch = document.getElementById('mapSearch');
            
            btnMyLocation.addEventListener('click', () => {
                alert('Menuju lokasi Anda...');
                btnMyLocation.classList.toggle('active');
            });
            
            btnLapangan.addEventListener('click', () => {
                alert('Menampilkan semua lapangan...');
            });
            
            btnFilter.addEventListener('click', () => {
                alert('Membuka filter...');
                btnFilter.classList.toggle('active');
            });
            
            btnMapSearch.addEventListener('click', () => {
                if (mapSearch.value.trim()) {
                    alert('Mencari: ' + mapSearch.value);
                }
            });
            
            mapSearch.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    btnMapSearch.click();
                }
            });
            
            // Marker info
            window.showMarkerInfo = function(markerId) {
                const markerInfo = document.getElementById('markerInfo');
                markerInfo.classList.add('active');
                
                // Close marker info ketika klik di luar
                setTimeout(() => {
                    document.addEventListener('click', function closeMarkerInfo(e) {
                        if (!markerInfo.contains(e.target)) {
                            markerInfo.classList.remove('active');
                            document.removeEventListener('click', closeMarkerInfo);
                        }
                    });
                }, 100);
            };
            
            // Back button
            document.querySelector('.back-button').addEventListener('click', () => {
                window.history.back();
            });
        });
    </script>
    @include('buyer.explore.partials.explore-script')
</body>
</html>
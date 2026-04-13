<!DOCTYPE html>
<html lang="id">
<head>
    {{-- Meta tag untuk karakter encoding --}}
    <meta charset="UTF-8">
    
    {{-- Meta tag untuk responsive design (mobile-friendly) --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Meta tag CSRF token untuk keamanan form dan AJAX request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Title halaman, akan diisi oleh child view menggunakan @yield('title') --}}
    <title>@yield('title') - Admin Panel</title>
    
    <!-- ================= CSS LIBRARIES ================= -->
    
    {{-- Bootstrap 5 CSS - Framework CSS utama untuk styling admin panel --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons - Icon library dari Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    {{-- Font Awesome 6 - Icon library alternatif dengan lebih banyak pilihan --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SweetAlert2 - Library untuk alert/modal yang lebih menarik --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- ================= CUSTOM STYLES ================= -->
    <style>
        /* ================ SIDEBAR STYLING ================ */
        /* Sidebar navigasi utama admin panel */
        .sidebar {
            width: 250px;                              /* Lebar tetap sidebar */
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);  /* Gradien gelap */
            color: white;
            min-height: 100vh;                         /* Tinggi minimal full viewport */
            padding: 20px 0;
            position: fixed;                           /* Posisi tetap di kiri */
            left: 0;
            top: 0;
            overflow-y: auto;                          /* Scroll jika konten melebihi tinggi */
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); /* Bayangan untuk efek kedalaman */
            z-index: 1000;                             /* Di atas elemen lain */
        }
        
        /* ================ MAIN CONTENT AREA ================ */
        /* Area konten utama di sebelah kanan sidebar */
        .main-content {
            margin-left: 250px;                        /* Memberi ruang untuk sidebar */
            padding: 20px;
            background: #f8f9fa;                       /* Warna latar abu-abu terang */
            min-height: 100vh;
        }
        
        /* ================ SIDEBAR HEADER ================ */
        /* Header/branding di bagian atas sidebar */
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);  /* Garis pemisah transparan */
            margin-bottom: 20px;
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-size: 18px;
            color: #ecf0f1;
            font-weight: 600;
        }
        
        /* ================ SIDEBAR NAVIGATION ================ */
        /* Menu navigasi di sidebar */
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 3px;
        }
        
        /* Styling link navigasi */
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #bdc3c7;                            /* Warna abu-abu terang untuk teks */
            text-decoration: none;
            border-left: 4px solid transparent;        /* Border kiri untuk efek active */
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        /* Icon dalam link navigasi */
        .sidebar-nav a i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        /* Efek hover pada link navigasi */
        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #3498db;                /* Border biru saat hover */
            color: white;
        }
        
        /* Styling untuk link yang aktif (halaman sedang dibuka) */
        .sidebar-nav a.active {
            background: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
            color: white;
            font-weight: 500;
        }
        
        /* ================ TABLE CONTAINER ================ */
        /* Container untuk tabel data dengan efek card */
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 25px;
            margin-top: 20px;
        }
        
        /* Preview gambar logo/venue di tabel */
        .logo-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        /* Container tombol aksi dalam tabel */
        .action-buttons .btn {
            margin-right: 5px;
        }
        
        /* Styling judul halaman */
        .page-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;          /* Garis bawah biru */
        }
        
        /* ================ CARD COMPONENT ================ */
        /* Styling card untuk dashboard/widget */
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
            transition: transform 0.2s;
        }
        
        /* Efek hover pada card */
        .card:hover {
            transform: translateY(-2px);                /* Angkat sedikit saat hover */
        }
        
        /* ================ BUTTON PRIMARY ================ */
        /* Tombol utama dengan gradien biru */
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9 0%, #3498db 100%);
            transform: translateY(-1px);
        }
        
        /* ================ MODAL STYLING ================ */
        /* Styling untuk modal dialog */
        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0;
        }
        
        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }
    </style>
    
    {{-- Area untuk menambahkan CSS tambahan dari child view --}}
    @stack('styles')
</head>
<body>

    {{-- ================= NAVBAR COMPONENT ================= --}}
    {{-- Memasukkan navbar (biasanya berisi info user, notifikasi, logout) --}}
    @include('components.navbar')
    
    {{-- ================= SIDEBAR COMPONENT ================= --}}
    {{-- Memasukkan sidebar navigasi (menu-menu admin) --}}
    @include('components.sidebar')

    {{-- ================= MAIN CONTENT ================= --}}
    {{-- Area utama konten yang akan diisi oleh child view --}}
    <div class="main-content">
        @yield('content')      {{-- Child view akan mengisi section 'content' di sini --}}
    </div>

    <!-- ================= JAVASCRIPT LIBRARIES ================= -->
    
    {{-- Bootstrap JS Bundle (termasuk Popper untuk tooltip/popover) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- jQuery - Library JavaScript untuk manipulasi DOM dan AJAX --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    {{-- Area untuk menambahkan script tambahan dari child view --}}
    @stack('scripts')

    {{-- ================= GLOBAL TOAST ALERT COMPONENT ================= --}}
    {{-- Memasukkan komponen toast alert untuk notifikasi global --}}
    @include('components.toast-alert')
    
    {{-- 
        CATATAN: Komponen navbar, sidebar, dan toast-alert biasanya disimpan di:
        - resources/views/components/navbar.blade.php
        - resources/views/components/sidebar.blade.php
        - resources/views/components/toast-alert.blade.php
    --}}
</body>
</html>
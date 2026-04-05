<style>
    /* RESET DAN BASE */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        overflow: hidden;
        height: 100vh;
        width: 100vw;
        background-color: #f8f9fa;
    }

    /* FIXED HEADER */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: linear-gradient(135deg, #41a67e 0%, #2f8d6a 100%);
        color: #fff;
        padding: 10px 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fixed-header .title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    
    .fixed-header .subtitle {
        font-size: 0.75rem;
        opacity: 0.9;
        font-weight: 400;
    }

    /* BOTTOM NAVBAR - LEBAR PENUH */
    .bottom-navbar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: #fff;
        border-top: 3px solid #e9ecef;
        padding: 15px 20px;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        height: 100px;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .bottom-navbar .nav-buttons {
        display: flex;
        gap: 10px;
        width: 100%;
        justify-content: space-between; /* Ini yang buat sebar ujung ke ujung */
        align-items: stretch;
        flex-wrap: nowrap;
    }

    /* TOMBOL NAVIGASI - SEMUA SAMA LEBAR */
    .bottom-navbar .btn-nav {
        flex: 1; /* Ini kunci untuk sama lebar */
        min-height: 70px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        text-align: center;
        white-space: nowrap;
        padding: 12px 8px;
        font-size: 0.9rem;
    }

    .bottom-navbar .btn-nav i {
        font-size: 1.3rem;
        margin-bottom: 2px;
    }

    .bottom-navbar .btn-nav span {
        font-size: 0.8rem;
        line-height: 1.2;
    }

    .bottom-navbar .btn-nav:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Dropdown Display - SAMA DENGAN TOMBOL LAIN */
    .display-dropdown {
        flex: 1;
        display: flex;
    }

    .display-dropdown .btn-nav {
        flex: 1;
        width: 100%;
    }

    /* DROPDOWN MENU LEBIH BESAR UNTUK TABLET */
    .display-dropdown .dropdown-menu {
        min-width: 280px;
        font-size: 1rem;
        border-radius: 12px;
        margin-top: 8px;
        margin-bottom: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .display-dropdown .dropdown-item {
        padding: 18px 22px;
        min-height: 60px;
        display: flex;
        align-items: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 6px;
        font-size: 1.1rem;
    }

    .display-dropdown .dropdown-item:last-child {
        margin-bottom: 0;
    }

    .display-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }

    .display-dropdown .dropdown-item i {
        font-size: 1.4rem;
        width: 32px;
    }

    /* WARNA TOMBOL */
    .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
        border-width: 2px;
    }

    .btn-outline-primary:hover, 
    .display-dropdown .btn-outline-primary.show {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
        border-width: 2px;
    }

    .btn-outline-info:hover {
        color: #fff;
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-outline-success {
        color: #198754;
        border-color: #198754;
        border-width: 2px;
    }

    .btn-outline-success:hover {
        background-color: #198754;
        color: white;
    }

    .btn-outline-warning {
        color: #fd7e14;
        border-color: #fd7e14;
        border-width: 2px;
    }

    .btn-outline-warning:hover {
        background-color: #fd7e14;
        border-color: #fd7e14;
        color: #fff;
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.4);
    }

    /* RESPONSIVE TABLET (iPad) */
    @media (max-width: 1024px) {
        .bottom-navbar {
            height: 110px;
            padding: 15px 20px;
        }

        .bottom-navbar .nav-buttons {
            gap: 8px;
        }

        .bottom-navbar .btn-nav {
            min-height: 75px;
            padding: 14px 8px;
        }

        .bottom-navbar .btn-nav i {
            font-size: 1.4rem;
        }

        .bottom-navbar .btn-nav span {
            font-size: 0.85rem;
        }

        /* DROPDOWN LEBIH BESAR DI TABLET */
        .display-dropdown .dropdown-menu {
            min-width: 350px;
            font-size: 1.2rem;
        }

        .display-dropdown .dropdown-item {
            min-height: 65px;
            padding: 20px 24px;
            font-size: 1.2rem;
        }

        .display-dropdown .dropdown-item i {
            font-size: 1.5rem;
            width: 36px;
        }
    }

    /* RESPONSIVE MOBILE */
    @media (max-width: 768px) {
        .bottom-navbar {
            height: 90px;
            padding: 10px 12px;
        }

        .bottom-navbar .nav-buttons {
            gap: 6px;
            justify-content: space-between;
        }

        .bottom-navbar .btn-nav {
            min-height: 65px;
            padding: 10px 6px;
        }

        .bottom-navbar .btn-nav i {
            font-size: 1.2rem;
        }

        .bottom-navbar .btn-nav span {
            font-size: 0.75rem;
        }

        /* DROPDOWN FULL WIDTH DI MOBILE */
        .display-dropdown .dropdown-menu {
            min-width: 95vw;
            left: 50% !important;
            transform: translateX(-50%) !important;
            font-size: 1.1rem;
        }

        .display-dropdown .dropdown-item {
            min-height: 55px;
            padding: 16px 20px;
            font-size: 1.1rem;
        }
    }

    /* MAIN CONTAINER */
    .ticket-container {
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        bottom: 100px; /* Sesuaikan dengan tinggi bottom-navbar */
        overflow-y: auto;
        overflow-x: hidden;
        padding: 12px;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
    }

    /* SECTION CARDS */
    .section-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        background: white;
        margin-bottom: 0;
        overflow: hidden;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .section-card .card-body {
        padding: 0;
        flex: 1;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* TABLE STYLES */
    .table th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #2d3748;
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #e9ecef;
        position: sticky;
        top: 0;
        z-index: 10;
        padding: 10px 12px;
    }
    
    .table td {
        padding: 10px 12px;
        font-size: 0.8rem;
    }

    .table-responsive {
        margin: 0;
        flex: 1;
        overflow-y: auto;
    }

    .empty-state {
        padding: 40px 0;
        color: #6c757d;
        text-align: center;
    }

    /* SCROLLBAR */
    .ticket-container::-webkit-scrollbar,
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .ticket-container::-webkit-scrollbar-track,
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .ticket-container::-webkit-scrollbar-thumb,
    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    
    .ticket-container::-webkit-scrollbar-thumb:hover,
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }
    
    /* LOGOUT MODAL */
    .logout-icon {
        width: 70px;
        height: 70px;
        background: #fff8e1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .logout-icon i {
        font-size: 28px;
        color: #ff9800;
    }

    /* RESPONSIVE HEADER */
    @media (max-width: 768px) {
        .fixed-header .title {
            font-size: 1.1rem;
        }
        .fixed-header .subtitle {
            font-size: 0.8rem;
        }
    }

    /* HAPUS SEMUA ATURAN CSS LAMA YANG KONFLIK */
    /* Tidak perlu aturan CSS lama karena sudah diganti di atas */
</style>
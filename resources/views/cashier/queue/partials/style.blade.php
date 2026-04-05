<style>
/* Fixed Layout Structure */
body {
    overflow: hidden; /* Prevent body scroll */
}

/* Fixed Header */
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
}

.fixed-header .title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0;
}

.fixed-header .subtitle {
    font-size: 0.7rem;
    opacity: 0.9;
}

    /* BOTTOM NAVBAR - UPDATE DENGAN STYLE YANG BARU */
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
        justify-content: space-between;
        align-items: stretch;
        flex-wrap: nowrap;
    }

    /* TOMBOL NAVIGASI - SEMUA SAMA LEBAR */
    .bottom-navbar .btn-nav {
        flex: 1;
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

/* Main Content Area */
.main-content {
    position: fixed;
    top: 60px;
    bottom: 100px;
    left: 0;
    right: 0;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 12px;
    background: #f8f9fa;
}

.content-wrapper {
    width: 100%;
}

.row.g-3, .row.g-4 {
    margin: 0;
}

.col-lg-7, .col-lg-5, .col-lg-8, .col-lg-4 {
    display: flex;
    flex-direction: column;
}

.section-card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    background: white;
    margin-bottom: 0;
    display: flex;
    flex-direction: column;
}
.section-card .card-header {
    background: #f7fdf9;
    border-bottom: 1px solid #e4f2ea;
    font-weight: 700;
    letter-spacing: 0.2px;
    flex-shrink: 0;
    padding: 10px 15px;
    font-size: 0.9rem;
}

.section-card .card-body {
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
}

/* ========== VENUE LIST STYLES ========== */
.venue-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 8px;
    padding: 4px;
}

.venue-grid::-webkit-scrollbar {
    width: 6px;
}

.venue-grid::-webkit-scrollbar-track {
    background: #f1f3f5;
    border-radius: 10px;
}

.venue-grid::-webkit-scrollbar-thumb {
    background: #41a67e;
    border-radius: 10px;
}

.venue-grid::-webkit-scrollbar-thumb:hover {
    background: #2f8d6a;
}

/* Venue Cards - Square Style */
.venue-card-item {
    height: 230px;
}

.venue-card {
    border: 0;
    border-radius: 10px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 220px;
    display: flex;
    flex-direction: column;
}

.venue-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.venue-card .venue-image {
    position: relative;
    height: 110px;
    overflow: hidden;
}

.venue-card .venue-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.venue-card:hover .venue-image img {
    transform: scale(1.05);
}

.venue-card .venue-status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.venue-card .venue-status-badge.open {
    background: rgba(25, 135, 84, 0.9);
    color: white;
}

.venue-card .venue-status-badge.closed {
    background: rgba(220, 53, 69, 0.9);
    color: white;
}

.venue-card .venue-status-badge.maintenance {
    background: rgba(255, 193, 7, 0.9);
    color: #212529;
}

.venue-card .card-body {
    padding: 8px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.venue-card .card-title {
    font-size: 11px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 2px;
    line-height: 1.2;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.venue-card .venue-info {
    flex: 1;
}

.venue-card .venue-info small {
    font-size: 9px;
    line-height: 1.2;
}

.venue-card .venue-footer {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #e9ecef;
}

.venue-card .venue-price {
    font-size: 14px;
}

.venue-card .venue-stats small {
    font-size: 11px;
}

/* ========== CART & PAYMENT STYLES ========== */
.col-lg-5 .section-card .card-body {
    padding: 10px;
}

.col-lg-5 .section-card .card-body::-webkit-scrollbar {
    width: 6px;
}

.col-lg-5 .section-card .card-body::-webkit-scrollbar-track {
    background: #f1f3f5;
    border-radius: 10px;
}

.col-lg-5 .section-card .card-body::-webkit-scrollbar-thumb {
    background: #41a67e;
    border-radius: 10px;
}

.col-lg-5 .section-card .card-body::-webkit-scrollbar-thumb:hover {
    background: #2f8d6a;
}

/* Payment Summary Styles */
.payment-summary {
    margin-top: 10px;
    padding-top: 10px;
    background: white;
    border-top: 1px solid #e9ecef !important;
    flex-shrink: 0;
}

.payment-summary::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 0;
    right: 0;
    height: 10px;
    background: linear-gradient(to bottom, transparent, white);
    pointer-events: none;
}

.btn-pay {
    padding: 10px;
    font-weight: 600;
    font-size: 14px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    transition: all 0.3s ease;
    border: none;
    background: linear-gradient(135deg, #198754, #20c997);
    margin-top: 4px;
}

.btn-pay:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4);
}

.btn-pay:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

/* Filter section - fixed at top */
.filter-section {
    flex-shrink: 0;
    margin-bottom: 16px;
}

/* Cart Items */
.cart-items {
    flex: 1;
    max-height: none;
    overflow: visible;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 8px;
}

.cart-items::-webkit-scrollbar {
    width: 6px;
}

.cart-items::-webkit-scrollbar-track {
    background: #f1f3f5;
    border-radius: 10px;
}

.cart-items::-webkit-scrollbar-thumb {
    background: #41a67e;
    border-radius: 10px;
}

.cart-items::-webkit-scrollbar-thumb:hover {
    background: #2f8d6a;
}

.cart-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 10px;
    margin-bottom: 8px;
    position: relative;
    transition: all 0.2s;
}

.cart-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.cart-item .cart-item-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 6px;
}

.cart-item .cart-item-venue {
    font-weight: 600;
    font-size: 13px;
    color: #2d3748;
}

.cart-item .cart-item-section {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 4px;
}

.cart-item .cart-item-time {
    font-size: 12px;
    color: #495057;
    margin-bottom: 2px;
}

.cart-item .cart-item-price {
    font-weight: 700;
    color: #2f8d6a;
    font-size: 14px;
}

.cart-item .remove-item {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s;
}

.cart-item .remove-item:hover {
    background: #dc3545;
    color: white;
}

/* Bottom Sheet Modal */
.bottom-sheet {
    position: fixed;
    bottom: -100%;
    left: 0;
    width: 100%;
    height: 75vh;
    background: white;
    border-radius: 24px 24px 0 0;
    box-shadow: 0 -10px 40px rgba(0,0,0,0.3);
    transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.bottom-sheet.show {
    bottom: 0;
}

.bottom-sheet-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    z-index: 1040;
}

.bottom-sheet-overlay.show {
    opacity: 1;
    visibility: visible;
}

.bottom-sheet-header {
    background: linear-gradient(135deg, #41a67e 0%, #2f8d6a 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.bottom-sheet-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.section-tab {
    display: inline-block;
    padding: 8px 16px;
    margin-right: 8px;
    border-radius: 20px;
    background: #e9ecef;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    font-size: 14px;
    font-weight: 500;
}

.section-tab:hover {
    background: #d3f9e8;
    color: #2f8d6a;
}

.section-tab.active {
    background: #41a67e;
    color: white;
    border-color: #2f8d6a;
}

/* Schedule Grid */
.schedule-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

@media (max-width: 768px) {
    .schedule-grid {
        grid-template-columns: 1fr;
    }
}

.schedule-card {
    border-radius: 12px;
    padding: 14px;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.schedule-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.schedule-card.available {
    background: linear-gradient(135deg, #d1f4e0 0%, #c0eed5 100%);
    border-color: #a8e6c8;
}

.schedule-card.occupied {
    background: linear-gradient(135deg, #ffd6d6 0%, #ffcccc 100%);
    border-color: #ffb3b3;
}

.schedule-card.reserved {
    background: linear-gradient(135deg, #fff4d6 0%, #ffefc0 100%);
    border-color: #ffe4a8;
}

.schedule-card.selected {
    border: 2px solid #198754;
    background-color: rgba(25, 135, 84, 0.05);
    position: relative;
    overflow: hidden;
}

.schedule-card.selected::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background-color: #198754;
}

.selected-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #198754;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    animation: fadeIn 0.3s ease;
}

.selected-badge i {
    font-size: 10px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.schedule-date {
    font-size: 11px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.schedule-time {
    font-weight: 700;
    font-size: 14px;
    color: #2d3748;
}

.schedule-status {
    font-size: 13px;
    font-weight: 600;
    margin-top: 4px;
}

.schedule-status.available {
    color: #2f8d6a;
}

.schedule-status.occupied {
    color: #dc3545;
}

.schedule-status.reserved {
    color: #f59e0b;
}

.schedule-meta {
    font-size: 12px;
    color: #6c757d;
    margin-top: 2px;
}

.schedule-price {
    position: absolute;
    top: 16px;
    right: 16px;
    font-weight: 700;
    font-size: 14px;
    color: #2f8d6a;
}

.field-header {
    background: #f8f9fa;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.field-name {
    font-weight: 700;
    font-size: 16px;
    color: #2d3748;
}

.field-meta {
    font-size: 13px;
    color: #6c757d;
}

.field-price {
    font-weight: 700;
    font-size: 15px;
    color: #2f8d6a;
}

/* Date Filter Buttons */
.date-btn {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 16px;
    border-radius: 12px;
    background: #f8f9fa;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    font-size: 13px;
    min-width: 80px;
    text-align: center;
}

.date-btn:hover {
    background: #e3f9ef;
    color: #2f8d6a;
    transform: translateY(-2px);
}

.date-btn.active {
    background: linear-gradient(135deg, #41a67e 0%, #2f8d6a 100%);
    color: white;
    border-color: #2f8d6a;
    box-shadow: 0 4px 12px rgba(65, 166, 126, 0.3);
}

.date-btn .day-label {
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 2px;
}

.date-btn .date-label {
    font-size: 11px;
    opacity: 0.85;
}

/* Autocomplete Dropdown */
.autocomplete-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 250px;
    overflow-y: auto;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    margin-top: 4px;
}

.autocomplete-item {
    padding: 10px 14px;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f5;
    transition: background 0.2s;
}

.autocomplete-item:last-child {
    border-bottom: none;
}

.autocomplete-item:hover {
    background: #f8f9fa;
}

.autocomplete-item .buyer-name {
    font-weight: 600;
    color: #2d3748;
    display: block;
}

.autocomplete-item .buyer-phone {
    font-size: 12px;
    color: #6c757d;
    display: block;
    margin-top: 2px;
}

/* Selected Buyer */
.selected-buyer {
    margin-top: 8px;
    padding: 10px 12px;
    background: #e3f9ef;
    border: 1px solid #a8e6c8;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.selected-buyer .buyer-info .buyer-name {
    font-weight: 600;
    color: #2f8d6a;
}

.selected-buyer .buyer-info .buyer-phone {
    font-size: 11px;
    color: #6c757d;
}

.selected-buyer .remove-buyer {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
}

.selected-buyer .remove-buyer:hover {
    background: #dc3545;
    color: white;
}

/* Modal Order Details */
.order-details {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.order-details::-webkit-scrollbar {
    width: 5px;
}

.order-details::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.order-details::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.order-group {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

.schedule-list {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 10px;
}

.schedule-item {
    transition: background-color 0.2s;
}

.schedule-item:hover {
    background-color: rgba(0,0,0,0.02);
}

/* Payment Summary Modal */
.payment-summary .card {
    border-radius: 12px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-total, .summary-cash, .summary-change {
    padding: 10px 0;
    border-top: 1px solid #e9ecef;
}

.summary-total {
    border-top: 2px solid #198754;
}

/* Buyer Info Card */
.buyer-info .card {
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.btn-warning {
    background-color: #ff9800;
    border-color: #ff9800;
    color: white;
    font-weight: 600;
    padding: 12px;
    border-radius: 10px;
}

.btn-warning:hover {
    background-color: #f57c00;
    border-color: #f57c00;
    color: white;
}

/* Logout Button */
.btn-outline-light {
    border-color: rgba(255, 255, 255, 0.25);
    color: rgba(255, 255, 255, 0.9);
}

.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
}

/* Logout Modal */
.logout-icon {
    width: 60px;
    height: 60px;
    background: #fff8e1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.logout-icon i {
    font-size: 24px;
    color: #ff9800;
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Success Animation */
.success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #198754, #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    animation: successPulse 0.6s ease-in-out;
}

.success-icon i {
    font-size: 36px;
    color: white;
}

@keyframes successPulse {
    0% {
        transform: scale(0.8);
        opacity: 0.7;
    }
    70% {
        transform: scale(1.1);
        opacity: 1;
    }
    100% {
        transform: scale(1);
    }
}

/* Error Icon Animation */
.error-icon i {
    animation: pulse 0.6s ease-in-out;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

/* Responsive Design for Bottom Navbar */
@media (max-width: 768px) {
    .bottom-navbar .nav-buttons {
        gap: 8px;
    }
    
    .bottom-navbar .btn-nav {
        padding: 12px 10px;
        font-size: 0.85rem;
        flex-direction: column;
        gap: 4px;
    }
    
    .bottom-navbar .btn-nav i {
        font-size: 1.1rem;
    }
    
    .venue-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 1200px) {
    .venue-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}
</style>
<style>
/* ================= BASE ================= */
body {
    background: #f8fafc;
    font-family: 'Inter', sans-serif;
}

.container-fluid {
    padding: 20px;
}

/* ================= HEADER ================= */
    .page-header-box {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        margin-bottom: 30px;
        border: 1px solid #e5f5ec;
        animation: fadeInDown 0.6s ease;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1a5c37;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        color: #22c55e;
        font-size: 32px;
    }
    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
    }

    .total-badge {
        background: #22c55e;
        color: white;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ================= STATS ================= */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-box {
        background: white;
        padding: 14px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .stat-box-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        margin-bottom: 8px;
    }

    .stat-box-icon.total { background: #16a34a; }
    .stat-box-icon.public { background: #22c55e; }
    .stat-box-icon.private { background: #f59e0b; }

    .stat-box-value {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .stat-box-label {
        font-size: 12px;
        color: #6b7280;
    }

    /* ================= FILTER ================= */
    .filter-section {
        background: white;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 10px;
    }

    .search-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
    }

    .filter-select {
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid #e5e7eb;
    }

    .reset-button {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        cursor: pointer;
    }

    /* ================= GRID ================= */
    .komunitas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 14px;
    }

    /* ================= CARD ================= */
    .komunitas-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: 0.2s;
    }

    .komunitas-card:hover {
        transform: translateY(-3px);
    }

    /* HEADER CARD */
    .card-header-section {
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .komunitas-avatar,
    .avatar-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
    }

    .avatar-placeholder {
        background: #22c55e;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    /* BADGE */
    .type-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 999px;
    }

    .type-badge.public {
        background: #dcfce7;
        color: #16a34a;
    }

    .type-badge.private {
        background: #fef3c7;
        color: #d97706;
    }

    /* BODY */
    .card-body-section {
        padding: 14px;
    }

    .komunitas-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .komunitas-description {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .komunitas-meta {
        font-size: 12px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    /* FOOTER */
    .card-footer-section {
        padding: 12px 14px;
        display: flex;
        gap: 8px;
        border-top: 1px solid #f1f5f9;
    }

    /* BUTTON */
    .detail-button {
        flex: 1;
        padding: 6px;
        font-size: 12px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-ban {
        background: #fee2e2;
        color: #dc2626;
    }

    .view-detail {
        background: #22c55e;
        color: white;
    }

    /* ================= EMPTY ================= */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 30px;
    }

    .empty-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    /* ================= MODAL FIX ================= */
    .swal2-popup {
        border-radius: 12px !important;
        padding: 0 !important;
    }

    .swal2-title,
    .swal2-html-container {
        margin: 0 !important;
        padding: 0 !important;
    }

    /* ================= MODAL CLEAN IMPROVED ================= */
    .modal-clean {
        border-radius: 16px !important;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
    }

    /* HEADER */
    .modal-header-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-bottom: 1px solid #eee;
    }

    .modal-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
    }

    .modal-avatar.placeholder {
        background: #22c55e;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    /* TITLE */
    .modal-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .modal-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    .modal-badge.public {
        background: #dcfce7;
        color: #16a34a;
    }

    .modal-badge.private {
        background: #fef3c7;
        color: #d97706;
    }

    /* BODY */
    .modal-body-custom {
        padding: 16px;
    }

    .modal-body-custom h6 {
        font-size: 13px;
        margin-bottom: 6px;
        color: #374151;
    }

    .modal-description {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 14px;
        line-height: 1.5;
    }

    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .modal-box {
        background: #f9fafb;
        border-radius: 10px;
        padding: 10px;
        display: flex;
        flex-direction: column; /* buat icon & teks vertikal */
        align-items: center;    /* horizontal center */
        justify-content: center; /* vertical center */
        text-align: center;      /* center teks */
        gap: 6px;                /* jarak antara icon & teks */
    }

    .modal-box i {
        font-size: 20px;         /* icon lebih besar */
        color: #22c55e;
    }

    .modal-box small {
        font-size: 11px;
        color: #6b7280;
    }

    .modal-box strong {
        font-size: 13px;
        color: #111827;
    }

    /* FOOTER ID */
    .modal-footer-id {
        text-align: center;
        font-size: 11px;
        color: #9ca3af;
        margin-top: 12px;
    }

    .komunitas-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        transition: all 0.25s ease;
    }

    .komunitas-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }

    .komunitas-description {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 10px;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .komunitas-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0; /* INI PENTING biar ga turun */
    }

    /* biar badge ga turun */
    .type-badge {
        white-space: nowrap;
    }

    .modal-title-row {
        display: flex;
        align-items: center; /* sejajar vertikal */
        gap: 8px; /* jarak antara title & badge */
    }

    .modal-badge {
        white-space: nowrap; /* biar badge ga pecah ke baris baru */
    }

    /* ===== BANNED STYLE ===== */
    .komunitas-card.banned {
        opacity: 0.6;
        filter: grayscale(80%);
        border: 1px solid #fecaca;
        background: #fff5f5;
    }

    .banned-badge {
        background: #fee2e2;
        color: #dc2626;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
    }

    .detail-button:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .swal2-popup {
        padding: 24px !important;
    }

    .swal2-title {
        margin-bottom: 12px !important;
    }

    .swal2-html-container {
        margin-top: 10px !important;
        line-height: 1.6;
    }
</style>
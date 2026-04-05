<style>
    /* ================= MAIN CONTENT ================= */
    .main-content {
        padding-top: 80px;
        padding-bottom: 90px;
    }

    /* ================= PAGE HEADER ================= */
    .page-header {
        padding: 20px;
        background: var(--gradient-light);
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--light-gray);
        margin-bottom: 25px;
    }

    .page-header::before {
        content: "";
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header-content {
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
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
        cursor: pointer;
        color: var(--primary);
        font-size: 20px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .back-button:hover {
        background: var(--primary);
        color: white;
        transform: translateX(-4px);
    }

    .page-title {
        font-size: 32px;
        font-weight: 900;
        color: var(--text);
        margin: 0;
        line-height: 1.3;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--text-light);
        margin-top: 8px;
        font-weight: 500;
    }

    /* ================= STATS CARDS ================= */
    .summary-cards {
        padding: 0 20px 25px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .summary-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: var(--shadow-md);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .summary-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-accent);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
        box-shadow: var(--shadow-sm);
    }

    .summary-card.pending .stat-icon {
        background: rgba(243, 156, 18, 0.1);
        color: var(--warning);
    }

    .summary-card.approved .stat-icon {
        background: rgba(46, 204, 113, 0.1);
        color: var(--success);
    }

    .summary-card.rejected .stat-icon {
        background: rgba(231, 76, 60, 0.1);
        color: var(--danger);
    }

    .summary-card.total .stat-icon {
        background: rgba(52, 152, 219, 0.1);
        color: var(--info);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 900;
        margin-bottom: 8px;
        line-height: 1;
    }

    .summary-card.pending .stat-number {
        color: var(--warning);
    }

    .summary-card.approved .stat-number {
        color: var(--success);
    }

    .summary-card.rejected .stat-number {
        color: var(--danger);
    }

    .summary-card.total .stat-number {
        color: var(--info);
    }

    .stat-label {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ================= FILTER TABS ================= */
    .filter-tabs {
        padding: 0 20px 20px;
    }

    .tab-list {
        display: flex;
        background: var(--light);
        border-radius: 14px;
        padding: 6px;
        gap: 8px;
        box-shadow: var(--shadow-sm);
    }

    .tab-button {
        flex: 1;
        padding: 12px 16px;
        border: none;
        background: transparent;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
    }

    .tab-button:hover {
        background: rgba(10, 92, 54, 0.05);
        color: var(--primary);
    }

    .tab-button.active {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .tab-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        min-width: 24px;
        text-align: center;
    }

    /* ================= REQUEST CARDS ================= */
    .requests-container {
        padding: 0 20px 30px;
    }

    .request-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 20px;
        border: 2px solid transparent;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .request-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--accent);
    }

    .request-header {
        padding: 20px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-bottom: 2px solid var(--light-gray);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: var(--shadow-sm);
        background: var(--light-gray);
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-phone {
        font-size: 13px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .request-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(10, 92, 54, 0.1);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
    }

    /* ================= REQUEST BODY ================= */
    .request-body {
        padding: 20px;
    }

    .request-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .request-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-label {
        font-size: 11px;
        color: var(--text-light);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .reason-box {
        background: var(--light);
        padding: 15px;
        border-radius: 12px;
        margin-top: 15px;
        border-left: 4px solid var(--accent);
    }

    .reason-label {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reason-text {
        font-size: 14px;
        color: var(--text);
        line-height: 1.5;
    }

    /* ================= REQUEST FOOTER ================= */
    .request-footer {
        padding: 15px 20px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-top: 2px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--shadow-sm);
    }

    .badge-pending {
        background: linear-gradient(135deg, var(--warning) 0%, #f1c40f 100%);
        color: white;
    }

    .badge-approved {
        background: linear-gradient(135deg, var(--success) 0%, var(--accent) 100%);
        color: white;
    }

    .badge-rejected {
        background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        min-width: 100px;
    }

    .btn-approve {
        background: var(--gradient-accent);
        color: white;
    }

    .btn-approve:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-approve:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-reject {
        background: white;
        color: var(--danger);
        border: 2px solid var(--danger);
    }

    .btn-reject:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-reject:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f9fdfb 0%, #f5faf7 100%);
        border-radius: 20px;
        border: 2px dashed rgba(10, 92, 54, 0.2);
        margin: 0 20px;
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(46, 204, 113, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .empty-state-icon {
        font-size: 80px;
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .empty-state-title {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 10px;
        color: var(--text);
        position: relative;
        z-index: 1;
    }

    .empty-state-desc {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 24px;
        line-height: 1.5;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 1;
    }

    /* ================= LOADING OVERLAY ================= */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 20px;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid var(--light-gray);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .loading-text {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ================= RESPONSIVE ================= */
    @media (min-width: 600px) {
        .summary-cards {
            grid-template-columns: repeat(4, 1fr);
        }

        .request-details {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 28px;
        }

        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
            padding: 0 15px 20px;
        }

        .requests-container {
            padding: 0 15px 25px;
        }

        .user-info {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
        }

        .request-body {
            padding: 15px;
        }

        .request-details {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .request-footer {
            flex-direction: column;
            gap: 12px;
            padding: 15px;
        }

        .action-buttons {
            width: 100%;
        }

        .btn-action {
            flex: 1;
        }
    }

    @media (max-width: 359px) {
        .page-title {
            font-size: 24px;
        }

        .summary-cards {
            grid-template-columns: 1fr;
        }

        .tab-button {
            padding: 10px 12px;
            font-size: 12px;
        }

        .request-details {
            grid-template-columns: 1fr;
        }
    }
</style>
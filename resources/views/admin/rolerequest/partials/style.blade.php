<style>
    body {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
        font-family: 'Inter', sans-serif;
    }
    
    .container-fluid {
        padding: 30px;
    
    }
    
    .page-header-wrapper {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        margin-bottom: 30px;
        border: 1px solid #e5f5ec;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    
    .filter-button-group {
        display: flex;
        gap: 8px;
        background: #f9fafb;
        padding: 6px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    
    .filter-btn {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #6b7280;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-btn:hover {
        color: #22c55e;
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease;
    }
    
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.3s; }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(34, 197, 94, 0.15);
    }
    
    .stat-card-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: white;
    }
    
    .stat-icon.pending {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }
    
    .stat-icon.approved {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }
    
    .stat-icon.rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .stat-icon.total {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-label {
        color: #9ca3af;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1a5c37;
    }
    
    .table-wrapper {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        animation: fadeInUp 0.6s ease 0.2s;
    }
    
    .table {
        margin: 0;
    }
    
    .table thead {
        background: linear-gradient(135deg, #1a5c37 0%, #22c55e 100%);
    }
    
    .table thead th {
        color: white;
        font-weight: 600;
        padding: 18px 20px;
        border: none;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        border-bottom: 1px solid #f0fdf4;
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: #f0fdf4;
    }
    
    .table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        color: #374151;
    }
    
    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .user-avatar-cell {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e5f5ec;
    }
    
    .avatar-circle-cell {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
    }
    
    .user-details {
        flex: 1;
    }
    
    .user-name {
        font-weight: 700;
        color: #1a5c37;
        margin-bottom: 2px;
    }
    
    .user-phone {
        color: #9ca3af;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .role-badge-cell {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    
    .role-badge-cell.penyewa {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .role-badge-cell.pemilik {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }
    
    .role-badge-cell.admin {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .role-arrow {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-badge.pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .status-badge.approved {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .status-badge.rejected {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .action-button-group {
        display: flex;
        gap: 6px;
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .btn-action.approve {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }
    
    .btn-action.approve:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    
    .btn-action.reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-action.reject:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-action:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .reason-text {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .time-text {
        color: #9ca3af;
        font-size: 13px;
    }
    
    .time-date {
        color: #6b7280;
        font-size: 12px;
        display: block;
        margin-top: 2px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-icon {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 20px;
    }
    
    .empty-text {
        color: #6b7280;
        font-size: 16px;
    }
    
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header.success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 20px 28px;
        border: none;
    }
    
    .modal-header.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 20px 28px;
        border: none;
    }
    
    .modal-title {
        font-weight: 700;
        font-size: 20px;
    }
    
    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 1;
    }
    
    .modal-body {
        padding: 28px;
    }
    
    .alert-box {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-box.info {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .alert-box.warning {
        background: #fef3c7;
        color: #92400e;
    }
    
    .user-card {
        background: #f9fafb;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    
    .user-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    
    .user-card-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
    }
    
    .user-card-name {
        font-weight: 700;
        color: #1a5c37;
        margin-bottom: 4px;
    }
    
    .user-card-phone {
        color: #9ca3af;
        font-size: 13px;
    }
    
    .role-comparison {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    
    .role-item {
        background: white;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }
    
    .role-item-label {
        color: #9ca3af;
        font-size: 12px;
        margin-bottom: 6px;
    }
    
    .modal-footer {
        padding: 20px 28px;
        border-top: 1px solid #f0fdf4;
    }
    
    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .btn-secondary:hover {
        background: #d1d5db;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    }
    
    .btn-danger-modal {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-danger-modal:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .container-fluid {
        
            padding: 20px;
        }
    }
</style>
<style>
    body {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
        font-family: 'Inter', sans-serif;
    }
    
    .container-fluid {
        padding: 30px;
   
    }
    
    .page-header-box {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        margin-bottom: 30px;
        border: 1px solid #e5f5ec;
        animation: fadeInDown 0.6s ease;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    
    .filter-buttons {
        display: flex;
        gap: 10px;
    }
    
    .filter-btn {
        padding: 10px 20px;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-btn:hover {
        border-color: #22c55e;
        color: #22c55e;
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-color: #22c55e;
        color: white;
    }
    
    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        animation: fadeInUp 0.6s ease;
    }
    
    .table-card-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        padding: 20px 28px;
        border-bottom: 1px solid #e5f5ec;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .table-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a5c37;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .count-badge {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
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
        transform: scale(1.01);
    }
    
    .table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        color: #374151;
    }
    
    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e5f5ec;
    }
    
    .avatar-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
    }
    
    .role-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-badge.admin {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .role-badge.buyer {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .role-badge.landowner {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }
    
    .phone-link {
        color: #22c55e;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }
    
    .phone-link:hover {
        color: #16a34a;
        transform: translateX(4px);
    }
    
    .token-badge {
        background: #fef3c7;
        color: #d97706;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }
    
    .time-info {
        color: #9ca3af;
        font-size: 13px;
    }
    
    .time-date {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        display: block;
        margin-top: 4px;
    }
    
    .empty-state-row {
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
    
    .table-responsive {
        overflow: hidden !important;
    }

    @media (max-width: 768px) {
        .container-fluid {

            padding: 20px;
        }

        .filter-buttons {
            flex-wrap: wrap;
        }
    }
</style>

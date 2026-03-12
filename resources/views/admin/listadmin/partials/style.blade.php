<style>
    body {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
        font-family: 'Inter', sans-serif;
    }
    
    .container-fluid {
        padding: 30px;  
    }
    
    .page-header {
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
    
    .btn-primary {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.35);
    }
    
    .table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        animation: fadeInUp 0.6s ease;
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
        padding: 18px;
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
        padding: 16px 18px;
        vertical-align: middle;
        color: #374151;
    }
    
    .avatar-preview {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5f5ec;
        transition: all 0.3s ease;
    }
    
    .avatar-preview:hover {
        transform: scale(1.5);
        border-color: #22c55e;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-sm {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #1a5c37 0%, #22c55e 100%);
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
    
    .form-label {
        font-weight: 600;
        color: #1a5c37;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 12px 16px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
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
    
    .badge-admin {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
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
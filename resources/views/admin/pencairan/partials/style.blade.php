<style>
    body {
        background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        font-family: 'Inter', sans-serif;
    }
    
    .container-fluid {
        padding: 30px;
    }
    
    .page-header {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 139, 230, 0.08);
        margin-bottom: 30px;
        border: 1px solid #e7f3ff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: fadeInDown 0.6s ease;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1a4a8d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-title i {
        color: #228be6;
        font-size: 32px;
    }
    
    /* Filter Card */
    .filter-card {
        border-radius: 16px;
        border: 1px solid #e7f3ff;
        box-shadow: 0 4px 20px rgba(34, 139, 230, 0.08);
        background: white;
        animation: fadeInUp 0.6s ease;
    }
    
    .filter-card .card-body {
        padding: 24px;
    }
    
    /* Withdrawal Cards */
    .withdrawal-card {
        border-radius: 16px;
        border: 1px solid #e7f3ff;
        box-shadow: 0 4px 20px rgba(34, 139, 230, 0.08);
        transition: all 0.3s ease;
        background: white;
        animation: fadeInUp 0.8s ease;
    }
    
    .withdrawal-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(34, 139, 230, 0.15);
    }
    
    .withdrawal-card .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e7f3ff 100%);
        border-bottom: 1px solid #e7f3ff;
        padding: 20px;
        border-radius: 16px 16px 0 0 !important;
    }
    
    .withdrawal-card .card-body {
        padding: 24px;
    }
    
    .withdrawal-card .card-footer {
        background: #f8fafc;
        border-top: 1px solid #e7f3ff;
        padding: 20px;
        border-radius: 0 0 16px 16px;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }
    
    .badge-info {
        background: linear-gradient(135deg, #228be6 0%, #1c7ed6 100%);
        color: white;
    }
    
    .badge-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }
    
    .badge-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .status-badge-lg {
        padding: 12px 24px;
        font-size: 14px;
    }
    
    /* User Info */
    .user-info .user-icon {
        color: #228be6;
    }
    
    /* Amount Display */
    .amount-display {
        background: linear-gradient(135deg, #f8fafc 0%, #e7f3ff 100%);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e7f3ff;
    }
    
    .amount-value {
        color: #1a4a8d;
        font-weight: 700;
        margin: 0;
        font-size: 28px;
    }
    
    /* Bank Info */
    .bank-details {
        background: #f8fafc;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .bank-details div {
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }
    
    .bank-details div:last-child {
        border-bottom: none;
    }
    
    .bank-details i {
        color: #228be6;
        width: 20px;
        text-align: center;
    }
    
    /* Photo Preview */
    .photo-preview {
        position: relative;
    }
    
    .photo-link {
        position: relative;
        display: block;
        overflow: hidden;
        border-radius: 12px;
    }
    
    .photo-thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .photo-link:hover .photo-thumbnail {
        transform: scale(1.05);
    }
    
    .photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(34, 139, 230, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .photo-link:hover .photo-overlay {
        opacity: 1;
    }
    
    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #1a4a8d;
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
        border-color: #228be6;
        box-shadow: 0 0 0 4px rgba(34, 139, 230, 0.1);
    }
    
    .form-control.bg-light {
        background-color: #f8fafc !important;
        cursor: not-allowed;
    }
    
    /* Section Title */
    .section-title {
        color: #1a4a8d;
        font-weight: 600;
        padding-bottom: 12px;
        border-bottom: 2px solid #e7f3ff;
        margin-bottom: 20px;
        font-size: 18px;
    }
    
    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #228be6;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #e7f3ff;
    }
    
    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #228be6 0%, #1c7ed6 100%);
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(34, 139, 230, 0.25);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1c7ed6 0%, #1971c2 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(34, 139, 230, 0.35);
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
    
    .btn-sm {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    /* Alert */
    .alert-info {
        background: linear-gradient(135deg, #e7f3ff 0%, #d0ebff 100%);
        border: 1px solid #a5d8ff;
        color: #1a4a8d;
        border-radius: 12px;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
        border: 1px solid #ffa8a8;
        color: #c92a2a;
        border-radius: 12px;
    }
    
    /* Pagination */
    .pagination {
        justify-content: center;
    }
    
    .pagination .page-item .page-link {
        border-radius: 8px;
        border: 1px solid #e7f3ff;
        color: #1a4a8d;
        margin: 0 4px;
        padding: 8px 16px;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #228be6 0%, #1c7ed6 100%);
        border-color: #1c7ed6;
        color: white;
    }
    
    /* Animations */
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 20px;
        }
        
        .page-header {
            padding: 20px;
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .withdrawal-card .card-body {
            padding: 16px;
        }
        
        .amount-value {
            font-size: 24px;
        }
    }
</style>
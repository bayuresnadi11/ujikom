<style>
    body {
        background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
        font-family: 'Inter', sans-serif;
    }
    
    .container-fluid {
        padding: 30px;
       
    }
    
    .page-header-section {
        margin-bottom: 30px;
        animation: fadeInDown 0.6s ease;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1a5c37;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-title i {
        color: #22c55e;
    }
    
    .page-subtitle {
        color: #6b7280;
        font-size: 16px;
    }
    
    .total-badge {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
    }
    
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-box {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease;
    }
    
    .stat-box:nth-child(2) {
        animation-delay: 0.1s;
    }
    
    .stat-box:nth-child(3) {
        animation-delay: 0.2s;
    }
    
    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(34, 197, 94, 0.15);
    }
    
    .stat-box-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 16px;
    }
    
    .stat-box-icon.total {
        background: linear-gradient(135deg, #1a5c37 0%, #22c55e 100%);
    }
    
    .stat-box-icon.public {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }
    
    .stat-box-icon.private {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-box-value {
        font-size: 36px;
        font-weight: 800;
        color: #1a5c37;
        margin-bottom: 4px;
    }
    
    .stat-box-label {
        color: #6b7280;
        font-size: 14px;
        font-weight: 600;
    }
    
    .filter-section {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        margin-bottom: 30px;
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 16px;
        align-items: center;
    }
    
    .search-wrapper {
        position: relative;
    }
    
    .search-wrapper i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 18px;
    }
    
    .search-input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }
    
    .filter-select {
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        min-width: 200px;
        transition: all 0.3s ease;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }
    
    .reset-button {
        width: 48px;
        height: 48px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6b7280;
        font-size: 18px;
    }
    
    .reset-button:hover {
        background: #22c55e;
        border-color: #22c55e;
        color: white;
        transform: rotate(180deg);
    }
    
    .komunitas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }
    
    .komunitas-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.08);
        border: 1px solid #e5f5ec;
        transition: all 0.4s ease;
        animation: fadeInUp 0.6s ease;
    }
    
    .komunitas-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(34, 197, 94, 0.15);
        border-color: #22c55e;
    }
    
    .card-header-section {
        padding: 24px;
        background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        border-bottom: 1px solid #e5f5ec;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .komunitas-avatar {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
    }
    
    .avatar-placeholder {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
    }
    
    .avatar-placeholder i {
        font-size: 28px;
        color: white;
    }
    
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .type-badge.public {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .type-badge.private {
        background: #fed7aa;
        color: #d97706;
    }
    
    .card-body-section {
        padding: 24px;
    }
    
    .komunitas-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a5c37;
        margin-bottom: 12px;
        line-height: 1.4;
    }
    
    .komunitas-description {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .komunitas-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #6b7280;
        font-size: 14px;
    }
    
    .komunitas-meta i {
        color: #22c55e;
        width: 18px;
    }
    
    .card-footer-section {
        padding: 20px 24px;
        background: #f9fafb;
        border-top: 1px solid #e5f5ec;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .komunitas-id {
        color: #9ca3af;
        font-weight: 600;
        font-size: 13px;
    }
    
    .detail-button {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .detail-button:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        transform: translateX(4px);
    }
    
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        border: 2px dashed #e5f5ec;
    }
    
    .empty-icon {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 20px;
    }
    
    .empty-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a5c37;
        margin-bottom: 8px;
    }
    
    .empty-description {
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
    
    @media (max-width: 768px) {
        .container-fluid {
         
            padding: 20px;
        }
        
        .filter-section {
            grid-template-columns: 1fr;
        }
        
        .komunitas-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
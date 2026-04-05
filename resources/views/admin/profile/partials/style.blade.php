<style>
    .profile-image {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.6);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .card .form-label {
            font-weight: 600;
        }

        .form-text {
            color: #6c757d;
        }

        .btn-primary {
            background-color: #27AE60 !important;
            border-color: #27AE60 !important;
        }

        .btn-primary:hover {
            background-color: #2ECC71 !important;
            border-color: #2ECC71 !important;
        }

        .btn-outline-primary {
            color: #27AE60 !important;
            border-color: #27AE60 !important;
        }

        .btn-outline-primary:hover {
            background-color: #27AE60 !important;
            border-color: #27AE60 !important;
        }

        .btn-link {
            color: #27AE60 !important;
        }

        .btn-link:hover {
            color: #2ECC71 !important;
        }

        @media (max-width: 768px) {
            .profile-image {
                width: 120px;
                height: 120px;
            }
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .alert-info {
            background-color: #e8f4fd;
            border-color: #b6e0fe;
            color: #0c5460;
        }

        .btn-primary {
            background-color: #27AE60 !important;
            border-color: #27AE60 !important;
        }

        .btn-primary:hover {
            background-color: #2ECC71 !important;
            border-color: #2ECC71 !important;
        }

        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        input[name="new_phone"] {
            font-family: monospace;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header.bg-primary {
            background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%);
            border-bottom: none;
        }

        .otp-input {
            font-size: 1.5rem !important;
            font-weight: 700;
            letter-spacing: 10px;
            padding: 1rem;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            border-color: #27AE60;
            box-shadow: 0 0 0 0.25rem rgba(39, 174, 96, 0.25);
        }

        .btn-primary {
            background-color: #27AE60 !important;
            border-color: #27AE60 !important;
            padding: 0.75rem;
        }

        .btn-primary:hover {
            background-color: #2ECC71 !important;
            border-color: #2ECC71 !important;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #27AE60;
            border-radius: 10px;
            transition: width 1s linear;
        }

        #resendBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
.profile-card {
    background: white;
    color: #2c3e50;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

        .profile-avatar {
            position: relative;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .avatar-placeholder {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .info-item {
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .info-label {
            font-weight: 500;
            margin-right: 8px;
            opacity: 0.9;
        }

        .info-value {
            font-weight: 600;
        }

        .role-badge {
            background: #27AE60;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .stats-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-icon i {
            font-size: 1.5rem;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .empty-card {
            background: white;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .empty-card:hover {
            border-color: #27AE60;
            background: #f8f9fa;
        }

        .empty-card .card-body {
            padding: 40px;
        }

        .empty-card i {
            color: #6c757d;
        }

        .empty-card:hover i {
            color: #27AE60;
        }

        .account-info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .account-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 20px;
        }

        .account-info-grid .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: left;
            transition: transform 0.2s ease;
        }

        .account-info-grid .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .account-info-grid .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .account-info-grid .stat-value {
            font-size: 1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .account-info-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(3, 1fr);
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .profile-image,
            .avatar-placeholder {
                width: 100px;
                height: 100px;
            }
        }
    </style>
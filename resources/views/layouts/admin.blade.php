<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            min-height: 100vh;
            padding: 20px 0;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        .sidebar-header h3 {
            margin: 0;
            font-size: 18px;
            color: #ecf0f1;
            font-weight: 600;
        }
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li {
            margin-bottom: 3px;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        .sidebar-nav a i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #3498db;
            color: white;
        }
        .sidebar-nav a.active {
            background: rgba(52, 152, 219, 0.2);
            border-left-color: #3498db;
            color: white;
            font-weight: 500;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 25px;
            margin-top: 20px;
        }
        .logo-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .page-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9 0%, #3498db 100%);
            transform: translateY(-1px);
        }
        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0;
        }
        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    @include('components.navbar')
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Muitas CRM')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d47a1 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .sidebar {
            background-color: white;
            min-height: calc(100vh - 70px);
            padding: 20px 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: var(--secondary-color);
            border-left-color: var(--primary-color);
        }

        .main-content {
            padding: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--secondary-color);
            border-bottom: 2px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0d47a1;
            border-color: #0d47a1;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--secondary-color);
            border-color: #e9ecef;
            font-weight: 600;
            color: #495057;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .form-control,
        .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
        }

        .stats-card {
            padding: 20px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d47a1 100%);
            color: white;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .stats-card p {
            margin: 0;
            opacity: 0.9;
        }

        footer {
            background-color: white;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-customs"></i> Muitas CRM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ auth()->user()->name ?? 'Lietotājs' }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-dashboard"></i> Panelis
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-car"></i> Transporta līdzekļi
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-briefcase"></i> Lietas
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-search"></i> Pārbaudes
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-file"></i> Dokumenti
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-users"></i> Piesaistītās puses
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="main-content">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Kļūda!</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
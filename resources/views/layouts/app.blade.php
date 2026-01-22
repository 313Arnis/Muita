<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Muitas CRM')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        .navbar {
            background: #15202b;
            padding: 0.3rem 1rem;
        }

        .navbar-brand {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 50px);
            border-right: 1px solid #dee2e6;
        }

        .sidebar .nav-link {
            padding: 8px 15px;
            font-size: 0.9rem;
            color: #555;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #eef4ff;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .main-content {
            padding: 15px;
        }

        .card {
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, .08);
        }

        .card-header {
            padding: 8px 15px;
            background: #fff;
            font-size: 0.95rem;
        }

        .alert {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand text-truncate" href="{{ route('dashboard') }}">
                <i class="fas fa-shield-halved me-1 text-primary"></i> M-CRM
            </a>
            <div class="ms-auto d-flex align-items-center">
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-white small" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->username }}
                            <span class="badge bg-primary ms-1"
                                style="font-size: 0.6rem;">{{ strtoupper(auth()->user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item small" href="#"><i class="fas fa-user me-2"></i>Profils</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item small text-danger fw-bold">
                                        <i class="fas fa-sign-out-alt me-2"></i>Izrakstīties
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 d-none d-md-block sidebar px-0">
                <div class="p-2 border-bottom mb-2 bg-light text-center">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Navigācija</small>
                </div>
                <nav class="nav flex-column">
                    @auth
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-2 w-20"></i>Sākums
                        </a>

                        {{-- INSPEKTORS --}}
                        @if(auth()->user()->role === 'inspector')
                            <a class="nav-link {{ request()->routeIs('inspector.assigned') ? 'active' : '' }}"
                                href="{{ route('inspector.assigned') }}">
                                <i class="fas fa-list-check me-2"></i>Piešķirtās pārbaudes
                            </a>
                            <a class="nav-link {{ request()->routeIs('inspector.decisions') ? 'active' : '' }}"
                                href="{{ route('inspector.decisions') }}">
                                <i class="fas fa-clipboard-check me-2"></i>Lēmumu pieņemšana
                            </a>
                            <a class="nav-link {{ request()->routeIs('inspections.*') ? 'active' : '' }}"
                                href="{{ route('inspections.index') }}">
                                <i class="fas fa-search me-2"></i>Visas pārbaudes
                            </a>
                        @endif

                        {{-- ANALĪTIĶIS --}}
                        @if(auth()->user()->role === 'analyst')
                            <a class="nav-link {{ request()->routeIs('analyst.dashboard') ? 'active' : '' }}"
                                href="{{ route('analyst.dashboard') }}">
                                <i class="fas fa-chart-line me-2"></i>Darba galds
                            </a>
                            <a class="nav-link {{ request()->routeIs('analyst.monitoring') ? 'active' : '' }}"
                                href="{{ route('analyst.monitoring') }}">
                                <i class="fas fa-eye me-2"></i>Monitoring
                            </a>
                            <a class="nav-link {{ request()->routeIs('analyst.risk-analysis') ? 'active' : '' }}"
                                href="{{ route('analyst.risk-analysis') }}">
                                <i class="fas fa-triangle-exclamation me-2"></i>Riska analīze
                            </a>
                        @endif

                        {{-- BROKERIS --}}
                        @if(auth()->user()->role === 'broker')
                            <a class="nav-link {{ request()->routeIs('broker.dashboard') ? 'active' : '' }}"
                                href="{{ route('broker.dashboard') }}">
                                <i class="fas fa-home me-2"></i>Darba galds
                            </a>
                            <a class="nav-link {{ request()->routeIs('broker.declarations') ? 'active' : '' }}"
                                href="{{ route('broker.declarations') }}">
                                <i class="fas fa-file-invoice me-2"></i>Manas deklarācijas
                            </a>
                            <a class="nav-link {{ request()->routeIs('broker.create-declaration') ? 'active' : '' }}"
                                href="{{ route('broker.create-declaration') }}">
                                <i class="fas fa-plus-circle me-2"></i>Jauna deklarācija
                            </a>
                        @endif

                        {{-- ADMINS --}}
                        @if(auth()->user()->role === 'admin')
                            <div class="p-2 mt-2 border-bottom mb-2 bg-light text-center">
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Sistēma</small>
                            </div>
                            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                                href="{{ route('admin.users') }}">
                                <i class="fas fa-users-gear me-2"></i>Lietotāju konti
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.configuration') ? 'active' : '' }}"
                                href="{{ route('admin.configuration') }}">
                                <i class="fas fa-sliders me-2"></i>Konfigurācija
                            </a>
                            <a class="nav-link" href="/log-viewer">
                                <i class="fas fa-terminal me-2"></i>Sistēmas žurnāls
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>

            <main class="col-md-10 main-content">
                {{-- Paziņojumu izvade --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                        <ul class="mb-0 px-3 small text-white">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
@extends('layouts.app')

@section('title', 'Inspector Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('inspector.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('inspector.inspections') }}">
                            <i class="fas fa-search me-2"></i>Inspections
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-gavel me-2"></i>Decisions
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Inspector Dashboard</h1>
                    <div>
                        <span class="badge bg-primary fs-6">{{ auth()->user()->full_name }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Inspections</h5>
                                <p class="card-text display-4">12</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Completed Today</h5>
                                <p class="card-text display-4">5</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Decisions Made</h5>
                                <p class="card-text display-4">8</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Recent Inspections</h5>
                    </div>
                    <div class="card-body">
                        <p>Inspection management interface will be implemented here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
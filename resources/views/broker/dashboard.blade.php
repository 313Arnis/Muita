@extends('layouts.app')

@section('title', 'Broker Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('broker.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('broker.documents') }}">
                            <i class="fas fa-file-alt me-2"></i>Documents
                        </a>
                        <a class="nav-link" href="{{ route('broker.declarations') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Declarations
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Broker Dashboard</h1>
                    <div>
                        <span class="badge bg-warning fs-6">{{ auth()->user()->full_name }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Documents</h5>
                                <p class="card-text display-4">7</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Submitted Today</h5>
                                <p class="card-text display-4">12</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Active Declarations</h5>
                                <p class="card-text display-4">45</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Document Management</h5>
                    </div>
                    <div class="card-body">
                        <p>Document submission and declaration management interface will be implemented here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
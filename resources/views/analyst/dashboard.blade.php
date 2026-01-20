@extends('layouts.app')

@section('title', 'Analyst Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('analyst.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('analyst.risk-analysis') }}">
                            <i class="fas fa-chart-line me-2"></i>Risk Analysis
                        </a>
                        <a class="nav-link" href="{{ route('analyst.monitoring') }}">
                            <i class="fas fa-eye me-2"></i>Monitoring
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Analyst Dashboard</h1>
                    <div>
                        <span class="badge bg-success fs-6">{{ auth()->user()->full_name }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Risk Assessments</h5>
                                <p class="card-text display-4">23</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">High Risk Cases</h5>
                                <p class="card-text display-4 text-danger">3</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Monitored Items</h5>
                                <p class="card-text display-4">156</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Risk Analysis Overview</h5>
                    </div>
                    <div class="card-body">
                        <p>Risk analysis and monitoring interface will be implemented here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
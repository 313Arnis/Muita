@extends('layouts.app')

@section('title', 'Brokera Panelis')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800">Sveicināti, Broker!</h2>
            <p class="text-muted small mb-0">Pārskats par jūsu deklarācijām un lietām.</p>
        </div>
        <a href="{{ route('broker.create-declaration') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Izveidot Deklarāciju
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-light p-3 rounded text-primary me-3">
                            <i class="fas fa-folder-open fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small text-uppercase fw-bold mb-1">Kopā Lietas</h6>
                            <h3 class="mb-0">{{ $stats['total_cases'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">
                <i class="fas fa-list me-2 text-primary"></i>Jaunākās Lietas
            </h5>
        </div>
        <div class="card-body">
            @if(isset($cases) && $cases->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Transportlīdzeklis</th>
                                <th class="border-0">Statuss</th>
                                <th class="border-0">Pievienots</th>
                                <th class="border-0 text-end">Darbība</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cases->take(5) as $case)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $case->external_id }}</td>
                                    <td>
                                        <i class="fas fa-truck text-muted me-2"></i>
                                        {{ $case->vehicle->plate_no ?? 'Nav norādīts' }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($case->status) {
                                                'screening' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ $badgeClass }} px-3">
                                            {{ ucfirst($case->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $case->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light border">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" style="width: 80px; opacity: 0.3;">
                    <p class="mt-3 text-muted">Nav atrasta neviena lieta.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endsection
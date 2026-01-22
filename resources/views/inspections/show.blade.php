@extends('layouts.app')

@section('title', 'Pārbaude: ' . $inspection->external_id)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-microscope text-primary me-2"></i>Pārbaude #{{ $inspection->external_id }}
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('inspections.index') }}">Pārbaudes</a></li>
                    <li class="breadcrumb-item active">{{ $inspection->external_id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($inspection->status === 'in_progress')
                <a href="{{ route('inspections.edit', $inspection->external_id) }}" class="btn btn-warning shadow-sm">
                    <i class="fas fa-clipboard-check me-1"></i> Pabeigt Pārbaudi
                </a>
            @endif
            <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Atpakaļ
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-muted small text-uppercase fw-bold">Pārbaudes saturs</h5>
                </div>
                <div class="card-body">
                    @if($inspection->checks && count($inspection->checks) > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-list-ul me-2"></i>Veiktās darbības</h6>
                            <div class="row">
                                @foreach($inspection->checks as $check)
                                    <div class="col-md-6 mb-2">
                                        <div class="p-2 border rounded bg-light d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>{{ $check }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold mb-3"><i class="fas fa-file-alt me-2"></i>Slēdziens / Rezultāti</h6>
                        @if($inspection->results)
                            <div class="p-4 rounded-3 border-start border-4 {{ $inspection->status === 'flagged' ? 'border-danger bg-danger-subtle' : 'border-success bg-light' }}">
                                <p class="mb-0 fs-5 lh-sm text-dark">{{ $inspection->results }}</p>
                            </div>
                        @else
                            <div class="alert alert-light border border-dashed text-center py-4">
                                <i class="fas fa-hourglass-half fa-2x text-muted mb-2 d-block"></i>
                                <span class="text-muted">Pārbaude vēl ir procesā, rezultāti nav pieejami.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($inspection->notes)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 text-muted small text-uppercase fw-bold">Iekšējās piezīmes</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary italic mb-0">{{ $inspection->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="p-3 text-center {{ $inspection->status === 'completed' ? 'bg-success' : ($inspection->status === 'flagged' ? 'bg-danger' : 'bg-warning') }} text-white fw-bold">
                    {{ strtoupper($inspection->status === 'in_progress' ? 'Procesā' : ($inspection->status === 'completed' ? 'Pabeigta' : 'Atrasti pārkāpumi')) }}
                </div>
                <div class="card-body p-0 text-center">
                    <div class="py-3 border-bottom">
                        <small class="text-muted d-block mb-1">Pārbaudes tips</small>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                            {{ ucfirst($inspection->type) }} pārbaude
                        </span>
                    </div>
                    <div class="p-3">
                        <small class="text-muted d-block mb-2">Pārbaudes laika līnija</small>
                        
                        <div class="timeline-small text-start">
                            <div class="timeline-item pb-2 border-start border-2 ms-2 ps-3 position-relative">
                                <span class="dot bg-secondary position-absolute start-0 translate-middle-x"></span>
                                <small class="text-muted d-block">Izveidots</small>
                                <strong>{{ $inspection->created_at->format('d.m.Y H:i') }}</strong>
                            </div>
                            @if($inspection->start_ts)
                            <div class="timeline-item pb-2 border-start border-2 ms-2 ps-3 position-relative">
                                <span class="dot bg-warning position-absolute start-0 translate-middle-x"></span>
                                <small class="text-muted d-block">Sākts</small>
                                <strong>{{ $inspection->start_ts->format('d.m.Y H:i') }}</strong>
                            </div>
                            @endif
                            @if($inspection->end_ts)
                            <div class="timeline-item ms-2 ps-3 position-relative">
                                <span class="dot bg-success position-absolute start-0 translate-middle-x"></span>
                                <small class="text-muted d-block">Noslēgts</small>
                                <strong>{{ $inspection->end_ts->format('d.m.Y H:i') }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Saistītā Lieta</h6>
                    @if($inspection->customsCase)
                        <div class="mb-3">
                            <small class="text-muted d-block">Lietas numurs</small>
                            <a href="{{ route('case.show', $inspection->customsCase->external_id) }}" class="fw-bold text-decoration-none">
                                {{ $inspection->customsCase->external_id }} <i class="fas fa-external-link-alt small ms-1"></i>
                            </a>
                        </div>
                        @if($inspection->customsCase->vehicle)
                        <div class="mb-0 pt-2 border-top">
                            <small class="text-muted d-block">Transportlīdzeklis</small>
                            <span class="d-block fw-bold text-dark">{{ $inspection->customsCase->vehicle->plate_no }}</span>
                            <small class="text-muted">{{ $inspection->customsCase->vehicle->make }} {{ $inspection->customsCase->vehicle->model }}</small>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-item .dot { width: 12px; height: 12px; border-radius: 50%; top: 5px; }
    .timeline-item { border-color: #dee2e6 !important; }
</style>
@endsection
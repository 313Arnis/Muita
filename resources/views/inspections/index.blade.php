@extends('layouts.app')

@section('title', 'Pārbaudes')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="fas fa-microscope me-2"></i>Pārbaužu Reģistrs
            </h5>
            <a href="{{ route('inspections.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Jauna Pārbaude
            </a>
        </div>
        
        <div class="card-body">
            <form method="GET" class="p-3 bg-light rounded-3 mb-4 border">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted">Meklēt</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" name="search" 
                                   value="{{ request('search') }}" placeholder="Auto numurs vai ID...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Tips</label>
                        <select class="form-select" name="type">
                            <option value="">Visi tipi</option>
                            <option value="dokumentu" {{ request('type') == 'dokumentu' ? 'selected' : '' }}>Dokumentu</option>
                            <option value="rtg" {{ request('type') == 'rtg' ? 'selected' : '' }}>RTG</option>
                            <option value="fiziskā" {{ request('type') == 'fiziskā' ? 'selected' : '' }}>Fiziskā</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-secondary px-4">Filtrēt</button>
                        @if(request()->anyFilled(['search', 'type']))
                            <a href="{{ route('inspections.index') }}" class="btn btn-link text-decoration-none text-muted">Notīrīt</a>
                        @endif
                    </div>
                </div>
            </form>

            @if($inspections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Pārbaudes ID</th>
                                <th>Lieta / Transportlīdzeklis</th>
                                <th>Tips</th>
                                <th>Statuss</th>
                                <th>Izveidots</th>
                                <th class="text-end px-4">Darbības</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inspections as $inspection)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $inspection->external_id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($inspection->customsCase)
                                                <small class="text-muted mb-1">Lieta: {{ $inspection->customsCase->external_id }}</small>
                                                @if($inspection->customsCase->vehicle)
                                                    <span class="fw-semibold">{{ $inspection->customsCase->vehicle->plate_no }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $typeClasses = [
                                                'dokumentu' => 'bg-info-subtle text-info border-info',
                                                'rtg' => 'bg-warning-subtle text-dark border-warning',
                                                'fiziskā' => 'bg-primary-subtle text-primary border-primary'
                                            ];
                                            $class = $typeClasses[$inspection->type] ?? 'bg-secondary-subtle text-muted';
                                        @endphp
                                        <span class="badge {{ $class }} border px-2 py-1">
                                            {{ ucfirst($inspection->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($inspection->status === 'in_progress')
                                            <span class="badge rounded-pill bg-warning text-dark">
                                                <i class="fas fa-spinner fa-spin me-1"></i> Procesā
                                            </span>
                                        @elseif($inspection->status === 'completed')
                                            <span class="badge rounded-pill bg-success">
                                                <i class="fas fa-check me-1"></i> Pabeigta
                                            </span>
                                        @elseif($inspection->status === 'flagged')
                                            <span class="badge rounded-pill bg-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Atzīmēta
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $inspection->created_at->format('d.m.Y') }}<br>
                                        {{ $inspection->created_at->format('H:i') }}
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('inspections.show', $inspection->external_id) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Skatīt">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($inspection->status === 'in_progress')
                                                <a href="{{ route('inspections.edit', $inspection->external_id) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Rediģēt">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $inspections->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-light-emphasis mb-3"></i>
                    <h5 class="text-muted">Nekas netika atrasts</h5>
                    <p class="text-muted small">Pamēģiniet mainīt meklēšanas nosacījumus vai izveidojiet jaunu ierakstu.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
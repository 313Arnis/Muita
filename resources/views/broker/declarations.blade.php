@extends('layouts.app')

@section('title', 'Deklarāciju Pārvaldība')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-contract text-primary me-2"></i>Deklarāciju Pārvaldība
            </h1>
            {{-- IZLABOTS: No create_declaration uz create-declaration --}}
            <a href="{{ route('broker.create-declaration') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Jauna Deklarācija
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if(isset($declarations) && $declarations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID / Ref</th>
                                    <th>Transportlīdzeklis</th>
                                    <th>Maršruts</th>
                                    <th>Statuss</th>
                                    <th>Prioritāte</th>
                                    <th>Izveidots</th>
                                    <th class="text-center">Dokumenti</th>
                                    <th class="text-end pe-4">Darbības</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($declarations as $dec)
                                    @php
                                        $statusStyle = match ($dec->status) {
                                            'screening'  => ['bg' => 'warning', 'txt' => 'Pārbaude'],
                                            'inspection' => ['bg' => 'info', 'txt' => 'Inspekcija'],
                                            'released'   => ['bg' => 'success', 'txt' => 'Atbrīvots'],
                                            'rejected'   => ['bg' => 'danger', 'txt' => 'Noraidīts'],
                                            default      => ['bg' => 'secondary', 'txt' => $dec->status]
                                        };
                                        $priorityStyle = match ($dec->priority) {
                                            'urgent' => 'danger',
                                            'high'   => 'warning',
                                            'low'    => 'info',
                                            default  => 'secondary'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('case.show', $dec->external_id) }}" class="fw-bold text-decoration-none">
                                                {{ $dec->external_id }}
                                            </a>
                                            @if($dec->external_ref)
                                                <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                    {{ $dec->external_ref }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($dec->vehicle)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light p-2 rounded me-2">
                                                        <i class="fas fa-truck text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold lh-1">{{ $dec->vehicle->plate_no }}</div>
                                                        <small class="text-muted">{{ $dec->vehicle->make }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge border text-dark fw-normal bg-white">
                                                {{ $dec->origin_country }} <i class="fas fa-long-arrow-alt-right mx-1 text-muted"></i>
                                                {{ $dec->destination_country }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $statusStyle['bg'] }} px-3">
                                                {{ $statusStyle['txt'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-circle text-{{ $priorityStyle }} small me-1"></i>
                                            <span class="small">{{ ucfirst($dec->priority) }}</span>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $dec->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-primary border border-primary-subtle">
                                                <i class="fas fa-file-pdf me-1"></i> {{ $dec->documents->count() }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group shadow-sm">
                                                <a href="{{ route('case.show', $dec->external_id) }}" class="btn btn-white btn-sm border" title="Skatīt">
                                                    <i class="fas fa-eye text-primary"></i>
                                                </a>
                                                <a href="{{ route('broker.documents', ['case' => $dec->external_id]) }}" class="btn btn-white btn-sm border" title="Augšupielādēt">
                                                    <i class="fas fa-upload text-success"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($declarations->hasPages())
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $declarations->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x text-light"></i>
                        </div>
                        <h5 class="text-muted">Netika atrasta neviena deklarācija</h5>
                        {{-- IZLABOTS: No create_declaration uz create-declaration --}}
                        <a href="{{ route('broker.create-declaration') }}" class="btn btn-primary mt-2">
                            Izveidot pirmo
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
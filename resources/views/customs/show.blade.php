@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lieta: {{ $case->external_ref }}</h1>
        <span class="badge bg-{{ $case->status == 'released' ? 'success' : 'warning' }} fs-5">
            {{ strtoupper($case->status) }}
        </span>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">Transportlīdzekļa informācija</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Numura zīme:</strong> {{ $case->vehicle->plate_no ?? 'Nav datu' }}</p>
                            <p><strong>Valsts:</strong> {{ $case->vehicle->country ?? '-' }}</p>
                            <p><strong>VIN:</strong> {{ $case->vehicle->vin ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Marka/Modelis:</strong> {{ $case->vehicle->make ?? '' }} {{ $case->vehicle->model ?? '' }}</p>
                            <p><strong>Ierašanās:</strong> {{ $case->arrival_ts }}</p>
                            <p><strong>Izcelsmes valsts:</strong> {{ $case->origin_country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">Veiktās pārbaudes</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tips</th>
                                <th>Vieta</th>
                                <th>Inspektors</th>
                                <th>Laiks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($case->inspections as $inspection)
                            <tr>
                                <td>{{ strtoupper($inspection->type) }}</td>
                                <td>{{ $inspection->location }}</td>
                                <td>{{ $inspection->assigned_to }}</td>
                                <td>{{ $inspection->start_ts->format('d.m.Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Nav veiktu pārbaužu</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">Iesaistītās puses</div>
                <div class="card-body">
                    <h6><strong>Deklarants:</strong></h6>
                    <p class="mb-3">{{ $case->declarant->name ?? 'Nav norādīts' }}<br>
                    <small class="text-muted">Reģ. nr: {{ $case->declarant->reg_code ?? '-' }}</small></p>
                    
                    <h6><strong>Saņēmējs:</strong></h6>
                    <p>{{ $case->consignee->name ?? 'Nav norādīts' }}<br>
                    <small class="text-muted">Reģ. nr: {{ $case->consignee->reg_code ?? '-' }}</small></p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Dokumenti</div>
                <ul class="list-group list-group-flush">
                    @forelse($case->documents as $doc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-muted d-block">{{ $doc->category }}</small>
                            {{ $doc->filename }}
                        </div>
                        <span class="badge bg-light text-dark border">{{ $doc->pages }} lpp.</span>
                    </li>
                    @empty
                    <li class="list-group-item">Nav pievienotu dokumentu</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Atpakaļ uz sarakstu</a>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="m-0 fw-bold">Piešķirtās pārbaudes ({{ $inspections->count() }})</h5>
    </div>

    @if($inspections->count())
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle m-0" style="font-size: 0.85rem;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">ID / Lieta</th>
                        <th>Tips & Vieta</th>
                        <th>Sākums</th>
                        <th>Lēmums</th>
                        <th class="text-end pe-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inspections as $insp)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary">{{ $insp->external_id }}</div>
                            <small class="text-muted">{{ $insp->customsCase?->external_id ?? $insp->case_id }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark border-0">{{ $insp->type }}</span>
                            <div class="small text-truncate" style="max-width: 150px;">{{ $insp->location }}</div>
                        </td>
                        <td class="text-nowrap">{{ $insp->start_ts?->format('d.m.y H:i') ?? '-' }}</td>
                        <td>
                            @if($insp->decision)
                                <span class="badge bg-success-subtle text-success">{{ $insp->decision }}</span>
                            @else
                                <span class="text-muted small italic">Nav</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('inspector.show', $insp->external_id) }}" class="btn btn-sm btn-primary py-0 px-3">Sākt</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-light border text-center">Nav aktuālu pārbaužu.</div>
    @endif
</div>
@endsection
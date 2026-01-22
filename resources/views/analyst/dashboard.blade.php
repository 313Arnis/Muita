@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Virsraksts un Lietotājs --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Analītiķa panelis</h1>
        <span class="badge bg-light text-dark border p-2">
            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->full_name }}
        </span>
    </div>

    {{-- Statistikas kartītes --}}
    <div class="row g-3 mb-4 text-center">
        @foreach([
            ['Novērtējumi', 23, 'primary'],
            ['Augsts risks', 3, 'danger'],
            ['Monitorings', 156, 'secondary']
        ] as [$label, $count, $color])
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body py-3">
                        <div class="small text-muted text-uppercase fw-bold">{{ $label }}</div>
                        <div class="h2 mb-0 text-{{ $color }}">{{ $count }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Darba zona --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">Riska analīzes pārskats</div>
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-graph-up display-1 opacity-25"></i>
            <p class="mt-3">Sistēma gatava analīzei. Izvēlieties darbību sānjoslā.</p>
        </div>
    </div>
</div>
@endsection
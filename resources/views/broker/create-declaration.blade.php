@extends('layouts.app')

@section('title', 'Izveidot Deklarāciju')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i>Jauna Deklarācija</h2>
        <a href="{{ route('broker.declarations') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Atpakaļ
        </a>
    </div>

    <form action="{{ route('broker.store-declaration') }}" method="POST" id="declarationForm">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3 text-muted small text-uppercase fw-bold">1. Transportlīdzeklis</h5>
                        <select class="form-select form-select-lg" id="vehicle_id" name="vehicle_id" required>
                            <option value="">Izvēlieties no saraksta...</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->external_id }}">{{ $v->plate_no }} ({{ $v->make }} {{ $v->model }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3 text-muted small text-uppercase fw-bold">2. Iesaistītās puses</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Deklarētājs</label>
                                <select class="form-select" id="declarant_id" name="declarant_id" required>
                                    <option value="">Izvēlieties...</option>
                                    @foreach($parties as $p)
                                        <option value="{{ $p->external_id }}">{{ $p->name ?? $p->external_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Saņēmējs</label>
                                <select class="form-select" id="consignee_id" name="consignee_id" required>
                                    <option value="">Izvēlieties...</option>
                                    @foreach($parties as $p)
                                        <option value="{{ $p->external_id }}">{{ $p->name ?? $p->external_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3 text-muted small text-uppercase fw-bold">3. Informācija par kravu</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small">Izcelšanās valsts</label>
                                <select class="form-select country-select" id="origin_country" name="origin_country" required>
                                    <option value="">Izvēlieties...</option>
                                    @foreach(['LV'=>'Latvija','EE'=>'Igaunija','LT'=>'Lietuva','PL'=>'Polija','DE'=>'Vācija'] as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Galamērķa valsts</label>
                                <select class="form-select country-select" id="destination_country" name="destination_country" required>
                                    <option value="">Izvēlieties...</option>
                                    @foreach(['LV'=>'Latvija','EE'=>'Igaunija','LT'=>'Lietuva','PL'=>'Polija','DE'=>'Vācija'] as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <label class="form-label small">Kravas apraksts / Piezīmes</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Ievadiet detaļas..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Pārskats</h5>
                        
                        <div id="live-summary">
                            <div class="d-flex mb-3">
                                <div class="me-3 text-primary"><i class="fas fa-truck fa-fw"></i></div>
                                <div><small class="text-muted d-block">Auto:</small><span id="sum-v" class="fw-bold">-</span></div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-primary"><i class="fas fa-map-marker-alt fa-fw"></i></div>
                                <div><small class="text-muted d-block">Maršruts:</small><span id="sum-r" class="fw-bold">-</span></div>
                            </div>
                            <hr>
                            <div class="mb-2">
                                <small class="text-muted d-block small uppercase">Deklarētājs:</small>
                                <span id="sum-d" class="small">-</span>
                            </div>
                            <div class="mb-4">
                                <small class="text-muted d-block small uppercase">Saņēmējs:</small>
                                <span id="sum-c" class="small">-</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-2">
                            <i class="fas fa-check-circle me-2"></i>APSTIPRINĀT
                        </button>
                        <button type="reset" class="btn btn-link btn-sm w-100 text-muted">Notīrīt formu</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = {
        'vehicle_id': 'sum-v',
        'declarant_id': 'sum-d',
        'consignee_id': 'sum-c'
    };

    // Atjaunot teksta laukus
    Object.keys(inputs).forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            document.getElementById(inputs[id]).innerText = this.options[this.selectedIndex].text;
        });
    });

    // Atjaunot maršrutu
    const routeInputs = ['origin_country', 'destination_country'];
    routeInputs.forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            const origin = document.getElementById('origin_country').options[document.getElementById('origin_country').selectedIndex].text;
            const dest = document.getElementById('destination_country').options[document.getElementById('destination_country').selectedIndex].text;
            
            if(document.getElementById('origin_country').value && document.getElementById('destination_country').value) {
                document.getElementById('sum-r').innerText = `${origin} → ${dest}`;
            }
        });
    });

    // Validācija ar Bootstrap stiliem
    document.getElementById('declarationForm').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
});
</script>

<style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    .form-select-lg { font-size: 1rem; }
    .sticky-top { z-index: 10; }
    .text-uppercase { letter-spacing: 0.5px; }
</style>
@endsection
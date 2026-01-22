@extends('layouts.app')

@section('title', 'Pabeigt Pārbaudi: ' . $inspection->external_id)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div id="cardHeader" class="card-header bg-white py-3 transition-all">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Pabeigt Pārbaudi: 
                        <span class="text-primary">{{ $inspection->external_id }}</span>
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                        <div class="col-sm-6">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Tips</small>
                            <span class="fw-semibold">
                                {{ ucfirst($inspection->type) }} pārbaude
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Vieta</small>
                            <span class="fw-semibold">{{ $inspection->location }}</span>
                        </div>
                        @if($inspection->customsCase && $inspection->customsCase->vehicle)
                        <div class="col-12 border-top pt-2">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Transportlīdzeklis</small>
                            <i class="fas fa-truck-moving me-1 text-secondary"></i>
                            <strong>{{ $inspection->customsCase->vehicle->plate_no }}</strong> 
                            <span class="text-muted">({{ $inspection->customsCase->vehicle->make }} {{ $inspection->customsCase->vehicle->model }})</span>
                        </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('inspections.update', $inspection->external_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Pārbaudes Rezultāts *</label>
                            <select class="form-select form-select-lg @error('status') is-invalid @enderror" 
                                    id="status" name="status" required onchange="updateUI(this.value)">
                                <option value="">Izvēlieties rezultātu...</option>
                                <option value="completed" class="text-success">✅ Pabeigta (bez atradumiem)</option>
                                <option value="flagged" class="text-danger">⚠️ Atzīmēta (atrasti pārkāpumi)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="results" class="form-label fw-bold">Detalizēti Rezultāti *</label>
                            <textarea class="form-control shadow-sm @error('results') is-invalid @enderror"
                                      id="results" name="results" rows="5"
                                      placeholder="Norādiet konstatētos faktus..." required>{{ old('results') }}</textarea>
                        </div>

                        @if($inspection->checks && count($inspection->checks) > 0)
                        <div class="mb-4 border-top pt-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Izpildītie kontrolpunkti</label>
                            <div class="list-group">
                                @foreach($inspection->checks as $index => $check)
                                <label class="list-group-item d-flex gap-3 py-3 shadow-sm mb-2 rounded border">
                                    <input class="form-check-input flex-shrink-0" type="checkbox" name="checks[]" 
                                           value="{{ $check }}" checked style="width: 1.5rem; height: 1.5rem;">
                                    <span class="pt-1 text-dark">{{ $check }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('inspections.show', $inspection->external_id) }}" class="btn btn-link text-muted px-0">
                                <i class="fas fa-times me-1"></i> Atcelt
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                <i class="fas fa-check-double me-2"></i>Noslēgt pārbaudi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .status-flagged { background-color: #fff5f5 !important; border-top: 5px solid #dc3545 !important; }
    .status-completed { background-color: #f8fff9 !important; border-top: 5px solid #198754 !important; }
</style>

<script>
    function updateUI(status) {
        const header = document.getElementById('cardHeader');
        header.classList.remove('status-flagged', 'status-completed');
        
        if (status === 'flagged') {
            header.classList.add('status-flagged');
        } else if (status === 'completed') {
            header.classList.add('status-completed');
        }
    }
</script>
@endsection
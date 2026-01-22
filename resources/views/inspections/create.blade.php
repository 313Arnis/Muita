@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-plus me-2 text-primary"></i>Jauna Pārbaude</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('inspections.store') }}">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lieta *</label>
                                <select name="case_id" class="form-select @error('case_id') is-invalid @enderror" required>
                                    <option value="">Izvēlieties...</option>
                                    @foreach($cases as $case)
                                        <option value="{{ $case->external_id }}" {{ (isset($selectedCase) && $selectedCase->external_id == $case->external_id) ? 'selected' : '' }}>
                                            {{ $case->external_id }} {{ $case->vehicle ? "({$case->vehicle->plate_no})" : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pārbaudes Tips *</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Izvēlieties...</option>
                                    @foreach(['dokumentu' => 'Dokumentu', 'rtg' => 'RTG (Rentgena)', 'fiziskā' => 'Fiziskā'] as $val => $label)
                                        <option value="{{ $val }}">{{ $label }} pārbaude</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Pārbaudes Vieta *</label>
                                <input type="text" name="location" class="form-control" placeholder="Ievadiet vietu..." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Piezīmes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Papildus informācija..."></textarea>
                            </div>
                        </div>

                        <div class="mb-4 pt-3 border-top">
                            <label class="form-label fw-bold text-muted uppercase small">Kontrolsaraksts</label>
                            <div id="checks-container">
                                <div class="input-group mb-2 shadow-sm">
                                    <input type="text" name="checks[]" class="form-control border-end-0" placeholder="Punkta nosaukums...">
                                    <button type="button" class="btn btn-white border border-start-0 text-danger" onclick="this.closest('.input-group').remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addCheck()">
                                <i class="fas fa-plus me-1"></i> Pievienot punktu
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('inspections.index') }}" class="text-decoration-none text-muted">Atcelt</a>
                            <button type="submit" class="btn btn-primary px-4 shadow">Izveidot Pārbaudi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addCheck() {
        const html = `
            <div class="input-group mb-2 shadow-sm animate__animated animate__fadeInIn">
                <input type="text" name="checks[]" class="form-control border-end-0" placeholder="Punkta nosaukums...">
                <button type="button" class="btn btn-white border border-start-0 text-danger" onclick="this.closest('.input-group').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
        document.getElementById('checks-container').insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection
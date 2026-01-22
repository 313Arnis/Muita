@extends('layouts.app')

@section('content')
<div class="container-fluid py-2" style="font-size: 0.85rem;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="m-0 fw-bold text-secondary">Sistēmas iestatījumi</h5>
        <span class="badge {{ ($settings['maintenance_mode'] ?? 0) ? 'bg-danger' : 'bg-success' }}">
            {{ ($settings['maintenance_mode'] ?? 0) ? 'APKOPES REŽĪMS' : 'SISTĒMA ONLINE' }}
        </span>
    </div>

    <div class="card border-0 shadow-sm">
        <form method="POST" action="{{ route('admin.update-configuration') }}" class="card-body p-3">
            @csrf
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="fw-bold mb-1">Nosaukums</label>
                    <input type="text" name="system_name" class="form-control form-control-sm" value="{{ $settings['system_name'] ?? 'Muitas CRM' }}" required>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold mb-1">Noklusējuma loma</label>
                    <select name="default_role" class="form-select form-select-sm">
                        @foreach(['analyst' => 'Analītiķis', 'inspector' => 'Inspektors', 'broker' => 'Brokeris', 'admin' => 'Admin'] as $v => $l)
                            <option value="{{ $v }}" {{ ($settings['default_role'] ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold mb-1">Riska slieksnis (%)</label>
                    <input type="number" name="risk_threshold" class="form-control form-control-sm" value="{{ $settings['risk_threshold'] ?? 50 }}" min="0" max="100">
                </div>

                <div class="col-12 py-2">
                    <div class="form-check form-switch bg-light border rounded p-2 ps-5">
                        <input type="checkbox" name="maintenance_mode" value="1" class="form-check-input" id="mm" {{ ($settings['maintenance_mode'] ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-danger" for="mm">Aktivizēt sistēmas apkopes režīmu (Viesiem piekļuve liegta)</label>
                    </div>
                </div>

                <div class="col-12 d-flex align-items-center justify-content-between border-top pt-2 mt-1">
                    <button class="btn btn-primary btn-sm px-4 fw-bold">SAGLABĀT</button>
                    <span class="text-muted" style="font-size: 0.7rem;">Sync: {{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
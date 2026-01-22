@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Iestatījumi</h3>
        <div class="badge {{ $settings['maintenance_mode'] ? 'bg-danger' : 'bg-success' }}">
            {{ $settings['maintenance_mode'] ? 'MAINTENANCE' : 'ONLINE' }}
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success p-2 small">{{ session('success') }}</div> @endif

    <div class="card shadow-sm">
        <form method="POST" action="{{ route('admin.update-configuration') }}" class="card-body">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="small fw-bold">Sistēmas nosaukums</label>
                    <input type="text" name="system_name" class="form-control form-control-sm" value="{{ $settings['system_name'] }}" required>
                </div>

                <div class="col-md-6">
                    <label class="small fw-bold">Noklusējuma loma</label>
                    <select name="default_role" class="form-select form-select-sm">
                        @foreach(['analyst' => 'Analītiķis', 'inspector' => 'Inspektors', 'broker' => 'Brokeris', 'admin' => 'Admin'] as $val => $label)
                            <option value="{{ $val }}" {{ $settings['default_role'] == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="small fw-bold">Riska slieksnis (%)</label>
                    <input type="number" name="risk_threshold" class="form-control form-control-sm" value="{{ $settings['risk_threshold'] }}" min="0" max="100">
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="maintenance_mode" value="1" class="form-check-input" id="mm" {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                        <label class="form-check-label small" for="mm">Apkopes režīms</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-primary btn-sm px-4">Saglabāt</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
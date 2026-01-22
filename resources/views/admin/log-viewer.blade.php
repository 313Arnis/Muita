@extends('layouts.app')

@section('title', 'Sistēmas Žurnāls')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history text-primary me-2"></i>Sistēmas Izmaiņu Vēsture
        </h1>
        <button onclick="window.location.reload()" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-sync-alt"></i> Atsvaidzināt
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Laiks</th>
                            <th>Lietotājs</th>
                            <th>Darbība</th>
                            <th>Apraksts</th>
                            <th>IP Adrese</th>
                            <th class="text-end pe-4">Dati</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 small">
                                {{ $log->created_at->format('d.m.Y H:i:s') }}
                            </td>
                            <td>
                                <span class="fw-bold">{{ $log->user_name }}</span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match(true) {
                                        str_contains($log->action, 'CREATE') => 'success',
                                        str_contains($log->action, 'DELETE') => 'danger',
                                        str_contains($log->action, 'UPDATE') => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} border border-{{ $badgeClass }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td class="text-muted small">{{ $log->ip_address }}</td>
                            <td class="text-end pe-4">
                                @if($log->payload)
                                    <button class="btn btn-sm btn-light border" 
                                            onclick="showPayload({{ $log->id }})" 
                                            title="Skatīt JSON datus">
                                        <i class="fas fa-code"></i>
                                    </button>
                                    <div id="payload-{{ $log->id }}" class="d-none">
                                        {{ json_encode($log->payload, JSON_PRETTY_PRINT) }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Žurnālā vēl nav ierakstu.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="payloadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Izmaiņu detaļas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="modal-json" class="bg-dark text-light p-3 rounded"></pre>
            </div>
        </div>
    </div>
</div>

<script>
function showPayload(id) {
    const rawJson = document.getElementById('payload-' + id).innerText;
    document.getElementById('modal-json').innerText = rawJson;
    new bootstrap.Modal(document.getElementById('payloadModal')).show();
}
</script>

<style>
    .table thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .badge { font-weight: 600; padding: 0.5em 0.8em; }
    pre { font-size: 0.85rem; max-height: 400px; overflow-y: auto; }
</style>
@endsection
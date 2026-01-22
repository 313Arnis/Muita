@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-primary"><i class="bi bi-eye-fill me-2"></i>Sistēmas Monitorings</h5>
                <button type="button" class="btn btn-primary btn-sm" onclick="updateMonitoring()">
                    <i class="bi bi-arrow-clockwise"></i> Atjaunināt
                </button>
            </div>
            <div class="row g-2">
                @foreach([
                    'monitorTimeRange' => ['Periods', ['1' => 'Pēdējā stunda', '24' => 'Pēdējās 24h', '168' => 'Nedēļa']],
                    'monitorType' => ['Tips', ['all' => 'Visi', 'cases' => 'Lietas', 'system' => 'Sistēma']],
                    'alertLevel' => ['Līmenis', ['all' => 'Visi', 'critical' => 'Kritiski', 'warning' => 'Brīdinājumi']]
                ] as $id => $data)
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">{{ $data[0] }}</label>
                    <select class="form-select form-select-sm" id="{{ $id }}">
                        @foreach($data[1] as $val => $label) <option value="{{ $val }}">{{ $label }}</option> @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4 text-center">
        @foreach([
            ['Aktīvās Sesijas', $monitoringData['active_sessions'], 'success', 'people'],
            ['Apstrādātās Lietas', $monitoringData['processed_cases'], 'primary', 'file-earmark-check'],
            ['Gaida Pārbaudi', $monitoringData['pending_inspections'], 'warning', 'hourglass-split'],
            ['Kritiskās Kļūdas', $monitoringData['critical_errors'], 'danger', 'exclamation-octagon']
        ] as [$title, $val, $color, $icon])
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-{{ $color }}">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <div class="small text-muted">{{ $title }}</div>
                            <div class="h4 mb-0 fw-bold text-{{ $color }}">{{ $val }}</div>
                        </div>
                        <i class="bi bi-{{ $icon }} fs-3 text-{{ $color }} opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        @foreach(['performanceChart' => 'Sistēmas Veiktspēja', 'userActivityChart' => 'Lietotāju Aktivitāte'] as $id => $title)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold small text-uppercase">{{ $title }}</div>
                <div class="card-body"><canvas id="{{ $id }}" height="180"></canvas></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold small text-uppercase text-muted">Aktivitātes Žurnāls</div>
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr><th class="ps-3">Laiks</th><th>Lietotājs</th><th>Darbība</th><th class="text-end pe-3">Statuss</th></tr>
                        </thead>
                        <tbody class="small">
                            @foreach($monitoringData['activity_log'] as $activity)
                            <tr>
                                <td class="ps-3 text-muted">{{ $activity['time'] }}</td>
                                <td class="fw-bold">{{ $activity['user'] }}</td>
                                <td>{{ $activity['action'] }} <span class="text-muted">({{ $activity['object'] }})</span></td>
                                <td class="text-end pe-3">
                                    <span class="badge bg-{{ ['success'=>'success','warning'=>'warning','error'=>'danger','info'=>'info'][$activity['status']] ?? 'secondary' }} font-monospace">
                                        {{ $activity['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold small text-uppercase text-danger">Kritiskie Brīdinājumi</div>
                <div class="card-body p-2" id="systemAlerts">
                    @foreach($monitoringData['system_alerts'] as $alert)
                    <div class="alert alert-{{ ['critical'=>'danger','warning'=>'warning','info'=>'info'][$alert['level']] ?? 'secondary' }} py-2 px-3 mb-2 small d-flex justify-content-between align-items-center">
                        <div><strong>{{ $alert['message'] }}</strong><br><span class="opacity-75">{{ $alert['time'] }}</span></div>
                        <button type="button" class="btn-close small" style="font-size: 0.6rem" onclick="this.closest('.alert').remove()"></button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const perfData = @json($monitoringData['performance']);
        const userData = @json($monitoringData['user_activity']);

        // Veiktspējas grafiks
        new Chart(document.getElementById('performanceChart'), {
            type: 'line',
            data: {
                labels: perfData.labels,
                datasets: [
                    { label: 'CPU', data: perfData.cpu_usage, borderColor: '#dc3545', tension: 0.3, fill: true, backgroundColor: 'rgba(220, 53, 69, 0.05)' },
                    { label: 'RAM', data: perfData.memory_usage, borderColor: '#0dcaf0', tension: 0.3 }
                ]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { max: 100 } } }
        });

        // Lietotāju grafiks
        new Chart(document.getElementById('userActivityChart'), {
            type: 'bar',
            data: {
                labels: userData.map(i => i.role),
                datasets: [{ data: userData.map(i => i.count), backgroundColor: '#0d6efd' }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    });
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Riska Analīze</h4>

    <div class="row g-2 mb-4">
        <div class="col-md-4">
            <select class="form-select" id="riskLevel" onchange="filterTable()">
                <option value="all">Visi riska līmeņi</option>
                <option value="high">Augsts</option>
                <option value="medium">Vidējs</option>
                <option value="low">Zems</option>
            </select>
        </div>
        <div class="col-md-4 text-end">
            <div class="p-2 bg-light border rounded">
                Kopā: <strong>{{ $riskData['statistics']['total'] }}</strong>
            </div>
        </div>
    </div>

    <table class="table table-sm border">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Auto</th>
                <th>Valsts</th>
                <th>Risks</th>
                <th>Darbības</th>
            </tr>
        </thead>
        <tbody id="riskTable">
            @foreach($riskData['cases'] as $case)
            <tr data-level="{{ $case->risk_info->level }}">
                <td>{{ $case->external_id }}</td>
                <td>{{ $case->vehicle->plate_no ?? 'N/A' }}</td>
                <td>{{ $case->origin_country }}</td>
                <td>
                    <span class="badge bg-{{ $case->risk_info->class }}">
                        {{ $case->risk_info->label }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="showDetails('{{ $case->external_id }}')">Skatīt</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 border p-3">
        <canvas id="riskTrendChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Vienkārša filtrēšana
    function filterTable() {
        const level = document.getElementById('riskLevel').value;
        document.querySelectorAll('#riskTable tr').forEach(tr => {
            tr.style.display = (level === 'all' || tr.dataset.level === level) ? '' : 'none';
        });
    }

    // Vienkāršs grafiks
    const trend = @json($riskData['trend']);
    new Chart(document.getElementById('riskTrendChart'), {
        type: 'line',
        data: {
            labels: trend.map(d => d.date),
            datasets: [{ label: 'Augsts risks', data: trend.map(d => d.high), borderColor: 'red' }]
        }
    });

    function showDetails(id) {
        alert('Rāda detaļas ID: ' + id);
    }
</script>
@endsection
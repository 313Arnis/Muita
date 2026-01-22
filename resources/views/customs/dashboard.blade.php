@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Muitas Pārbaudes Punktu CRM</h1>

    {{-- Aizstājam $isAuthenticated ar Laravel iebūvēto pārbaudi --}}
    @auth
        <form action="{{ route('dashboard') }}" method="GET" class="mb-4 d-flex gap-2">
            <input type="text" name="search" placeholder="Auto numurs..." class="form-control" value="{{ request('search') }}">
            <select name="status" class="form-select">
                <option value="">Visi statusi</option>
                <option value="new">Jauns</option>
                <option value="screening">Pārbaude</option>
                <option value="released">Atbrīvots</option>
            </select>
            <button type="submit" class="btn btn-primary">Meklēt</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Auto Numurs</th>
                    <th>Statuss</th>
                    <th>Riska punkti</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cases as $case)
                <tr>
                    <td>{{ $case->id }}</td>
                    <td>{{ $case->vehicle->plate_no ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $case->status == 'released' ? 'success' : 'warning' }}">
                            {{ strtoupper($case->status) }}
                        </span>
                    </td>
                    <td><strong>{{ $case->risk_score }}</strong></td>
                    <td>
                        <a href="{{ route('case.show', $case->external_id) }}" class="btn btn-sm btn-info text-white">
                            Atvērt lietu
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $cases->links() }}

    @else
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-info-circle"></i> Publiskā informācija</h4>
                    <p>Sveicināti Muitas Pārbaudes Punktu CRM sistēmā! Šeit varat apskatīt vispārējo statistiku par mūsu darbu.</p>
                    <hr>
                    <p class="mb-0">Lai piekļūtu detalizētai informācijai un lietu pārvaldībai, lūdzu <a href="{{ route('login') }}" class="alert-link">pierakstieties</a> sistēmā.</p>
                </div>
            </div>
        </div>

        {{-- Statistikas bloks publiskajiem lietotājiem --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-car fa-2x text-primary mb-2"></i>
                        <h5 class="card-title">{{ number_format($totals['vehicles'] ?? 0) }}</h5>
                        <p class="card-text">Transporta līdzekļi</p>
                    </div>
                </div>
            </div>
            {{-- ... pārējās kartītes līdzīgi ... --}}
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Piekļuve sistēmai</h5>
                        <p class="card-text">Lai skatītu detalizētu informāciju par lietām un veiktu pārbaudes, jums jābūt reģistrētam lietotājam.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">
                            <i class="fas fa-sign-in-alt"></i> Pierakstīties
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus"></i> Reģistrēties
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>
@endsection
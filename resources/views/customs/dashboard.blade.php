@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Muitas Pārbaudes Punktu CRM</h1>

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
</div>
@endsection
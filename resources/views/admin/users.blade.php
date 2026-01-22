@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Lietotāju pārvaldība</h4>
        <div class="text-muted small">Kopā: {{ $users->count() }}</div>
    </div>

  

    <div class="card shadow-sm border-0 mb-4">
        <form method="POST" action="{{ route('admin.create-user') }}" class="card-body bg-light">
            @csrf
            <div class="row g-2">
                <div class="col-md-3"><input type="text" name="username" class="form-control form-control-sm" placeholder="Lietotājvārds" required></div>
                <div class="col-md-3"><input type="text" name="full_name" class="form-control form-control-sm" placeholder="Vārds Uzvārds" required></div>
                <div class="col-md-2">
                    <select name="role" class="form-select form-select-sm">
                        @foreach(['analyst' => 'Analītiķis', 'inspector' => 'Inspektors', 'broker' => 'Brokeris', 'admin' => 'Admin'] as $v => $l)
                            <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><input type="password" name="password" class="form-control form-control-sm" placeholder="Parole" required></div>
                <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">Pievienot</button></div>
            </div>
        </form>
    </div>

    <div class="table-responsive bg-white shadow-sm rounded">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Lietotājvārds / Vārds</th>
                    <th>Loma</th>
                    <th class="text-center">Aktīvs</th>
                    <th class="text-end pe-3">Darbības</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold">{{ $u->username }}</div>
                        <div class="small text-muted">{{ $u->full_name }}</div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.update-user', $u) }}">
                            @csrf @method('PUT')
                            <select name="role" onchange="this.form.submit()" class="form-select form-select-sm py-0 w-auto">
                                @foreach(['analyst' => 'Analītiķis', 'inspector' => 'Inspektors', 'broker' => 'Brokeris', 'admin' => 'Admin'] as $v => $l)
                                    <option value="{{ $v }}" {{ $u->role == $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="active" value="{{ $u->active ? 1 : 0 }}">
                        </form>
                    </td>
                    <td class="text-center">
                        <form method="POST" action="{{ route('admin.update-user', $u) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="role" value="{{ $u->role }}">
                            <div class="form-check form-switch d-inline-block">
                                <input type="checkbox" name="active" value="1" class="form-check-input" {{ $u->active ? 'checked' : '' }} onchange="this.form.submit()">
                            </div>
                        </form>
                    </td>
                    <td class="text-end pe-3">
                      
                        @if($u->id !== \Illuminate\Support\Facades\Auth::id())
                            <form method="POST" action="{{ route('admin.delete-user', $u) }}" onsubmit="return confirm('Dzēst?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i> Dzēst</button>
                            </form>
                        @else
                            <span class="badge bg-light text-dark border">Tu</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
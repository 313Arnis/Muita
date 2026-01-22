@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body py-2">
                    <small>Gaida pārbaudi</small>
                    <h2 class="mb-0 fw-bold">12</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body py-2">
                    <small>Pabeigtas šodien</small>
                    <h2 class="mb-0 fw-bold">5</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body py-2">
                    <small>Lēmumi pieņemti</small>
                    <h2 class="mb-0 fw-bold">8</h2>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Aktuālie uzdevumi</h6>
                    <a href="{{ route('inspections.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none">Visi &raquo;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Auto</th>
                                <th>Statuss</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">4921</td>
                                <td>LV-1234</td>
                                <td><span class="badge bg-warning text-dark">Procesā</span></td>
                                <td class="text-end py-2">
                                    <a href="#" class="btn btn-sm btn-primary">Atvērt</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
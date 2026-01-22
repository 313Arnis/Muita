@extends('layouts.app')

@section('content')
<div class="container-fluid py-2" style="font-size: 0.9rem;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="m-0 fw-bold">Pārbaude: {{ $inspection->external_id }}</h5>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary py-0 px-2">Atpakaļ</a>
    </div>

    <div class="row g-2">
        <div class="col-md-5">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-3">
                    <div class="mb-2">
                        <span class="text-muted small">Lieta:</span>
                        @if($inspection->customsCase)
                            <a href="{{ route('case.show', $inspection->customsCase->external_id) }}" class="fw-bold text-decoration-none ms-1">{{ $inspection->customsCase->external_id }}</a>
                        @else
                            <span class="fw-bold ms-1">{{ $inspection->case_id }}</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small text-nowrap">Tips & Sākums:</span>
                        <span class="badge bg-light text-dark border ms-1">{{ $inspection->type }}</span>
                        <span class="ms-1 fw-bold">{{ $inspection->start_ts?->format('d.m.y H:i') ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small">Vieta:</span>
                        <span class="ms-1">{{ $inspection->location ?? '-' }}</span>
                    </div>

                    <div class="p-2 bg-light rounded border">
                        <h6 class="small fw-bold border-bottom pb-1 mb-2 text-uppercase">Pārbaudes punkti</h6>
                        <ul class="list-unstyled mb-0 small">
                            @if(is_array($inspection->checks))
                                @foreach($inspection->checks as $k => $v)
                                    <li class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">{{ $k }}:</span>
                                        <span class="fw-bold">{{ $v }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li>{{ $inspection->checks }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card h-100 shadow-sm border-primary">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3">Pieņemt lēmumu</h6>
                    <form action="{{ route('inspector.make-decision') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inspection_id" value="{{ $inspection->external_id }}">
                        
                        <div class="row g-2">
                            <div class="col-md-5 mb-2">
                                <label class="small fw-bold mb-1">Lēmums</label>
                                <select name="decision" class="form-select form-select-sm" required>
                                    <option value="">Izvēlēties...</option>
                                    <option value="accept" class="text-success fw-bold">APSTIPRINĀT (Accept)</option>
                                    <option value="reject" class="text-danger fw-bold">NORAIDĪT (Reject)</option>
                                    <option value="hold" class="text-warning fw-bold">AIZTURĒT (Hold)</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="small fw-bold mb-1">Piezīmes</label>
                                <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Ievadiet slēdzienu..."></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn-sm w-100 fw-bold shadow-sm" type="submit">SAGLABĀT LĒMUMU</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
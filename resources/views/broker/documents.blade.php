@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Dokumenti</h4>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('broker.submit-document') }}" method="POST" enctype="multipart/form-data" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <select name="case_id" class="form-select" required>
                        <option value="">Izvēlēties lietu...</option>
                        @foreach($cases as $case)
                            <option value="{{ $case->external_id }}">{{ $case->external_id }} ({{ $case->vehicle->plate_no ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="document_type" class="form-select" required>
                        <option value="invoice">Rēķins</option>
                        <option value="packing_list">Pavadzīme</option>
                        <option value="other">Cits</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="file" name="document_file" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Pievienot</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm text-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Tips</th>
                        <th class="border-0">Lieta</th>
                        <th class="border-0">Fails</th>
                        <th class="border-0">Iesniegts</th>
                        <th class="border-0 text-end px-4">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td><span class="badge bg-light text-dark border fw-normal">{{ $doc->category }}</span></td>
                        <td><a href="{{ route('case.show', $doc->case_id) }}" class="fw-bold">{{ $doc->case_id }}</a></td>
                        <td class="text-muted">{{ Str::limit($doc->filename, 25) }}</td>
                        <td class="small">{{ $doc->created_at->format('d.m.y H:i') }}</td>
                        <td class="text-end px-4">
                            <a href="{{ asset('storage/documents/' . $doc->filename) }}" target="_blank" class="btn btn-sm btn-light border" title="Skatīt"><i class="fas fa-eye text-primary"></i></a>
                            <a href="{{ asset('storage/documents/' . $doc->filename) }}" download class="btn btn-sm btn-light border" title="Lejuplādēt"><i class="fas fa-download text-success"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted small">Saraksts ir tukšs</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
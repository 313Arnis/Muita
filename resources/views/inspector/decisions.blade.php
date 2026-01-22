@extends('layouts.app')

@section('title', 'Inspector - Pending Decisions')

@section('content')
    <div class="container">
        <h3>Pending Decisions</h3>

        @if($inspections->count())
            <div class="list-group">
                @foreach($inspections as $insp)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $insp->external_id }} - {{ $insp->type }}</h5>
                            <small>{{ $insp->start_ts?->format('d.m.Y H:i') ?? '-' }}</small>
                        </div>
                        <p class="mb-1">Case: @if($insp->customsCase)<a
                        href="{{ route('case.show', $insp->customsCase->external_id) }}">{{ $insp->customsCase->external_id }}</a>@else{{ $insp->case_id }}@endif
                        </p>
                        <a href="{{ route('inspector.show', $insp->external_id) }}" class="btn btn-sm btn-outline-primary">Make
                            decision</a>
                    </div>
                @endforeach
            </div>
        @else
            <p>No pending inspections.</p>
        @endif
    </div>
@endsection
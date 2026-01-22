@extends('layouts.app')

@section('title', 'Lēmumu Pieņemšana')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-gavel me-2"></i>Lēmumu Pieņemšana
            </h5>
        </div>
        <div class="card-body">
            {{-- Piezīme: Filtrēšanu labāk veikt Kontrolierī: $cases = Case::has('inspections')->paginate(15); --}}
            @forelse($cases as $case)
                @if($loop->first)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Lietas Nr.</th>
                                <th>Transportlīdzeklis</th>
                                <th>Pēdējā Pārbaude</th>
                                <th>Statuss</th>
                                <th class="text-end">Darbības</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                
                <tr>
                    <td class="fw-bold">
                        <a href="{{ route('case.show', $case->external_id) }}" class="text-decoration-none">
                            {{ $case->external_id }}
                        </a>
                    </td>
                    <td>
                        @if($case->vehicle)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck text-muted me-2"></i>
                                <div>
                                    <span class="d-block fw-semibold text-uppercase">{{ $case->vehicle->plate_no }}</span>
                                    <small class="text-muted">{{ $case->vehicle->make }} {{ $case->vehicle->model }}</small>
                                </div>
                            </div>
                        @else
                            <span class="text-muted small">Nav norādīts</span>
                        @endif
                    </td>
                    <td>
                        <span class="small">
                            {{ $case->inspections->first()?->created_at->format('d.m.Y H:i') ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @php $status = $case->inspections->first()?->status; @endphp
                        @switch($status)
                            @case('completed') <span class="badge rounded-pill bg-success-subtle text-success border border-success">Pabeigta</span> @break
                            @case('flagged')   <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning">Atzīmēta</span> @break
                            @default           <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary">{{ $status }}</span>
                        @endswitch
                    </td>
                    <td class="text-end">
                        <div class="btn-group shadow-sm">
                            <button class="btn btn-sm btn-success" onclick="makeDecision('{{ $case->external_id }}', 'release')" title="Atbrīvot">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="makeDecision('{{ $case->external_id }}', 'hold')" title="Aizturēt">
                                <i class="fas fa-pause"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="makeDecision('{{ $case->external_id }}', 'reject')" title="Noraidīt">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                @if($loop->last)
                        </tbody>
                    </table>
                </div>
                @endif
            @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-check fa-3x text-light-emphasis"></i>
                    </div>
                    <h5 class="text-muted">Nav lietu, kurām jāpieņem lēmumi</h5>
                    <p class="small text-muted">Visas lietas ir apstrādātas vai nav pabeigtu pārbaužu.</p>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $cases->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modālis paliek līdzīgs, bet ar vizuāliem uzlabojumiem --}}
@include('partials.decision_modal') {{-- Ieteicams iznest atsevišķā failā --}}

@endsection
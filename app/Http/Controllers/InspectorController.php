<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Inspection;
use App\Models\CustomsCase;

class InspectorController extends Controller
{
    public function __construct()
    {
        // Tikai inspektori un admini
        $this->middleware(['auth', 'role:inspector,admin']);
    }

    /**
     * Inspektora darba galds - rāda tikai viņam piešķirtās pārbaudes
     */
    public function index()
    {
        $u = Auth::user();

        // Atlasām pārbaudes, kas piešķirtas šim inspektoram (izmantojot ID vai username)
        $inspections = Inspection::where('assigned_to', $u->id) 
            ->orWhere('assigned_to', $u->username)
            ->with('customsCase')
            ->orderBy('start_ts', 'desc')
            ->get();

        return view('inspector.index', [
            'inspections' => $inspections,
            'active_count' => $inspections->whereNull('decision')->count()
        ]);
    }

    public function show($external_id)
    {
        $inspection = Inspection::with(['customsCase.vehicle', 'customsCase.documents'])
            ->where('external_id', $external_id)
            ->firstOrFail();

        return view('inspector.show', compact('inspection'));
    }

    /**
     * Saraksts ar visām neapstrādātajām pārbaudēm
     */
    public function decisions()
    {
        $items = Inspection::whereNull('decision')
            ->with(['customsCase', 'inspector'])
            ->latest('start_ts')
            ->get();

        return view('inspector.decisions', ['inspections' => $items]);
    }

    /**
     * Galīgā lēmuma pieņemšana par pārbaudes rezultātu
     */
    public function makeDecision(Request $request)
    {
        $v = $request->validate([
            'inspection_id' => 'required|exists:inspections,external_id',
            'decision'      => 'required|in:accept,reject,hold',
            'notes'         => 'nullable|string|max:1000',
        ]);

        try {
            $insp = Inspection::where('external_id', $v['inspection_id'])->firstOrFail();
            $user = Auth::user();

            // 1. Atjaunojam pārbaudes datus
            $insp->update([
                'decision'       => $v['decision'],
                'decision_by'    => $user->username,
                'decision_notes' => $v['notes'],
                'end_ts'         => now(),
                'status'         => 'completed'
            ]);

            // 2. Sinhroni atjaunojam CustomsCase statusu
            if ($insp->customsCase) {
                $case = $insp->customsCase;
                
                // Kartējam inspektora lēmumu uz lietas globālo statusu
                $case->status = match($v['decision']) {
                    'accept' => 'released',
                    'reject' => 'rejected',
                    'hold'   => 'on_hold',
                };
                
                $case->save();
            }

            Log::info("Lēmums pieņemts: {$user->username} -> {$v['decision']} priekš {$insp->external_id}");

            return back()->with('success', 'Lēmums sekmīgi reģistrēts un lietas statuss atjaunots.');

        } catch (\Exception $e) {
            Log::error("Kļūda makeDecision: " . $e->getMessage());
            return back()->with('error', 'Sistēmas kļūda: Neizdevās saglabāt lēmumu.');
        }
    }
}
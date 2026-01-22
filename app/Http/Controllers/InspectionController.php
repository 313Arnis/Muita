<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\CustomsCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    public function __construct()
    {
        // Tikai inspektori un admini var pārvaldīt pārbaudes
        $this->middleware(['auth', 'role:inspector,admin']);
    }

    public function index(Request $request)
    {
        $q = Inspection::with(['customsCase', 'inspector']);

        // Meklēšana pēc lietas numura vai atsauces
        if ($request->filled('search')) {
            $s = $request->search;
            $q->whereHas('customsCase', function($query) use ($s) {
                $query->where('external_ref', 'like', "%$s%")
                      ->orWhere('external_id', 'like', "%$s%");
            });
        }

        $inspections = $q->latest()->paginate(15);
        return view('inspections.index', compact('inspections'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'case_id'  => 'required|exists:customs_cases,external_id',
            'type'     => 'required|in:dokumentu,rtg,fiziskā',
            'location' => 'required|string|max:255',
            'notes'    => 'nullable|string',
            'checks'   => 'nullable|array',
        ]);

        try {
            // Sākam pārbaudi
            $inspection = Inspection::create([
                'external_id' => 'INSP-' . strtoupper(bin2hex(random_bytes(4))),
                'case_id'     => $v['case_id'],
                'type'        => $v['type'],
                'location'    => $v['location'],
                'notes'       => $v['notes'] ?? null,
                'checks'      => $v['checks'] ?? [], // Eloquent cast parūpēsies par JSON
                'assigned_to' => Auth::id(),
                'start_ts'    => now(),
                'status'      => 'in_progress'
            ]);

            // Nomainām pašas lietas statusu uz 'inspection'
            CustomsCase::where('external_id', $v['case_id'])->update(['status' => 'inspection']);

            Log::info("Inspektors " . Auth::user()->username . " uzsāka pārbaudi {$inspection->external_id}");

            return redirect()->route('inspections.show', $inspection->external_id)
                             ->with('success', 'Pārbaude veiksmīgi uzsākta.');

        } catch (\Exception $e) {
            Log::error('Kļūda izveidojot pārbaudi: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Neizdevās izveidot pārbaudi.');
        }
    }

    public function makeDecision(Request $request)
    {
        $v = $request->validate([
            'inspection_id' => 'required|exists:inspections,external_id',
            'decision'      => 'required|in:release,hold,reject',
            'notes'         => 'nullable|string'
        ]);

        $inspection = Inspection::where('external_id', $v['inspection_id'])->firstOrFail();
        $case = CustomsCase::where('external_id', $inspection->case_id)->firstOrFail();

        // 1. Atjaunojam lietas statusu
        $statusMap = [
            'release' => 'released',
            'hold'    => 'on_hold',
            'reject'  => 'rejected'
        ];

        $case->update([
            'status'           => $statusMap[$v['decision']],
            'decision_notes'   => $v['notes'],
            'decision_made_at' => now(),
            'decision_made_by' => Auth::id()
        ]);

        // 2. Noslēdzam pārbaudes ierakstu
        $inspection->update([
            'status'   => 'completed',
            'decision' => $v['decision'],
            'end_ts'   => now()
        ]);

        Log::info("Inspektors " . Auth::user()->username . " pieņēma lēmumu ({$v['decision']}) lietā {$case->external_id}");

        return response()->json(['success' => true, 'message' => 'Lēmums pieņemts un process noslēgts!']);
    }
}
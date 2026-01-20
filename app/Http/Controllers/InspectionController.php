<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\CustomsCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InspectionController extends Controller
{
    /**
     * Parāda visu pārbaužu sarakstu.
     */
    public function index(Request $request): View
    {
        $query = Inspection::with(['customsCase.vehicle']);

        // Meklēšana pēc transportlīdzekļa numura
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('customsCase.vehicle', function ($q) use ($searchTerm) {
                $q->where('plate_no', 'like', '%' . $searchTerm . '%');
            });
        }

        $inspections = $query->latest()->paginate(15);

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Parāda jaunas pārbaudes izveides formu.
     */
    public function create(Request $request): View
    {
        // Ja nāk no konkrētas lietas skata, piesaistām to uzreiz
        $selectedCase = null;
        if ($request->has('case_id')) {
            $selectedCase = CustomsCase::where('external_id', $request->case_id)->first();
        }

        $cases = CustomsCase::where('status', '!=', 'released')->get();
        
        return view('inspections.create', compact('cases', 'selectedCase'));
    }

    /**
     * Saglabā jauno pārbaudi datubāzē.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'case_id'      => 'required|exists:customs_cases,external_id',
            'type'         => 'required|in:fiziska,dokumentu,skenēšana',
            'location'     => 'required|string|max:255',
            'notes'        => 'nullable|string',
            'checks'       => 'nullable|array', // Masīvs ar kontrolsarakstu
        ]);

        try {
            $inspection = Inspection::create([
                'external_id'  => 'INSP-' . strtoupper(uniqid()),
                'case_id'      => $validated['case_id'],
                'type'         => $validated['type'],
                'location'     => $validated['location'],
                'notes'        => $validated['notes'],
                'checks'       => $validated['checks'] ?? [],
                'assigned_to'  => Auth::id(),
                'start_ts'     => now(),
                'status'       => 'in_progress'
            ]);

            Log::info('Jauna pārbaude izveidota', ['id' => $inspection->external_id, 'by' => Auth::user()->username]);

            return redirect()->route('inspections.show', $inspection->external_id)
                             ->with('success', 'Pārbaude veiksmīgi uzsākta.');
        } catch (\Exception $e) {
            Log::error('Kļūda veidojot pārbaudi: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sistēmas kļūda. Mēģiniet vēlreiz.');
        }
    }

    /**
     * Parāda konkrētas pārbaudes detaļas.
     */
    public function show(string $external_id): View
    {
        $inspection = Inspection::with(['customsCase.vehicle', 'inspector'])
            ->where('external_id', $external_id)
            ->firstOrFail();

        return view('inspections.show', compact('inspection'));
    }

    /**
     * Parāda formas rediģēšanas skatu (piemēram, rezultātu ievadei).
     */
    public function edit(string $external_id): View
    {
        $inspection = Inspection::where('external_id', $external_id)->firstOrFail();
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Atjaunina pārbaudes datus (pabeigšana).
     */
    public function update(Request $request, string $external_id): RedirectResponse
    {
        $inspection = Inspection::where('external_id', $external_id)->firstOrFail();

        $validated = $request->validate([
            'status'  => 'required|in:completed,flagged',
            'results' => 'required|string',
            'checks'  => 'nullable|array'
        ]);

        $inspection->update([
            'status'   => $validated['status'],
            'results'  => $validated['results'],
            'checks'   => $validated['checks'] ?? $inspection->checks,
            'end_ts'   => now(),
        ]);

        Log::info('Pārbaude pabeigta', ['id' => $inspection->external_id]);

        return redirect()->route('inspections.index')->with('success', 'Pārbaudes rezultāti saglabāti.');
    }

    /**
     * Dzēš pārbaudi (tikai adminiem).
     */
    public function destroy(string $external_id): RedirectResponse
    {
        $inspection = Inspection::where('external_id', $external_id)->firstOrFail();
        $inspection->delete();

        return redirect()->route('inspections.index')->with('success', 'Pārbaude dzēsta.');
    }
}
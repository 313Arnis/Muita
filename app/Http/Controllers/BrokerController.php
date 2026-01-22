<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomsCase;
use App\Models\Document;
use App\Models\Vehicle;
use App\Models\Party;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BrokerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:broker']);
    }

    /**
     * Iegūst autorizētā brokera identifikatorus
     */
    private function getBrokerIds() {
        $u = Auth::user();
        return array_filter([$u->id, $u->external_id]);
    }

    /**
     * Brokera galvenais panelis (Dashboard)
     */
    public function index()
    {
        $ids = $this->getBrokerIds();
        
        $cases = CustomsCase::where(function($q) use ($ids) {
                $q->whereIn('declarant_id', $ids)
                  ->orWhereIn('consignee_id', $ids);
            })
            ->with(['vehicle', 'documents'])
            ->latest()
            ->get();

        // IZLABOTS: Atslēgas saskaņotas ar Blade skatu
        $stats = [
            'total_cases'  => $cases->count(), // Šī atslēga novērš "Undefined array key"
            'active_cases' => $cases->whereIn('status', ['screening', 'inspected'])->count(),
            'done_cases'   => $cases->where('status', 'released')->count(),
            'docs_pending' => $cases->sum(fn($c) => $c->documents->where('category', 'pending')->count())
        ];

        return view('broker.dashboard', compact('cases', 'stats'));
    }

    /**
     * Deklarāciju saraksts ar pagināciju
     */
    public function declarations()
    {
        $ids = $this->getBrokerIds();
        
        $declarations = CustomsCase::where(function($q) use ($ids) {
                $q->whereIn('declarant_id', $ids)
                  ->orWhereIn('consignee_id', $ids);
            })
            ->with(['vehicle', 'declarant', 'consignee'])
            ->latest()
            ->paginate(15);

        return view('broker.declarations', compact('declarations'));
    }

    /**
     * Jaunas deklarācijas izveides forma
     */
    public function createDeclaration()
    {
        return view('broker.create-declaration', [
            'vehicles' => Vehicle::where('active', true)->get(),
            'parties'  => Party::all()
        ]);
    }

    /**
     * Deklarācijas saglabāšana
     */
    public function storeDeclaration(Request $request)
    {
        $req = $request->validate([
            'vehicle_id'          => 'required|exists:vehicles,external_id',
            'declarant_id'        => 'required|exists:parties,external_id',
            'consignee_id'        => 'required|exists:parties,external_id',
            'origin_country'      => 'required|string|size:2',
            'destination_country' => 'required|string|size:2',
        ]);

        $newCase = CustomsCase::create([
            'external_id'         => 'CASE-' . strtoupper(Str::random(10)),
            'external_ref'        => 'BR-' . date('Ymd') . '-' . rand(1000, 9999),
            'status'              => 'screening',
            'priority'            => 'normal',
            'arrival_ts'          => now(),
            'origin_country'      => strtoupper($req['origin_country']),
            'destination_country' => strtoupper($req['destination_country']),
            'risk_flags'          => [], 
            'declarant_id'        => $req['declarant_id'],
            'consignee_id'        => $req['consignee_id'],
            'vehicle_id'          => $req['vehicle_id']
        ]);

        return redirect()->route('broker.declarations')->with('success', 'Deklarācija iesniegta sistēmā!');
    }

    /**
     * Dokumenta augšupielāde esošai lietai
     */
    public function submitDocument(Request $request)
    {
        $request->validate([
            'case_id'       => 'required|exists:cases,external_id', // Pārbaudi vai tabula ir 'cases' vai 'customs_cases'
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $ids = $this->getBrokerIds();
        $case = CustomsCase::where('external_id', $request->case_id)
            ->where(function ($q) use ($ids) {
                $q->whereIn('declarant_id', $ids)->orWhereIn('consignee_id', $ids);
            })->firstOrFail();

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $name = time() . '_' . Str::slug($file->getClientOriginalName());
            $path = $file->storeAs('documents/' . $case->external_id, $name, 'public');

            Document::create([
                'external_id' => 'DOC-' . strtoupper(Str::random(8)),
                'case_id'     => $case->id, 
                'case_ext_id' => $case->external_id,
                'filename'    => $path,
                'mime_type'   => $file->getMimeType(),
                'category'    => $request->document_type,
                'uploaded_by' => Auth::user()->username
            ]);

            return back()->with('success', 'Dokuments veiksmīgi pievienots.');
        }

        return back()->with('error', 'Fails netika saņemts.');
    }
}
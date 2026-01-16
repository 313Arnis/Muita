<?php

namespace App\Http\Controllers\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\CustomsCase;
use App\Models\Inspection;
use App\Models\Party;

class DashboardController extends Controller
{

    public function index(Request $request)
    {

        $totals = [
            'vehicles'    => Vehicle::count(),
            'cases'       => CustomsCase::count(),
            'inspections' => Inspection::count(),
            'parties'     => Party::count(),
        ];


        $query = CustomsCase::with(['vehicle']);


        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('vehicle', function ($q) use ($searchTerm) {
                $q->where('plate_no', 'like', '%' . $searchTerm . '%');
            });
        }


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        $cases = $query->latest()->paginate(15);


        return view('customs.dashboard', compact('cases', 'totals'));
    }


    public function show($external_id)
    {

        $case = CustomsCase::with(['vehicle', 'declarant', 'consignee', 'inspections', 'documents'])
            ->where('external_id', $external_id)
            ->firstOrFail();

        return view('customs.show', compact('case'));
    }
}
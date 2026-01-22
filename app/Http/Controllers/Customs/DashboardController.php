<?php

namespace App\Http\Controllers\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route; 
use App\Models\Vehicle;
use App\Models\CustomsCase;
use App\Models\Inspection;
use App\Models\Party;

class DashboardController extends Controller
{
   
    public function index(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;

            
            $routes = [
                'admin'     => 'admin.dashboard',
                'inspector' => 'inspector.dashboard',
                'analyst'   => 'analyst.dashboard',
                'broker'    => 'broker.dashboard',
            ];

            $goto = $routes[$role] ?? 'dashboard';

      
            if (Route::currentRouteName() !== $goto && Route::has($goto)) {
                Log::info("Redirect uz lomas ({$role}) paneli: {$goto}");
                return redirect()->route($goto);
            }
            
           
        }

        $stats = $this->getStats();
       
        $cases = Auth::check() 
            ? CustomsCase::with('vehicle')->latest()->paginate(10) 
            : collect();

        return view('customs.dashboard', [
            'cases' => $cases,
            'stats' => $stats,
            'isAuth' => Auth::check()
        ]);
    }

 
    private function getStats()
    {
        return [
            'vehicles'    => Vehicle::exists() ? Vehicle::count() : 0,
            'cases'       => CustomsCase::exists() ? CustomsCase::count() : 0,
            'inspections' => Inspection::exists() ? Inspection::count() : 0,
            'parties'     => Party::exists() ? Party::count() : 0,
        ];
    }

   
    public function show($external_id)
    {
        
        $case = CustomsCase::with(['vehicle', 'declarant', 'consignee', 'inspections', 'documents'])
            ->where('external_id', $external_id)
            ->firstOrFail();

        return view('customs.show', compact('case'));
    }
}
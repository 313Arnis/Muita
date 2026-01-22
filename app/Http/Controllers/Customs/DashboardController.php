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
    /**
     * Galvenais ieejas punkts, kas sadala lietotājus pa paneļiem
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;

            // Definējam mērķa maršrutus atbilstoši lomām
            $routes = [
                'admin'     => 'admin.dashboard',
                'inspector' => 'inspector.dashboard',
                'analyst'   => 'analyst.dashboard',
                'broker'    => 'broker.dashboard',
            ];

            $goto = $routes[$role] ?? 'dashboard';

            // Pārbaudām vai mēs jau neesam tajā maršrutā un vai tāds eksistē
            if (Route::currentRouteName() !== $goto && Route::has($goto)) {
                Log::info("Redirect uz lomas ({$role}) paneli: {$goto}");
                return redirect()->route($goto);
            }
            
            // Ja maršruts neeksistē (piemēram, analyst.dashboard vēl nav web.php), 
            // turpinām izpildi, lai nerādītu kļūdu, bet ielādētu noklusējuma skatu.
        }

        // Ja lietotājs paliek šeit (nav specifiska redirect), rādām kopējo statistiku
        $stats = $this->getStats();
        
        // Paņemam pēdējās lietas tikai ielogotiem, citādi tukšu kolekciju
        $cases = Auth::check() 
            ? CustomsCase::with('vehicle')->latest()->paginate(10) 
            : collect();

        return view('customs.dashboard', [
            'cases' => $cases,
            'stats' => $stats,
            'isAuth' => Auth::check()
        ]);
    }

    /**
     * Statistika galvenajam panelim
     */
    private function getStats()
    {
        return [
            'vehicles'    => Vehicle::exists() ? Vehicle::count() : 0,
            'cases'       => CustomsCase::exists() ? CustomsCase::count() : 0,
            'inspections' => Inspection::exists() ? Inspection::count() : 0,
            'parties'     => Party::exists() ? Party::count() : 0,
        ];
    }

    /**
     * Konkrētas muitas lietas apskate pēc external_id (piem. USR-XXXX)
     */
    public function show($external_id)
    {
        // Pievienojam datu ielādi, lai skatā nav lieku SQL pieprasījumu
        $case = CustomsCase::with(['vehicle', 'declarant', 'consignee', 'inspections', 'documents'])
            ->where('external_id', $external_id)
            ->firstOrFail();

        return view('customs.show', compact('case'));
    }
}
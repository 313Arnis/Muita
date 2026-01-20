<?php

namespace App\Http\Controllers\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route; // SALABOTS: Importēta Route fasāde
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

            $redirectRoute = match ($user->role) {
                'inspector' => 'inspector.dashboard',
                'analyst'   => 'analyst.dashboard',
                'broker'    => 'broker.dashboard',
                'admin'     => 'admin.dashboard',
                default     => 'dashboard'
            };

            // SVARĪGI: Pārbaudām, vai mēs JAU neesam mērķa maršrutā, lai nebūtu bezgalīgs cikls
            if (Route::currentRouteName() !== $redirectRoute) {
                if (Route::has($redirectRoute)) {
                    Log::info('Redirecting to role dashboard', ['route' => $redirectRoute]);
                    return redirect()->route($redirectRoute);
                }
                Log::error('Route defined in match does not exist in web.php', ['route' => $redirectRoute]);
            }
        }

        // Ja esam šeit, rādām dashboard saturu (vai nu viesim, vai pēc sekmīga redirect)
        $totals = $this->getTotals();

        // Autorizētiem lietotājiem rādām pēdējās lietas, viesiem - tukšu
        $cases = Auth::check()
            ? CustomsCase::with(['vehicle'])->latest()->take(10)->get()
            : collect();

        return view('customs.dashboard', [
            'cases' => $cases,
            'totals' => $totals,
            'isAuthenticated' => Auth::check()
        ]);
    }

    private function getTotals()
    {
        return [
            'vehicles'    => Vehicle::count(),
            'cases'       => CustomsCase::count(),
            'inspections' => Inspection::count(),
            'parties'     => Party::count(),
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
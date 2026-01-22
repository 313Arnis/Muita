<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Paņemam maintenance statusu no settingiem
        // Ja modeļa nav vai kļūda, pieņemam, ka viss strādā (false)
        $isDown = false;
        if (class_exists(Setting::class)) {
            $isDown = Setting::get('maintenance_mode', '0') === '1';
        }

        if ($isDown) {
            // Pārbaudām, vai ielogojies lietotājs ir admins
            $user = Auth::user();

            // Ja nav ielogojies, metam uz 503
            if (!Auth::check()) {
                return response()->view('maintenance', [], 503);
            }

            // Ja ir ielogojies, bet nav adminis - metam ārā un dzēšam sesiju
            if ($user && $user->role !== 'admin') {
                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Sistēmā šobrīd notiek apkope.');
            }
        }

        return $next($request);
    }
}
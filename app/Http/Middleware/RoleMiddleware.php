<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ja nav ielogojies, sūtām uz login lapu
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Dabūjam lietotāju caur Auth fasādi (drošāk nekā $request->user())
        $user = Auth::user();

        // Pārbaudām, vai lietotāja loma ir atļauto sarakstā
        if ($user && !in_array($user->role, $roles)) {
            
            // Ielogojam kļūdu, lai administrators redz, ka kāds mēģina "līst kur nevajag"
            Log::warning("Access denied for user {$user->username}. Required roles: " . implode(', ', $roles));

            // Metam 403 kļūdu ar saprotamu tekstu
            abort(403, 'Piekļuve liegta. Šī sadaļa nav paredzēta tavam lietotāja līmenim.');
        }

        return $next($request);
    }
}
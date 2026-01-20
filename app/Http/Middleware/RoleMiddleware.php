<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importēta Auth fasāde
use Illuminate\Support\Facades\Log;  // SALABOTS: Importēta Log fasāde
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Iegūstam lietotāju caur $request objektu
        $user = $request->user();

        // Pārbaudām lomu
        if ($user && !in_array($user->role, $roles)) {
            abort(403, 'Unauthorized. Tev nav nepieciešamās lomas: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}

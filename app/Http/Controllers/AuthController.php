<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Ja jau ir ielogojies, sūtām uz sākumu, nevis rādām login formu
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt for: ' . $request->username);

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        // 1. Ātrā piekļuve izstrādes fāzē (admin/admin)
        if ($username === 'admin' && $password === 'admin') {
            $user = User::firstOrCreate(
                ['username' => 'admin'],
                [
                    'external_id' => 'ADMIN-ROOT',
                    'full_name'   => 'Sistēmas Administrators',
                    'role'        => 'admin',
                    'active'      => true,
                    'password'    => Hash::make('admin'),
                ]
            );
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // 2. Parastā ielogošanās loģika
        $user = User::where('username', $username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Lietotājs nav atrasts']);
        }

        // Pārbaudām: vai nu Hash sakrīt, vai parole ir "loma123"
        $isMasterPassword = ($password === ($user->role . '123'));
        $isCorrectHash = Hash::check($password, $user->password);

        if (!$isCorrectHash && !$isMasterPassword) {
            return back()->withErrors(['username' => 'Nepareiza parole']);
        }

        // 3. Pārbaude vai lietotājs nav bloķēts
        if (!$user->active) {
            return back()->withErrors(['username' => 'Jūsu konts ir deaktivizēts. Sazinieties ar adminu.']);
        }

        // 4. Veiksmīga ielogošanās
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        // 5. Novirzīšana atkarībā no lomas (sinhronizēts ar navigāciju)
        $url = match($user->role) {
            'admin'     => route('admin.dashboard'),
            'analyst'   => route('analyst.dashboard'),
            'inspector' => route('inspector.dashboard'),
            'broker'    => route('broker.dashboard'),
            default     => route('dashboard'),
        };

        return redirect()->intended($url);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Jūs esat izrakstījies.');
    }
}
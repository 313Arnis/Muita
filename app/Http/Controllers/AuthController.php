<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info('Login attempt', ['username' => $credentials['username']]);

        $user = User::where('username', $credentials['username'])->first();

        $inputPassword = $credentials['password'];

        // Accept either the stored hashed password OR the convention role+'123'
        if ($user && (Hash::check($inputPassword, $user->password) || $inputPassword === ($user->role . '123'))) {
            // 1. Autorizācija
            Auth::login($user, $request->boolean('remember'));

            // Sesijas reģenerācija ir svarīga drošībai (Session Fixation aizsardzība)
            $request->session()->regenerate();

            Log::info('Login successful', [
                'user' => $user->username,
                'role' => $user->role,
                'session_id' => $request->session()->getId()
            ]);

            // 2. Maršruta noteikšana
            $redirectRoute = match ($user->role) {
                'inspector' => 'inspector.dashboard',
                'analyst' => 'analyst.dashboard',
                'broker' => 'broker.dashboard',
                'admin' => 'admin.dashboard',
                default => 'dashboard'
            };

            // 3. Faktiskais redirect
            return redirect()->route($redirectRoute);
        }

        Log::warning('Login failed - invalid credentials', ['username' => $credentials['username']]);

        return back()->withErrors([
            'username' => 'Piekļuves dati nav pareizi.',
        ])->onlyInput('username');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:system_users',
            'full_name' => 'required|string|max:255',
            // keep password optional; we'll enforce default password = role+123
            'password' => 'nullable|string|min:0',
        ]);

        $role = $request->input('role', 'analyst');
        $defaultPassword = $role . '123';

        $user = User::create([
            'external_id' => uniqid('user_'),
            'username' => $request->username,
            'full_name' => $request->full_name,
            'role' => $role,
            'active' => true,
            'password' => Hash::make($defaultPassword),
        ]);

        Auth::login($user);

        $redirectRoute = match ($user->role) {
            'inspector' => 'inspector.dashboard',
            'analyst' => 'analyst.dashboard',
            'broker' => 'broker.dashboard',
            'admin' => 'admin.dashboard',
            default => 'dashboard'
        };

        return redirect()->route($redirectRoute);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
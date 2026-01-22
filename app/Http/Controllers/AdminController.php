<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'lastUsers' => User::latest()->take(5)->get()
        ]);
    }

    public function users()
    {
      
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function configuration()
    {
        $config = [
            'system_name'      => Setting::get('system_name', 'Muitas CRM'),
            'default_role'     => Setting::get('default_role', 'analyst'),
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
            'risk_threshold'   => Setting::get('risk_threshold', '50'),
        ];

        return view('admin.configuration', ['settings' => $config]);
    }

    public function updateConfiguration(Request $request)
    {
        $input = $request->validate([
            'system_name'      => 'required|string|max:255',
            'default_role'     => 'required|in:analyst,inspector,broker,admin',
            'maintenance_mode' => 'nullable|in:1,0',
            'risk_threshold'   => 'required|integer|min:0|max:100',
        ]);

        Setting::set('system_name', $input['system_name']);
        Setting::set('default_role', $input['default_role']);
        Setting::set('risk_threshold', $input['risk_threshold']);
        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');

        return back()->with('success', 'Iestatījumi veiksmīgi atjaunināti!');
    }

    public function createUser(Request $request)
    {
        $v = $request->validate([
            'username'  => 'required|string|unique:system_users,username|max:255',
            'full_name' => 'required|string|max:255',
            'role'      => 'required|in:analyst,inspector,broker,admin',
        ]);

        $rolePassword = $v['role'] . '123';

        User::create([
            'external_id' => 'USR-' . strtoupper(bin2hex(random_bytes(4))),
            'username'    => $v['username'],
            'full_name'   => $v['full_name'],
            'role'        => $v['role'],
            'active'      => true,
            'password'    => Hash::make($rolePassword),
        ]);

        return back()->with('success', "Lietotājs izveidots! Parole ir: {$rolePassword}");
    }

    public function updateUser(Request $request, $id)
    {
        $u = User::findOrFail($id);

        $request->validate([
            'role'   => 'required|in:analyst,inspector,broker,admin',
            'active' => 'required|boolean',
        ]);

       
        if ($u->role !== $request->role) {
            $u->password = Hash::make($request->role . '123');
        }

        $u->role = $request->role;
        $u->active = $request->active;
        $u->save();

        return back()->with('success', 'Lietotāja profils atjaunināts.');
    }

    public function deleteUser($id)
    {
        $targetUser = User::findOrFail($id);
        

        if (Auth::id() == $targetUser->id) {
            return back()->with('error', 'Jūs nevarat dzēst savu kontu!');
        }

        $targetUser->delete();
        return back()->with('success', 'Lietotājs dzēsts.');
    }
    public function logViewer()
{
 
    $logs = \App\Models\ActivityLog::latest()->paginate(50);
    
    return view('admin.log-viewer', compact('logs'));
}
}
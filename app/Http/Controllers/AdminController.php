<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        // Logic to manage users
        return view('admin.users');
    }

    public function configuration()
    {
        // Logic for system configuration
        return view('admin.configuration');
    }

    public function createUser(Request $request)
    {
        // Logic to create user
        return redirect()->back()->with('success', 'User created successfully');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // return view('admin/login');
        return response(view('admin.login'))
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Add status check (optional but recommended)
        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'status'   => '1'
        ])) {
            $request->session()->regenerate();
            return redirect('/admin/admindashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid username or password.'
        ]);


    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

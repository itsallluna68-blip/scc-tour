<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserHistory;

class AuthController extends Controller
{
    public function showLogin()
    {
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

        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'status'   => 'active'
        ])) {
            $request->session()->regenerate();
            UserHistory::create([
                'user_type' => Auth::user()->usertype ?? 'admin',
                'username' => Auth::user()->username,
                'full_name' => Auth::user()->fname . ' ' . Auth::user()->lname,
                'date_time' => now(),
                'action_taken' => 'Logged in'
            ]);

            return redirect('/admin/admindashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid username or password.'
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            UserHistory::create([
                'user_type' => Auth::user()->usertype ?? 'admin',
                'username' => Auth::user()->username,
                'full_name' => Auth::user()->fname . ' ' . Auth::user()->lname,
                'date_time' => now(),
                'action_taken' => 'Logged out'
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
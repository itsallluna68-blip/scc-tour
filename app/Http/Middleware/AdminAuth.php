<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // If user is NOT logged in, redirect to login
        if (!Auth::check()) {
            // Only redirect for admin routes
            if ($request->is('admin/*')) {
                return redirect()->route('login');
            }
        }

        // If user is logged in and staff, restrict access
        if (Auth::check() && Auth::user()->usertype === 'Staff') {
            // allow only monthly visits routes for staff
            $allowed = [
                'monthly-visits*',
                'monthlyvisits*',
            ];
            $path = $request->path();
            $allowedAccess = false;
            foreach ($allowed as $pattern) {
                if ($request->is($pattern)) {
                    $allowedAccess = true;
                    break;
                }
            }
            if (!$allowedAccess) {
                // redirect staff to monthly visits page or abort
                return redirect()->route('monthlyvisits.index');
            }
        }

        $response = $next($request);

        // Prevent caching to avoid back button showing expired pages
        return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            // Redirect based on user's actual role
            if ($user->hasRole('client')) {
                return redirect('/client/dashboard')->with('error', 'Access denied. Admin access required.');
            }
            if ($user->hasRole('agent')) {
                return redirect('/agent/dashboard')->with('error', 'Access denied. Admin access required.');
            }
            
            // Fallback for users without proper roles
            return redirect('/')->with('error', 'Access denied. Please contact administrator.');
        }

        return $next($request);
    }
}
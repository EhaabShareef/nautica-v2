<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
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
        if (!$user->hasRole('client')) {
            // Redirect based on user's actual role
            if ($user->hasRole('admin')) {
                return redirect('/admin/dashboard')->with('error', 'Access denied. Client access required.');
            }
            if ($user->hasRole('agent')) {
                return redirect('/agent/dashboard')->with('error', 'Access denied. Client access required.');
            }
            
            // Fallback for users without proper roles
            return redirect('/')->with('error', 'Access denied. Please contact administrator.');
        }

        return $next($request);
    }
}
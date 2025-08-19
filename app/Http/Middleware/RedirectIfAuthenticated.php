<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect based on user role
                if ($user->hasRole('admin')) {
                    return redirect('/admin/dashboard');
                }
                if ($user->hasRole('agent')) {
                    return redirect('/agent/dashboard');
                }
                if ($user->hasRole('client')) {
                    return redirect('/client/dashboard');
                }
                
                // Fallback redirect
                return redirect('/');
            }
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::user();
        // dd($user);
        if ($user && $user->roleId == $role) {
            return $next($request);
        }
        Auth::logout();

        // return redirect()->route('accounts.login')->with('error', 'Unauthorized access');
        return redirect()->route('loginOption')->with('error', 'Unauthorized access');
    }
}

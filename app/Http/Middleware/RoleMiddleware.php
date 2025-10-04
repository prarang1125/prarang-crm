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

        $roles = explode('|',$role);
        
       
        if (is_array($roles)) {
            if (in_array($user->roleId, $roles)) {
                return $next($request);
            }
        }
        
        Auth::logout();
        return redirect()->route('loginOption')->with('error', 'Unauthorized access');
    }
}

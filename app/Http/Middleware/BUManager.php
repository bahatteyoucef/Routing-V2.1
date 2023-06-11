<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BUManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $guard
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Check if the user has the "Admin" role
        if ((Auth::guard('api')->user()->hasRole('Administrateur')) || (Auth::guard('api')->user()->hasRole('RTM Manager')) || (Auth::guard('api')->user()->hasRole('BU Manager'))) {
            return $next($request);
        }
    
        // If the user does not have the "Admin" role, return an error response
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}

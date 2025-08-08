<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontOffice {

    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Check if the user has the "Admin" role
        if ((Auth::guard('api')->user()->hasRole('FrontOffice')) || (Auth::guard('api')->user()->hasRole('Super Admin')) || (Auth::guard('api')->user()->hasRole('BU Manager')) || (Auth::guard('api')->user()->hasRole('BackOffice'))) {
            return $next($request);
        }
    
        // If the user does not have the "Admin" role, return an error response
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}

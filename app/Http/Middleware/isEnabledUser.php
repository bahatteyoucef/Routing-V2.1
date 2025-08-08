<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isEnabledUser {

    public function handle(Request $request, Closure $next) {

        $user = Auth::guard('api')->user();

        // If not logged in, let auth:api handle that (401).
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // If logged in but not enabled, revoke tokens & return 403.
        if ($user->status !== 'enabled') {
            return response()->json([
                'message' => 'User disabled'
            ], 403);
        }

        return $next($request);
    }
}

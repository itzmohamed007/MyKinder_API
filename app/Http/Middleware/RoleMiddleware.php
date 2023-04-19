<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if ($user && $user->role === $role) {
            return $next($request);
        }
        
        return new JsonResponse(['message' => 'You do not have permission to access this resource.'], 403);
    }
}

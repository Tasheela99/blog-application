<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->user_role !== "ADMIN") {
            return new JsonResponse([
                'error' => 'Unauthorized action.',
                'message' => 'You do not have the required permissions to access this resource.'
            ], 401);
        }
        return $next($request);
    }
}

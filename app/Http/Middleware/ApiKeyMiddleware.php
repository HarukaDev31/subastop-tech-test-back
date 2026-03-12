<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $apiKey = $request->header('X-API-KEY');

        if (empty($apiKey) || $apiKey !== config('app.api_key')) {
            return response()->json([
                'success' => false,
                'message' => 'API key inválida o ausente.',
            ], 401);
        }

        return $next($request);
    }
}

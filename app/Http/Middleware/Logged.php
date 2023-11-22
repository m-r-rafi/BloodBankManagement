<?php

namespace App\Http\Middleware;

use App\Http\Services\AuthService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Logged
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['message' => 'No token supplied'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        if (!$this->authService->isTokenValid($token)) {
            return response()->json(['message' => 'Token is invalid or expired'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}

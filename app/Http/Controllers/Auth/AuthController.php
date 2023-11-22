<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }
    public function logIn(Request $request): JsonResponse
    {
        try {
            $logIn = $request->all();
            $res = $this->authService->authenticate($logIn['Email'], $logIn['Password']);

            if ($res !== null) {
                return response()->json($res, ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logOut(Request $request): JsonResponse
    {
        $token = $request->header('Authorization');

        try {
            $res = $this->authService->logOut($token);

            if ($res) {
                return response()->json(['message' => 'Logged out successfully'], ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'Token not found or already expired'], ResponseAlias::HTTP_NOT_FOUND);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

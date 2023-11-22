<?php

namespace App\Http\Controllers;

use App\Http\DTOs\EligibleUpdateModelDTO;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\User\IUserRepository;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class UserController extends Controller
{
    public function __construct(private  readonly IUserRepository $userRepository)
    {
    }
    public function allUsers(): JsonResponse
    {
        try {
            $data = $this->userRepository->list();
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
    public function createUser(UserCreateRequest $request): JsonResponse
    {
        try {
            $userData = $request->validated();
            $user = $this->userRepository->create($userData);

            return response()->json(['user' => $user, ], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
    public function updateUser(UserUpdateRequest $request, $id): JsonResponse
    {
        try {
            $userData = $request->validated();
            $res = $this->userRepository->update($id, $userData);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getUserById($id): JsonResponse
    {
        try {
            $data = $this->userRepository->find($id);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
    public function deleteUser($id): JsonResponse
    {
        try {
            $data = $this->userRepository->delete($id);
            if (!$data) {
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['message' => 'Record deleted successfully'], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
    public function isEligible($id): JsonResponse
    {
        try {
            $data = $this->userRepository->isEligible($id);
            if (!$data) {
                return response()->json(['message' => 'You are not eligible'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
    public function isEligibleUpdate(Request $request)
    {
        try{
            $userId = $request->input('userId');
            $date = $request->input('date');
            $model = new EligibleUpdateModelDTO($userId, $date);
            $data = $this->userRepository->isEligibleUpdate($model);

            return response()->json($data, ResponseAlias::HTTP_OK);
        }catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\DTOs\AdminUpdateModelDTO;
use App\Http\Requests\BloodBankCreateRequest;
use App\Http\Requests\BloodBankUpdateRequest;
use App\Http\Services\BloodBankService;
use App\Repositories\BloodBank\IBloodBankRepository;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BloodBankController extends Controller
{
    public function __construct(
        private readonly IBloodBankRepository $bloodBankRepository,
        private readonly BloodBankService     $bloodBankService
    )
    {
    }

    public function create(BloodBankCreateRequest $request): JsonResponse
    {
        try {
            $bloodBank = $request->all();
            $res = $this->bloodBankRepository->create($bloodBank);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function update(BloodBankUpdateRequest $request, $id): JsonResponse
    {
        try {
            $bloodBank = $request->validated();
            $res = $this->bloodBankRepository->update($id, $bloodBank);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $data = $this->bloodBankRepository->delete($id);
            if (!$data) {
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['message' => 'Record deleted successfully'], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function allBlood(): JsonResponse
    {
        try {
            $data = $this->bloodBankRepository->list();
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getBloodById(int $id): JsonResponse
    {
        try {
            $data = $this->bloodBankRepository->find($id);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function updateByAdmin(Request $request): JsonResponse
    {
        try {
            $bloodName = $request->input('bloodName');
            $bags = $request->input('bags');
            $model = new AdminUpdateModelDTO($bloodName, $bags);
            $data = $this->bloodBankService->updateByAdmin($model);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function availableBlood(Request $request): JsonResponse
    {
        try {
            $bloodName = $request->json()->all();
            //  dd($bloodName);
            $data = $this->bloodBankService->availableBlood($bloodName['bloodName']);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}

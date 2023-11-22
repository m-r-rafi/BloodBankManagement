<?php

namespace App\Http\Controllers;

use App\Http\DTOs\ChangeStatusModelDTO;
use App\Http\DTOs\HistoryModelDTO;
use App\Http\Requests\DonateCreateRequest;
use App\Http\Requests\DonateUpdateRequest;
use App\Http\Services\DonateBloodService;
use App\Repositories\DonateBlood\IDonateBloodRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DonateBloodController extends Controller
{

    public function __construct(
        private readonly IDonateBloodRepository $donateBloodRepository,
        private readonly DonateBloodService     $donateBloodService
    )
    {
    }

    public function create(DonateCreateRequest $request): JsonResponse
    {
        try {
            $donateBlood = $request->all();
            $res = $this->donateBloodRepository->create($donateBlood);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function update(DonateUpdateRequest $request,$id): JsonResponse
    {
        try {
            $donateBlood = $request->validated();
            $res = $this->donateBloodRepository->update($id,$donateBlood);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $data = $this->donateBloodRepository->delete($id);
            if (!$data) {
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['message' => 'Record deleted successfully'], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function allDonate(): JsonResponse
    {
        try {
            $data = $this->donateBloodRepository->list();
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getDonateById(int $id): JsonResponse
    {
        try {
            $data = $this->donateBloodRepository->find($id);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getAllByStatus(int $id): JsonResponse
    {
        try {
            $data = $this->donateBloodService->getAllByStatus($id);
            if(!$data){
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function donate(int $id): JsonResponse
    {
        try {
            $isAllowed = $this->donateBloodService->isAllowedRequest($id);
            $data = ($isAllowed) ? $this->donateBloodService->donate($id) : false;
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function isAllowedRequest(int $id): JsonResponse
    {
        try {
            $data = $this->donateBloodService->isAllowedRequest($id);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getHistory(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('userId');
            $statusId = $request->input('statusId');
            $model = new HistoryModelDTO($userId, $statusId);
            $data = $this->donateBloodService->getByUserIdStatus($model);

            if(!$data){
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }

            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function viewChangeStatus(Request $request): JsonResponse
    {
        try {
            $donateId = $request->input('donateId');
            $data = $this->donateBloodService->viewChangeStatus($donateId);
            if(!$data){
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function changeStatus(Request $request): JsonResponse
    {
        try {
            $receiveId = $request->input('receiveId');
            $statusId = $request->input('statusId');
            $model = new ChangeStatusModelDTO($receiveId, $statusId);
            $data = $this->donateBloodService->changeStatus($model);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}

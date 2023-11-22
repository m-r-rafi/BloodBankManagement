<?php

namespace App\Http\Controllers;

use App\Http\DTOs\ChangeStatusModelDTO;
use App\Http\DTOs\HistoryModelDTO;
use App\Http\DTOs\RequestBloodModelDTO;
use App\Http\Requests\ReceiveCreateRequest;
use App\Http\Requests\ReceiveUpdateRequest;
use App\Http\Services\ReceiveBloodService;
use App\Repositories\ReceiveBlood\IReceiveBloodRepository;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReceiveBloodController extends Controller
{
    public function __construct(
        private readonly IReceiveBloodRepository $receiveBloodRepository,
        private readonly ReceiveBloodService     $receiveBloodService
    )
    {
    }

    public function create(ReceiveCreateRequest $request): JsonResponse
    {
        try {
            $receiveBlood = $request->all();
            $res = $this->receiveBloodRepository->create($receiveBlood);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function update(ReceiveUpdateRequest $request,$id): JsonResponse
    {
        try {
            $receiveBlood = $request->validated();
            $res = $this->receiveBloodRepository->update($id,$receiveBlood);
            return response()->json($res, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $data = $this->receiveBloodRepository->delete($id);
            if (!$data) {
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['message' => 'Record deleted successfully'], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function allReceive(): JsonResponse
    {
        try {
            $data = $this->receiveBloodRepository->list();
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getReceiveById(int $id): JsonResponse
    {
        try {
            $data = $this->receiveBloodRepository->find($id);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function getAllByStatus(int $id): JsonResponse
    {
        try {
            $data = $this->receiveBloodService->getAllByStatus($id);
            if(!$data){
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
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
            $data = $this->receiveBloodService->getByUserIdStatus($model);

            if(!$data){
                return response()->json(['message' => 'Record not found'], ResponseAlias::HTTP_NOT_FOUND);
            }

            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function requestBlood(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('userId');
            $bloodName = $request->input('bloodName');
            $bags = $request->input('bags');
            $model = new RequestBloodModelDTO($userId, $bloodName, $bags);
            $data = $this->receiveBloodService->requestBlood($model);

            if (isset($data['error'])) {
                return response()->json(['message' => $data['error']], ResponseAlias::HTTP_BAD_REQUEST);
            } elseif (isset($data['success'])) {
                return response()->json(['message' => $data['success']], ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'An unexpected error occurred'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function isAllowedRequest(int $id): JsonResponse
    {
        try {
            $data = $this->receiveBloodService->isAllowedRequest($id);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function viewChangeStatus(Request $request): JsonResponse
    {
        try {
            $receiveId = $request->input('receiveId');
            $data = $this->receiveBloodService->viewChangeStatus($receiveId);
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
            $data = $this->receiveBloodService->changeStatus($model);
            return response()->json($data, ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}

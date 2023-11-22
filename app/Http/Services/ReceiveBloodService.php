<?php

namespace App\Http\Services;


use App\Http\DTOs\ChangeStatusModelDTO;
use App\Http\DTOs\HistoryModelDTO;
use App\Http\DTOs\ReceiveChangeStatusDTO;
use App\Http\DTOs\RequestBloodModelDTO;
use App\Models\StatusSetting;
use App\Repositories\BloodBank\IBloodBankRepository;
use App\Repositories\ReceiveBlood\IReceiveBloodRepository;
use App\Repositories\StatusSetting\IStatusSettingRepository;
use App\Repositories\User\IUserRepository;
use Illuminate\Database\Eloquent\Model;

class ReceiveBloodService
{
    public function __construct(
        private readonly IReceiveBloodRepository  $receiveBloodRepository,
        private readonly IBloodBankRepository     $bloodBankRepository,
        private readonly IStatusSettingRepository $statusSettingRepository,
        private readonly IUserRepository          $userRepository
    )
    {
    }

    public function getAllByStatus(int $statusId): ?array
    {
        $receives = $this->receiveBloodRepository->list();
        $matchingReceives = [];

        if ($receives) {
            foreach ($receives as $receive) {
                if ($receive['StatusId'] == $statusId) {
                    $matchingReceives[] = $receive;
                }
            }
        }
        if (!$matchingReceives) {
            return null;
        }
        return $matchingReceives;
    }

    public function requestBlood(RequestBloodModelDTO $model)
    {
        $user = $this->userRepository->find($model->userId);
        $blood = $this->bloodBankRepository->findByName($model->bloodName);

        if ($blood->Quantity < $model->bags) {
            return ['error' => 'Blood quantity not sufficient'];
        }

        if (!$this->isAllowedRequest($model->userId)) {
            return ['error' => 'You are not allowed to request blood'];
        }

        $objToSave = [
            'Quantity' => $model->bags,
            'ReceivedOn' => date('Y-m-d H:i:s'),
            'UserId' => $user->id,
            'BloodId' => $blood->id,
            'StatusId' => 1,
        ];

        $saved = $this->receiveBloodRepository->create($objToSave);
        if ($saved) {
            return ['success' => 'Blood request successful'];
        } else {
            return ['error' => 'Failed to create a blood request'];
        }
    }

    public function isAllowedRequest(int $id): bool
    {
        $latestReceive = $this->receiveBloodRepository
            ->getByUserId($id)->sortByDesc('ReceivedOn')->first();

        if(is_null($latestReceive) || $latestReceive->StatusId == 4 || $latestReceive->StatusId == 3){
            return true;
        }

        return false;
    }

    public function changeStatus(ChangeStatusModelDTO $model)
    {
        $receive = $this->receiveBloodRepository->find($model->receiveId);
        $receive->StatusId = $model->statusId;

        if ($model->statusId === 4) {
            $user = $this->userRepository->find($receive->UserId);
            $user->LastDonatedOn = date('Y-m-d H:i:s');
            $bloodBank = $this->bloodBankRepository->find($receive->BloodId);
            $bloodBank->Quantity -= $receive->Quantity;

            $successBloodBank = $this->bloodBankRepository->update($receive->BloodId, ['Quantity' => $bloodBank->Quantity]);

            if ($successBloodBank) {
                $value = $this->receiveBloodRepository->update($model->receiveId, ['StatusId' => $model->statusId]);
                if ($value) {
                    return true;
                }
            }

            return false;
        }

        if ($model->statusId === 2) {
            $val = "Request Accepted";
        }

        if ($model->statusId === 3) {
            $val = 'Request Rejected';
        }

        $value = $this->receiveBloodRepository->update($model->receiveId, ['StatusId' => $model->statusId]);
        if ($value) {
            return $val = 'Updated Successfully';
        }
        return $val = 'Update Failed';
    }


    public function viewChangeStatus(int $receiveId)
    {
        $receive = $this->receiveBloodRepository->find($receiveId);
        $res = new ReceiveChangeStatusDTO();
        $res->receiveUser = $receive;
        $res->statuses = [];

        if(!$receive){
            return null;
        }

        if ($receive->StatusId == 1) {
            $res->statuses[] = $this->statusSettingRepository->find(2);
            $res->statuses[] = $this->statusSettingRepository->find(3);
        } elseif ($receive->StatusId == 2) {
            $res->statuses[] = $this->statusSettingRepository->find(3);
            $res->statuses[] = $this->statusSettingRepository->find(4);
        }

        return $res;
    }


    public function getByUserIdStatus(HistoryModelDTO $model): ?array
    {
        $receives = $this->receiveBloodRepository->getByUserId($model->userId);
        $receiveStatuses = [];
        if ($receives) {
            foreach ($receives as $receive) {
                if ($receive['StatusId'] == $model->statusId) {
                    $receiveStatuses[] = $receive;
                }
            }
        }
        if (!$receiveStatuses) {
            return null;
        }
        return $receiveStatuses;
    }

}

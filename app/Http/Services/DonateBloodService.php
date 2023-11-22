<?php

namespace App\Http\Services;


use App\Http\DTOs\ChangeStatusModelDTO;
use App\Http\DTOs\DonateChangeStatusDTO;
use App\Http\DTOs\HistoryModelDTO;
use App\Models\DonateBlood;
use App\Repositories\BloodBank\IBloodBankRepository;
use App\Repositories\DonateBlood\IDonateBloodRepository;
use App\Repositories\StatusSetting\IStatusSettingRepository;
use App\Repositories\User\IUserRepository;
use Carbon\Carbon;

class DonateBloodService
{
    public function __construct(
        private readonly IDonateBloodRepository   $donateBloodRepository,
        private readonly IUserRepository          $userRepository,
        private readonly IStatusSettingRepository $statusSettingRepository,
        private readonly IBloodBankRepository     $bloodBankRepository

    )
    {
    }

    public function getAllByStatus(int $id): ?array
    {
        $donates = $this->donateBloodRepository->list();
        $matchingDonates = [];

        if ($donates) {
            foreach ($donates as $donate) {
                if ($donate['StatusId'] == $id) {
                    $matchingDonates[] = $donate;
                }
            }
        }
        if (!$matchingDonates) {
            return null;
        }
        return $matchingDonates;
    }

    public function getByUserIdStatus(HistoryModelDTO $model): ?array
    {
        $donates = $this->donateBloodRepository->getByUserId($model->userId);
        $donateStatuses = [];
        if ($donates) {
            foreach ($donates as $donate) {
                if ($donate['StatusId'] == $model->statusId) {
                    $donateStatuses[] = $donate;
                }
            }
        }
        if (!$donateStatuses) {
            return null;
        }
        return $donateStatuses;
    }

    public function donate(int $id): bool
    {
        if (!$this->userRepository->isEligible($id)) return false;

        $user = $this->userRepository->find($id);
        $blood = $this->bloodBankRepository->findByName($user->BloodGroup);
        if (!$blood) {
            return false;
        }

        $donateBlood = new DonateBlood();
        $donateBlood->Quantity = 1;
        $donateBlood->DonatedOn = Carbon::now();
        $donateBlood->UserId = $user->id;
        $donateBlood->BloodId = $blood->id;
        $donateBlood->StatusId = 1;

        $donor = $this->donateBloodRepository->create($donateBlood->toArray());

        if ($donor) {
            $user->LastDonatedOn = $donateBlood->DonatedOn;
            $user->save();
            return true;
        } else {
            return false;
        }
    }

    public function isAllowedRequest(int $id): bool
    {
        $latestDonation = $this->donateBloodRepository
            ->getByUserId($id)->sortByDesc('DonatedOn')->first();

        if (is_null($latestDonation) || $latestDonation->StatusId == 4 || $latestDonation->StatusId == 3) {
            return true;
        }

        return false;
    }


    public function changeStatus(ChangeStatusModelDTO $model): bool
    {
        $donate = $this->donateBloodRepository->find($model->receiveId);
        $donate->StatusId = $model->statusId;

        if ($model->statusId === 4) {
            $user = $this->userRepository->find($donate->UserId);
            $user->LastDonatedOn = date('Y-m-d H:i:s');
            $bloodBank = $this->bloodBankRepository->find($donate->BloodId);
            $bloodBank->Quantity -= $donate->Quantity;

            $successBloodBank = $this->bloodBankRepository->update($donate->BloodId, ['Quantity' => $bloodBank->Quantity]);

            if ($successBloodBank) {
                $val = $this->donateBloodRepository->update($model->receiveId, ['StatusId' => $model->statusId]);
                if ($val) {
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

        $val = $this->donateBloodRepository->update($model->receiveId, ['StatusId' => $model->statusId]);
        if ($val) {
            return $val = 'Updated Successfully';
        }
        return $val = 'Update Failed';
    }

    public function viewChangeStatus(int $donateId)
    {
        $receive = $this->donateBloodRepository->find($donateId);
        $res = new DonateChangeStatusDTO();
        $res->donateUser = $receive;
        $res->statuses = [];

        if (!$receive) {
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
}

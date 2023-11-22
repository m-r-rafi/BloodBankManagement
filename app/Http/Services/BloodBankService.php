<?php

namespace App\Http\Services;

use App\Repositories\BloodBank\IBloodBankRepository;

class BloodBankService
{
    public function __construct(
        private readonly IBloodBankRepository $bloodBankRepository
    )
    {
    }

    public function updateByAdmin($model): bool
    {
        $blood = $this->bloodBankRepository->findByName($model->bloodName)->first();

        if ($blood) {
            $blood->Quantity += $model->bags;

            if ($blood->Quantity < 0) {
                return false;
            }

            $blood->save();
            return true;
        }

        return false;
    }

    public function availableBlood($bloodName): int
    {
       // dd($bloodName);
        $blood = $this->bloodBankRepository->findByName($bloodName)->first();

        if ($blood) {
            return $blood->Quantity;
        } else {
            return 0;
        }

    }

}

<?php

namespace App\Repositories\BloodBank;

use App\Repositories\IBaseRepository;

interface IBloodBankRepository extends IBaseRepository
{
    public function list(): array;
}

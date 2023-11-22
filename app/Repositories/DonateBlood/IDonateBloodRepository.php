<?php

namespace App\Repositories\DonateBlood;

use App\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;

interface IDonateBloodRepository extends IBaseRepository
{
    public function list(): array;

    public function getByUserId(int $id): ?Collection;
}

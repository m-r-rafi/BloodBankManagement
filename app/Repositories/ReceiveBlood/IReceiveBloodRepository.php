<?php

namespace App\Repositories\ReceiveBlood;

use App\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;

interface IReceiveBloodRepository extends IBaseRepository
{
    public function list(): array;

    public function getByUserId(int $id): ?Collection;
}

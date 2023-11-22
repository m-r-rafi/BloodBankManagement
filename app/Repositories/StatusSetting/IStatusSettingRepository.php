<?php

namespace App\Repositories\StatusSetting;

use App\Repositories\IBaseRepository;

interface IStatusSettingRepository extends IBaseRepository
{
    public function list(): array;
}

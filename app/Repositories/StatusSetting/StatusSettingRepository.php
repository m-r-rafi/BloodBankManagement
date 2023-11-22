<?php

namespace App\Repositories\StatusSetting;

use App\Models\StatusSetting;
use App\Repositories\BaseRepository;

class StatusSettingRepository extends BaseRepository implements IStatusSettingRepository
{
    public function __construct(StatusSetting $statusSetting)
    {
        parent::__construct($statusSetting);
    }

    public function list(): array
    {
        $list = StatusSetting::all();
        return $list->toArray();
    }
}

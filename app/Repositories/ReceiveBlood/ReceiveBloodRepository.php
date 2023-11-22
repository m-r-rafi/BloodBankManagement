<?php

namespace App\Repositories\ReceiveBlood;

use App\Models\BloodBank;
use App\Models\ReceiveBlood;
use App\Models\StatusSetting;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class ReceiveBloodRepository extends BaseRepository implements IReceiveBloodRepository
{
    public function __construct(ReceiveBlood $model)
    {
        parent::__construct($model);
    }

    public function list(): array
    {
        $receives = ReceiveBlood::with('statusSetting', 'bloodBank', 'user')->get();

        return $receives->toArray();
    }

    public function find($id): ?Model
    {
        return $this->model->with('statusSetting', 'bloodBank', 'user')->find($id);
    }

    public function getByUserId(int $id): ?Collection
    {
        return $this->model->with('statusSetting', 'bloodBank')->where('UserId', $id)->get();
    }

}

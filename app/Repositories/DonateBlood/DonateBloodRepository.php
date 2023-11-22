<?php

namespace App\Repositories\DonateBlood;

use App\Models\BloodBank;
use App\Models\DonateBlood;
use App\Models\StatusSetting;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DonateBloodRepository extends BaseRepository implements IDonateBloodRepository
{
    public function __construct(DonateBlood $model)
    {
        parent::__construct($model);
    }

    public function list(): array
    {
        $donates = DonateBlood::with('statusSetting', 'bloodBank', 'user')->get();

        return $donates->toArray();
    }

    public function find($id): ?Model
    {
        return $this->model->with('statusSetting', 'bloodBank', 'user')->find($id);
    }

    public function getByUserId(int $id): ?Collection
    {
        return $this->model->with('statusSetting', 'bloodBank', 'user')->where('UserId', $id)->get();
    }

}

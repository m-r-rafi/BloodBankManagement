<?php

namespace App\Repositories\BloodBank;

use App\Models\BloodBank;
use App\Repositories\BaseRepository;

class BloodBankRepository extends BaseRepository implements IBloodBankRepository
{
    public function __construct(BloodBank $model)
    {
        parent::__construct($model);
    }

    public function list(): array
    {
        $list = BloodBank::all();
        return $list->toArray();
    }


}

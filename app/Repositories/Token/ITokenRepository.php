<?php

namespace App\Repositories\Token;

use App\Repositories\IBaseRepository;

interface ITokenRepository extends IBaseRepository
{
    public function list(): array;
}

<?php

namespace App\Repositories\User;

use App\Repositories\IBaseRepository;

interface IUserRepository extends IBaseRepository
{
    public function list(): array;
    public function isEligible(int $id): bool;

    public function isEligibleUpdate($obj): bool;

}

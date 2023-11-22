<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    public function find($id): ?Model;

    public function findByName(string $name): ?Model;

    public function create($obj): Model;

    public function update(int $id, array $data): Model;

    public function delete($id);

}



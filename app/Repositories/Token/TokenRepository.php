<?php

namespace App\Repositories\Token;

use App\Models\Token;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class TokenRepository extends BaseRepository implements ITokenRepository
{
    public function __construct(Token $token)
    {
        parent::__construct($token);
    }

    public function findByName(string $name): ?Model
    {
        return $this->model->where('TKey',$name)->first();
    }

    public function list(): array
    {
        $list = Token::all();
        return $list->toArray();
    }

}

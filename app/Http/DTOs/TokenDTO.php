<?php

namespace App\Http\DTOs;

class TokenDTO
{
    public string $TKey;
    public $CreatedAt;
    public $ExpiredAt = null;

    public int $UserId;
    public string $UserEmail;
    public int $UserTypeId;
}

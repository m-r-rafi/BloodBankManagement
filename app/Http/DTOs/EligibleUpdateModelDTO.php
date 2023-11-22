<?php

namespace App\Http\DTOs;

class EligibleUpdateModelDTO
{
    public int $userId;
    public string $date;

    public function __construct(int $userId, string $date)
    {
        $this->userId = $userId;
        $this->date = $date;
    }

}

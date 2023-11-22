<?php

namespace App\Http\DTOs;

class RequestBloodModelDTO
{
    public int $userId;
    public string $bloodName;

    public int $bags;

    public function __construct(int $userId, string $bloodName, int $bags)
    {
        $this->userId = $userId;
        $this->bloodName = $bloodName;
        $this->bags = $bags;
    }
}

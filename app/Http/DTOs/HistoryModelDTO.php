<?php

namespace App\Http\DTOs;

class HistoryModelDTO
{
    public int $userId;
    public int $statusId;

    public function __construct(int $userId, int $statusId)
    {
        $this->userId = $userId;
        $this->statusId = $statusId;
    }
}

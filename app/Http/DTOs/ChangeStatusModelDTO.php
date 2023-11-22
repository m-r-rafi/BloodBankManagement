<?php

namespace App\Http\DTOs;

class ChangeStatusModelDTO
{
    public int $receiveId;
    public int $statusId;

    public function __construct(int $receiveId, int $statusId)
    {
        $this->receiveId = $receiveId;
        $this->statusId = $statusId;
    }
}

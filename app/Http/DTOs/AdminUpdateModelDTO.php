<?php

namespace App\Http\DTOs;

class AdminUpdateModelDTO
{
    public string $bloodName;
    public int $bags;

    public function __construct(string $bloodName, int $bags)
    {
        $this->bloodName = $bloodName;
        $this->bags = $bags;
    }
}

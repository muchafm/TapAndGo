<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\ValueObject;

class Station
{
    public string $id;

    public function __construct(
        public string $name, 
        public ValueObject\Position $position, 
        public string $address,
        public int $totalStands,
        public int $availableBikes,
        public City $city
    ) {
        $this->id = \uuid_create();
    }
}
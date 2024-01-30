<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

final class Station
{
    public function __construct(
        public string $name, 
        public Position $position, 
        public string $address,
        public int $totalStands,
        public int $availableBikes
    ) {

    }
}
<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ModifyAStation;

final readonly class Input
{
    public function __construct(
        public string $id,
        public string $name,
        public string $address,
        public float $latitude,
        public float $longitude,
        public int $totalStands,
        public int $availableBikes,
        public string $cityId
    ){

    }
}

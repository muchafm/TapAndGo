<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddAStation;

final readonly class Input
{
    /**
     * @param int<0, max> $capacity
     */
    public function __construct(
        public string $cityId,
        public string $name,
        public string $address,
        public float $latitude,
        public float $longitude,
        public int $capacity
    ){

    }
}

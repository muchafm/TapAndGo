<?php

declare(strict_types=1);

namespace App\Domain\UseCase\AddACity;

final readonly class Input
{
    public function __construct(
        public string $name,
        public float $latitude,
        public float $longitude,
        public bool $isActive
    ){

    }
}

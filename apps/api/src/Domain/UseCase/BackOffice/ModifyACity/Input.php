<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\ModifyACity;

final readonly class Input
{
    /**
     * @param array<string> $stationIds
     */
    public function __construct(
        public string $id,
        public ?string $name,
        public ?float $latitude,
        public ?float $longitude,
        public ?bool $isActive,
        public ?array $stationIds
    ){

    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\Enum;

class Bike
{
    private string $id;

    public function __construct(
        private int $bikeNumber,
        private Enum\State $state
    ) {
        $this->id = uuid_create();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getState(): Enum\State
    {
        return $this->state;
    }

    public function getBikeNumber(): int
    {
        return $this->bikeNumber;
    }

    public function update(Enum\State $state): void
    {
        $this->state = $state;
    }
}
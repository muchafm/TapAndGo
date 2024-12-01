<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\Enum;

class Dock
{
    public string $id;

    public function __construct(
        private int $dockNumber,
        private Station $station,
        private Enum\State $state
    ) {
        $this->id = uuid_create();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDockNumber(): int
    {
        return $this->dockNumber;
    }

    public function getState(): Enum\State
    {
        return $this->state;
    }

    public function getStation(): Station
    {
        return $this->station;
    }

    public function update(Enum\State $state): void
    {
        $this->state = $state;
    }
}
<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\ValueObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class City
{
    private string $id;

    /**
     * @var Collection<int, Station>
     */
    public Collection $stations;

    public function __construct(
        private string $name,
        private ValueObject\Position $position,
        private bool $isActive
    ){
        $this->id = \uuid_create();
        $this->stations = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPosition(): ValueObject\Position
    {
        return $this->position;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Station>
     */
    public function getStations(): Collection
    {
        return $this->stations;
    }

    public function addStation(Station $station): void
    {
        if (!$this->stations->contains($station)) {
            $this->stations->add($station);
        }
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function update(
        ?string $name,
        ?float $latitude,
        ?float $longitude,
        ?bool $isActive,
    ): void {
        if (null !== $name) {
            $this->$name = $name;
        }

        if (null !== $latitude) {
            $this->position = new ValueObject\Position($latitude, $this->position->getLongitude());
        }

        if (null !== $longitude) {
            $this->position = new ValueObject\Position($this->position->getLatitude(), $longitude);
        }

        if (null !== $isActive) {
            $this->isActive = $isActive;
        }
    }
}
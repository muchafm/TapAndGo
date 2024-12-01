<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\Enum;
use App\Domain\Data\ValueObject;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Station
{
    private string $id;

    /**
     * @var Collection<int, Dock>
     */
    private Collection $docks;

    /**
     * @param int<0, max> $capacity
     */
    public function __construct(
        private string $name,
        private ValueObject\Position $position,
        private string $address,
        private Enum\State $state,
        private int $capacity,
        private City $city
    ) {
        $this->id = \uuid_create();
        $this->docks = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int<0, max>
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getPosition(): ValueObject\Position
    {
        return $this->position;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function getState(): Enum\State
    {
        return $this->state;
    }

    /**
     * @return Collection<int, Dock>
     */
    public function getDocks(): Collection
    {
        return $this->docks;
    }

    public function addDock(Dock $dock): void
    {
        if (!$this->docks->contains($dock)) {
            $this->docks->add($dock);
        }
    }

    /**
     * @param int<0, max> $capacity
     */
    public function update(
        ?string $name,
        ?string $address,
        ?float $latitude,
        ?float $longitude,
        ?int $capacity
    ): void {
        if (null !== $name) {
            $this->name = $name;
        }

        if (null !== $address) {
            $this->address = $address;
        }

        if (null !== $latitude) {
            $this->position = new ValueObject\Position($latitude, $this->position->getLongitude());
        }
        
        if (null !== $longitude) {
            $this->position = new ValueObject\Position($this->position->getLatitude(), $longitude);
        }

        if (null !== $capacity) {
            $this->capacity = $capacity;
        }
    }
}
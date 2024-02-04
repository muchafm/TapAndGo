<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\ValueObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class City
{
    public string $id;

    /**
     * @var Collection<int, Station>
     */
    public Collection $stations;

    public function __construct(
        public string $name, 
        public ValueObject\Position $position, 
        public bool $isActive
    ){
        $this->id = \uuid_create();
        $this->stations = new ArrayCollection();
    }

    /**
     * @return array<string>
     */
    public function getStationsAsString(): array
    {
        return array_map(
            fn (Station $station) => $station->name,
            $this->stations->toArray()
        );
    }

    /**
     * @param array<Station> $stations
     */
    public function setStations(array $stations): void
    {
        if (
            0 === sizeof(array_diff($stations, $this->getStationsAsString()))
            && 0 === sizeof(array_diff($this->getStationsAsString(), $stations))
        ) {
            return;
        }

        $this->stations = new ArrayCollection($stations);
    }
}
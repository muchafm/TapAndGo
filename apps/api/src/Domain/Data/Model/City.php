<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

use App\Domain\Data\ValueObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class City
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
     * @param array<Station> $stations
     */
    public function setStations(array $stations): void
    {

        $this->stations = new ArrayCollection($stations);
    }
}
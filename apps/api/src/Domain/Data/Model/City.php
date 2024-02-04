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

    public function addStation(Station $station): void
    {
        $this->stations->add($station);
    }
}
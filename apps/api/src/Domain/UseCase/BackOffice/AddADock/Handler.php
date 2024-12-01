<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddADock;

use App\Domain\Data\Model\Dock;
use App\Domain\Data\Collection\Docks;
use App\Domain\Data\Collection\Stations;
use App\Domain\Exception\StationDockCapacityFullException;
use App\Domain\Exception\StationNotFoundException;

readonly class Handler
{
    public function __construct(
        private Stations $stations,
        private Docks $docks
    ) {

    }

    public function __invoke(Input $input): Output
    {
        if (null === $station = $this->stations->find($input->stationId)) {
            throw new StationNotFoundException($input->stationId);
        }

        if ($station->getDocks()->count() === $station->getCapacity()) {
            throw new StationDockCapacityFullException($station->getId());
        }
        
        $dock = new Dock($input->dockNumber, $station, $input->state);
        
        $this->docks->add($dock);

        $station->addDock($dock);

        return new Output($dock);
    }
}
<?php

declare(strict_types=1);

namespace App\Domain\UseCase\RetrieveAStation;

use App\Domain\Exception\StationNotFoundException;
use App\Domain\Data\Collection\Stations;

readonly class Handler
{
    public function __construct(private Stations $stations)
    {
        
    }

    public function __invoke(Input $input): Output
    {
        if (null === $station = $this->stations->find($input->id)) {
            throw new StationNotFoundException($input->id);
        }

        return new Output($station);
    }
}

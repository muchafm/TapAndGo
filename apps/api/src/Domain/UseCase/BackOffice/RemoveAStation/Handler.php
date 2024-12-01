<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\RemoveAStation;

use App\Domain\Data\Collection\Stations;
use App\Domain\Exception\StationNotFoundException;

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

        $this->stations->remove($station);

        return new Output();
    }
}

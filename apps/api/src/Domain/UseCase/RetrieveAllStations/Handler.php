<?php

declare(strict_types=1);

namespace App\Domain\UseCase\RetrieveAllStations;

use App\Domain\Data\Collection\Stations;

readonly class Handler
{
    public function __construct(private Stations $stations)
    {

    }

    public function __invoke(Input $input): Output
    {
        return new Output($this->stations->findAll());
    }
}

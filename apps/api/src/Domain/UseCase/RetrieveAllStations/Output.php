<?php

declare(strict_types=1);

namespace App\Domain\UseCase\RetrieveAllStations;

use App\Domain\Data\Model\Station;

final readonly class Output
{
    /**
     * @param array<Station> $stations
     */
    public function __construct(public array $stations)
    {

    }
}

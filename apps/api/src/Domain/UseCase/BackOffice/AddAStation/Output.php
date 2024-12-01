<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddAStation;

use App\Domain\Data\Model\Station;

final readonly class Output
{
    public function __construct(public Station $station)
    {

    }
}

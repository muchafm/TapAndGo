<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\ModifyACity;

use App\Domain\Data\Model\City;

final readonly class Output
{
    public function __construct(public City $city)
    {

    }
}

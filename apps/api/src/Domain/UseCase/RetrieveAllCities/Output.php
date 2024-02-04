<?php

declare(strict_types=1);

namespace App\Domain\UseCase\RetrieveAllCities;

use App\Domain\Data\Model\City;

final readonly class Output
{
    /**
     * @param array<City> $cities
     */
    public function __construct(public array $cities)
    {
        
    }
}

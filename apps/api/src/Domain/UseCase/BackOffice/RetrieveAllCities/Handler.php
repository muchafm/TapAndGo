<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\RetrieveAllCities;

use App\Domain\Data\Collection\Cities;

readonly class Handler
{
    public function __construct(private Cities $cities)
    {
        
    }

    public function __invoke(Input $input): Output
    {
        return new Output($this->cities->findAll());
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\RetrieveACity;

use App\Domain\Exception\CityNotFoundException;
use App\Domain\Data\Collection\Cities;

readonly class Handler
{
    public function __construct(
        private Cities $cities
    ){

    }

    public function __invoke(Input $input): Output
    {
        if (null === $city = $this->cities->find($input->id)) {
            throw new CityNotFoundException($input->id);
        }

        return new Output($city);
    }
}

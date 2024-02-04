<?php

declare(strict_types=1);

namespace App\Domain\UseCase\RemoveACity;

use App\Domain\Data\Collection\Cities;
use App\Domain\Exception\CityNotFoundException;

readonly class Handler
{
    public function __construct(private Cities $cities)
    {

    }

    public function __invoke(Input $input): Output
    {
        if (null === $city = $this->cities->find($input->id)) {
            throw new CityNotFoundException($input->id);
        }

        $this->cities->remove($city);

        return new Output();
    }
}

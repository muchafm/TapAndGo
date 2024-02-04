<?php

declare(strict_types=1);

namespace App\Domain\UseCase\AddACity;

use App\Domain\Data\Model\City;
use App\Domain\Data\Collection\Cities;
use App\Domain\Data\ValueObject\Position;

readonly class Handler
{
    public function __construct(private Cities $cities)
    {

    }

    public function __invoke(Input $input): Output
    {
        $city = new City($input->name, new Position($input->latitude, $input->longitude), $input->isActive);
        
        $this->cities->add($city);

        return new Output($city);
    }
}

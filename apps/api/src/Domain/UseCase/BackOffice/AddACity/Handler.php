<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddACity;

use App\Domain\Data\Model\City;
use App\Domain\Data\Collection\Cities;
use App\Domain\Data\ValueObject\Position;
use App\Domain\Exception\CityAlreadyExistsException;

readonly class Handler
{
    public function __construct(private Cities $cities)
    {

    }

    public function __invoke(Input $input): Output
    {
        if (null !== $this->cities->findByName($input->name)) {
            throw new CityAlreadyExistsException($input->name);
        }
        
        $city = new City($input->name, new Position($input->latitude, $input->longitude), $input->isActive);
        
        $this->cities->add($city);

        return new Output($city);
    }
}

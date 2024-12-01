<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddAStation;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Enum;
use App\Domain\Data\Model\Station;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\ValueObject\Position;
use App\Domain\Exception\CityNotFoundException;

readonly class Handler
{
    public function __construct(private Stations $stations, private Cities $cities)
    {

    }

    public function __invoke(Input $input): Output
    {
        if (null === $city = $this->cities->find($input->cityId)) {
            throw new CityNotFoundException($input->cityId);
        }

        $station = new Station(
            $input->name, 
            new Position($input->latitude, $input->longitude), 
            $input->address,
            false !== $city->isActive() ? Enum\State::ENABLED : Enum\State::DISABLED,
            $input->capacity,
            $city
        );

        $this->stations->add($station);

        return new Output($station);
    }
}

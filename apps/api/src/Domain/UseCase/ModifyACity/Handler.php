<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ModifyACity;

use App\Domain\Data\Model\City;
use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\ValueObject\Position;
use App\Domain\Exception\CityNotFoundException;

readonly class Handler
{
    public function __construct(
        private Cities $cities,
        private Stations $stations
    ){

    }

    public function __invoke(Input $input): Output
    {
        if (null === $city = $this->cities->find($input->id)) {
            throw new CityNotFoundException($input->id);
        }
        
        if (null !== $input->stationIds && \count($input->stationIds) !== 0) {
            $stations = $this->stations->findByIds($input->stationIds);

            foreach ($stations as $station) {
                if (!\in_array($station, $city->stations->toArray())) {
                    $city->addStation($station);
                }
            }
        }
        
        if (null !== $input->name) {
            $city->name = $input->name;
        }

        if (null !== $input->latitude) {
            $city->position = new Position($input->latitude, $city->position->longitude);
        }

        if (null !== $input->longitude) {
            $city->position = new Position($city->position->latitude, $input->longitude);
        }

        if (null !== $input->isActive) {
            $city->isActive = $input->isActive;
        }

        $this->cities->persist($city);

        return new Output($city);
    }
}

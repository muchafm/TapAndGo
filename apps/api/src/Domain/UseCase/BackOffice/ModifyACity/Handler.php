<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\ModifyACity;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Collection\Stations;
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

        $city->update($input->name, $input->latitude, $input->longitude, $input->isActive);

        $this->cities->persist($city);

        return new Output($city);
    }
}

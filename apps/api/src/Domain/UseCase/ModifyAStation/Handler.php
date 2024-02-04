<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ModifyAStation;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Model\Station;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\ValueObject\Position;
use App\Domain\Exception\CityNotFoundException;
use App\Domain\Exception\StationNotFoundException;

readonly class Handler
{
    public function __construct(private Stations $stations, private Cities $cities)
    {

    }

    public function __invoke(Input $input): Output
    {
        if (null === $station = $this->stations->find($input->id)) {
            throw new StationNotFoundException($input->id);
        }

        if (null !== $input->cityId) {
            if (null === $city = $this->cities->find($input->cityId)) {
                throw new CityNotFoundException($input->cityId);
            }

            $station->city = $city;
        }

        if (null !== $input->name) {
            $station->name = $input->name;
        }

        if (null !== $input->address) {
            $station->address = $input->address;
        }

        if (null !== $input->latitude) {
            $station->position = new Position($input->latitude, $station->position->longitude);
        }

        if (null !== $input->longitude) {
            $station->position = new Position($station->position->latitude, $input->longitude);
        }

        if (null !== $input->totalStands) {
            $station->totalStands = $input->totalStands;
        }

        if (null !== $input->availableBikes) {
            $station->availableBikes = $input->availableBikes;
        }

        return new Output($station);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ModifyAStation;

use App\Domain\Data\Collection\Stations;
use App\Domain\Data\ValueObject\Position;
use App\Domain\Exception\StationNotFoundException;

readonly class Handler
{
    public function __construct(private Stations $stations)
    {

    }

    public function __invoke(Input $input): Output
    {
        if (null === $station = $this->stations->find($input->id)) {
            throw new StationNotFoundException($input->id);
        }

        $station->update(
            $input->name,
            $input->address,
            $input->latitude,
            $input->longitude,
            $input->capacity
        );

        $this->stations->persist($station);
        
        return new Output($station);
    }
}

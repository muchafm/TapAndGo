<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\ModifyADockState;

use App\Domain\Data\Collection\Docks;
use App\Domain\Exception\DockNotFoundException;

readonly class Handler
{
    public function __construct(private Docks $docks)
    {

    }

    public function __invoke(Input $input): Output
    {
        if (null === $dock = $this->docks->find($input->id)) {
            throw new DockNotFoundException($input->id);
        }

        $dock->update(
            $input->state
        );

        $this->docks->persist($dock);
        
        return new Output($dock);
    }
}

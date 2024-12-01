<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\RetrieveADock;

use App\Domain\Exception\DockNotFoundException;
use App\Domain\Data\Collection\Docks;

readonly class Handler
{
    public function __construct(
        private Docks $docks
    ){

    }

    public function __invoke(Input $input): Output
    {
        if (null === $dock = $this->docks->find($input->id)) {
            throw new DockNotFoundException($input->id);
        }

        return new Output($dock);
    }
}

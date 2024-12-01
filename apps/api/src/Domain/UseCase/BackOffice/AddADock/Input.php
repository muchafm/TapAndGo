<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddADock;

use App\Domain\Data\Enum;

final readonly class Input
{
    public function __construct(
        public string $stationId,
        public int $dockNumber,
        public Enum\State $state
    ) {

    }
}
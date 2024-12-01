<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\ModifyADockState;

use App\Domain\Data\Enum;

final readonly class Input
{
    public function __construct(
        public string $id,
        public Enum\State $state
    ){

    }
}

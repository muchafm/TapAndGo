<?php

declare(strict_types=1);

namespace App\Domain\UseCase\BackOffice\AddADock;

use App\Domain\Data\Model\Dock;

final readonly class Output
{
    public function __construct(public Dock $dock)
    {

    }
}
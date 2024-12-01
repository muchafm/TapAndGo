<?php

declare(strict_types=1);

namespace App\Domain\Data\Enum;

enum State: string
{
    case ENABLED = "ENABLED";
    case DISABLED = "DISABLED";
    case UNDER_REPAIR = "UNDER_REPAIR";
}
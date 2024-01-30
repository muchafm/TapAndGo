<?php

declare(strict_types=1);

namespace App\Domain\Data\ValueObject;

final class Position
{
    public function __construct(public readonly float $latitude, public readonly float $longitude)
    {
    }
}
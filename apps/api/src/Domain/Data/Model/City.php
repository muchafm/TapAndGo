<?php

declare(strict_types=1);

namespace App\Domain\Data\Model;

final class City
{
    public function __construct(public string $name, public Position $position, public bool $isActive)
    {

    }
}
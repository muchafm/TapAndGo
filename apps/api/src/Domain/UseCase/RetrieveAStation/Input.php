<?php

declare(strict_types=1);

namespace App\Domain\UseCase\RetrieveAStation;

final readonly class Input
{
    public function __construct(public string $id)
    {
        
    }
}
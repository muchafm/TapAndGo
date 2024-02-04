<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class StationNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Station with ID {$id} was not found.");
    }
}

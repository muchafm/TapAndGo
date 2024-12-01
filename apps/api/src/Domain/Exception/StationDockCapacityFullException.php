<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class StationDockCapacityFullException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("The docks capacity station with ID {$id} is full.");
    }
}
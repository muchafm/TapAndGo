<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class CityNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("City with ID {$id} was not found.");
    }
}

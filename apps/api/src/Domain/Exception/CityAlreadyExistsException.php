<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class CityAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("City with name {$name} already exists.");
    }
}

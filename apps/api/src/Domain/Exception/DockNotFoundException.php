<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class DockNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Dock with ID {$id} was not found.");
    }
}

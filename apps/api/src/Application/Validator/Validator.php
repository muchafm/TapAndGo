<?php

declare(strict_types=1);

namespace App\Application\Validator;

use ReflectionClass;
use ReflectionProperty;

abstract class Validator
{
    abstract protected function getClass(): string;

    /**
     * @return String[]
     */
    protected function getPropertiesNames(string $class): array
    {
        $reflection = new ReflectionClass($class);

        return array_map(fn (ReflectionProperty $property): string => $property->getName(), $reflection->getProperties());;
    }

}
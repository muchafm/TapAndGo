<?php

declare(strict_types=1);

namespace App\Application\Validator;

use App\Domain;
use InvalidArgumentException;

class Station extends Validator
{
    protected function getClass(): string
    {
        return Domain\Data\Model\Station::class;
    }

    /**
     * @param array<string, scalar> $data
     */
    public function validatePostData(array $data): void
    {
        $propertiesNames = [];

        $propertiesNames = $this->getPropertiesNames($this->getClass());
        unset($propertiesNames['id']);
        foreach ($data as $key => $value) {
            if (!in_array($key, $propertiesNames)) {
                throw new InvalidArgumentException("{$key} is not excepted.");
            }
        }
    }
}
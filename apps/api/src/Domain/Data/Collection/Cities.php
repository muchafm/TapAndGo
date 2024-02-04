<?php

declare(strict_types=1);

namespace App\Domain\Data\Collection;

use App\Domain\Data\Model\City;

interface Cities
{
    public function add(City $city): void;

    public function find(string $id): ?City;

    /**
     * @return array<City>
     */
    public function findAll(): array;

    public function persist(City $city): void;

    public function remove(City $city): void;
}
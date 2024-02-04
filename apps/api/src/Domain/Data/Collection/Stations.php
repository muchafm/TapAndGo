<?php

declare(strict_types=1);

namespace App\Domain\Data\Collection;

use App\Domain\Data\Model\Station;

interface Stations
{
    public function add(Station $station): void;

    public function find(string $id): ?Station;

    /**
     * @return array<Station>
     */
    public function findAll(): array;

    /**
     * @param array<string> $ids
     * @return array<int, Station>
     */
    public function findByIds(array $ids): array;
}
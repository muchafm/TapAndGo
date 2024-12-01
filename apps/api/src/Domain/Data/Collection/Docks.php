<?php

declare(strict_types=1);

namespace App\Domain\Data\Collection;

use App\Domain\Data\Model\Dock;

interface Docks
{
    public function add(Dock $dock): void;

    public function find(string $id): ?Dock;

    /**
     * @return array<Dock>
     */
    public function findAll(): array;

    public function persist(Dock $dock): void;

    public function remove(Dock $dock): void;
}
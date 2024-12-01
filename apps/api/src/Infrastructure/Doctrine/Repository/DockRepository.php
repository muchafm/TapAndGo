<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final readonly class DockRepository implements Domain\Data\Collection\Docks
{
    /**
     * @var ServiceEntityRepository<Domain\Data\Model\Dock>
     */
    private ServiceEntityRepository $repository;

    public function __construct(
        private ManagerRegistry $registry
    ) {
        $this->repository = new ServiceEntityRepository(
            $registry,
            Domain\Data\Model\Dock::class,
        );
    }

    public function add(
        Domain\Data\Model\Dock $dock,
    ): void {
        $this->registry->getManager()->persist($dock);
        $this->registry->getManager()->flush();
    }

    public function find(string $id): ?Domain\Data\Model\Dock
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    /**
     * @return array<Domain\Data\Model\Dock>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array<string> $ids
     * @return array<int, Domain\Data\Model\Dock>
     */
    public function findByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids]);
    }

    public function persist(Domain\Data\Model\Dock $dock): void
    {
        $this->registry->getManager()->persist($dock);
        $this->registry->getManager()->flush();
    }

    public function remove(Domain\Data\Model\Dock $dock): void
    {
        $this->registry->getManager()->remove($dock);
        $this->registry->getManager()->flush();
    }
}
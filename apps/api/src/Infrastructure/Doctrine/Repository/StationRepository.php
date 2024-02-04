<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final readonly class StationRepository implements Domain\Data\Collection\Stations
{
    /**
     * @var ServiceEntityRepository<Domain\Data\Model\Station>
     */
    private ServiceEntityRepository $repository;

    public function __construct(
        private ManagerRegistry $registry
    ) {
        $this->repository = new ServiceEntityRepository(
            $registry,
            Domain\Data\Model\Station::class,
        );
    }

    public function add(
        Domain\Data\Model\Station $station,
    ): void {
        $this->registry->getManager()->persist($station);
        $this->registry->getManager()->flush();
    }

    public function find(string $id): ?Domain\Data\Model\Station
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    /**
     * @return array<Domain\Data\Model\Station>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array<string> $ids
     * @return array<int, Domain\Data\Model\Station>
     */
    public function findByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids]);
    }

    public function persist(Domain\Data\Model\Station $station): void
    {
        $this->registry->getManager()->persist($station);
        $this->registry->getManager()->flush();
    }

    public function remove(Domain\Data\Model\Station $station): void
    {
        $this->registry->getManager()->remove($station);
        $this->registry->getManager()->flush();
    }
}
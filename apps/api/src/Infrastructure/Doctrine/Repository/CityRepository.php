<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final readonly class CityRepository implements Domain\Data\Collection\Cities
{
    /**
     * @var ServiceEntityRepository<Domain\Data\Model\City>
     */
    private ServiceEntityRepository $repository;

    public function __construct(
        private ManagerRegistry $registry
    ) {
        $this->repository = new ServiceEntityRepository(
            $registry,
            Domain\Data\Model\City::class,
        );
    }

    public function add(
        Domain\Data\Model\City $city,
    ): void {
        $this->registry->getManager()->persist($city);
        $this->registry->getManager()->flush();
    }

    public function find(string $id): ?Domain\Data\Model\City
    {
        return $this->repository->find($id);
    }

    /**
     * @return array<Domain\Data\Model\City>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findByName(string $name): ?Domain\Data\Model\City
    {
        return $this->repository
            ->createQueryBuilder('city')
            ->where('city.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function persist(Domain\Data\Model\City $city): void
    {
        $this->registry->getManager()->persist($city);
        $this->registry->getManager()->flush();
    }

    public function remove(Domain\Data\Model\City $city): void
    {
        $this->registry->getManager()->remove($city);
        $this->registry->getManager()->flush();
    }
}
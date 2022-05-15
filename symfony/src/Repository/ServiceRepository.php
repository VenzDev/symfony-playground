<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ServiceRepository extends ServiceEntityRepository implements ServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @param User $user
     *
     * @return QueryBuilder
     */
    private function getByUserQuery(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.owner = :user')
            ->setParameter('user', $user);
    }

    /**
     * @param User $user
     *
     * @return Service[]
     */
    public function getByUser(User $user): array
    {
        return $this->getByUserQuery($user)
            ->getQuery()
            ->getResult();
    }

    public function getByUserAndId(User $user, int $id): ?Service
    {
        return $this->getByUserQuery($user)
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

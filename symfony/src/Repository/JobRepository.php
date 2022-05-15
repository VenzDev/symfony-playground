<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Job;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class JobRepository extends ServiceEntityRepository implements JobRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    private function getByUserQuery(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->innerJoin('j.service', 's')
            ->andWhere('s.owner = :user')
            ->setParameter('user', $user);
    }

    /**
     * @param User $user
     * @param int $serviceId
     *
     * @return Job[]
     */
    public function getByUser(User $user, int $serviceId): array
    {
        return $this->getByUserQuery($user)
            ->andWhere('s.id = :serviceId')
            ->setParameter('serviceId', $serviceId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int $serviceId
     * @param int $id
     *
     * @return ?Job
     * @throws NonUniqueResultException
     */
    public function getByUserAndId(User $user, int $serviceId, int $id): ?Job
    {
        return $this->getByUserQuery($user)
            ->andWhere('s.id = :serviceId')
            ->setParameter('serviceId', $serviceId)
            ->andWhere('j.id = :jobId')
            ->setParameter('jobId', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

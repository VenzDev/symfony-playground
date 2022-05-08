<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Job $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Job $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
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
     * @param int  $serviceId
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
     * @param int  $serviceId
     * @param int  $id
     *
     * @return Job
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByUserAndId(User $user, int $serviceId, int $id): Job
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

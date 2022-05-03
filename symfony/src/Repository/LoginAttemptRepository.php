<?php

namespace App\Repository;

use App\Entity\Admin;
use App\Entity\LoginAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LoginAttempt>
 *
 * @method LoginAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginAttempt[]    findAll()
 * @method LoginAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginAttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginAttempt::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(LoginAttempt $entity, bool $flush = true): void
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
    public function remove(LoginAttempt $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return LoginAttempt[]
     */
    public function getLastLoginAttemptForEachAdmin()
    {
        return $this->createQueryBuilder('lastAttempt')
            ->select('lastAttempt')
            ->andWhere('lastAttempt.date IN (SELECT MAX(l.date) from App\Entity\LoginAttempt l GROUP BY l.userAdmin)')
            ->getQuery()
            ->getResult();
    }

    public function getLoginAttemptsByAdmin(Admin $admin)
    {
        return $this->createQueryBuilder('lastAttempt')
            ->andWhere('lastAttempt.userAdmin = :admin')
            ->setParameter('admin', $admin)
            ->getQuery()
            ->getResult();
    }

    public function getLoginAttempts()
    {
        return $this->createQueryBuilder('l')
            ->select('userAdmin.id', 'l.date')
            ->innerJoin('l.userAdmin', 'userAdmin')
            ->orderBy('l.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

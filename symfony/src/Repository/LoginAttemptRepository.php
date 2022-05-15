<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Admin;
use App\Entity\LoginAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LoginAttemptRepository extends ServiceEntityRepository implements LoginAttemptRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginAttempt::class);
    }

    /**
     * @return LoginAttempt[]
     */
    public function getLastLoginAttemptForEachAdmin(): array
    {
        return $this->createQueryBuilder('lastAttempt')
            ->select('lastAttempt')
            ->andWhere('lastAttempt.date IN (SELECT MAX(l.date) from App\Entity\LoginAttempt l GROUP BY l.userAdmin)')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Admin $admin
     *
     * @return LoginAttempt[]
     */
    public function getLoginAttemptsByAdmin(Admin $admin): array
    {
        return $this->createQueryBuilder('lastAttempt')
            ->andWhere('lastAttempt.userAdmin = :admin')
            ->setParameter('admin', $admin)
            ->getQuery()
            ->getResult();
    }

    public function getLoginAttempts(): array
    {
        return $this->createQueryBuilder('l')
            ->select('userAdmin.id', 'l.date')
            ->innerJoin('l.userAdmin', 'userAdmin')
            ->orderBy('l.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

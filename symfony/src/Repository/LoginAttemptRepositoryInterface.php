<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Admin;
use App\Entity\LoginAttempt;

/**
 * @method LoginAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginAttempt[]    findAll()
 * @method LoginAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface LoginAttemptRepositoryInterface
{
    public function getLastLoginAttemptForEachAdmin(): array;

    public function getLoginAttemptsByAdmin(Admin $admin): array;

    public function getLoginAttempts(): array;
}
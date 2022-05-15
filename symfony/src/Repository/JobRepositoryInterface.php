<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Job;
use App\Entity\User;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface JobRepositoryInterface
{
    /**
     * @param User $user
     * @param int $serviceId
     * @return Job[]
     */
    public function getByUser(User $user, int $serviceId): array;

    /**
     * @param User $user
     * @param int $serviceId
     * @param int $id
     * @return ?Job
     */
    public function getByUserAndId(User $user, int $serviceId, int $id): ?Job;
}
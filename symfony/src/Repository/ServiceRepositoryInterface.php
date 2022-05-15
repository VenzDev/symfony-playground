<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Service;
use App\Entity\User;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface ServiceRepositoryInterface
{
    public function getByUser(User $user): array;

    public function getByUserAndId(User $user, int $id): ?Service;
}
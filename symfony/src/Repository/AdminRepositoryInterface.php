<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Admin;

/**
 * @method Admin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admin[]    findAll()
 * @method Admin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface AdminRepositoryInterface
{
    /**
     * @return Admin[]
     */
    public function getAdminsWithoutRoot(): array;
}
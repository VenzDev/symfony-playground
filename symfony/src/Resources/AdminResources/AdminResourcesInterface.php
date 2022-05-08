<?php

declare(strict_types=1);

namespace App\Resources\AdminResources;

use App\Entity\Admin;

interface AdminResourcesInterface
{
    /**
     * @return Admin[]
     */
    public function getAdminWithLastAttempt(): array;

    public function getUnverifiedCount(): int;

    public function getAdminCount(): int;

    public function getBlockedCount(): int;

    public function getOnlineTodayCount(): int;
}


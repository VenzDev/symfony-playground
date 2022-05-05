<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Admin;
use App\Entity\LoginAttempt;

final class LoginAttemptFactory
{
    public static function create(Admin $admin, int $daysFromNow = 0): LoginAttempt
    {
        $attempt = new LoginAttempt();
        $attempt->setUserAdmin($admin);
        $attempt->setDate((new \DateTime())->modify("-$daysFromNow day"));

        return $attempt;
    }
}
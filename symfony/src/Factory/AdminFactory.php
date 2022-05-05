<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Admin;
use Faker\Factory;

final class AdminFactory
{
    public static function create(bool $isVerified = false, bool $isBlocked = false, string $role = Admin::ROLE_ADMIN): Admin
    {
        $faker = Factory::create();

        $admin = new Admin();
        $admin->setName($faker->name);
        $admin->setEmail($faker->email);
        $admin->setIsVerified($isVerified);
        $admin->setIsBlocked($isBlocked);
        $admin->setRoles([$role]);
        $admin->setPassword('$argon2id$v=19$m=10,t=3,p=1$eyXPWiQFWUO901E78Bb3UQ$hyu9dFDz7fo2opQyCSoX/NfJDvEpzER/a+WbiAagqqw'); //"test"

        return $admin;
    }
}
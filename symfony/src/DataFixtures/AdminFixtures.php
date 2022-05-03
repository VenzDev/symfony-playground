<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\LoginAttempt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $superAdmin = new Admin();

        $superAdmin->setName('ROOT');
        $superAdmin->setEmail('carparkersender@gmail.com');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $password = $this->passwordHasher->hashPassword($superAdmin, 'Zaq12wsxcde3!');

        $superAdmin->setPassword($password);

        $manager->persist($superAdmin);

        $admin = new Admin();

        $admin->setName('John');
        $admin->setEmail('email@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $password = $this->passwordHasher->hashPassword($superAdmin, 'Zaq12wsxcde3!');
        $admin->setPassword($password);

        $loginAttempt = new LoginAttempt();

        $loginAttempt->setUserAdmin($admin);
        $loginAttempt->setDate((new \DateTime())->modify('-1 day'));

        $admin2 = new Admin();

        $admin2->setName('Michael');
        $admin2->setEmail('email1@gmail.com');
        $admin2->setRoles(['ROLE_ADMIN']);

        $password = $this->passwordHasher->hashPassword($superAdmin, 'Zaq12wsxcde3!');
        $admin2->setPassword($password);

        $loginAttempt2 = new LoginAttempt();

        $loginAttempt2->setUserAdmin($admin2);
        $loginAttempt2->setDate(new \DateTime());

        $manager->persist($admin);
        $manager->persist($loginAttempt);
        $manager->persist($admin2);
        $manager->persist($loginAttempt2);

        $manager->flush();
    }

}
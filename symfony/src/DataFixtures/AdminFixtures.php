<?php

namespace App\DataFixtures;

use App\Entity\Admin;
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

        $superAdmin->setEmail('carparkersender@gmail.com');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $password = $this->passwordHasher->hashPassword($superAdmin, 'Zaq12wsxcde3!');

        $superAdmin->setPassword($password);

        $manager->persist($superAdmin);
        $manager->flush();
    }
}
<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\LoginAttempt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    protected Generator $faker;

    private int $adminCount = 20;

    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create();
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createSuperAdmin());

        for ($i = 0; $i < $this->adminCount; $i++) {
            $admin = $this->createAdmin();
            $loginAttempt = $this->createLoginAttempt($admin);

            $manager->persist($admin);
            $manager->persist($loginAttempt);
        }

        $manager->flush();
    }

    private function createSuperAdmin(): Admin
    {
        $superAdmin = new Admin();

        $superAdmin->setName('ROOT');
        $superAdmin->setEmail('carparkersender@gmail.com');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $password = $this->passwordHasher->hashPassword($superAdmin, 'Zaq12wsxcde3!');

        $superAdmin->setPassword($password);

        return $superAdmin;
    }

    /**
     * @return Admin
     */
    private function createAdmin(): Admin
    {
        $superAdmin = new Admin();

        $superAdmin->setName($this->faker->name);
        $superAdmin->setEmail($this->faker->email);
        $superAdmin->setRoles(['ROLE_ADMIN']);

        $password = $this->passwordHasher->hashPassword($superAdmin, 'Zaq12wsxcde3!');

        $superAdmin->setPassword($password);

        return $superAdmin;
    }

    /**
     * @param Admin $admin
     *
     * @return LoginAttempt
     */
    private function createLoginAttempt(Admin $admin): LoginAttempt
    {
        $loginAttempt = new LoginAttempt();

        $randomNumber = $this->faker->randomDigit;

        $loginAttempt->setUserAdmin($admin);
        $loginAttempt->setDate((new \DateTime())->modify("-$randomNumber day"));

        return $loginAttempt;
    }

}
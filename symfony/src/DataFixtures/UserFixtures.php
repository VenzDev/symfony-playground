<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    protected Generator $faker;

    private int $userCount = 20;

    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create();
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $myUser = $this->createUser('John', 'email@email.com', 'Zaq12wsxcde3!');

        for ($i = 0; $i < $this->userCount; $i++) {
            $user = $this->createUser();

            $manager->persist($user);
        }

        $manager->persist($myUser);

        $manager->flush();
    }

    /**
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     *
     * @return User
     */
    private function createUser(?string $name = null, ?string $email = null, ?string $password = null): User
    {
        $user = new User();

        $user->setUsername($name ?: $this->faker->name);
        $user->setEmail($email ?: $this->faker->email);
        $user->setRoles(['ROLE_USER']);

        $password = $this->passwordHasher->hashPassword($user, $password ?: $this->faker->password);

        $user->setPassword($password);

        return $user;
    }

}
<?php

declare(strict_types=1);

namespace App\Controller\Api\Registration;

use App\Controller\Api\AbstractApiController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/users", name: "api_users_post", methods: "POST")]
class RegistrationController extends AbstractApiController
{
    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->deserialize($request, User::class);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            return $this->error($errors);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());

        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->response($this->serialize($user, ['get_user']), 201);
    }
}
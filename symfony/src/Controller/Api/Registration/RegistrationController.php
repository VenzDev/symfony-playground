<?php

declare(strict_types=1);

namespace App\Controller\Api\Registration;

use App\Controller\Api\AbstractApiController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api/v1/users", name: "api_users_post", methods: "POST")]
class RegistrationController extends AbstractApiController
{
    public function __invoke(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->deserialize($request, User::class);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->error($errors);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());

        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->response($this->serialize($user, ['get_user']), 201);
    }
}
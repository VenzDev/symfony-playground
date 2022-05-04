<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/api/v1/user", name: "api_v1_")]
class UserController extends AbstractApiController
{
    #[Route('/{id}', name: 'test', methods: ["GET"])]
    public function getUserData(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->error('No user found');
        }

        return $this->response($this->serialize($user, ['get_user']));
    }

}
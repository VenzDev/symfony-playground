<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/api/v1", name: "api_v1_")]
class TestController extends AbstractApiController
{
    #[Route('/test', name: 'test', methods: ["GET"])]
    public function getSomething(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->response($this->serialize($users, ['get_user']));
    }

    #[Route('/test/post', name: 'test_post', methods: ["POST"])]
    public function setSomething(Request $request): Response
    {
        $res = $this->deserialize($request, User::class);

        return $this->response("test");
    }
}
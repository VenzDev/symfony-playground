<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/api/test', name: 'api_test', methods: ["GET"])]
    public function getSomething(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->json($users);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path'    => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/admin', name: 'app_test_admin')]
    public function admin(): Response
    {
        return $this->json([
            'message' => 'Admin View',
            'path'    => 'src/Controller/TestController.php',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Admin\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends DashboardController
{
    #[Route('/admin', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('pages/home/index.html.twig');
    }
}
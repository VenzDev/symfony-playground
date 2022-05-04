<?php

namespace App\Controller\Admin\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends DashboardController
{
    #[Route('/admin/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('pages/profile/index.html.twig');
    }
}
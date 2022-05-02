<?php

namespace App\Controller\Admin\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends DashboardController
{
    #[Route('/admin/manageAdmins', name: 'app_admins')]
    public function index(): Response
    {
        return $this->render('pages/admin/index.html.twig');
    }
}
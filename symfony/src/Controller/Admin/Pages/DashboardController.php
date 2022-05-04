<?php

declare(strict_types=1);

namespace App\Controller\Admin\Pages;

use App\Dashboard\Sidebar\Sidebar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

abstract class DashboardController extends AbstractController
{
    private Sidebar $sidebar;

    public function __construct(Sidebar $sidebar)
    {
        $this->sidebar = $sidebar;
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->sidebar->setActiveSidebar(get_called_class());

        $parameters['__sidebar'] = $this->sidebar->get();

        return parent::render($view, $parameters, $response);
    }

    /** @noinspection PhpUnused */
    #[Route('/flashes', name: 'app_flashes')]
    public function getFlashesJSON(Session $session): JsonResponse
    {
        $success = $session->getFlashBag()->get('success');
        $error = $session->getFlashBag()->get('error');

        return $this->json(['success' => $success, 'error' => $error]);
    }
}
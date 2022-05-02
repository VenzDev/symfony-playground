<?php

namespace App\Controller\Admin\Pages;

use App\Dashboard\Sidebar\Sidebar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
}
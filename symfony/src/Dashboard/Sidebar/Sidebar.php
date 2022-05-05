<?php

declare(strict_types=1);

namespace App\Dashboard\Sidebar;

use App\Controller\Admin\Pages\AdminController;
use App\Controller\Admin\Pages\HomeController;
use App\Entity\Admin;
use Symfony\Component\Security\Core\Security;

class Sidebar
{
    /* @var SidebarItem[] $sidebarItems */
    private array $sidebarItems;

    public function __construct(Security $security)
    {
        $user = $security->getUser();

        $item = new SidebarItem();
        $item->setTitle('Home');
        $item->setIcon('bi bi-house');
        $item->setOrder(0);
        $item->setPath('app_home');
        $item->setController(HomeController::class);

        $this->sidebarItems[] = $item;

        if (in_array(Admin::ROLE_SUPER_ADMIN, $user->getRoles())) {
            $item = new SidebarItem();
            $item->setTitle('Manage Admins');
            $item->setIcon('bi bi-shield-fill');
            $item->setOrder(1);
            $item->setPath('app_admins');
            $item->setController(AdminController::class);

            $this->sidebarItems[] = $item;
        }
    }

    /**
     * @return SidebarItem[]
     */
    public function get(): array
    {
        $this->order();

        return $this->sidebarItems;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function setActiveSidebar(string $className): bool
    {
        foreach ($this->sidebarItems as $item) {
            $item->setIsActive(false);

            if ($item->getController() === $className) {
                $item->setIsActive(true);
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    private function order(): bool
    {
        return usort($this->sidebarItems, function (SidebarItem $a, SidebarItem $b) {
            return $a->getOrder() > $b->getOrder();
        });
    }
}
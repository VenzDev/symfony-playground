<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Admin;

class MailMessage
{
    private Admin $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function validateToken(Admin $admin, string $hash): bool
    {
        return password_verify($admin->getEmail(), $hash);
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}
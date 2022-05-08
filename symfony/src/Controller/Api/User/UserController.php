<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/users", name: "api_users_get", methods: "GET")]
class UserController extends AbstractApiController
{
    public function __invoke(): Response
    {
        $user = $this->getUser();

        return $this->response($this->serialize($user, ['get_user']));
    }
}
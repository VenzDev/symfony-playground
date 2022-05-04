<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Admin;
use App\Entity\LoginAttempt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private Session $session;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->session = new Session();
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function onSuccessLogin()
    {
        /** @var Admin $user */
        $user = $this->security->getUser();

        $loginAttempt = new LoginAttempt();
        $loginAttempt->setDate(new \DateTime());
        $loginAttempt->setUserAdmin($user);

        $this->entityManager->persist($loginAttempt);
        $this->entityManager->flush();


        $this->session->getFlashBag()->add('success', 'Login Success');
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onSuccessLogin'];
    }

}
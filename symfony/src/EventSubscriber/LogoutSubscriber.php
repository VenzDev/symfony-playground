<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }


    public function onLogout()
    {
        $this->session->getFlashBag()->add('success', 'Logout Success');
    }

    public static function getSubscribedEvents(): array
    {
        return [ LogoutEvent::class => 'onLogout'];
    }

}
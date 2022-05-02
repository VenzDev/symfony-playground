<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }


    public function onSuccessLogin()
    {
        $this->session->getFlashBag()->add('success', 'Login Success');
    }

    public static function getSubscribedEvents(): array
    {
        return [ LoginSuccessEvent::class => 'onSuccessLogin'];
    }

}
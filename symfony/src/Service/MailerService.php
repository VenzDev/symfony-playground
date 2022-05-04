<?php

namespace App\Service;

use App\Entity\Admin;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;

class MailerService
{
    private string $routeName = 'app_admin_verify';
    private string $from = 'carparkersender@gmail.com';

    private RouterInterface $router;
    private UrlHelper $urlHelper;
    private MailerInterface $mailer;
    private Email $email;

    public function __construct(MailerInterface $mailer, UrlHelper $urlHelper, RouterInterface $router)
    {
        $this->router = $router;
        $this->urlHelper = $urlHelper;
        $this->mailer = $mailer;
        $this->email = new Email();
        $this->email->from($this->from);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendResetPasswordEmail(Admin $admin)
    {
        $this->email->to($admin->getEmail());
        $this->email->subject('Reset Password');
        $this->email->text("Click below to reset password:");

        $url = $this->generateUrl($this->generateToken($admin), $admin);

        $this->email->html("<p>Link: $url</p>");

        $this->mailer->send($this->email);
    }

    public function validateToken(Admin $admin, string $hash): bool
    {
        return password_verify($admin->getEmail(), $hash);
    }

    /**
     * @param Admin $admin
     * Only for development
     */
    private function generateToken(Admin $admin): string
    {
        return password_hash($admin->getEmail(), PASSWORD_BCRYPT);
    }


    private function generateUrl(string $token, Admin $admin): string
    {
        $route = $this->router->getRouteCollection()->get($this->routeName);

        return $this->urlHelper->getAbsoluteUrl($route->getPath().'?token='.$token.'&id='.$admin->getId());
    }
}
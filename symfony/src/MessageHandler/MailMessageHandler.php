<?php

namespace App\MessageHandler;

use App\Entity\Admin;
use App\Message\MailMessage;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;

#[AsMessageHandler]
class MailMessageHandler
{
    private string $routeName = 'app_admin_verify';
    private string $from = 'carparkersender@gmail.com';

    private MailerInterface $mailer;
    private UrlHelper $urlHelper;
    private RouterInterface $router;
    private Email $email;

    public function __construct(MailerInterface $mailer, UrlHelper $urlHelper, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->urlHelper = $urlHelper;
        $this->router = $router;
        $this->email = new Email();
        $this->email->from($this->from);
    }

    public function __invoke(MailMessage $message)
    {
        $admin = $message->getAdmin();

        $this->email->to($admin->getEmail());
        $this->email->subject('Reset Password');
        $this->email->text("Click below to reset password:");

        $url = $this->generateUrl($this->generateToken($admin), $admin);

        $this->email->html("<p>Link: $url</p>");

        $this->mailer->send($this->email);
    }

    private function generateUrl(string $token, Admin $admin): string
    {
        $route = $this->router->getRouteCollection()->get($this->routeName);

        return $this->urlHelper->getAbsoluteUrl($route->getPath().'?token='.$token.'&id='.$admin->getId());
    }

    /**
     * @param Admin $admin
     * Only for development
     */
    private function generateToken(Admin $admin): string
    {
        return password_hash($admin->getEmail(), PASSWORD_BCRYPT);
    }
}
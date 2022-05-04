<?php

namespace App\Controller\Admin\Pages;

use App\Message\SmsNotification;
use App\Repository\AdminRepository;
use App\Repository\LoginAttemptRepository;
use App\Resources\AdminGraph;
use App\Resources\AdminResources;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends DashboardController
{
    #[Route('/admin/manageAdmins', name: 'app_admins')]
    public function index(AdminResources $adminResources, MessageBusInterface $bus): Response
    {
        $bus->dispatch(new SmsNotification('Content'));

        return $this->render(
            'pages/admin/index.html.twig',
            [
                'admins'          => $adminResources->getAdminWithLastAttempt(),
                'adminsCount'     => $adminResources->getAdminCount(),
                'unverifiedCount' => $adminResources->getUnverifiedCount(),
                'blockedCount'    => $adminResources->getBlockedCount(),
                'onlineCount'     => $adminResources->getOnlineTodayCount(),
            ]
        );
    }

    #[Route('/admin/manageAdmins/block/{id}/{value}', name: 'app_admins_block')]
    public function block(int $id, bool $value, AdminRepository $adminRepository, EntityManagerInterface $entityManager): Response
    {
        $admin = $adminRepository->find($id);

        if ($admin) {
            $admin->setIsBlocked($value);
            $entityManager->flush();

            $this->addFlash('success', 'Admin updated successfully.');
        } else {
            $this->addFlash('error', 'Admin not found.');
        }

        return $this->redirectToRoute('app_admins');
    }

    #[Route('/admin/manageAdmins/delete/{id}', name: 'app_admins_remove')]
    public function delete(
        int $id,
        AdminRepository $adminRepository,
        EntityManagerInterface $entityManager,
        LoginAttemptRepository $loginAttemptRepository
    ): Response {
        $admin = $adminRepository->find($id);

        if ($admin) {
            $attempts = $loginAttemptRepository->getLoginAttemptsByAdmin($admin);

            foreach ($attempts as $attempt) {
                $entityManager->remove($attempt);
            }

            $entityManager->remove($admin);
            $entityManager->flush();

            $this->addFlash('success', 'Admin deleted successfully.');
        } else {
            $this->addFlash('error', 'Admin not found.');
        }

        return $this->redirectToRoute('app_admins');
    }

    #[Route('/admin/resetPassword/{id}', name: 'app_reset_password')]
    public function resetPassword(int $id, MailerService $mailerService, AdminRepository $adminRepository): Response
    {
        $admin = $adminRepository->find($id);

        try {
            if (!$admin) {
                throw new \Exception('Admin not found.');
            }

            $mailerService->sendResetPasswordEmail($admin);

            $this->addFlash('success', 'Email successfully sent.');
        } catch (\Exception|TransportExceptionInterface $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_admins');
    }

    #[Route('/admin/login_logs', name: 'app_login_logs_json')]
    public function getLoginLogsJSON(AdminGraph $adminGraph): JsonResponse
    {
        $data = $adminGraph->getData();

        return $this->json($data);
    }

}
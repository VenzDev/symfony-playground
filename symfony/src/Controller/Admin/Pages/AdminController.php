<?php

declare(strict_types=1);

namespace App\Controller\Admin\Pages;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepositoryInterface;
use App\Resources\AdminGraph\AdminGraphInterface;
use Exception;
use App\Message\MailMessage;
use App\Repository\AdminRepository;
use App\Repository\LoginAttemptRepository;
use App\Resources\AdminResources\AdminResourcesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends DashboardController
{
    #[Route('/admin/manageAdmins', name: 'app_admins')]
    public function index(AdminResourcesInterface $adminResources): Response
    {
        $admin = new Admin();

        $form = $this->createForm(AdminType::class, $admin);

        return $this->render(
            'pages/admin/index.html.twig',
            [
                'form' => $form->createView(),
                'admins' => $adminResources->getAdminWithLastAttempt(),
                'adminsCount' => $adminResources->getAdminCount(),
                'unverifiedCount' => $adminResources->getUnverifiedCount(),
                'blockedCount' => $adminResources->getBlockedCount(),
                'onlineCount' => $adminResources->getOnlineTodayCount(),
            ]
        );
    }

    #[Route('/admin/manageAdmins/block/{id}/{value}', name: 'app_admins_block')]
    public function block(
        int $id,
        bool $value,
        AdminRepositoryInterface $adminRepository,
        EntityManagerInterface $entityManager
    ): Response {
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
        AdminRepositoryInterface $adminRepository,
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
    public function resetPassword(int $id, AdminRepository $adminRepository, MessageBusInterface $bus): Response
    {
        $admin = $adminRepository->find($id);

        try {
            if (!$admin) {
                throw new Exception('Admin not found.');
            }

            $message = new MailMessage($admin);

            $bus->dispatch($message);

            $this->addFlash('success', 'Email successfully sent.');
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_admins');
    }

    /** @noinspection PhpUnused */
    #[Route('/admin/login_logs', name: 'app_login_logs_json')]
    public function getLoginLogsJSON(AdminGraphInterface $adminGraph): JsonResponse
    {
        $data = $adminGraph->getData();

        return $this->json($data);
    }

}
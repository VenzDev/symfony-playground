<?php

namespace App\Controller\Admin;

use App\Repository\AdminRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class VerifyController extends AbstractController
{
    #[Route('/admin/verify', name: 'app_admin_verify')]
    public function verifyAdmin(Request $request): Response
    {
        $token = $request->query->get('token');
        $id = $request->query->get('id');

        return $this->render('actions/validate.html.twig', ['token' => $token, 'id' => $id]);
    }

    #[Route('/admin/verifyPassword', name: 'app_admin_verify_password')]
    public function verifyPassword(
        Request $request,
        AdminRepository $adminRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailerService $mailerService
    ): Response {
        $id = $request->request->get('id');
        $token = $request->request->get('token');

        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');

        try {
            $admin = $adminRepository->find($id);

            if ($password != $confirmPassword) {
                throw new \Exception('Password and confirm password must be the same.');
            }

            if (!$admin) {
                throw new \Exception('Invalid verification link.');
            }

            if (!$mailerService->validateToken($admin, $token)) {
                throw new \Exception('Invalid token.');
            }

            $password = $passwordHasher->hashPassword($admin, $password);

            $admin->setPassword($password);
            $admin->setIsVerified(true);

            $entityManager->flush($admin);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->render('actions/validate.html.twig', ['token' => $token, 'id' => $id]);
        }

        $this->addFlash('success', 'Account verified, now you can login.');

        return $this->redirectToRoute('app_login');
    }

}

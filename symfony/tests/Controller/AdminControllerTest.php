<?php

namespace App\Tests\Controller;

use App\Entity\Admin;
use App\Factory\AdminFactory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private ObjectManager $em;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->em = $this->getEntityManager();
    }

    public function testAdminLogin(): void
    {
        $admin = AdminFactory::create();

        $this->em->persist($admin);
        $this->em->flush();


        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
    }

    public function testCanSuperAdminOpenManageAdminsTab(): void
    {
        $superAdmin = AdminFactory::create(true, false, Admin::ROLE_SUPER_ADMIN);
        $this->em->persist($superAdmin);
        $this->em->flush();

        $this->client->loginUser($superAdmin);

        $this->client->request('GET', '/admin/manageAdmins');
        $this->assertResponseIsSuccessful();
    }

    public function testCanAdminOpenManageAdminsTab(): void
    {
        $admin = AdminFactory::create();
        $this->em->persist($admin);
        $this->em->flush();

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/manageAdmins');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testRemoveAdmin()
    {
        $superAdmin = AdminFactory::create(true, false, Admin::ROLE_SUPER_ADMIN);
        $admin = AdminFactory::create();
        $this->em->persist($superAdmin);
        $this->em->persist($admin);

        $this->em->flush();

        $this->client->loginUser($superAdmin);

        $this->client->request('GET', "/admin/manageAdmins/delete/{$admin->getId()}");
        $this->assertResponseRedirects('/admin/manageAdmins');

        $this->assertNull($admin->getId());
    }

    private function getEntityManager(): ObjectManager
    {
        return self::$kernel->getContainer()->get('doctrine')->getManager();
    }
}

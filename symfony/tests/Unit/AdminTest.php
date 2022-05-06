<?php

namespace App\Tests;

use App\Entity\Admin;
use App\Factory\AdminFactory;
use App\Factory\LoginAttemptFactory;
use App\Resources\AdminGraph;
use App\Resources\AdminResources;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminTest extends KernelTestCase
{
    private ObjectManager $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = $this->getEntityManager();
    }

    public function testCreateAdmin(): void
    {
        $adminRepository = $this->em->getRepository(Admin::class);

        $admin = AdminFactory::create();

        $this->em->persist($admin);
        $this->em->flush();

        $this->assertCount(1, $adminRepository->findAll());
    }

    public function testAdminResources()
    {
        $container = static::getContainer();

        $admin1 = AdminFactory::create(true, true);
        $admin2 = AdminFactory::create(false, true);

        $loginAttempt1 = LoginAttemptFactory::create($admin1, 0);
        $loginAttempt2 = LoginAttemptFactory::create($admin2, 1);


        $this->em->persist($admin1);
        $this->em->persist($admin2);
        $this->em->persist($loginAttempt1);
        $this->em->persist($loginAttempt2);

        $this->em->flush();


        $adminResources = $container->get(AdminResources::class);

        $this->assertEquals(2, $adminResources->getBlockedCount());
        $this->assertEquals(1, $adminResources->getUnverifiedCount());
        $this->assertEquals(1, $adminResources->getOnlineTodayCount());
        $this->assertEquals(2, $adminResources->getAdminCount());
    }

    public function testAdminGraphData()
    {
        $container = static::getContainer();

        $admin1 = AdminFactory::create(true, true);
        $admin2 = AdminFactory::create(false, true);

        $this->em->persist($admin1);
        $this->em->persist($admin2);

        $attempt = LoginAttemptFactory::create($admin1, 0);
        $this->em->persist($attempt);

        for ($i = 0; $i < 10; $i++) {
            if ($i % 2) {
                $attempt = LoginAttemptFactory::create($admin1, $i);
            } else {
                $attempt = LoginAttemptFactory::create($admin2, $i);
            }

            $this->em->persist($attempt);
        }

        $this->em->flush();

        $graphData = $container->get(AdminGraph::class);

        $data = $graphData->getData();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('labels', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(30, $data['labels']);
        $this->assertCount(30, $data['data']);

        $values = array_count_values($data['data']);

        $this->assertEquals(1, $values[2]);
        $this->assertEquals(9, $values[1]);
        $this->assertEquals(20, $values[0]);
    }

    private function getEntityManager(): ObjectManager
    {
        return self::$kernel->getContainer()->get('doctrine')->getManager();
    }
}

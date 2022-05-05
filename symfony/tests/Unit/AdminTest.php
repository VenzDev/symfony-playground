<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $adminRepository = static::getContainer()->get(UserRepository::class);

        $this->assertCount(1, $adminRepository->findAll());
    }
}

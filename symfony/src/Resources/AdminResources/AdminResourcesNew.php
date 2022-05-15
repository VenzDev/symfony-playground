<?php

declare(strict_types=1);

namespace App\Resources\AdminResources;

use App\Entity\Admin;
use App\Entity\LoginAttempt;
use App\Repository\AdminRepository;
use App\Repository\LoginAttemptRepository;

/**
 * THIS CLASS IS ONLY FOR TESTING SYMFONY REACTION ON MULTIPLE CLASS THAT IMPLEMENTS THE SAME INTERFACE
 */
class AdminResourcesNew implements AdminResourcesInterface
{
    private AdminRepository $adminRepository;
    private LoginAttemptRepository $loginAttemptRepository;

    /** @var Admin[] */
    private array $admins;

    private int $adminCount = 0;
    private int $unverified = 0;
    private int $blocked = 0;
    private int $onlineToday = 0;

    public function __construct(
        array $params,
        LoginAttemptRepository $loginAttemptRepository,
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
        $this->loginAttemptRepository = $loginAttemptRepository;

        $this->admins = $this->prepare();
    }

    /**
     * @return Admin[]
     */
    private function prepare(): array
    {
        $admins = $this->adminRepository->getAdminsWithoutRoot();
        $loginAttempts = $this->loginAttemptRepository->getLastLoginAttemptForEachAdmin();

        $loginAttemptsIds = [];

        foreach ($loginAttempts as $loginAttempt) {
            $loginAttemptsIds[] = $loginAttempt->getUserAdmin()->getId();
        }

        foreach ($admins as $admin) {
            if (!$admin->getIsVerified()) {
                $this->unverified++;
            }
            if ($admin->getIsBlocked()) {
                $this->blocked++;
            }

            if (in_array($admin->getId(), $loginAttemptsIds)) {
                $lastAttempt = $this->getByAttemptId($loginAttempts, $admin->getId());

                if ($this->isToday($lastAttempt->getDate())) {
                    $this->onlineToday++;
                }

                $admin->setLastLoginAttempt($lastAttempt);
            }
        }

        $this->adminCount = count($admins);

        return $admins;
    }

    /**
     * @return Admin[]
     */
    public function getAdminWithLastAttempt(): array
    {
        return $this->admins;
    }

    public function getUnverifiedCount(): int
    {
        return $this->unverified;
    }

    public function getAdminCount(): int
    {
        return $this->adminCount;
    }

    public function getBlockedCount(): int
    {
        return $this->blocked;
    }

    public function getOnlineTodayCount(): int
    {
        return $this->onlineToday;
    }

    /**
     * @param Array<LoginAttempt> $loginAttempts
     * @param int $id
     *
     * @return LoginAttempt|null
     */
    private function getByAttemptId(array $loginAttempts, int $id): ?LoginAttempt
    {
        foreach ($loginAttempts as $attempt) {
            if ($attempt->getUserAdmin()->getId() == $id) {
                return $attempt;
            }
        }

        return null;
    }

    private function isToday(\DateTimeInterface $time): bool
    {
        $nowStart = new \DateTime();
        $nowStart->setTime(0, 0, 0);

        $nowEnd = new \DateTime();
        $nowEnd->setTime(23, 59, 59);

        if ($time >= $nowStart && $time <= $nowEnd) {
            return true;
        }

        return false;
    }
}
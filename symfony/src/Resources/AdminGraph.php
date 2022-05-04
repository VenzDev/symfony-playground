<?php

declare(strict_types=1);

namespace App\Resources;

use App\Repository\LoginAttemptRepository;

class AdminGraph
{
    private LoginAttemptRepository $loginAttemptRepository;

    public function __construct(LoginAttemptRepository $loginAttemptRepository)
    {
        $this->loginAttemptRepository = $loginAttemptRepository;
    }

    public function getData(): array
    {
        $loginAttempts = $this->loginAttemptRepository->getLoginAttempts();

        $labels = [];
        $data = [];

        $daysAgo = 30;

        while ($daysAgo >= 0) {
            $date = (new \DateTime())->modify("-$daysAgo day");

            $loginInThisDay = $this->getLoginInDay($loginAttempts, $date);

            $labels[] = $date->format('m-d');
            $data[] = $loginInThisDay;

            $daysAgo--;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getLoginInDay($data, \DateTime $date): int
    {
        $dateStart = clone $date;
        $dateStart->setTime(0, 0, 0);

        $dateEnd = clone $date;
        $dateEnd->setTime(23, 59, 59);

        $loginInThisDay = 0;

        foreach ($data as $d) {
            $date = $d['date'];

            if ($date >= $dateStart && $date <= $dateEnd) {
                $loginInThisDay++;
            }
        }

        return $loginInThisDay;
    }
}
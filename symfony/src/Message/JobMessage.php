<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Job;

class JobMessage
{
    private Job $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function getJob(): Job
    {
        return $this->job;
    }
}
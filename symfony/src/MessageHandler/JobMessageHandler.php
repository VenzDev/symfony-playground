<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\JobMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class JobMessageHandler
{
    public function __invoke(JobMessage $message)
    {
        //Some logic to suspend/unsuspend/terminate service
    }

}
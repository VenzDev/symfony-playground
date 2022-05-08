<?php

declare(strict_types=1);

namespace App\Controller\Api\Job;

use App\Controller\Api\AbstractApiController;
use App\Entity\User;
use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/")]
class JobController extends AbstractApiController
{
    #[Route("{serviceId}/job/{jobId}", name: "api_jobs_get_one", methods: ["GET"])]
    public function getJobOne(int $serviceId, int $jobId, JobRepository $jobRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $job = $jobRepository->getByUserAndId($user, $serviceId, $jobId);

        return $this->response($this->serialize($job));
    }


    #[Route("{serviceId}/job", name: "api_jobs_get", methods: ["GET"])]
    public function getJobs(int $serviceId, JobRepository $jobRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $jobs = $jobRepository->getByUser($user, $serviceId);

        return $this->response($this->serialize($jobs));
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Api\Service;

use App\Controller\Api\AbstractApiController;
use App\Dto\ServiceControlDto;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api/v1/")]
class ServiceController extends AbstractApiController
{
    #[Route("services/{id}", name: "api_services_get_one", methods: ["GET"])]
    public function getService(int $id, ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();

        $service = $serviceRepository->getByUserAndId($user, $id);

        if (!$service) {
            return $this->error('cannot find service');
        }

        return $this->response($this->serialize($service));
    }

    #[Route("services", name: "api_services_get", methods: ["GET"])]
    public function getServices(ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();

        $services = $serviceRepository->getByUser($user);

        return $this->response($this->serialize($services));
    }

    #[Route("services", name: "api_services_post", methods: ["POST"])]
    public function createService(Request $request, ValidatorInterface $validator): Response
    {
        /** @var Service $service */
        $service = $this->deserialize($request, Service::class);

        $errors = $validator->validate($service);

        if (count($errors) > 0) {
            return $this->error($errors);
        }

        return $this->response($this->serialize($service), 201);
    }

    #[Route("services/{id}", name: "api_services_control", methods: ["PUT"])]
    public function controlService(int $id, Request $request, ValidatorInterface $validator): Response
    {
        /** @var ServiceControlDto $serviceControl */
        $serviceControl = $this->deserialize($request, ServiceControlDto::class);

        $errors = $validator->validate($serviceControl);

        if (count($errors) > 0) {
            return $this->error($errors);
        }

        //Message Bus - Create Job and return

        return $this->response($this->serialize($serviceControl));
    }

}

<?php

declare(strict_types=1);

namespace App\Controller\Api\Service;

use App\Controller\Api\AbstractApiController;
use App\Dto\CreateServiceDto;
use App\Dto\ServiceControlDto;
use App\Entity\Job;
use App\Entity\Service;
use App\Entity\User;
use App\Message\JobMessage;
use App\Repository\ProductRepository;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/")]
class ServiceController extends AbstractApiController
{
    #[Route("services/{id}", name: "api_services_get_one", methods: ["GET"])]
    public function getService(int $id, ServiceRepository $serviceRepository): Response
    {
        /** @var User $user */
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
        /** @var User $user */
        $user = $this->getUser();

        $services = $serviceRepository->getByUser($user);

        return $this->response($this->serialize($services));
    }

    #[Route("services", name: "api_services_post", methods: ["POST"])]
    public function createService(Request $request, ProductRepository $productRepository): Response
    {
        /** @var CreateServiceDto $createService */
        $createService = $this->deserialize($request, CreateServiceDto::class);

        $errors = $this->validator->validate($createService);

        if (count($errors) > 0) {
            return $this->error($errors);
        }

        $product = $productRepository->find($createService->getProductId());

        if (!$product) {
            return $this->error('cannot find product');
        }

        /** @var User $user */
        $user = $this->getUser();

        $service = new Service();

        $service->setName($createService->getName());
        $service->setProduct($product);
        $service->setStatus(Service::STATUS_PENDING);
        $service->setOwner($user);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        return $this->response($this->serialize($service), 201);
    }

    #[Route("services/{id}", name: "api_services_control", methods: ["PUT"])]
    public function controlService(int $id, Request $request, ServiceRepository $serviceRepository, MessageBusInterface $messageBus): Response
    {
        /** @var ServiceControlDto $serviceControl */
        $serviceControl = $this->deserialize($request, ServiceControlDto::class);

        $errors = $this->validator->validate($serviceControl);

        if (count($errors) > 0) {
            return $this->error($errors);
        }

        $service = $serviceRepository->find($id);

        if (!$service) {
            return $this->error('cannot find service');
        }

        $job = new Job();
        $job->setDelay($serviceControl->getDelay() ?? 0);
        $job->setServiceOption($serviceControl->getOption());
        $job->setService($service);
        $job->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        $messageBus->dispatch(new JobMessage($job));

        return $this->response($this->serialize($job));
    }

}

<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{
    private string $format = 'json';
    private string $contentType = 'application/json';

    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function serialize(mixed $data, ?array $groups = []): string
    {
        return $this->serializer->serialize($data, $this->format, ['groups' => $groups]);
    }

    public function deserialize(Request $request, string $type): mixed
    {
        return $this->serializer->deserialize($request->getContent(), $type, $this->format);
    }

    public function response(string $data, int $code = 200): Response
    {
        return new Response($data, $code, ['Content-Type' => $this->contentType]);
    }

    public function error(mixed $message, int $code = 422): Response
    {
        return new Response(
            $this->serialize(['status' => 'error', 'result' => $message]),
            $code,
            ['Content-Type' => $this->contentType]
        );
    }
}
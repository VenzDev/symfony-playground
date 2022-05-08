<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiController extends AbstractController
{
    private string $format = 'json';
    private string $contentType = 'application/json';

    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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
        return new Response($this->serialize(['status' => 'error', 'result' => $message]), $code, ['Content-Type' => $this->contentType]);
    }
}
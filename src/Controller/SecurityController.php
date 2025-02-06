<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\LoginDto;
use App\Entity\User;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\RequestBody(content: new Model(type: LoginDto::class))]
    #[OA\Response(
        response: 200,
        description: 'Logged in successfully.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "bool", example: true)
            ],
            type: "object"
        ),
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid credentials.',
    )]
    public function login(): JsonResponse
    {
        throw new \RuntimeException('Should not be reached');
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Logged in user.',
        content: new Model(type: User::class, groups: [User::GROUP_PUBLIC])
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden',
    )]
    public function me(): JsonResponse
    {
        return new JsonResponse($this->getUser());
    }
}

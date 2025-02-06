<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Dto\UserRequestDto;
use App\Service\FusionAuthUserService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly FusionAuthUserService $fusionAuthUserService,
    ) {}

    #[Route('/users', name: 'users', methods: ['GET'])]
    #[OA\Parameter(
        name: 'search',
        description: 'Searches users by email, first name, last name, full name or username',
        in: 'query',
        schema: new OA\Schema(type: 'string'),
        example: 'email@test.com'
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserDto::class))
        )
    )]
    public function users(Request $request)
    {
        return new JsonResponse($this->fusionAuthUserService->searchUsers($request->query->get('search')));
    }

    #[Route('/users', name: 'user_create', methods: ['POST'])]
    #[OA\RequestBody(content: new Model(type: UserRequestDto::class))]
    #[OA\Response(
        response: 201,
        description: 'User created successfully',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "error", type: "string", example: "Error message")
            ],
            type: "object"
        ),
    )]
    public function userCreate(Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $userCreateDto = $serializer->deserialize(
                $request->getContent(),
                UserRequestDto::class,
                'json',
            );

            $userDto = $this->fusionAuthUserService->createUser($userCreateDto);
        } catch (ClientExceptionInterface $clientException) {
            return new JsonResponse(['error' => $clientException->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($userDto, Response::HTTP_CREATED);
    }

    #[Route('/users/{userId}', name: 'user', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
    )]
    public function user(Request $request, string $userId, SerializerInterface $serializer): JsonResponse
    {
        try {
            $userDto = $this->fusionAuthUserService->getUserById($userId);
        } catch (ResourceNotFoundException) {
            return new JsonResponse(status: Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($userDto, Response::HTTP_OK);
    }

    #[Route('/users/{userId}', name: 'user_edit', methods: ['PUT'])]
    #[OA\RequestBody(content: new Model(type: UserRequestDto::class))]
    #[OA\Response(
        response: 204,
        description: 'Successful response',
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "error", type: "string", example: "Error message")
            ],
            type: "object"
        ),
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
    )]
    public function userEdit(Request $request, string $userId, SerializerInterface $serializer): JsonResponse
    {
        try {
            $userCreateDto = $serializer->deserialize(
                $request->getContent(),
                UserRequestDto::class,
                'json',
            );

            $this->fusionAuthUserService->editUser($userCreateDto, $userId);
        } catch (ClientExceptionInterface $clientException) {
            return new JsonResponse(['error' => $clientException->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/{userId}', name: 'user_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'User deleted successfully',
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
    )]
    public function userDelete(string $userId): JsonResponse
    {
        if ($userId === $this->getUser()->getUserIdentifier()) {
            return new JsonResponse(['message' => 'Cannot delete own self'], status: Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->fusionAuthUserService->deleteUser($userId);
        } catch (ClientExceptionInterface $clientException) {
            return new JsonResponse(['error' => $clientException->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}

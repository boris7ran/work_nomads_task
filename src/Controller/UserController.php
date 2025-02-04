<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserRequestDto;
use App\Service\FusionAuthUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly FusionAuthUserService $fusionAuthUserService,
    ) {}

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function users(Request $request)
    {
        return new JsonResponse($this->fusionAuthUserService->searchUsers($request->query->get('search')));
    }

    #[Route('/users', name: 'user_create', methods: ['POST'])]
    public function userCreate(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $userCreateDto = $serializer->deserialize(
            $request->getContent(),
            UserRequestDto::class,
            'json',
        );

        $userDto = $this->fusionAuthUserService->createUser($userCreateDto);

        return new JsonResponse($userDto, Response::HTTP_CREATED);
    }

    #[Route('/users/{userId}', name: 'user', methods: ['GET'])]
    public function user(Request $request, string $userId, SerializerInterface $serializer): JsonResponse
    {
        $userDto = $this->fusionAuthUserService->getUserById($userId);

        return new JsonResponse($userDto, Response::HTTP_OK);
    }

    #[Route('/users/{userId}', name: 'user_edit', methods: ['PUT'])]
    public function userEdit(Request $request, string $userId, SerializerInterface $serializer): JsonResponse
    {
        $userCreateDto = $serializer->deserialize(
            $request->getContent(),
            UserRequestDto::class,
            'json',
        );

        $this->fusionAuthUserService->editUser($userCreateDto, $userId);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/{userId}', name: 'user_delete', methods: ['DELETE'])]
    public function userDelete(string $userId): JsonResponse
    {
        if ($userId === $this->getUser()->getUserIdentifier()) {
            return new JsonResponse(['message' => 'Cannot delete own self'], status: Response::HTTP_BAD_REQUEST);
        }

        $this->fusionAuthUserService->deleteUser($userId);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
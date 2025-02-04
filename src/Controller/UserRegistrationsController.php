<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserRegistrationRequestDto;
use App\Dto\UserRequestDto;
use App\Service\FusionAuthUserRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserRegistrationsController extends AbstractController
{
    public function __construct(
        private readonly FusionAuthUserRegistrationService $fusionAuthUserRegistrationService,
    ) {}

    #[Route('/users/registrations/{userId}', name: 'user_registration_create', methods: ['POST'])]
    public function userCreate(Request $request, string $userId, SerializerInterface $serializer): JsonResponse
    {
        $userRegistrationRequestDto = $serializer->deserialize(
            $request->getContent(),
            UserRegistrationRequestDto::class,
            'json',
        );

        $this->fusionAuthUserRegistrationService->createUserRegistration($userRegistrationRequestDto, $userId);

        return new JsonResponse([], Response::HTTP_CREATED);
    }

    #[Route('/users/registrations/{userId}/{applicationId}', name: 'user_registration_get', methods: ['GET'])]
    public function user(
        string $userId,
        string $applicationId,
    ): JsonResponse {
        try {
            $userRegistrationDto = $this->fusionAuthUserRegistrationService->getUserRegistration($userId, $applicationId);
        } catch (HttpExceptionInterface $httpException) {
            if ($httpException->getResponse()->getStatusCode() === Response::HTTP_NOT_FOUND) {
                return new JsonResponse(status: Response::HTTP_NOT_FOUND);
            } else {
                return new JsonResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($userRegistrationDto, Response::HTTP_OK);
    }

    #[Route('/users/registrations/{userId}/{applicationId}', name: 'user_registration_edit', methods: ['PUT'])]
    public function userRegistrationEdit(
        Request $request,
        string $userId,
        string $applicationId,
        SerializerInterface $serializer,
    ): JsonResponse {
        $userRegistrationRequestDto = $serializer->deserialize(
            $request->getContent(),
            UserRegistrationRequestDto::class,
            'json',
        );

        $this->fusionAuthUserRegistrationService->editUserRegistration($userRegistrationRequestDto, $userId, $applicationId);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/registrations/{userId}/{applicationId}', name: 'user_registration_delete', methods: ['DELETE'])]
    public function userDelete(string $userId, string $applicationId): JsonResponse
    {
        $this->fusionAuthUserRegistrationService->deleteUserRegistration($userId, $applicationId);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
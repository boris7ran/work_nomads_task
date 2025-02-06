<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserRegistrationDto;
use App\Dto\UserRegistrationRequestDto;
use App\Service\FusionAuthUserRegistrationService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserRegistrationsController extends AbstractController
{
    public function __construct(
        private readonly FusionAuthUserRegistrationService $fusionAuthUserRegistrationService,
    ) {}

    #[Route('/users/registrations/{userId}', name: 'user_registration_create', methods: ['POST'])]
    #[OA\RequestBody(content: new Model(type: UserRegistrationRequestDto::class))]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
    )]
    public function userCreate(Request $request, string $userId, SerializerInterface $serializer): JsonResponse
    {
        try {
            $userRegistrationRequestDto = $serializer->deserialize(
                $request->getContent(),
                UserRegistrationRequestDto::class,
                'json',
            );

            $this->fusionAuthUserRegistrationService->createUserRegistration($userRegistrationRequestDto, $userId);
        } catch (ClientExceptionInterface $clientException) {
            return new JsonResponse(['error' => $clientException->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(status: Response::HTTP_CREATED);
    }

    #[Route('/users/registrations/{userId}/{applicationId}', name: 'user_registration_get', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: UserRegistrationDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
    )]
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
    #[OA\RequestBody(content: new Model(type: UserRegistrationRequestDto::class))]
    #[OA\Response(
        response: 204,
        description: 'Successful response',
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
    )]
    public function userRegistrationEdit(
        Request $request,
        string $userId,
        string $applicationId,
        SerializerInterface $serializer,
    ): JsonResponse {
        try {
            $userRegistrationRequestDto = $serializer->deserialize(
                $request->getContent(),
                UserRegistrationRequestDto::class,
                'json',
            );

            $this->fusionAuthUserRegistrationService->editUserRegistration($userRegistrationRequestDto, $userId, $applicationId);
        } catch (ClientExceptionInterface $clientException) {
            return new JsonResponse(['error' => $clientException->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/registrations/{userId}/{applicationId}', name: 'user_registration_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'User registration deleted successfully',
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
    )]
    public function userDelete(string $userId, string $applicationId): JsonResponse
    {
        $this->fusionAuthUserRegistrationService->deleteUserRegistration($userId, $applicationId);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}

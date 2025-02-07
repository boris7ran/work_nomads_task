<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\LoginRequestDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Service\FusionAuthUserService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\RequestBody(content: new Model(type: LoginRequestDto::class))]
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
    public function login(
        Request $request,
        FusionAuthUserService $fusionAuthUserService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
    ): JsonResponse {
        $loginResponseDto = $serializer->deserialize(
            $request->getContent(),
            LoginRequestDto::class,
            'json',
        );

        $errors = $validator->validate($loginResponseDto);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => true, 'message' => 'Invalid Credentials'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $loginResponseDto = $fusionAuthUserService->loginUser($loginResponseDto);
        } catch (ClientExceptionInterface $exception) {
            return new JsonResponse(['error' => true, 'message' => $exception->getMessage()], $exception->getCode());
        };

        $response = new JsonResponse(['success' => true]);
        $response->headers->setCookie(Cookie::create('token', $loginResponseDto->token));

        if ($loginResponseDto->refreshToken) {
            $response->headers->setCookie(Cookie::create('refreshToken', $loginResponseDto->refreshToken));
        }

        return $response;
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Logged in user.',
        content: new Model(type: UserDto::class)
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden',
    )]
    public function me(FusionAuthUserService $fusionAuthUserService): JsonResponse
    {
        $userDto = $fusionAuthUserService->getUserById($this->getUser()->getUserIdentifier());

        return new JsonResponse($userDto);
    }
}

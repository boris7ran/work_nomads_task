<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\LoginRequestDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Service\FusionAuthIdentityProviderService;
use App\Service\FusionAuthUserService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    #[Route('/oauth/redirect', name: 'oauth_redirect', methods: ['GET'])]
    public function oauthRedirect(ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry->getClient('google');
        $client->setAsStateless();

        return $client->redirect(['profile', 'email', 'openid']);
    }

    #[Route('/oauth/check', name: 'oauth_check', methods: ['GET'])]
    public function oauthCheck(
        Request $request,
        FusionAuthIdentityProviderService $fusionAuthIdentityProviderService,
        UrlGeneratorInterface $urlGenerator,
        string $reactRedirectUrl,
    ) {
        $identityProviderDto = $fusionAuthIdentityProviderService->getIdentityProvider('Google');

        if (!$identityProviderDto) {
            return new JsonResponse(['error' => true, 'message' => 'No identity provider found.'], Response::HTTP_BAD_REQUEST);
        }

        $redirectUri = $urlGenerator->generate('oauth_check', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);

        try {
            $loginResponseDto = $fusionAuthIdentityProviderService->getTokenFromOAuthCode(
                $request->query->get('code'),
                $redirectUri,
                $identityProviderDto,
            );
        } catch (ClientExceptionInterface $exception) {
            return new JsonResponse(['error' => true, 'message' => $exception->getMessage()], $exception->getCode());
        };

        $response = new JsonResponse(['success' => true], status: Response::HTTP_PERMANENTLY_REDIRECT);
        $response->headers->setCookie(Cookie::create('token', $loginResponseDto->token));
        $response->headers->set('Location', $reactRedirectUrl);

        if ($loginResponseDto->refreshToken) {
            $response->headers->setCookie(Cookie::create('refreshToken', $loginResponseDto->refreshToken));
        }

        return $response;
    }
}

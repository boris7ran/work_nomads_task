<?php

namespace App\Security;

use App\Dto\LoginDto;
use App\Service\FusionAuthUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class FusionAuthAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly FusionAuthUserService $fusionAuthService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'login';
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $loginDto = $this->serializer->deserialize(
            $request->getContent(),
            LoginDto::class,
            'json',
        );

        $errors = $this->validator->validate($loginDto);

        if (count($errors) > 0) {
            throw new AuthenticationException('Invalid body params.');
        }

        try {
            $response = $this->fusionAuthService->loginUser($loginDto);

            $userData = json_decode($response->getContent(), true);
        } catch (ClientExceptionInterface $exception) {
            throw new AuthenticationException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if (!isset($userData['user']['id'])) {
            throw new AuthenticationException('User ID not found in response.');
        }

        return new SelfValidatingPassport(new UserBadge($userData['user']['id']));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse(['success' => true]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_BAD_REQUEST);
    }
}

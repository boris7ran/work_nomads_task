<?php

namespace App\Security;

use App\Service\FusionAuthUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FusionAuthAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly FusionAuthUserService $fusionAuthService,
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'login';
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (!$email || !$password) {
            throw new AuthenticationException('Email and password must be provided.');
        }

        $response = $this->fusionAuthService->loginUser($email, $password);

        $userData = json_decode($response->getContent(), true);

        if (!isset($userData['user']['id'])) {
            throw new AuthenticationException('User ID not found in response.');
        }

        return new SelfValidatingPassport(new UserBadge($userData['user']['id']));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse(['success' => 'true']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
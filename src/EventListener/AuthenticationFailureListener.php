<?php

namespace App\EventListener;

use App\Service\FusionAuthJwtService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureListener
{
    public function __construct(private readonly FusionAuthJwtService $fusionAuthJwtService)
    {}

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if ($response->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
            $token = $request->cookies->get('token');
            $refreshToken = $request->cookies->get('refreshToken');

            if ($token && $refreshToken) {
                try {
                    $refreshTokenResponseDto = $this->fusionAuthJwtService->refreshToken($token, $refreshToken);

                    $newResponse = new Response(
                        status: Response::HTTP_TEMPORARY_REDIRECT,
                        headers: ['Location' => $request->getUri()],
                    );

                    $newResponse->headers->setCookie(Cookie::create('token', $refreshTokenResponseDto->token));
                    $newResponse->headers->setCookie(Cookie::create('refreshToken', $refreshTokenResponseDto->refreshToken));

                    $event->setResponse($newResponse);
                } catch (\Exception) {
                }
            }
        }
    }
}

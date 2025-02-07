<?php

namespace App\Security;

use App\Entity\User;
use App\Service\FusionAuthJwtService;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class FusionAuthTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly FusionAuthJwtService $fusionAuthJwtService,
    ) {}

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        if (!$this->fusionAuthJwtService->validateAccessToken($accessToken)) {
            throw new AuthenticationException('Invalid token');
        }

        [, $payloadEncoded] = explode('.', $accessToken);

        $payload = json_decode(base64_decode($payloadEncoded), true);

        return new UserBadge(
            $payload['sub'],
            function (string $id) use ($payload) {

                return new User(
                    $id,
                    $payload['email'] ?? null,
                    null,
                    null,
                );
            }
        );
    }
}

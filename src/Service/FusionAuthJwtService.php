<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FusionAuthJwtService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
    ) {
    }

    public function validateAccessToken(string $accessToken): bool
    {
        $response = $this->fusionAuthClient->request(
            'GET',
            '/api/jwt/validate',
            [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                ]
            ]
        );

        return $response->getStatusCode() === 200;
    }
}
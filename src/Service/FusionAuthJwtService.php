<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\RefreshTokenResponseDto;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FusionAuthJwtService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
        private readonly SerializerInterface $serializer,
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

    public function refreshToken(string $accessToken, string $refreshToken): RefreshTokenResponseDto
    {
        $response = $this->fusionAuthClient->request(
            'POST',
            '/api/jwt/refresh',
            [
                'json' => [
                    'token' => $accessToken,
                    'refreshToken' => $refreshToken,
                ]
            ]
        );

        return $this->serializer->deserialize(
            $response->getContent(),
            RefreshTokenResponseDto::class,
            'json',
        );
    }
}
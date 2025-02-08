<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\IdentityProviderDto;
use App\Dto\IdentityProviderRequestDto;
use App\Dto\LoginResponseDto;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FusionAuthIdentityProviderService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
        private readonly SerializerInterface $serializer,
        private readonly string $fusionAuthApplicationId,
    ) {}

    public function createIdentityProvider(IdentityProviderRequestDto $identityProviderRequestDto): IdentityProviderDto
    {
        $data = $this->serializer->normalize(
            $identityProviderRequestDto,
            context: [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
        );

        $response = $this->fusionAuthClient->request(
            'POST',
            "/api/identity-provider",
            [
                'json' => $data,
            ]
        );

        return $this->serializer->deserialize(
            $response->getContent(),
            IdentityProviderDto::class,
            'json',
        );
    }

    public function getIdentityProvider(string $type): ?IdentityProviderDto
    {
        $response = $this->fusionAuthClient->request(
            'GET',
            "/api/identity-provider?type=$type",
        );

        if ($response->getStatusCode() !== 200) {
            return null;
        } else {
            return $this->serializer->deserialize(
                $response->getContent(),
                IdentityProviderDto::class,
                'json',
            );
        }
    }

    public function getTokenFromOAuthCode(string $code, string $redirectUri, IdentityProviderDto $identityProviderDto): LoginResponseDto
    {
        $response = $this->fusionAuthClient->request(
            'POST',
            '/api/identity-provider/login',
            [
                'json' => [
                    'applicationId' => $this->fusionAuthApplicationId,
                    'identityProviderId' => $identityProviderDto->id,
                    'data' => [
                        'code' => $code,
                        'redirect_uri' => $redirectUri,
                    ]
                ]
            ]
        );

        return $this->serializer->deserialize(
            $response->getContent(),
            LoginResponseDto::class,
            'json',
        );
    }
}
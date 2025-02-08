<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ApplicationDto;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FusionAuthApplicationService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
        private readonly SerializerInterface $serializer,
    ) {}

    /**
     * @return ApplicationDto[]
     */
    public function getApplications(): array
    {
        $response = $this->fusionAuthClient->request(
            'GET',
            '/api/application/search',
        );

        return $this->serializer->deserialize(
            $response->getContent(),
            sprintf('%s[]', ApplicationDto::class),
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[applications]',
            ]
        );
    }

    public function getApplication(string $applicationId): ApplicationDto
    {
        $response = $this->fusionAuthClient->request(
            'GET',
            "/api/application/{$applicationId}",
        );

        return $this->serializer->deserialize(
            $response->getContent(),
            ApplicationDto::class,
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[application]',
            ]
        );
    }
}
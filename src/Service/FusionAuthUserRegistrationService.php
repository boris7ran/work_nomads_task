<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRegistrationDto;
use App\Dto\UserRegistrationRequestDto;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FusionAuthUserRegistrationService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
        private readonly SerializerInterface $serializer,
    ) {}

    public function getUserRegistration(string $userId, string $applicationId): UserRegistrationDto
    {
        $response = $this->fusionAuthClient->request('GET', "/api/user/registration/{$userId}/{$applicationId}");

        return $this->serializer->deserialize(
            $response->getContent(),
            UserRegistrationDto::class,
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[registration]',
            ]
        );
    }

    public function createUserRegistration(
        UserRegistrationRequestDto $userRegistrationRequestDto,
        string $userId,
    ): void {
        $data = $this->serializer->normalize(
            $userRegistrationRequestDto,
            context: [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
        );

        $this->fusionAuthClient->request(
            'POST',
            "/api/user/registration/$userId",
            [
                'json' => $data,
            ]
        );
    }

    public function editUserRegistration(
        UserRegistrationRequestDto $userRegistrationRequestDto,
        string $userId,
        string $applicationId,
    ): void {
        $data = $this->serializer->normalize(
            $userRegistrationRequestDto,
            context: [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
        );

        $this->fusionAuthClient->request(
            'PUT',
            "/api/user/registration/$userId/$applicationId",
            [
                'json' => $data,
            ]
        );
    }

    public function deleteUserRegistration(string $userId, string $applicationId): void
    {
        $this->fusionAuthClient->request(
            'DELETE',
            "/api/user/registration/$userId/$applicationId",
        );
    }
}

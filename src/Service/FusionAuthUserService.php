<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRequestDto;
use App\Dto\UserDto;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FusionAuthUserService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function loginUser(string $email, string $password): ResponseInterface
    {
        return $this->fusionAuthClient->request('POST', '/api/login', [
            'json' => [
                'loginId' => $email,
                'password' => $password,
            ],
        ]);
    }

    public function getUserById(string $userId): UserDto
    {
        $response = $this->fusionAuthClient->request('GET', "/api/user/{$userId}");

        $userDto = $this->serializer->deserialize(
            $response->getContent(),
            UserDto::class,
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[user]',
            ]
        );

        $errors = $this->validator->validate($userDto);

        if (count($errors) > 0) {
            throw new \Exception('Invalid user response returned');
        }

        return $userDto;
    }

    /**
     * @param string|null $searchQuery
     *
     * @return UserDto[]
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function searchUsers(string $searchQuery = null): array
    {
        $response = $this->fusionAuthClient->request(
            'GET',
            '/api/user/search',
            [
                'query' => [
                    'queryString' => $searchQuery ?: '*',
                ],
            ]
        );

        $userDtos = $this->serializer->deserialize(
            $response->getContent(),
            sprintf('%s[]', UserDto::class),
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[users]',
            ]
        );

        $errors = $this->validator->validate($userDtos);

        if (count($errors) > 0) {
            throw new \Exception('Invalid user response returned');
        }

        return $userDtos;
    }

    public function createUser(UserRequestDto $userCreateDto): UserDto
    {
        $response = $this->fusionAuthClient->request(
            'POST',
            '/api/user',
            [
                'json' => $this->serializer->normalize($userCreateDto),
            ]
        );

        $userDto = $this->serializer->deserialize(
            $response->getContent(),
            UserDto::class,
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[user]',
            ]
        );

        $errors = $this->validator->validate($userDto);

        if (count($errors) > 0) {
            throw new \Exception('Invalid user response returned');
        }

        return $userDto;
    }

    public function editUser(UserRequestDto $userRequestDto, string $userId): UserDto
    {
        $response = $this->fusionAuthClient->request(
            'PUT',
            "/api/user/$userId",
            [
                'json' => $this->serializer->normalize($userRequestDto),
            ]
        );

        $userDto = $this->serializer->deserialize(
            $response->getContent(),
            UserDto::class,
            'json',
            [
                UnwrappingDenormalizer::UNWRAP_PATH => '[user]',
            ]
        );

        $errors = $this->validator->validate($userDto);

        if (count($errors) > 0) {
            throw new \Exception('Invalid user response returned');
        }

        return $userDto;
    }

    public function deleteUser(string $userId): void
    {
        $this->fusionAuthClient->request(
            'DELETE',
            "/api/user/$userId",
            [
                'query' => ['hardDelete' => 'true'],
            ],
        );
    }
}

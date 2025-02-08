<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[SerializedPath('[registration][applicationId]')]
        #[OA\Property(example: '27b05353-f723-4eb7-b8c2-d6d0c2815949')]
        public string $applicationId,
        #[SerializedPath('[registration][username]')]
        #[OA\Property(example: 'username')]
        public ?string $username = null,
        #[SerializedPath('[registration][timezone]')]
        #[OA\Property(example: 'username')]
        public ?string $timezone = null,
        #[SerializedPath('[registration][data]')]
        #[OA\Property(example: [])]
        public ?array $data = null,
        #[SerializedPath('[registration][generateAuthenticationToken]')]
        public ?bool $generateAuthenticationToken = null,
        #[SerializedPath('[registration][authenticationToken]')]
        public ?bool $authenticationToken = null,
        #[SerializedPath('[registration][preferredLanguages]')]
        #[OA\Property(example: ['en', 'de'])]
        public ?array $preferredLanguages = null,
        #[SerializedPath('[registration][roles]')]
        #[OA\Property(example: ['admin'])]
        public ?array $roles = null,
    ) {}
}

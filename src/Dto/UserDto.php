<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    public function __construct(
        #[Assert\Uuid]
        #[OA\Property(example: '27b05353-f723-4eb7-b8c2-d6d0c2815949')]
        public string $id,
        #[Assert\NotBlank]
        #[Assert\Email]
        #[OA\Property(example: 'email@test.com')]
        public string $email,
        #[OA\Property(example: 'First')]
        public ?string $firstName = null,
        #[OA\Property(example: 'Last')]
        public ?string $lastName = null,
        #[OA\Property(example: '1989-12-12')]
        public ?string $birthDate = null,
    ) {}
}

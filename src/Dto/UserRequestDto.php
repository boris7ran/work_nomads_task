<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class UserRequestDto
{
    public function __construct(
        #[SerializedPath('[user][email]')]
        #[OA\Property(example: 'email@test.com')]
        public string $email,
        #[SerializedPath('[user][password]')]
        #[OA\Property(minimum: 8, example: 'secret123!')]
        #[Assert\Length(min: 8)]
        public ?string $password = null,
        #[SerializedPath('[user][firstName]')]
        #[OA\Property(example: 'First')]
        public ?string $firstName = null,
        #[SerializedPath('[user][lastName]')]
        #[OA\Property(example: 'Last')]
        public ?string $lastName = null,
        #[Assert\Date]
        #[SerializedPath('[user][birthDate]')]
        #[OA\Property(example: '1989-12-12')]
        public ?string $birthDate = null,
        #[SerializedPath('[user][mobilePhone]')]
        #[OA\Property(example: '+123456789')]
        public ?string $mobilePhone = null,
    ){}
}

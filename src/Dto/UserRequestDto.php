<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class UserRequestDto
{
    public function __construct(
        #[SerializedPath('[user][email]')]
        public ?string $email = null,
        #[SerializedPath('[user][firstName]')]
        public ?string $firstName = null,
        #[SerializedPath('[user][lastName]')]
        public ?string $lastName = null,
        #[SerializedPath('[user][password]')]
        public ?string $password = null,
        #[SerializedPath('[user][birthDate]')]
        public ?string $birthDate = null,
        #[SerializedPath('[user][mobilePhone]')]
        public ?string $mobilePhone = null,
    ){}
}
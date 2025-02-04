<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $birthDate = null,
    ) {}
}

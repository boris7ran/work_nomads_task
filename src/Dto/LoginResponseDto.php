<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginResponseDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $token,
        #[Assert\NotBlank]
        public int $tokenExpirationInstant,
        public UserDto $user,
        public ?string $refreshToken = null,
    ) {}
}

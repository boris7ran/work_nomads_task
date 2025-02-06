<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginDto
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {}
}

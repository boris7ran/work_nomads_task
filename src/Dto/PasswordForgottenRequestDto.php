<?php

declare(strict_types=1);

namespace App\Dto;

class PasswordForgottenRequestDto
{
    public function __construct(
        public readonly string $userEmail,
    ) {}
}
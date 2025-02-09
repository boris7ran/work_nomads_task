<?php

declare(strict_types=1);

namespace App\Dto;

class PasswordForgottenDto
{
    public function __construct(
        public readonly string $changePasswordId,
    ) {}
}
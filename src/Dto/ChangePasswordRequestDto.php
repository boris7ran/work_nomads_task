<?php

declare(strict_types=1);

namespace App\Dto;

class ChangePasswordRequestDto
{
    public function __construct(
        public readonly string $changePasswordId,
        public readonly string $password,
    ) {}
}
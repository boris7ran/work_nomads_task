<?php

declare(strict_types=1);

namespace App\Dto;

class RefreshTokenResponseDto
{
    public function __construct(
        public readonly string $token,
        public readonly string $refreshToken,
    ) {}
}
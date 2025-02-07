<?php

declare(strict_types=1);

namespace App\Dto;

class WebhookRequestDto
{
    public function __construct(
        public string $url,
        public array $eventsEnabled,
        public int $connectTimeout = 1000,
        public int $readTimeout = 2000,
        public ?array $headers = null,
        public bool $global = false,
        public ?string $description = null,
    ) {}
}
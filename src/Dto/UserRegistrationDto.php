<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class UserRegistrationDto
{
    public function __construct(
        public string $id,
        public string $applicationId,
        public ?array $data = null,
        public ?array $roles = [],
        public ?array $token = [],
        public ?string $usernameStatus = null,
        public ?array $preferredLanguages = [],
        public ?string $timezone = null,
        public ?bool $verified = null,
        #[Context(
            normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'd/m/Y H:i'],
            denormalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'U'],
        )]
        public ?DateTimeImmutable $insertInstant = null,
        #[Context(
            normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'd/m/Y H:i'],
            denormalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'U'],
        )]
        public ?DateTimeImmutable $lastLoginInstant = null,
        #[Context(
            normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'd/m/Y H:i'],
            denormalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'U'],
        )]
        public ?DateTimeImmutable $lastUpdateInstant = null,
        #[Context(
            normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'd/m/Y H:i'],
            denormalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'U'],
        )]
        public ?DateTimeImmutable $verifiedInstant = null,
    ) {}
}

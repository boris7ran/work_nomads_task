<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[SerializedPath('[registration][applicationId]')]
        public string $applicationId,
        #[SerializedPath('[registration][data]')]
        public ?array $data = null,
        #[SerializedPath('[registration][generateAuthenticationToken]')]
        public ?bool $generateAuthenticationToken = null,
        #[SerializedPath('[registration][authenticationToken]')]
        public ?bool $authenticationToken = null,
        #[SerializedPath('[registration][preferredLanguages]')]
        public ?array $preferredLanguages = null,
        #[SerializedPath('[registration][roles]')]
        public ?array $roles = null,
    ) {}
}

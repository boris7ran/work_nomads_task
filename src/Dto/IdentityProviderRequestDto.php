<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class IdentityProviderRequestDto
{
    public function __construct(
        #[SerializedPath('[identityProvider][name]')]
        public string $name,
        #[SerializedPath('[identityProvider][client_id]')]
        public string $clientId,
        #[SerializedPath('[identityProvider][client_secret]')]
        public string $clientSecret,
        #[SerializedPath('[identityProvider][scope]')]
        public string $scope,
        #[SerializedPath('[identityProvider][type]')]
        public string $type,
        #[SerializedPath('[identityProvider][enabled]')]
        public bool $enabled = true,
    ) {}
}
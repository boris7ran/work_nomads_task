<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class IdentityProviderDto
{
    public function __construct(
        #[SerializedPath('[identityProviders][0][id]')]
        public string $id,
    ) {}
}
<?php

declare(strict_types=1);

namespace App\Webhook;

use App\Dto\UserDto;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Serializer\Attribute\SerializedPath;

class FusionAuthEvent extends RemoteEvent
{
    public function __construct(
        #[SerializedPath('[event][id]')]
        public string $id,
        #[SerializedPath('[event][type]')]
        public string $type,
        #[SerializedPath('[event][user]')]
        public UserDto $userDto,
        public string $name = 'FusionAuthEvent',
    ) {
        parent::__construct($name, $id, []);
    }
}
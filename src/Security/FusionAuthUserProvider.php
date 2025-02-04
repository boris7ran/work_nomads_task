<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Service\FusionAuthUserService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FusionAuthUserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly FusionAuthUserService $fusionAuthUserService,
    ) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userDto = $this->fusionAuthUserService->getUserById($identifier);

        return new User(
            $userDto->id,
            $userDto->email,
            $userDto->firstName,
            $userDto->lastName,
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException("Invalid user class.");
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}

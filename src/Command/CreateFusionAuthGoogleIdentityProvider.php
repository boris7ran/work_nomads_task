<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\IdentityProviderRequestDto;
use App\Service\FusionAuthIdentityProviderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create-fusionauth-google-idp', description: 'Create Google Identity Provider on FusionAuth')]
class CreateFusionAuthGoogleIdentityProvider extends Command
{
    public function __construct(
        private readonly FusionAuthIdentityProviderService $fusionAuthIdentityProviderService,
        private readonly string $googleClientId,
        private readonly string $googleClientSecret,
        ?string $name = 'create fusion auth google idp',
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $identityProviderDto = new IdentityProviderRequestDto(
            'Google IDP',
            $this->googleClientId,
            $this->googleClientSecret,
            'openid profile email',
            'Google',
        );

        try {
            $this->fusionAuthIdentityProviderService->createIdentityProvider($identityProviderDto);
        } catch (\Exception) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
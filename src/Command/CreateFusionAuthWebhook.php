<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\WebhookRequestDto;
use App\Enum\FusionAuthEventsEnum;
use App\Service\FusionAuthWebhookService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create-fusionauth-webhook', description: 'Create a FusionAuth webhook')]
class CreateFusionAuthWebhook extends Command
{
    const WEBHOOK_URL = 'http://nginx:80/webhook/fusionauth';

    public function __construct(
        private readonly FusionAuthWebhookService $fusionAuthWebhookService,
        ?string $name = 'create fusion auth webhook',
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $webhookRequestDto = new WebhookRequestDto(
            static::WEBHOOK_URL,
            [
                FusionAuthEventsEnum::UserCreate->value => true,
                FusionAuthEventsEnum::UserLoginFailed->value => true,
            ],
            global: true,
        );

        try {
            $this->fusionAuthWebhookService->createWebhook($webhookRequestDto);
        } catch (\Exception) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
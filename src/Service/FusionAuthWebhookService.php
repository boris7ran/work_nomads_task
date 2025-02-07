<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\WebhookRequestDto;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FusionAuthWebhookService
{
    public function __construct(
        private readonly HttpClientInterface $fusionAuthClient,
        private readonly SerializerInterface $serializer,
    ) {}

    public function createWebhook(WebhookRequestDto $webhookRequestDto)
    {
        $this->fusionAuthClient->request('POST', '/api/webhook', [
            'json' => [
                'webhook' => $this->serializer->normalize(
                    $webhookRequestDto,
                    context: [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
                )
            ]
        ]);
    }
}
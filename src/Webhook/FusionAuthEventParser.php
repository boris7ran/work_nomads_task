<?php

declare(strict_types=1);

namespace App\Webhook;

use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Webhook\Client\AbstractRequestParser;

class FusionAuthEventParser extends AbstractRequestParser
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {}

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new IsJsonRequestMatcher(),
            new MethodRequestMatcher('POST'),
        ]);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): FusionAuthEvent
    {
        return $this->serializer->deserialize(
            $request->getContent(),
            FusionAuthEvent::class,
            'json',
        );
    }
}
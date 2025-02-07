<?php

declare(strict_types=1);

namespace App\Webhook;

use App\Enum\FusionAuthEventsEnum;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('fusionauth')]
class FusionAuthListener implements ConsumerInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {}

    public function consume(RemoteEvent $event): void
    {
        if (!($event instanceof FusionAuthEvent)) {
            throw new \Exception('Invalid event provided');
        }

        match ($event->type) {
            FusionAuthEventsEnum::UserCreate->value => $this->handleUserCreatedEvent($event),
            FusionAuthEventsEnum::UserLoginFailed->value => $this->handleUserLoginFailedEvent($event),
            default => throw new \Exception('Invalid event type'),
        };
    }

    private function handleUserCreatedEvent(FusionAuthEvent $event)
    {
        $email = (new Email())
            ->from('task@example.com')
            ->to($event->userDto->email)
            ->subject('User Created')
            ->text('User Created Succesfully')
            ->html("<p>Dear {$event->userDto->firstName}</p><p>Your account is created successfully</p>")
        ;

        $this->mailer->send($email);
    }

    private function handleUserLoginFailedEvent(FusionAuthEvent $event)
    {
        $email = (new Email())
            ->from('task@example.com')
            ->to($event->userDto->email)
            ->subject('Login Failed')
            ->text('Login Failed')
            ->html("<p>Login failed attempt</p>")
        ;

        $this->mailer->send($email);
    }
}
<?php

namespace App\Telegram;

use Throwable;

final class FakeTelegramClient implements TelegramClient
{
    /** @var list<array{token: string, url: string, secret: string}> */
    public array $webhookRegistrations = [];

    public function __construct(
        private readonly ?BotProfile $profile = null,
        private readonly ?Throwable $validationFailure = null,
        private ?WebhookInfo $webhookInfo = null,
        private readonly ?Throwable $webhookFailure = null,
    ) {}

    public function validateToken(string $token): BotProfile
    {
        if ($this->validationFailure !== null) {
            throw $this->validationFailure;
        }

        return $this->profile ?? throw new InvalidTelegramToken;
    }

    public function setWebhook(string $token, string $url, string $secret): void
    {
        if ($this->webhookFailure !== null) {
            throw $this->webhookFailure;
        }

        $this->webhookRegistrations[] = compact('token', 'url', 'secret');
        $this->webhookInfo ??= new WebhookInfo($url);
    }

    public function getWebhookInfo(string $token): WebhookInfo
    {
        if ($this->webhookFailure !== null) {
            throw $this->webhookFailure;
        }

        return $this->webhookInfo ?? new WebhookInfo('');
    }
}

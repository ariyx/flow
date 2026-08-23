<?php

namespace App\Telegram;

use Throwable;

final class FakeTelegramClient implements TelegramClient
{
    public function __construct(
        private readonly ?BotProfile $profile = null,
        private readonly ?Throwable $failure = null,
    ) {}

    public function validateToken(string $token): BotProfile
    {
        if ($this->failure !== null) {
            throw $this->failure;
        }

        return $this->profile ?? throw new InvalidTelegramToken;
    }
}

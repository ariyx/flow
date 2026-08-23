<?php

namespace App\Telegram;

final readonly class BotProfile
{
    public function __construct(
        public string $telegramId,
        public ?string $username,
        public string $displayName,
    ) {}
}

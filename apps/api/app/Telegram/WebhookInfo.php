<?php

namespace App\Telegram;

final readonly class WebhookInfo
{
    public function __construct(
        public string $url,
        public ?string $lastErrorMessage = null,
    ) {}
}

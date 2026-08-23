<?php

namespace App\Telegram;

use App\Models\Bot;

final class BotWebhookConfigurator
{
    public function configure(Bot $bot, TelegramClient $telegram): void
    {
        $bot->forceFill([
            'webhook_url' => $this->urlFor($bot),
            'webhook_secret' => bin2hex(random_bytes(32)),
            'webhook_status' => 'not_configured',
            'webhook_error' => null,
            'webhook_checked_at' => null,
        ]);

        $telegram->setWebhook($bot->token, $bot->webhook_url, $bot->webhook_secret);
        $this->refresh($bot, $telegram);
    }

    public function refresh(Bot $bot, TelegramClient $telegram): void
    {
        $info = $telegram->getWebhookInfo($bot->token);
        $status = match (true) {
            $info->url === '' => 'not_configured',
            $info->url !== $bot->webhook_url => 'mismatch',
            $info->lastErrorMessage !== null => 'error',
            default => 'healthy',
        };

        $bot->forceFill([
            'webhook_status' => $status,
            'webhook_error' => $info->lastErrorMessage,
            'webhook_checked_at' => now(),
        ]);
    }

    private function urlFor(Bot $bot): string
    {
        $baseUrl = config('services.telegram.webhook_base_url');
        $parts = is_string($baseUrl) ? parse_url($baseUrl) : false;
        $port = $parts['port'] ?? 443;

        if (! is_array($parts) || ($parts['scheme'] ?? null) !== 'https' || ! isset($parts['host']) || ! in_array($port, [80, 88, 443, 8443], true)) {
            throw new InvalidWebhookConfiguration('A public HTTPS Telegram webhook base URL is required.');
        }

        return rtrim($baseUrl, '/').'/telegram/webhooks/'.$bot->id;
    }
}

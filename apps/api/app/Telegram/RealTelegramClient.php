<?php

namespace App\Telegram;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class RealTelegramClient implements TelegramClient
{
    public function validateToken(string $token): BotProfile
    {
        try {
            $response = Http::acceptJson()->get(sprintf('https://api.telegram.org/bot%s/getMe', $token));
        } catch (ConnectionException) {
            throw new TelegramApiException;
        }

        /** @var array{ok?: bool, error_code?: int, result?: array{id?: int|string, username?: string, first_name?: string}} $payload */
        $payload = $response->json();

        if (($payload['ok'] ?? false) !== true) {
            if (($payload['error_code'] ?? null) === 401) {
                throw new InvalidTelegramToken;
            }

            throw new TelegramApiException;
        }

        $bot = $payload['result'] ?? [];

        if (! isset($bot['id'], $bot['first_name'])) {
            throw new TelegramApiException;
        }

        return new BotProfile(
            telegramId: (string) $bot['id'],
            username: isset($bot['username']) ? (string) $bot['username'] : null,
            displayName: (string) $bot['first_name'],
        );
    }
}

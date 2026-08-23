<?php

namespace App\Telegram;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class RealTelegramClient implements TelegramClient
{
    public function validateToken(string $token): BotProfile
    {
        try {
            $response = Http::acceptJson()->get($this->endpoint($token, 'getMe'));
        } catch (ConnectionException) {
            throw new TelegramApiException;
        }

        /** @var array{ok?: bool, error_code?: int, result?: array{id?: int|string, username?: string, first_name?: string}} $payload */
        $payload = $response->json();
        $bot = $this->result($payload);

        if (! isset($bot['id'], $bot['first_name'])) {
            throw new TelegramApiException;
        }

        return new BotProfile(
            telegramId: (string) $bot['id'],
            username: isset($bot['username']) ? (string) $bot['username'] : null,
            displayName: (string) $bot['first_name'],
        );
    }

    public function setWebhook(string $token, string $url, string $secret): void
    {
        $result = $this->post($token, 'setWebhook', ['url' => $url, 'secret_token' => $secret]);

        if ($result !== true) {
            throw new TelegramApiException;
        }
    }

    public function getWebhookInfo(string $token): WebhookInfo
    {
        $result = $this->post($token, 'getWebhookInfo', []);

        if (! is_array($result) || ! isset($result['url']) || ! is_string($result['url'])) {
            throw new TelegramApiException;
        }

        return new WebhookInfo(
            url: $result['url'],
            lastErrorMessage: isset($result['last_error_message']) ? (string) $result['last_error_message'] : null,
        );
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function post(string $token, string $method, array $parameters): mixed
    {
        try {
            $response = Http::acceptJson()->post($this->endpoint($token, $method), $parameters);
        } catch (ConnectionException) {
            throw new TelegramApiException;
        }

        /** @var array{ok?: bool, error_code?: int, result?: mixed} $payload */
        $payload = $response->json();

        return $this->result($payload);
    }

    /**
     * @param  array{ok?: bool, error_code?: int, result?: mixed}  $payload
     */
    private function result(array $payload): mixed
    {
        if (($payload['ok'] ?? false) !== true) {
            if (($payload['error_code'] ?? null) === 401) {
                throw new InvalidTelegramToken;
            }

            throw new TelegramApiException;
        }

        return $payload['result'] ?? null;
    }

    private function endpoint(string $token, string $method): string
    {
        return sprintf('https://api.telegram.org/bot%s/%s', $token, $method);
    }
}

<?php

namespace App\Telegram;

interface TelegramClient
{
    /**
     * @throws InvalidTelegramToken
     * @throws TelegramApiException
     */
    public function validateToken(string $token): BotProfile;

    /**
     * @throws InvalidTelegramToken
     * @throws TelegramApiException
     */
    public function setWebhook(string $token, string $url, string $secret): void;

    /**
     * @throws InvalidTelegramToken
     * @throws TelegramApiException
     */
    public function getWebhookInfo(string $token): WebhookInfo;
}

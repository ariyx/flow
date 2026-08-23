<?php

namespace App\Telegram;

interface TelegramClient
{
    /**
     * @throws InvalidTelegramToken
     * @throws TelegramApiException
     */
    public function validateToken(string $token): BotProfile;
}

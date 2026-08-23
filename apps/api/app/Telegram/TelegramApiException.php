<?php

namespace App\Telegram;

use RuntimeException;

final class TelegramApiException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Telegram could not validate the bot token.');
    }
}

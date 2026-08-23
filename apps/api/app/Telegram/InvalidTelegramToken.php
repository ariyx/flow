<?php

namespace App\Telegram;

use RuntimeException;

final class InvalidTelegramToken extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The Telegram bot token is invalid.');
    }
}

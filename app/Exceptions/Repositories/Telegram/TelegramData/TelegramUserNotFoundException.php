<?php

namespace App\Exceptions\Repositories\Telegram\TelegramData;

use App\Exceptions\CheckedException;

class TelegramUserNotFoundException extends CheckedException
{
    protected string $messageFormat = 'User not found in telegram response data';
}

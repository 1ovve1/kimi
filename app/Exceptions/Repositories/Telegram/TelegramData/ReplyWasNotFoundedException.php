<?php

namespace App\Exceptions\Repositories\Telegram\TelegramData;

use App\Exceptions\CheckedException;
use Exception;

class ReplyWasNotFoundedException extends CheckedException
{
    protected string $messageFormat = "Reply message was not founded";
}

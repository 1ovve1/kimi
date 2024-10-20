<?php

namespace App\Exceptions\Repositories\Telegram\TelegramData;

use App\Exceptions\CheckedException;

class TelegramChatUserWasNotFounded extends CheckedException
{
    protected string $messageFormat = "Telegram chat user was not founded (maybe that's a private chat?)";
}

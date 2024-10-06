<?php

namespace App\Exceptions\Repositories\Telegram\User;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\UserData;
use App\Exceptions\CheckedException;

class UserNotFoundInGivenChatException extends CheckedException
{
    protected string $messageFormat = "User '%s' was not founded in given chat:\n%s\nUser:\n%s";

    public function __construct(ChatData $chatData, UserData $userData)
    {
        parent::__construct($this->formatMessage($userData->first_name, $this->print($chatData), $this->print($userData)));
    }
}

<?php

namespace App\Exceptions\Repositories\Telegram;

use App\Data\Telegram\UserData;
use App\Exceptions\CheckedException;

class UserNotFoundException extends CheckedException
{
    protected string $messageFormat = "User '%s' was not founded by given credentials:\n%s";

    public function __construct(UserData $userData)
    {
        parent::__construct($this->formatMessage($userData->id, $this->print($userData)));
    }
}

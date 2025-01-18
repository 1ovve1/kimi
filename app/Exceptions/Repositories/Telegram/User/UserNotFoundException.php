<?php

namespace App\Exceptions\Repositories\Telegram\User;

use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\UserData;
use App\Exceptions\CheckedException;

class UserNotFoundException extends CheckedException
{
    protected string $messageFormat = "User '%s' was not founded by given credentials:\n%s";

    public function __construct(UserData|PrivateData $userData)
    {
        parent::__construct($this->formatMessage($userData->tg_id, $this->print($userData)));
    }
}

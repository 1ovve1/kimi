<?php

namespace App\Exceptions\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\ChatData;
use App\Exceptions\CheckedException;

class ChatNotFoundException extends CheckedException
{
    protected string $messageFormat = "Chat with tg_id '%s' was not founded. Chat data given:\n%s";

    public function __construct(ChatData $chatData)
    {
        parent::__construct($this->formatMessage($chatData->target->tg_id, $this->print($chatData->toArray())));
    }
}

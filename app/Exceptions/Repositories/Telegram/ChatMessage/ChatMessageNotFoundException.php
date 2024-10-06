<?php

namespace App\Exceptions\Repositories\Telegram\ChatMessage;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\CheckedException;

class ChatMessageNotFoundException extends CheckedException
{
    protected string $messageFormat = "Chat message with given tg_id (%s) was not founded:\n%s";

    public function __construct(ChatMessageData $chatMessageData)
    {
        parent::__construct($this->formatMessage($chatMessageData->tg_id, $this->print($chatMessageData->toArray())));
    }
}

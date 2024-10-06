<?php

namespace App\Exceptions\Repositories\Telegram\ChatMessage;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\CheckedException;

class ChatMessageAlreadyExistsException extends CheckedException
{
    protected string $messageFormat = 'Chat message with id already exists in db (%s)';

    public function __construct(ChatMessageData $chatMessageData)
    {
        parent::__construct($this->formatMessage($chatMessageData->id));
    }
}

<?php

namespace App\Exceptions\Repositories\Telegram;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\CheckedException;
use App\Models\ChatMessage;
use Exception;
use Throwable;

class ChatMessageNotFoundException extends CheckedException
{
    protected string $messageFormat = "Chat message with given id (%s% was not founded:\n%s";

    public function __construct(ChatMessageData $chatMessageData)
    {
        parent::__construct($this->formatMessage($chatMessageData->id, $this->print($chatMessageData->toArray())));
    }
}

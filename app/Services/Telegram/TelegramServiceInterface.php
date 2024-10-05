<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;

interface TelegramServiceInterface
{
    public function replyToMessage(string $content, ?ChatMessageData $chatMessageData = null): ChatMessageData;

    public function sendMessage(string $content, ?ChatData $chatData = null): ChatMessageData;

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function replyToMessageAndSave(string $content, ?ChatMessageData $chatMessageData = null): ChatMessageData;

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function sendMessageAndSave(string $content, ?CHatData $chatData = null): ChatMessageData;

    public function deleteMessage(ChatMessageData $chatMessageData, ?ChatData $chatData = null): void;
}

<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Telegram\Abstract\Keyboards\TelegramKeyboard;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;

interface TelegramServiceInterface
{
    public function sendMessage(string $content, ?ChatData $chatData = null): ChatMessageData;

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function sendMessageAndSave(string $content, ?CHatData $chatData = null): ChatMessageData;

    public function replyToMessage(string $content, ?ChatMessageData $chatMessageData = null, ?ChatData $chatData = null): ChatMessageData;

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function replyToMessageAndSave(string $content, ?ChatMessageData $chatMessageData = null, ?ChatData $chatData = null): ChatMessageData;

    public function sendMessageWithKeyboard(TelegramKeyboardInterface $telegramKeyboard, ?string $content = null, ?ChatData $chatData = null): ChatMessageData;

    public function updateKeyboard(TelegramKeyboard $telegramKeyboard, ?ChatMessageData $chatMessageData = null): void;

    public function deleteMessage(?ChatMessageData $chatMessageData = null, ?ChatData $chatData = null): void;
}

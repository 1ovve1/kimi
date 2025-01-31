<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;

interface ChatServiceInterface
{
    public function dryAnswer(string $question): DialogMessageData;

    public function answer(ChatData $chatData, ChatMessageData $chatMessageData): DialogMessageData;

    /**
     * @throws ChatNotFoundException
     */
    public function interactiveAnswer(ChatData $chatData): DialogMessageData;

    /**
     * Provide godmode prompt
     *
     * @todo make work interactive and other characters
     */
    public function experimental(ChatMessageData $chatMessageData): DialogMessageData;
}

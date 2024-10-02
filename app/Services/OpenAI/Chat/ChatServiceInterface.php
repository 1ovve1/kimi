<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;

interface ChatServiceInterface
{
    public function answer(ChatMessageData $chatMessageData): DialogMessageData;

    public function interactiveAnswer(ChatData $chatData): DialogMessageData;
}

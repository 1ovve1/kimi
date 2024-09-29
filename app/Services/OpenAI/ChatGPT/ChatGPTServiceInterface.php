<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT;

use App\Data\OpenAI\ChatGPT\GPTDialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;

interface ChatGPTServiceInterface
{
    public function answer(ChatMessageData $chatMessageData): GPTDialogMessageData;

    public function answerWithMemory(ChatData $chatData): GPTDialogMessageData;
}

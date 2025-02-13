<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repository\OpenAI\Character\CharacterNotFoundException;

interface ChatServiceInterface
{
    /**
     * @throws CharacterNotFoundException
     */
    public function dryAnswer(string $question, CharacterEnum $characterEnum = CharacterEnum::DEFAULT): DialogMessageData;

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

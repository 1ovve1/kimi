<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;

class ChatServiceWIthEscapePrelude extends ChatService implements ChatServiceInterface
{
    public function answer(ChatMessageData $chatMessageData): DialogMessageData
    {
        $answer = parent::answer($chatMessageData);

        return new DialogMessageData(
            $this->escapePrelude($answer->content),
            DialogRolesEnum::from($answer->role),
        );
    }

    public function interactiveAnswer(ChatData $chatData): DialogMessageData
    {
        $answer = parent::interactiveAnswer($chatData);

        return new DialogMessageData(
            $this->escapePrelude($answer->content),
            DialogRolesEnum::from($answer->role),
        );
    }

    private function escapePrelude(string $content): string
    {
        return preg_replace(
            '/^.*from \'.*\': /m',
            '',
            preg_replace('/^.*#\d+: /m', '', $content)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Telegram\Actions\OpenAI\ChatGPT;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAIChatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class AskKimiAction extends AbstractTelegramAction
{
    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     */
    public function handle(
        OpenAIChatServiceInterface $chatService,
        TelegramServiceInterface $telegramService,
        TelegramDataServiceInterface $telegramDataService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $chat = $telegramDataService->resolveChat();

        if ($chat->interactive_mode) {
            $chatMessage = $telegramDataService->resolveMessage();

            $answer = $chatService->interactiveAnswer($chat);

            $telegramService->replyToMessageAndSave($answer->content, $chatMessage);
        } else {
            $chatMessage = $telegramDataRepository->getMessage();

            $answer = $chatService->answer($chatMessage);

            $telegramService->replyToMessage($answer->content, $chatMessage);
        }
    }
}

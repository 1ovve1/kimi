<?php

namespace App\Telegram\Commands\OpenAI\Chat;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAIChatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class AskKimiCommand extends AbstractTelegramCommand
{
    protected string $command = 'ask {text}';

    protected ?string $description = 'ask Kimi about something';

    public function onHandle(
        OpenAIChatServiceInterface $openAiChatService,
        TelegramServiceInterface $telegramService,
        TelegramDataServiceInterface $telegramDataService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $chat = $telegramDataService->resolveChat();
        $message = $telegramDataRepository->getMessage();

        $answer = $openAiChatService->answer($chat, $message);

        $telegramService->replyToMessage($answer->content, $message);
    }
}

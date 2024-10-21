<?php

declare(strict_types=1);

namespace App\Telegram\Commands\OpenAI\Chat;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAIChatServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class DanKimiCommand extends AbstractTelegramCommand
{
    protected string $command = 'dan {text}';

    protected ?string $description = 'ask Kimi about something in GODMODE';

    public function onHandle(
        OpenAIChatServiceInterface $openAiChatService,
        TelegramServiceInterface $telegramService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $message = $telegramDataRepository->getMessage();

        $answer = $openAiChatService->experimental($message);

        $telegramService->replyToMessage($answer->content, $message);
    }
}

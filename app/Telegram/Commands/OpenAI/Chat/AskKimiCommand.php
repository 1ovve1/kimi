<?php

namespace App\Telegram\Commands\OpenAI\Chat;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAIChatServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class AskKimiCommand extends AbstractTelegramCommand
{
    protected string $command = 'ask {text}';

    protected ?string $description = 'ask Kimi about something';

    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatGPTService = $this->getChatService();
        $message = $telegramDataRepository->getMessage();

        $answer = $chatGPTService->answer($message);

        $telegramService->replyToMessage($answer->content, $message);
    }

    private function getChatService(): OpenAIChatServiceInterface
    {
        return app(OpenAIChatServiceInterface::class);
    }
}

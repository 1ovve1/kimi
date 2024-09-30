<?php

namespace App\Telegram\Commands\ChatGPT;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\ChatGPT\ChatGPTServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class AskKimiTelegramCommand extends AbstractTelegramCommand
{
    protected string $command = 'ask {text}';

    protected ?string $description = 'ask Kimi about something';

    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatGPTService = $this->getChatGPTService();
        $message = $telegramDataRepository->getMessage();

        $answer = $chatGPTService->answer($message);

        $telegramService->replyToMessage($answer->content, $message);
    }

    private function getChatGPTService(): ChatGPTServiceInterface
    {
        return app(ChatGPTServiceInterface::class);
    }
}

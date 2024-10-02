<?php

declare(strict_types=1);

namespace App\Telegram\Commands\OpenAI\Chat;

use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface as OpenAIChatMemoryServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class ResetCommand extends AbstractTelegramCommand
{
    protected string $command = 'reset';

    protected ?string $description = 'reset chat memories';

    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chat = $this->getChatRepository()->find($telegramDataRepository->getChat());

        if ($chat->interactive_mode) {
            $count = $this->getMemoryService()->reset($chat);

            $telegramService->sendMessage(
                __('telegram.commands.reset.info', ['count' => $count])
            );
        }
    }

    private function getChatRepository(): ChatRepositoryInterface
    {
        return app(ChatRepositoryInterface::class);
    }

    private function getMemoryService(): OpenAIChatMemoryServiceInterface
    {
        return app(OpenAIChatMemoryServiceInterface::class);
    }
}

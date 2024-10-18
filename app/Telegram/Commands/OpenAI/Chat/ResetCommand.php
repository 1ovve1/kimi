<?php

declare(strict_types=1);

namespace App\Telegram\Commands\OpenAI\Chat;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface as OpenAIChatMemoryServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class ResetCommand extends AbstractTelegramCommand
{
    protected string $command = 'reset';

    protected ?string $description = 'reset chat memories';

    /**
     * @throws ChatNotFoundException
     */
    public function onHandle(
        ChatRepositoryInterface $chatRepository,

        OpenAIChatMemoryServiceInterface $memoryService,
        TelegramServiceInterface $telegramService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $chat = $chatRepository->find($telegramDataRepository->getChat());

        if ($chat->interactive_mode) {
            $count = $memoryService->reset($chat);

            $telegramService->sendMessage(
                __('telegram.commands.reset.info', ['count' => $count])
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Telegram\Commands\ChatGPT;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class InteractiveCommand extends AbstractTelegramCommand
{
    protected string $command = 'interactive';

    protected ?string $description = 'activate interactive mode';

    /**
     * @throws ChatNotFoundException
     */
    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatRepository = $this->getChatRepository();
        $chat = $chatRepository->find($telegramDataRepository->getChat());

        if ($chat->interactive_mode) {
            $chatRepository->setInteractiveMode($chat, false);
            $telegramService->sendMessage('Interactive mode disabled');
        } else {
            $chatRepository->setInteractiveMode($chat, true);
            $telegramService->sendMessage('Interactive mode enabled');
        }
    }

    private function getChatRepository(): ChatRepositoryInterface
    {
        return app(ChatRepositoryInterface::class);
    }
}

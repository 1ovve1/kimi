<?php

namespace App\Telegram\Commands;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;
use App\Telegram\Keyboards\StartKeyboardFactory;

class StartTelegramCommand extends AbstractTelegramCommand
{
    protected string $command = 'start';

    protected ?string $description = 'start kimi';

    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatRepository = $this->getChatRepository();

        $chatData = $chatRepository->save($telegramDataRepository->getChat());
        try {
            $chatRepository->appendUser($chatData, $telegramDataRepository->getMe());
            $chatRepository->appendUser($chatData, $telegramDataRepository->getUser());
        } catch (ChatNotFoundException|TelegramUserNotFoundException $e) {
        }

        $startKeyboardFactory = new StartKeyboardFactory();

        $telegramService->sendMessageWithKeyboard(__('telegram.commands.start.greetings'), $startKeyboardFactory->get());
    }

    public function getChatRepository(): ChatRepositoryInterface
    {
        return app(ChatRepositoryInterface::class);
    }
}

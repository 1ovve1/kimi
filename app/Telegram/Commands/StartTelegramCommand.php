<?php

namespace App\Telegram\Commands;

use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;
use App\Telegram\Keyboards\StartKeyboardFactory;

class StartTelegramCommand extends AbstractTelegramCommand
{
    protected string $command = 'start';

    protected ?string $description = 'start kimi';

    public function onHandle(
        TelegramServiceInterface $telegramService,

        StartKeyboardFactory $startKeyboardFactory,
    ): void {
        $telegramService->sendMessageWithKeyboard($startKeyboardFactory->withAiGreetingsDescription());
    }
}

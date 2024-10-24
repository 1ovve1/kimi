<?php

namespace App\Telegram\Commands;

use App\Services\OpenAI\Chat\ChatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;
use App\Telegram\Keyboards\StartKeyboardFactory;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

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

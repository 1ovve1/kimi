<?php

namespace App\Telegram\Commands;

use App\Services\OpenAI\Chat\ChatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;
use App\Telegram\Keyboards\StartKeyboardFactory;

class StartTelegramCommand extends AbstractTelegramCommand
{
    protected string $command = 'start';

    protected ?string $description = 'start kimi';

    public function onHandle(
        ChatServiceInterface $openAiChatService,
        TelegramServiceInterface $telegramService,
        TelegramDataServiceInterface $telegramDataService
    ): void {
        $telegramDataService->storeChatAndUsersInDb();

        $startKeyboardFactory = new StartKeyboardFactory;

        $greetings = $openAiChatService->dryAnswer(__('openai.chat.characters.kimi.greetings'));

        $telegramService->sendMessageWithKeyboard($greetings->content, $startKeyboardFactory->get());
    }
}

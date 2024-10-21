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

    /**
     * @throws InvalidArgumentException
     */
    public function onHandle(
        ChatServiceInterface $openAiChatService,
        TelegramServiceInterface $telegramService,
        TelegramDataServiceInterface $telegramDataService
    ): void {
        $chat = $telegramDataService->resolveChat();

        $telegramDataService->storeChatAndUsersInDb();

        $greetings = Cache::get('chat.'.$chat->id.'.greetings', fn () => $openAiChatService->dryAnswer(__('openai.chat.prompts.greetings')));
        Cache::set('chat.'.$chat->id.'.greetings', $greetings);

        $telegramService->sendMessageWithKeyboard($greetings->content, (new StartKeyboardFactory)->get());
    }
}

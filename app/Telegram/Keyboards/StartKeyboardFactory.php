<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Services\OpenAI\Chat\ChatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Telegram\Abstract\Keyboards\TelegramKeyboard;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;
use App\Telegram\Keyboards\Buttons\InteractiveButton;
use App\Telegram\Keyboards\Buttons\ResetButton;
use App\Telegram\Keyboards\Buttons\SelectCharacterButton;
use Illuminate\Support\Facades\Cache;

class StartKeyboardFactory
{
    public function get(): TelegramKeyboardInterface
    {
        return (new TelegramKeyboard)
            ->addColumn(
                new InteractiveButton,
                new ResetButton,
                new SelectCharacterButton,
            );
    }

    public function withAiGreetingsDescription(): TelegramKeyboardInterface
    {
        $telegramDataService = app(TelegramDataServiceInterface::class);
        $openAiChatService = app(ChatServiceInterface::class);

        $chat = $telegramDataService->resolveChat();
        /** @var DialogMessageData $greetings */
        $greetings = Cache::get('chat.'.$chat->id.'.greetings', fn () => $openAiChatService->dryAnswer(__('openai.chat.prompts.greetings')));
        Cache::set('chat.'.$chat->id.'.greetings', $greetings);

        return $this->get()->setDescription($greetings->content);
    }
}

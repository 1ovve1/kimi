<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Data\Telegram\Chat\ChatData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\StartKeyboardFactory;

class InteractiveButton extends AbstractTelegramButton
{
    /**
     * @throws ChatNotFoundException
     */
    function handle(TelegramServiceInterface $telegramService, CallbackServiceInterface $callbackService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatRepository = $this->getChatRepository();

        $chat = $chatRepository->find($telegramDataRepository->getChat());

        if ($chat->interactive_mode) {
            $chatRepository->setInteractiveMode($chat, false);
            $callbackService->answerCallback(__('telegram.commands.interactive.disabled'));
        } else {
            $chatRepository->setInteractiveMode($chat, true);
            $callbackService->answerCallback(__('telegram.commands.interactive.enabled'));
        }
    }

    static function text(): string
    {
        $telegramDataRepository = app(TelegramDataRepositoryInterface::class);
        $chatRepository = app(ChatRepositoryInterface::class);

        $chat = $chatRepository->find($telegramDataRepository->getChat());

        if ($chat->interactive_mode) {
            return __('telegram.commands.interactive.name') . ' ✅';
        } else {
            return __('telegram.commands.interactive.name') . ' ❌';
        }
    }

    private function getChatRepository(): ChatRepositoryInterface
    {
        return app(ChatRepositoryInterface::class);
    }
}

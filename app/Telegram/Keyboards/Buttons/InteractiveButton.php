<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\StartKeyboardFactory;

class InteractiveButton extends AbstractTelegramButton
{
    /**
     * @throws ChatNotFoundException
     */
    public function handle(
        ChatRepositoryInterface $chatRepository,
        TelegramServiceInterface $telegramService,
        CallbackServiceInterface $callbackService,
        TelegramDataServiceInterface $telegramDataService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $chat = $telegramDataService->resolveChat();

        if ($chat->interactive_mode) {
            $chatRepository->setInteractiveMode($chat, false);
            $callbackService->answerCallback(__('telegram.commands.interactive.disabled'));
        } else {
            $chatRepository->setInteractiveMode($chat, true);
            $callbackService->answerCallback(__('telegram.commands.interactive.enabled'));
        }

        $telegramService->updateKeyboard((new StartKeyboardFactory)->get());
    }

    public static function text(TelegramDataServiceInterface $telegramDataService): string
    {
        $chat = $telegramDataService->resolveChat();

        return __('telegram.commands.interactive.name', [
            'status' => $chat->interactive_mode ? '✅' : '❌',
        ]);
    }
}

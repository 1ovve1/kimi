<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\OpenAI\Chat\Memory\MemoryRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\StartKeyboardFactory;

class ResetButton extends AbstractTelegramButton
{
    /**
     * @throws UserNotFoundException
     * @throws TelegramUserNotFoundException
     * @throws ChatNotFoundException
     */
    public function handle(
        TelegramServiceInterface $telegramService,
        CallbackServiceInterface $callbackService,
        TelegramDataServiceInterface $telegramDataService,
        MemoryServiceInterface $memoryService,
        UserRepositoryInterface $userRepository,
    ): void {
        $chat = $telegramDataService->resolveChat();
        $user = $telegramDataService->resolveUser();

        if ($userRepository->isAdmin($chat, $user)) {
            $count = $memoryService->reset($chat);
            $callbackService->answerCallback(__('telegram.keyboards.buttons.reset.info', ['count' => $count]));

            if ($count > 0) {
                $telegramService->updateKeyboard((new StartKeyboardFactory)->withAiGreetingsDescription());
            }
        } else {
            $callbackService->answerCallback(__('telegram.keyboards.buttons.default.permissions_denied'));
        }
    }

    /**
     * @throws ChatNotFoundException
     */
    public static function text(TelegramDataServiceInterface $telegramDataService, MemoryRepositoryInterface $memoryRepository): string
    {
        return __('telegram.keyboards.buttons.reset.name', ['count' => $memoryRepository->count($telegramDataService->resolveChat())]);
    }
}

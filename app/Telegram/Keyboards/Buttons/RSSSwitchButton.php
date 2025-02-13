<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\StartKeyboardFactory;

class RSSSwitchButton extends AbstractTelegramButton
{
    /**
     * @throws UserNotFoundException
     * @throws TelegramUserNotFoundException
     * @throws ChatNotFoundException
     */
    public function handle(
        ChatRepositoryInterface $chatRepository,
        UserRepositoryInterface $userRepository,
        TelegramServiceInterface $telegramService,
        CallbackServiceInterface $callbackService,
        TelegramDataServiceInterface $telegramDataService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $chat = $telegramDataService->resolveChat();
        $user = $telegramDataService->resolveUser();

        if ($userRepository->isAdmin($chat, $user)) {
            if ($chat->rss) {
                $chatRepository->setRss($chat, false);
                $callbackService->answerCallback(__('telegram.keyboards.buttons.rss.disabled'));
            } else {
                $chatRepository->setRss($chat, true);
                $callbackService->answerCallback(__('telegram.keyboards.buttons.rss.enabled'));
            }

            $telegramService->updateKeyboard((new StartKeyboardFactory)->withAiGreetingsDescription());
        } else {
            $callbackService->answerCallback(__('telegram.keyboards.buttons.default.permissions_denied'));
        }
    }

    public static function text(TelegramDataServiceInterface $telegramDataService): string
    {
        $chat = $telegramDataService->resolveChat();

        return __('telegram.keyboards.buttons.rss.name', [
            'status' => $chat->rss ? '✅' : '❌',
        ]);
    }
}

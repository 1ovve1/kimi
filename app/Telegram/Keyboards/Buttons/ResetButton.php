<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Repositories\OpenAI\Chat\Memory\MemoryRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\StartKeyboardFactory;

class ResetButton extends AbstractTelegramButton
{
    public function handle(TelegramServiceInterface $telegramService, CallbackServiceInterface $callbackService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $memoryService = app(MemoryServiceInterface::class);

        $count = $memoryService->reset($telegramDataRepository->getChat());

        $callbackService->answerCallback(__('telegram.commands.reset.info', ['count' => $count]));

        $telegramService->updateKeyboard((new StartKeyboardFactory)->get());
    }

    public static function text(): string
    {
        $telegramDataRepository = app(TelegramDataRepositoryInterface::class);
        $memoryRepository = app(MemoryRepositoryInterface::class);

        return __('telegram.commands.reset.name', ['count' => $memoryRepository->count($telegramDataRepository->getChat())]);
    }
}

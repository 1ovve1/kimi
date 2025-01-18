<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;
use Closure;

class BackToButton extends AbstractTelegramButton
{
    public function __construct(
        private readonly Closure $keyboardResolver,
        private readonly ?ChatMessageData $chatMessageData = null,
    ) {}

    public function handle(
        TelegramDataServiceInterface $telegramDataService,
        TelegramServiceInterface $telegramService,
    ): void {
        $telegramService->updateKeyboard(($this->keyboardResolver)($telegramDataService, $telegramService), $this->chatMessageData);
    }

    public function text(): string
    {
        return __('telegram.keyboards.buttons.back_to.name');
    }
}

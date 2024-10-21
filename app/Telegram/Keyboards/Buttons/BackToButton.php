<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;

class BackToButton extends AbstractTelegramButton
{
    public function __construct(
        private readonly TelegramKeyboardInterface $telegramKeyboard,
        private readonly ?ChatMessageData          $chatMessageData = null,
    )
    {
    }

    public function handle(
        TelegramServiceInterface $telegramService,
    ): void
    {
        $telegramService->updateKeyboard($this->telegramKeyboard, $this->chatMessageData);
    }

    public function text(): string
    {
        return __('telegram.keyboards.buttons.back_to.name');
    }
}

<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards;

use App\Telegram\Abstract\Keyboards\TelegramKeyboard;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;
use App\Telegram\Keyboards\Buttons\InteractiveButton;
use App\Telegram\Keyboards\Buttons\ResetButton;

class StartKeyboardFactory
{
    public function get(): TelegramKeyboardInterface
    {
        return (new TelegramKeyboard)
            ->addRow(
                new InteractiveButton,
                new ResetButton,
            );
    }
}

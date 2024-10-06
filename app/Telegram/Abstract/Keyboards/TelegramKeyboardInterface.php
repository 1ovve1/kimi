<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards;

use App\Telegram\Abstract\Keyboards\Buttons\TelegramButtonInterface;

interface TelegramKeyboardInterface
{
    public function make(): mixed;
    public function addRow(TelegramButtonInterface ...$button): self;
}

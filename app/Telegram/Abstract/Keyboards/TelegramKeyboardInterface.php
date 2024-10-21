<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards;

use App\Telegram\Abstract\Keyboards\Buttons\TelegramButtonInterface;
use SergiX44\Nutgram\Nutgram;

interface TelegramKeyboardInterface
{
    public function make(): mixed;

    public function addRow(TelegramButtonInterface ...$button): self;

    public function addColumn(TelegramButtonInterface ...$button): self;

    public function listen(Nutgram $nutgram): void;
}
